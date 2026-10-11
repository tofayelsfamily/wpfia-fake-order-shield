<?php
/**
 * WPFIA Fake Order Shield - Core Checkout Security Engine
 *
 * Enforces Honeypot, Submission Time-Gate, Disposable Email Blocking,
 * Bogus Phone Filtering, and IP Order Rate Limiting on WooCommerce checkout.
 *
 * @package WPFIA_Fake_Order_Shield
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WPFIA_FOS_Shield {

    /**
     * Bootstrap checkout security hooks.
     */
    public static function init() {
        $settings = WPFIA_FOS_Settings::get_instance();
        if ( ! $settings->get( 'enabled', 1 ) ) {
            return;
        }

        // 1. Inject Honeypot and Time-Gate fields into Checkout Form
        add_action( 'woocommerce_after_checkout_billing_form', array( __CLASS__, 'render_checkout_traps' ) );
        add_action( 'woocommerce_review_order_before_submit', array( __CLASS__, 'render_checkout_traps' ) );

        // 2. Validate Order Submission
        add_action( 'woocommerce_checkout_process', array( __CLASS__, 'validate_checkout_security' ) );
    }

    /**
     * Inject invisible Honeypot field and HMAC Time-Gate token.
     */
    public static function render_checkout_traps() {
        static $rendered = false;
        if ( $rendered ) {
            return;
        }
        $rendered = true;

        $settings = WPFIA_FOS_Settings::get_instance();

        // 1. Invisible Honeypot Input
        if ( $settings->get( 'honeypot', 1 ) ) {
            ?>
            <div class="wpfia-fos-hp-wrap" style="position: absolute !important; left: -9999px !important; top: -9999px !important; opacity: 0 !important; width: 0 !important; height: 0 !important; overflow: hidden !important;" aria-hidden="true">
                <label for="wpfia_hp_website"><?php esc_html_e( 'Leave this field blank', 'wpfia-fake-order-shield' ); ?></label>
                <input type="text" name="wpfia_hp_website" id="wpfia_hp_website" value="" autocomplete="off" tabindex="-1" />
            </div>
            <?php
        }

        // 2. HMAC Signed Time-Gate Token
        if ( $settings->get( 'time_gate', 1 ) ) {
            $timestamp = time();
            $salt      = wp_salt( 'nonce' );
            $hash      = hash_hmac( 'sha256', (string) $timestamp, $salt );
            $token     = $timestamp . ':' . $hash;
            ?>
            <input type="hidden" name="wpfia_fos_tg" value="<?php echo esc_attr( $token ); ?>" />
            <?php
        }
    }

    /**
     * Comprehensive checkout security validation.
     */
    public static function validate_checkout_security() {
        $settings = WPFIA_FOS_Settings::get_instance();
        if ( ! $settings->get( 'enabled', 1 ) ) {
            return;
        }

        // -------------------------------------------------------------
        // MODULE 1: HONEYPOT TRAP CHECK
        // -------------------------------------------------------------
        if ( $settings->get( 'honeypot', 1 ) ) {
            if ( ! empty( $_POST['wpfia_hp_website'] ) ) {
                $settings->increment_stat( 'honeypot_hits' );
                wc_add_notice(
                    __( 'Order submission could not be verified. Please refresh and try again.', 'wpfia-fake-order-shield' ),
                    'error'
                );
                return;
            }
        }

        // -------------------------------------------------------------
        // MODULE 2: SUBMISSION VELOCITY / TIME-GATE CHECK
        // -------------------------------------------------------------
        if ( $settings->get( 'time_gate', 1 ) ) {
            $token = isset( $_POST['wpfia_fos_tg'] ) ? sanitize_text_field( wp_unslash( $_POST['wpfia_fos_tg'] ) ) : '';
            $min_s = max( 1, (int) $settings->get( 'min_seconds', 3 ) );

            $valid_token = false;
            if ( ! empty( $token ) && false !== strpos( $token, ':' ) ) {
                list( $timestamp, $hash ) = explode( ':', $token, 2 );
                $timestamp = intval( $timestamp );
                $salt      = wp_salt( 'nonce' );
                $expected  = hash_hmac( 'sha256', (string) $timestamp, $salt );

                if ( hash_equals( $expected, $hash ) ) {
                    $elapsed = time() - $timestamp;
                    if ( $elapsed >= $min_s ) {
                        $valid_token = true;
                    }
                }
            }

            if ( ! $valid_token ) {
                $settings->increment_stat( 'time_gate_hits' );
                wc_add_notice(
                    __( 'Please take a moment to review your order details before submitting.', 'wpfia-fake-order-shield' ),
                    'error'
                );
                return;
            }
        }

        // -------------------------------------------------------------
        // MODULE 3: DISPOSABLE & SPAM EMAIL BLOCKER
        // -------------------------------------------------------------
        if ( $settings->get( 'block_disposable_emails', 1 ) ) {
            $email = isset( $_POST['billing_email'] ) ? sanitize_email( wp_unslash( $_POST['billing_email'] ) ) : '';
            if ( ! empty( $email ) ) {
                $custom_domains = (string) $settings->get( 'custom_blocked_domains', '' );
                if ( WPFIA_FOS_Disposable_Domains::is_disposable( $email, $custom_domains ) ) {
                    $settings->increment_stat( 'email_hits' );
                    wc_add_notice(
                        __( 'Please provide a permanent personal or business email address for order confirmation.', 'wpfia-fake-order-shield' ),
                        'error'
                    );
                    return;
                }
            }
        }

        // -------------------------------------------------------------
        // MODULE 4: BOGUS PHONE NUMBER DETECTOR
        // -------------------------------------------------------------
        if ( $settings->get( 'block_bogus_phones', 1 ) ) {
            $phone = isset( $_POST['billing_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['billing_phone'] ) ) : '';
            if ( ! empty( $phone ) ) {
                if ( self::is_bogus_phone( $phone ) ) {
                    $settings->increment_stat( 'phone_hits' );
                    wc_add_notice(
                        __( 'Please provide a valid contact phone number for delivery coordination.', 'wpfia-fake-order-shield' ),
                        'error'
                    );
                    return;
                }
            }
        }

        // -------------------------------------------------------------
        // MODULE 5: IP RATE LIMITING / ORDER FLOOD SHIELD
        // -------------------------------------------------------------
        if ( $settings->get( 'ip_rate_limiting', 1 ) ) {
            $ip = self::get_client_ip();
            if ( ! empty( $ip ) ) {
                $max_orders = max( 2, (int) $settings->get( 'max_orders_per_hour', 5 ) );
                $transient  = 'wpfia_fos_ip_' . md5( $ip );
                $attempts   = (int) get_transient( $transient );

                if ( $attempts >= $max_orders ) {
                    $settings->increment_stat( 'rate_limit_hits' );
                    wc_add_notice(
                        __( 'Too many order requests detected from your network. Please wait a short while before trying again.', 'wpfia-fake-order-shield' ),
                        'error'
                    );
                    return;
                }

                // Increment and cache for 1 hour
                set_transient( $transient, $attempts + 1, HOUR_IN_SECONDS );
            }
        }
    }

    /**
     * Check if a phone number is an obvious bogus pattern.
     *
     * @param string $phone Raw phone number.
     * @return bool True if bogus, false if valid.
     */
    public static function is_bogus_phone( $phone ) {
        // Strip non-numeric characters
        $digits = preg_replace( '/\D/', '', $phone );
        $len    = strlen( $digits );

        // Length must be reasonable (7 to 15 digits)
        if ( $len < 7 || $len > 16 ) {
            return true;
        }

        // 1. Repeating identical digits (e.g. 0000000000, 1111111111, 9999999999)
        if ( preg_match( '/^(\d)\1+$/', $digits ) ) {
            return true;
        }

        // 2. Common fake test sequences
        $fake_sequences = array(
            '1234567890',
            '0123456789',
            '9876543210',
            '0987654321',
            '12345678',
            '87654321',
        );

        foreach ( $fake_sequences as $seq ) {
            if ( $digits === $seq || 0 === strpos( $digits, $seq ) ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Retrieve client IP address safely.
     *
     * @return string
     */
    public static function get_client_ip() {
        $ip = '';
        if ( ! empty( $_SERVER['HTTP_CF_CONNECTING_IP'] ) ) {
            $ip = sanitize_text_field( wp_unslash( $_SERVER['HTTP_CF_CONNECTING_IP'] ) );
        } elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
            $parts = explode( ',', sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) );
            $ip    = trim( $parts[0] );
        } elseif ( ! empty( $_SERVER['REMOTE_ADDR'] ) ) {
            $ip = sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) );
        }
        return filter_var( $ip, FILTER_VALIDATE_IP ) ? $ip : '';
    }
}

// Bootstrap checkout shield
WPFIA_FOS_Shield::init();
