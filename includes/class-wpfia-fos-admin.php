<?php
/**
 * WPFIA Fake Order Shield - Admin Dashboard & Settings
 *
 * @package WPFIA_Fake_Order_Shield
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WPFIA_FOS_Admin {

    /**
     * Bootstrap admin handlers.
     */
    public static function init() {
        add_action( 'admin_menu', array( __CLASS__, 'register_admin_menu' ) );
        add_action( 'admin_init', array( __CLASS__, 'save_settings' ) );
        add_action( 'admin_post_wpfia_fos_reset_stats', array( __CLASS__, 'handle_reset_stats' ) );
    }

    /**
     * Register submenu under WooCommerce.
     */
    public static function register_admin_menu() {
        $capability = 'manage_woocommerce';
        if ( ! current_user_can( $capability ) && current_user_can( 'manage_options' ) ) {
            $capability = 'manage_options';
        }

        add_submenu_page(
            'woocommerce',
            __( 'WPFIA Fake Order Shield', 'wpfia-fake-order-shield' ),
            __( 'Fake Order Shield', 'wpfia-fake-order-shield' ),
            $capability,
            'wpfia-fake-order-shield',
            array( __CLASS__, 'render_settings_page' )
        );
    }

    /**
     * Render settings page.
     */
    public static function render_settings_page() {
        if ( ! current_user_can( 'manage_woocommerce' ) && ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'Unauthorized access.', 'wpfia-fake-order-shield' ) );
        }

        $settings = WPFIA_FOS_Settings::get_instance();
        $all      = $settings->get_all();
        $stats    = $settings->get_stats();

        include WPFIA_FAKE_ORDER_SHIELD_PATH . 'templates/admin-settings.php';
    }

    /**
     * Save settings handler.
     */
    public static function save_settings() {
        if ( ! isset( $_POST['wpfia_fos_save_settings'] ) ) {
            return;
        }

        check_admin_referer( 'wpfia_fos_settings_nonce', 'wpfia_fos_nonce' );

        if ( ! current_user_can( 'manage_woocommerce' ) && ! current_user_can( 'manage_options' ) ) {
            return;
        }

        $settings = WPFIA_FOS_Settings::get_instance();

        $settings->set( 'enabled', ! empty( $_POST['enabled'] ) ? 1 : 0 );
        $settings->set( 'honeypot', ! empty( $_POST['honeypot'] ) ? 1 : 0 );
        $settings->set( 'time_gate', ! empty( $_POST['time_gate'] ) ? 1 : 0 );
        $settings->set( 'min_seconds', max( 1, intval( $_POST['min_seconds'] ?? 3 ) ) );
        $settings->set( 'block_disposable_emails', ! empty( $_POST['block_disposable_emails'] ) ? 1 : 0 );
        $settings->set( 'custom_blocked_domains', sanitize_textarea_field( wp_unslash( $_POST['custom_blocked_domains'] ?? '' ) ) );
        $settings->set( 'block_bogus_phones', ! empty( $_POST['block_bogus_phones'] ) ? 1 : 0 );
        $settings->set( 'ip_rate_limiting', ! empty( $_POST['ip_rate_limiting'] ) ? 1 : 0 );
        $settings->set( 'max_orders_per_hour', max( 1, intval( $_POST['max_orders_per_hour'] ?? 5 ) ) );

        wp_safe_redirect( add_query_arg( array( 'page' => 'wpfia-fake-order-shield', 'saved' => '1' ), admin_url( 'admin.php' ) ) );
        exit;
    }

    /**
     * Handle resetting threat statistics.
     */
    public static function handle_reset_stats() {
        check_admin_referer( 'wpfia_fos_reset_nonce' );

        if ( ! current_user_can( 'manage_woocommerce' ) && ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'Unauthorized.', 'wpfia-fake-order-shield' ) );
        }

        $settings = WPFIA_FOS_Settings::get_instance();
        $settings->reset_stats();

        wp_safe_redirect( add_query_arg( array( 'page' => 'wpfia-fake-order-shield', 'reset' => '1' ), admin_url( 'admin.php' ) ) );
        exit;
    }
}

// Bootstrap admin hooks
WPFIA_FOS_Admin::init();
