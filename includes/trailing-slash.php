<?php

/**
 * The admin-facing functionality of the plugin.
 *
 * @package    WP Page Permalink Extension
 * @subpackage Includes
 * @author     Sayan Datta
 * @license    http://www.gnu.org/licenses/ GNU General Public License
 */

add_filter( 'user_trailingslashit', 'cwpp_no_page_trailing_slash', 66, 2 );

function cwpp_no_page_trailing_slash( $string, $type ) {

    global $wp_rewrite;
    $cwpp_settings = get_option('cwpp_cus_extension');

    if( isset($cwpp_settings['cwpp_auto_escape_slash_page_cb']) && ($cwpp_settings['cwpp_auto_escape_slash_page_cb'] == 1 ) ) {
        if ( $wp_rewrite->using_permalinks() && $wp_rewrite->use_trailing_slashes == true  && $type == 'page' ) {
            return untrailingslashit( $string );
        }
    }
    
    if( isset($cwpp_settings['cwpp_auto_escape_slash_static_cb']) && ($cwpp_settings['cwpp_auto_escape_slash_static_cb'] == 1 ) ) {
        if( $wp_rewrite->using_permalinks() && $wp_rewrite->use_trailing_slashes == true && $type == 'home' ) {
            return untrailingslashit( $string );
        }
    }
    
    if( isset($cwpp_settings['cwpp_add_rewrite_rule_cb']) && ($cwpp_settings['cwpp_add_rewrite_rule_cb'] == 1 ) && !empty( $cwpp_settings['cwpp_add_rewrite_rule'] ) && isset($cwpp_settings['cwpp_auto_add_slash']) && ($cwpp_settings['cwpp_auto_add_slash'] == 1) ) {
        if( $wp_rewrite->using_permalinks() && $wp_rewrite->use_trailing_slashes == false && $type == 'home' ) {
            return trailingslashit( $string );
        }
    }
    
    return $string;
}
