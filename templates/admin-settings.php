<?php
/**
 * WPFIA Fake Order Shield - Admin Dashboard Template
 *
 * @package WPFIA_Fake_Order_Shield
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="wrap" style="max-width: 1040px; margin: 24px auto 40px auto; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">

    <!-- Executive Brand Header -->
    <div style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #fff; padding: 28px 32px; border-radius: 14px; box-shadow: 0 4px 20px rgba(0,0,0,0.15); margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
        <div>
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 6px;">
                <span style="background: #2563eb; color: #fff; font-size: 11px; font-weight: 800; padding: 3px 8px; border-radius: 6px; letter-spacing: 0.5px;">WPFIA PLUGIN STUDIO</span>
                <span style="background: #10b981; color: #fff; font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 6px;">100% LIFETIME FREE</span>
                <span style="color: #94a3b8; font-size: 12px; font-weight: 600;">v<?php echo esc_html( WPFIA_FAKE_ORDER_SHIELD_VERSION ); ?></span>
            </div>
            <h1 style="color: #fff; margin: 0; font-size: 24px; font-weight: 800; display: flex; align-items: center; gap: 8px;">
                <span>🛡️</span> <?php esc_html_e( 'Fake Order & Spam Checkout Shield', 'wpfia-fake-order-shield' ); ?>
            </h1>
            <p style="color: #94a3b8; margin: 6px 0 0 0; font-size: 13.5px;">
                <?php esc_html_e( 'Protect your WooCommerce store from card-testing bots, fake COD orders, disposable email spam, and checkout floods.', 'wpfia-fake-order-shield' ); ?>
            </p>
        </div>

        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" onsubmit="return confirm('<?php echo esc_js( __( 'Are you sure you want to reset threat statistics counter?', 'wpfia-fake-order-shield' ) ); ?>');">
            <input type="hidden" name="action" value="wpfia_fos_reset_stats">
            <?php wp_nonce_field( 'wpfia_fos_reset_nonce' ); ?>
            <button type="submit" style="background: #334155; color: #e2e8f0; border: 1px solid #475569; padding: 8px 14px; border-radius: 6px; font-weight: 600; font-size: 12px; cursor: pointer;">
                🔄 <?php esc_html_e( 'Reset Counters', 'wpfia-fake-order-shield' ); ?>
            </button>
        </form>
    </div>

    <?php if ( isset( $_GET['saved'] ) ) : ?>
        <div class="notice notice-success is-dismissible" style="border-left-color: #10b981; padding: 12px 16px; border-radius: 6px; margin-bottom: 24px;">
            <p style="font-weight: 600; margin: 0;"><?php esc_html_e( 'Security shield settings updated successfully.', 'wpfia-fake-order-shield' ); ?></p>
        </div>
    <?php endif; ?>

    <?php if ( isset( $_GET['reset'] ) ) : ?>
        <div class="notice notice-info is-dismissible" style="border-left-color: #3b82f6; padding: 12px 16px; border-radius: 6px; margin-bottom: 24px;">
            <p style="font-weight: 600; margin: 0;"><?php esc_html_e( 'Threat statistics counters have been reset to zero.', 'wpfia-fake-order-shield' ); ?></p>
        </div>
    <?php endif; ?>

    <!-- Card 1: Threat Metrics Overview -->
    <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); margin-bottom: 24px;">
        <h2 style="font-size: 17px; font-weight: 700; color: #0f172a; margin: 0 0 16px 0; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px; display: flex; align-items: center; gap: 8px;">
            <span>📊</span> <?php esc_html_e( 'Live Threat Neutralization Metrics', 'wpfia-fake-order-shield' ); ?>
        </h2>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 14px;">
            <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px; padding: 16px; text-align: center;">
                <div style="font-size: 28px; font-weight: 800; color: #16a34a;"><?php echo esc_html( number_format_i18n( $stats['total_blocked'] ) ); ?></div>
                <div style="font-size: 12px; font-weight: 700; color: #15803d; text-transform: uppercase; margin-top: 4px;"><?php esc_html_e( 'Total Attacks Blocked', 'wpfia-fake-order-shield' ); ?></div>
            </div>

            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 16px; text-align: center;">
                <div style="font-size: 24px; font-weight: 800; color: #0f172a;"><?php echo esc_html( number_format_i18n( $stats['honeypot_hits'] ) ); ?></div>
                <div style="font-size: 11px; font-weight: 600; color: #64748b; text-transform: uppercase; margin-top: 4px;">🍯 <?php esc_html_e( 'Honeypot Traps', 'wpfia-fake-order-shield' ); ?></div>
            </div>

            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 16px; text-align: center;">
                <div style="font-size: 24px; font-weight: 800; color: #0f172a;"><?php echo esc_html( number_format_i18n( $stats['time_gate_hits'] ) ); ?></div>
                <div style="font-size: 11px; font-weight: 600; color: #64748b; text-transform: uppercase; margin-top: 4px;">⏱️ <?php esc_html_e( 'Fast-Bot Velocity', 'wpfia-fake-order-shield' ); ?></div>
            </div>

            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 16px; text-align: center;">
                <div style="font-size: 24px; font-weight: 800; color: #0f172a;"><?php echo esc_html( number_format_i18n( $stats['email_hits'] ) ); ?></div>
                <div style="font-size: 11px; font-weight: 600; color: #64748b; text-transform: uppercase; margin-top: 4px;">📧 <?php esc_html_e( 'Disposable Emails', 'wpfia-fake-order-shield' ); ?></div>
            </div>

            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 16px; text-align: center;">
                <div style="font-size: 24px; font-weight: 800; color: #0f172a;"><?php echo esc_html( number_format_i18n( $stats['phone_hits'] ) ); ?></div>
                <div style="font-size: 11px; font-weight: 600; color: #64748b; text-transform: uppercase; margin-top: 4px;">📱 <?php esc_html_e( 'Bogus Phones', 'wpfia-fake-order-shield' ); ?></div>
            </div>

            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 16px; text-align: center;">
                <div style="font-size: 24px; font-weight: 800; color: #0f172a;"><?php echo esc_html( number_format_i18n( $stats['rate_limit_hits'] ) ); ?></div>
                <div style="font-size: 11px; font-weight: 600; color: #64748b; text-transform: uppercase; margin-top: 4px;">🚦 <?php esc_html_e( 'IP Rate Limits', 'wpfia-fake-order-shield' ); ?></div>
            </div>
        </div>
    </div>

    <!-- Card 2: Security Configuration Form -->
    <form method="post" action="">
        <?php wp_nonce_field( 'wpfia_fos_settings_nonce', 'wpfia_fos_nonce' ); ?>
        <input type="hidden" name="wpfia_fos_save_settings" value="1">

        <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); margin-bottom: 24px;">
            <h2 style="font-size: 17px; font-weight: 700; color: #0f172a; margin: 0 0 16px 0; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                <span>🔒</span> <?php esc_html_e( 'Checkout Defense Shield Modules', 'wpfia-fake-order-shield' ); ?>
            </h2>

            <div style="display: flex; flex-direction: column; gap: 20px;">
                <!-- Master Toggle -->
                <div style="padding-bottom: 16px; border-bottom: 1px solid #f1f5f9;">
                    <label style="display: flex; align-items: center; gap: 8px; font-weight: 700; font-size: 14.5px; color: #1e293b; cursor: pointer;">
                        <input type="checkbox" name="enabled" value="1" <?php checked( $all['enabled'], 1 ); ?>>
                        <?php esc_html_e( 'Enable Fake Order & Spam Checkout Shield', 'wpfia-fake-order-shield' ); ?>
                    </label>
                    <p style="color: #64748b; font-size: 12.5px; margin: 4px 0 0 24px;">
                        <?php esc_html_e( 'Master killswitch for all checkout anti-bot and spam filters.', 'wpfia-fake-order-shield' ); ?>
                    </p>
                </div>

                <!-- Module 1: Honeypot -->
                <div>
                    <label style="display: flex; align-items: center; gap: 8px; font-weight: 600; font-size: 14px; color: #1e293b; cursor: pointer;">
                        <input type="checkbox" name="honeypot" value="1" <?php checked( $all['honeypot'], 1 ); ?>>
                        <?php esc_html_e( 'Invisible Honeypot Bot Trap', 'wpfia-fake-order-shield' ); ?>
                    </label>
                    <p style="color: #64748b; font-size: 12px; margin: 4px 0 0 24px;">
                        <?php esc_html_e( 'Injects a hidden field invisible to human shoppers. Automated headless bots fill it and get instantly caught.', 'wpfia-fake-order-shield' ); ?>
                    </p>
                </div>

                <!-- Module 2: Time Gate -->
                <div>
                    <label style="display: flex; align-items: center; gap: 8px; font-weight: 600; font-size: 14px; color: #1e293b; cursor: pointer;">
                        <input type="checkbox" name="time_gate" value="1" <?php checked( $all['time_gate'], 1 ); ?>>
                        <?php esc_html_e( 'Submission Velocity Time-Gate (Anti-Headless Bot)', 'wpfia-fake-order-shield' ); ?>
                    </label>
                    <p style="color: #64748b; font-size: 12px; margin: 4px 0 0 24px;">
                        <?php esc_html_e( 'Blocks instant automated script submissions made under a human threshold.', 'wpfia-fake-order-shield' ); ?>
                    </p>
                    <div style="margin: 8px 0 0 24px; display: flex; align-items: center; gap: 8px;">
                        <span style="font-size: 12.5px; color: #475569;"><?php esc_html_e( 'Minimum checkout duration threshold:', 'wpfia-fake-order-shield' ); ?></span>
                        <input type="number" min="1" max="15" name="min_seconds" value="<?php echo esc_attr( $all['min_seconds'] ); ?>" style="width: 60px;">
                        <span style="font-size: 12.5px; color: #64748b;"><?php esc_html_e( 'seconds (Default: 3s)', 'wpfia-fake-order-shield' ); ?></span>
                    </div>
                </div>

                <!-- Module 3: Disposable Emails -->
                <div>
                    <label style="display: flex; align-items: center; gap: 8px; font-weight: 600; font-size: 14px; color: #1e293b; cursor: pointer;">
                        <input type="checkbox" name="block_disposable_emails" value="1" <?php checked( $all['block_disposable_emails'], 1 ); ?>>
                        <?php esc_html_e( 'Block Disposable & Throwaway Email Domains', 'wpfia-fake-order-shield' ); ?>
                    </label>
                    <p style="color: #64748b; font-size: 12px; margin: 4px 0 0 24px;">
                        <?php esc_html_e( 'Blocks burner email providers (Mailinator, GuerrillaMail, 10MinuteMail, Temp-Mail, etc.).', 'wpfia-fake-order-shield' ); ?>
                    </p>
                    <div style="margin: 8px 0 0 24px;">
                        <label style="font-size: 12px; font-weight: 600; color: #475569; display: block; margin-bottom: 4px;">
                            <?php esc_html_e( 'Custom Blocked Domains (One per line or comma-separated):', 'wpfia-fake-order-shield' ); ?>
                        </label>
                        <textarea name="custom_blocked_domains" rows="3" style="width: 100%; font-size: 12px; border-radius: 6px; border: 1px solid #cbd5e1; padding: 6px 10px;" placeholder="example-spam-domain.com, throwawaytest.net"><?php echo esc_textarea( $all['custom_blocked_domains'] ); ?></textarea>
                    </div>
                </div>

                <!-- Module 4: Bogus Phones -->
                <div>
                    <label style="display: flex; align-items: center; gap: 8px; font-weight: 600; font-size: 14px; color: #1e293b; cursor: pointer;">
                        <input type="checkbox" name="block_bogus_phones" value="1" <?php checked( $all['block_bogus_phones'], 1 ); ?>>
                        <?php esc_html_e( 'Block Bogus & Repeating Phone Numbers (Fake COD Shield)', 'wpfia-fake-order-shield' ); ?>
                    </label>
                    <p style="color: #64748b; font-size: 12px; margin: 4px 0 0 24px;">
                        <?php esc_html_e( 'Prevents fake delivery phone numbers like 0000000000, 1111111111, 1234567890, or invalid lengths.', 'wpfia-fake-order-shield' ); ?>
                    </p>
                </div>

                <!-- Module 5: IP Rate Limiting -->
                <div>
                    <label style="display: flex; align-items: center; gap: 8px; font-weight: 600; font-size: 14px; color: #1e293b; cursor: pointer;">
                        <input type="checkbox" name="ip_rate_limiting" value="1" <?php checked( $all['ip_rate_limiting'], 1 ); ?>>
                        <?php esc_html_e( 'IP Order Velocity Rate Limiting (Anti-Card Testing)', 'wpfia-fake-order-shield' ); ?>
                    </label>
                    <p style="color: #64748b; font-size: 12px; margin: 4px 0 0 24px;">
                        <?php esc_html_e( 'Protects your store payment gateway from stolen credit card testing attacks and order floods.', 'wpfia-fake-order-shield' ); ?>
                    </p>
                    <div style="margin: 8px 0 0 24px; display: flex; align-items: center; gap: 8px;">
                        <span style="font-size: 12.5px; color: #475569;"><?php esc_html_e( 'Max orders permitted per IP per hour:', 'wpfia-fake-order-shield' ); ?></span>
                        <input type="number" min="2" max="50" name="max_orders_per_hour" value="<?php echo esc_attr( $all['max_orders_per_hour'] ); ?>" style="width: 60px;">
                        <span style="font-size: 12.5px; color: #64748b;"><?php esc_html_e( 'orders (Default: 5)', 'wpfia-fake-order-shield' ); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="button button-primary" style="background: #2563eb; border-color: #1d4ed8; font-size: 14px; padding: 8px 24px; height: auto; font-weight: 700; border-radius: 8px; margin-bottom: 24px;">
            💾 <?php esc_html_e( 'Save Shield Settings', 'wpfia-fake-order-shield' ); ?>
        </button>
    </form>

    <!-- Card 3: WPFIA Suite Ecosystem Banner -->
    <div style="background: #0f172a; border: 1px solid #1e293b; border-radius: 12px; padding: 20px 24px; color: #cbd5e1; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px;">
        <div>
            <div style="font-weight: 700; color: #60a5fa; font-size: 13px; margin-bottom: 2px;">⚡ <?php esc_html_e( 'Scale and protect your WooCommerce store with WPFIA', 'wpfia-fake-order-shield' ); ?></div>
            <div style="font-size: 12px; color: #94a3b8;"><?php esc_html_e( 'Explore enterprise eCommerce plugins: QuickCart, SellBot, Currency Switcher, and Safe SVG.', 'wpfia-fake-order-shield' ); ?></div>
        </div>
        <a href="https://wpfia.com/plugins" target="_blank" rel="noopener noreferrer" style="background: #1e293b; color: #38bdf8; border: 1px solid #0284c7; padding: 8px 14px; border-radius: 6px; font-size: 12px; font-weight: 700; text-decoration: none;">
            <?php esc_html_e( 'Explore WPFIA Suite', 'wpfia-fake-order-shield' ); ?> →
        </a>
    </div>

</div>
