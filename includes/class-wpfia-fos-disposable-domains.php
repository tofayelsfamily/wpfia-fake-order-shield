<?php
/**
 * WPFIA Fake Order Shield - Offline Disposable Email Repository
 *
 * Fast O(1) hash lookup for disposable and throwaway email domains.
 * Zero external API requests required.
 *
 * @package WPFIA_Fake_Order_Shield
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WPFIA_FOS_Disposable_Domains {

    /**
     * Curated list of known disposable and throwaway email domains.
     *
     * @var array
     */
    private static $domains = array(
        '10minutemail.com'       => 1,
        '10minutemail.net'       => 1,
        'burnermail.io'          => 1,
        'crazymailing.com'       => 1,
        'dispostable.com'        => 1,
        'dropmail.me'            => 1,
        'emailondeck.com'        => 1,
        'fakeinbox.com'          => 1,
        'fakemailgenerator.com'  => 1,
        'generator.email'        => 1,
        'getairmail.com'         => 1,
        'getnada.com'            => 1,
        'guerrillamail.biz'      => 1,
        'guerrillamail.com'      => 1,
        'guerrillamail.de'       => 1,
        'guerrillamail.net'      => 1,
        'guerrillamail.org'      => 1,
        'inboxkitten.com'        => 1,
        'mailcatch.com'          => 1,
        'mailinator.com'         => 1,
        'mailnesia.com'          => 1,
        'mohmal.com'             => 1,
        'mytemp.email'           => 1,
        'nada.ltd'               => 1,
        'sharklasers.com'        => 1,
        'temp-mail.org'          => 1,
        'tempail.com'            => 1,
        'tempmail.com'           => 1,
        'tempmail.net'           => 1,
        'throwawaymail.com'      => 1,
        'trashmail.com'          => 1,
        'trashmail.net'          => 1,
        'yopmail.com'            => 1,
        'yopmail.fr'             => 1,
        'yopmail.net'            => 1,
    );

    /**
     * Check if an email address belongs to a disposable domain.
     *
     * @param string $email Email address to verify.
     * @param string $custom_domains_string Custom domains entered by store owner.
     * @return bool True if disposable/spam domain, false otherwise.
     */
    public static function is_disposable( $email, $custom_domains_string = '' ) {
        if ( ! is_email( $email ) ) {
            return false;
        }

        $parts = explode( '@', strtolower( trim( $email ) ) );
        if ( 2 !== count( $parts ) ) {
            return false;
        }

        $domain = $parts[1];

        // 1. Check built-in O(1) repository
        if ( isset( self::$domains[ $domain ] ) ) {
            return true;
        }

        // 2. Check custom user-defined blocked domains
        if ( ! empty( $custom_domains_string ) ) {
            $custom_list = array_filter( array_map( 'trim', preg_split( '/[\r\n,]+/', strtolower( $custom_domains_string ) ) ) );
            if ( in_array( $domain, $custom_list, true ) ) {
                return true;
            }
        }

        return false;
    }
}
