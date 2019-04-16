<?php

/**
 * The admin-facing functionality of the plugin.
 *
 * @package    WP Page Permalink Extension
 * @subpackage Includes
 * @author     Sayan Datta
 * @license    http://www.gnu.org/licenses/ GNU General Public License
 */

if( !empty($cwpp_settings['cwpp_custom_extension']) && ( isset($cwpp_settings['cwpp_add_rewrite_rule_cb']) && ($cwpp_settings['cwpp_add_rewrite_rule_cb'] == 1 ) ) && ( isset($cwpp_settings['cwpp_enable_auto_redirect']) && ($cwpp_settings['cwpp_enable_auto_redirect'] == 1 ) ) ) {
    add_action( 'template_redirect', 'cwpp_add_redirection_to_custom_url' );
}

function cwpp_add_redirection_to_custom_url() {
    global $wp_rewrite;
    $cwpp_settings = get_option('cwpp_cus_extension');

    if ( 'page' != get_option( 'show_on_front' ) ) return;

    if ( empty( $cwpp_settings['cwpp_add_rewrite_rule'] ) ) return;

    $post_id = get_option( 'page_for_posts' );
    $post = get_post( $post_id ); 
    $slug = $post->post_name;
    $cwpp_slug_raw = $cwpp_settings['cwpp_add_rewrite_rule'];
    $cwpp_slug = !empty( $cwpp_slug_raw ) ? $cwpp_slug_raw : '';
    $cwpp_blog_url = str_replace( '/', '', $cwpp_slug );
    $cwpp_site_url = get_home_url() .'/'. $cwpp_blog_url;
    if( $wp_rewrite->using_permalinks() && $wp_rewrite->use_trailing_slashes == false && isset( $cwpp_settings['cwpp_auto_add_slash']) && ($cwpp_settings['cwpp_auto_add_slash'] == 1 ) ) {
        $cwpp_site_url = $cwpp_site_url . '/';
    }
    $cwpp_get_perma = $wp_rewrite->get_page_permastruct();
    if( $wp_rewrite->using_permalinks() && $wp_rewrite->use_trailing_slashes == true && isset( $cwpp_settings['cwpp_auto_escape_slash_static_cb']) && ($cwpp_settings['cwpp_auto_escape_slash_static_cb'] == 1) ) {
        $cwpp_get_perma = $cwpp_get_perma;
    } elseif( $wp_rewrite->using_permalinks() && $wp_rewrite->use_trailing_slashes == true ) {
        $cwpp_get_perma = $cwpp_get_perma . '/';
    }
    if( $wp_rewrite->using_permalinks() && $wp_rewrite->use_trailing_slashes == false && isset( $cwpp_settings['cwpp_auto_add_slash']) && ($cwpp_settings['cwpp_auto_add_slash'] == 1 ) ) {
        $cwpp_get_perma = $cwpp_get_perma . '/';
    }
    $url = str_replace( '%pagename%', $slug, $cwpp_get_perma );
    $url = get_home_url() . $url;
    $request_uri = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

    if( $request_uri == $url ) {
        wp_safe_redirect( $cwpp_site_url, 301 );
        exit(); // prevents any accidental output
    }
}