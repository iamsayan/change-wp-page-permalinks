<?php

/**
 * The admin-facing functionality of the plugin.
 *
 * @package    WP Page Permalink Extension
 * @subpackage Includes
 * @author     Sayan Datta
 * @license    http://www.gnu.org/licenses/ GNU General Public License
 */

if ( isset($cwpp_settings['cwpp_add_rewrite_rule_cb']) && ($cwpp_settings['cwpp_add_rewrite_rule_cb'] == 1 ) && !empty( $cwpp_settings['cwpp_add_rewrite_rule'] ) ) {
    add_action( 'init', 'cwpp_custom_rewrite_rule' );
    add_filter( 'page_link', 'cwpp_filter_static_permalink', 10, 2 ); 
}

function cwpp_custom_rewrite_rule() {
    $cwpp_settings = get_option('cwpp_cus_extension');
    $page_id = get_option( 'page_for_posts' );

    if ( 'page' != get_option( 'show_on_front' ) ) return;

    $cwpp_slug = $cwpp_settings['cwpp_add_rewrite_rule'];
    $cwpp_blog_url = str_replace( '/', '', $cwpp_slug );

    add_rewrite_rule( '^'. $cwpp_blog_url .'/?$', 'index.php?page_id=$matches['. $page_id .']', 'top' );
    add_rewrite_rule( '^'. $cwpp_blog_url .'/page/([0-9]+)/?$', 'index.php?page_id=$matches['. $page_id .']&paged=$matches[1]', 'top' );
}

function cwpp_filter_static_permalink( $permalink, $post_id ) {
    global $wp_rewrite;
    $cwpp_settings = get_option('cwpp_cus_extension');
    $page_id = get_option( 'page_for_posts' );

    if ( 'page' != get_option( 'show_on_front' ) ) return $permalink;

    $cwpp_slug = $cwpp_settings['cwpp_add_rewrite_rule'];

    $cwpp_new_url = $cwpp_slug . '/';
    if( isset( $cwpp_settings['cwpp_auto_escape_slash_static_cb']) && ($cwpp_settings['cwpp_auto_escape_slash_static_cb'] == 1 ) || ( $wp_rewrite->using_permalinks() && $wp_rewrite->use_trailing_slashes == false ) ) {
        $cwpp_new_url = str_replace( '/', '', $cwpp_slug );
    }
    if( $wp_rewrite->using_permalinks() && $wp_rewrite->use_trailing_slashes == false && isset( $cwpp_settings['cwpp_auto_add_slash']) && ($cwpp_settings['cwpp_auto_add_slash'] == 1 ) ) {
        $cwpp_new_url = $cwpp_slug . '/';
    }
    
    if ( empty( $post_id ) ) return $permalink;

    $post = get_post( $post_id );

    if( $post->ID == $page_id ) {
        return home_url( $cwpp_new_url );
    }

    return $permalink;
}