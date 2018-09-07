<?php

/**
 * The admin-facing functionality of the plugin.
 *
 * @package    WP Page Permalink Extension
 * @subpackage Admin
 * @author     Sayan Datta
 * @license    http://www.gnu.org/licenses/ GNU General Public License
 */

function cwpp_add_redirection_to_custom_url() {

    $cwpp_settings = get_option('cwpp_cus_extension');
    $page_id = get_option( 'page_for_posts' );

    if ( 'page' != get_option( 'show_on_front' ) ) return;

    if ( empty( $cwpp_settings['cwpp_add_rewrite_rule'] ) ) return;

    $cwpp_url = $cwpp_settings['cwpp_add_rewrite_rule'];
    $cwpp_blog_url = str_replace( '/', '', $cwpp_url );

    $request_uri = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

    $url = get_permalink( $page_id );
    if( $request_uri == $url ) {
        wp_redirect( site_url( $cwpp_blog_url ), 301 );
        exit(); // prevents any accidental output
    }
}

//add_action( 'template_redirect', 'cwpp_add_redirection_to_custom_url' );
