<?php
/**
 * Plugin Name:       WPFIA Fake Order & Spam Checkout Shield
 * Plugin URI:        https://wpfia.com/plugins/fake-order-shield
 * Description:       Lightweight checkout fraud protection for WooCommerce. Stops card testing, bot spam, fake COD orders, disposable emails, and checkout floods. 100% Lifetime Free.
 * Version:           1.0.0
 * Author:            WPFIA
 * Author URI:        https://wpfia.com
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wpfia-fake-order-shield
 * Domain Path:       /languages
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * WC requires at least: 5.0
 * WC tested up to:   9.5
 *
 * @package WPFIA_Fake_Order_Shield
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Global Definitions
define( 'WPFIA_FAKE_ORDER_SHIELD_VERSION', '1.0.0' );
define( 'WPFIA_FAKE_ORDER_SHIELD_FILE', __FILE__ );
define( 'WPFIA_FAKE_ORDER_SHIELD_PATH', plugin_dir_path( __FILE__ ) );
define( 'WPFIA_FAKE_ORDER_SHIELD_URL', plugin_dir_url( __FILE__ ) );

/**
 * Main Initialization Class.
 */
final class WPFIA_Fake_Order_Shield {

    /**
     * Singleton Instance.
     *
     * @var WPFIA_Fake_Order_Shield|null
     */
    private static $instance = null;

    /**
     * Get Singleton Instance.
     *
     * @return WPFIA_Fake_Order_Shield
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor.
     */
    private function __construct() {
        $this->includes();
        $this->init_hooks();
    }

    /**
     * Include core classes.
     */
    private function includes() {
        require_once WPFIA_FAKE_ORDER_SHIELD_PATH . 'includes/class-wpfia-fos-settings.php';
        require_once WPFIA_FAKE_ORDER_SHIELD_PATH . 'includes/class-wpfia-fos-disposable-domains.php';
        require_once WPFIA_FAKE_ORDER_SHIELD_PATH . 'includes/class-wpfia-fos-shield.php';

        if ( is_admin() ) {
            require_once WPFIA_FAKE_ORDER_SHIELD_PATH . 'includes/class-wpfia-fos-admin.php';
        }
    }

    /**
     * Initialize WordPress & WooCommerce hooks.
     */
    private function init_hooks() {
        add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
        add_action( 'before_woocommerce_init', array( $this, 'declare_hpos_compatibility' ) );
        add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'add_action_links' ) );
    }

    /**
     * Load plugin translations.
     */
    public function load_textdomain() {
        load_plugin_textdomain( 'wpfia-fake-order-shield', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
    }

    /**
     * Declare WooCommerce HPOS compatibility.
     */
    public function declare_hpos_compatibility() {
        if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'cart_checkout_blocks', __FILE__, true );
        }
    }

    /**
     * Add quick settings link to plugins list.
     *
     * @param array $links Existing action links.
     * @return array
     */
    public function add_action_links( $links ) {
        $settings_link = '<a href="' . esc_url( admin_url( 'admin.php?page=wpfia-fake-order-shield' ) ) . '">' . esc_html__( 'Settings', 'wpfia-fake-order-shield' ) . '</a>';
        array_unshift( $links, $settings_link );
        return $links;
    }
}

/**
 * Bootstrap the plugin.
 */
function wpfia_fake_order_shield() {
    return WPFIA_Fake_Order_Shield::get_instance();
}

// Bootstrap immediately or on plugins_loaded
if ( did_action( 'plugins_loaded' ) ) {
    wpfia_fake_order_shield();
} else {
    add_action( 'plugins_loaded', 'wpfia_fake_order_shield', 10 );
}
