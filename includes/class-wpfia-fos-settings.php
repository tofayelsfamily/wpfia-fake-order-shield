<?php
/**
 * WPFIA Fake Order Shield - Settings & Threat Counter Engine
 *
 * @package WPFIA_Fake_Order_Shield
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WPFIA_FOS_Settings {

    /**
     * Singleton instance.
     *
     * @var WPFIA_FOS_Settings|null
     */
    private static $instance = null;

    /**
     * Option key in wp_options.
     *
     * @var string
     */
    private $option_key = 'wpfia_fos_settings';

    /**
     * Stats option key.
     *
     * @var string
     */
    private $stats_key = 'wpfia_fos_stats';

    /**
     * Settings cache.
     *
     * @var array
     */
    private $options = array();

    /**
     * Get singleton instance.
     *
     * @return WPFIA_FOS_Settings
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
        $this->options = get_option( $this->option_key, array() );
    }

    /**
     * Retrieve single option value.
     *
     * @param string $key Option key.
     * @param mixed  $default Default value.
     * @return mixed
     */
    public function get( $key, $default = null ) {
        if ( isset( $this->options[ $key ] ) ) {
            return $this->options[ $key ];
        }
        $defaults = $this->get_defaults();
        return isset( $defaults[ $key ] ) ? $defaults[ $key ] : $default;
    }

    /**
     * Save single option.
     *
     * @param string $key Option key.
     * @param mixed  $value Option value.
     * @return bool
     */
    public function set( $key, $value ) {
        $this->options[ $key ] = $value;
        return update_option( $this->option_key, $this->options );
    }

    /**
     * Retrieve all options merged with defaults.
     *
     * @return array
     */
    public function get_all() {
        return wp_parse_args( $this->options, $this->get_defaults() );
    }

    /**
     * Default settings.
     *
     * @return array
     */
    public function get_defaults() {
        return array(
            'enabled'                 => 1,
            'honeypot'                => 1,
            'time_gate'               => 1,
            'min_seconds'             => 3,
            'block_disposable_emails' => 1,
            'custom_blocked_domains'  => '',
            'block_bogus_phones'      => 1,
            'ip_rate_limiting'        => 1,
            'max_orders_per_hour'     => 5,
        );
    }

    /**
     * Retrieve threat blocked statistics.
     *
     * @return array
     */
    public function get_stats() {
        $defaults = array(
            'honeypot_hits'   => 0,
            'time_gate_hits'  => 0,
            'email_hits'      => 0,
            'phone_hits'      => 0,
            'rate_limit_hits' => 0,
            'total_blocked'   => 0,
        );
        $saved = get_option( $this->stats_key, array() );
        return wp_parse_args( $saved, $defaults );
    }

    /**
     * Increment threat counter.
     *
     * @param string $key Counter key (e.g. honeypot_hits).
     */
    public function increment_stat( $key ) {
        $stats = $this->get_stats();
        if ( isset( $stats[ $key ] ) ) {
            $stats[ $key ]++;
        }
        $stats['total_blocked']++;
        update_option( $this->stats_key, $stats );
    }

    /**
     * Reset all threat counters to zero.
     */
    public function reset_stats() {
        delete_option( $this->stats_key );
    }
}
