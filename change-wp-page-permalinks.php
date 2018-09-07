<?php
/**
 * Plugin Name: WP Page Permalink Extension
 * Plugin URI: https://wordpress.org/plugins/change-wp-page-permalinks/
 * Description: WP Page Permalink Extension plugin will help you to add anything like .html, .php, .aspx, .htm, .asp, .shtml as WordPress Page Extention.
 * Version: 1.4.7
 * Author: Sayan Datta
 * Author URI: https://profiles.wordpress.org/infosatech/
 * License: GPLv3
 * Text Domain: change-wp-page-permalinks
 * Domain Path: /languages
 * 
 * WP Page Permalink Extension is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * WP Page Permalink Extension is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with WP Page Permalink Extension. If not, see <http://www.gnu.org/licenses/>.
 *
 * @category Core
 * @package  WP Page Permalink Extension
 * @author   Sayan Datta
 * @license  http://www.gnu.org/licenses/ GNU General Public License
 * @link     https://wordpress.org/plugins/change-wp-page-permalinks/
 */

//Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Internationalization
add_action( 'plugins_loaded', 'cwpp_plugin_load_textdomain' );
/**
 * Load plugin textdomain.
 * 
 * @since 1.4.2
 */
function cwpp_plugin_load_textdomain() {
    load_plugin_textdomain( 'change-wp-page-permalinks', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 
}

function cwpp_print_version() {
    // fetch plugin version
    $cwpppluginfo = get_plugin_data(__FILE__);
    $cwppversion = $cwpppluginfo['Version'];    
    return $cwppversion;
}

function cwpp_plugin_activation() {

    global $wp_rewrite;

    if ( ! current_user_can( 'activate_plugins' ) ) {
        return;
	}

    $cwpp_settings = get_option( 'cwpp_cus_extension' );
    $cwpp_extension = $cwpp_settings['cwpp_custom_extension'];

    if ( !empty( $cwpp_extension ) ) {

        $cwpp_extension = preg_replace( '#/+#', '/', '/' . str_replace( '#', '', $cwpp_extension ) );

        if ( strpos( $wp_rewrite->get_page_permastruct(), $cwpp_extension ) === false  ) {
            $wp_rewrite->page_structure = $wp_rewrite->root . $cwpp_extension;
            flush_rewrite_rules();
        }
    }
}

function cwpp_plugin_deactivation() {

    global $wp_rewrite;

    if ( ! current_user_can( 'activate_plugins' ) ) {
        return;
	}

    $cwpp_settings = get_option( 'cwpp_cus_extension' );
    $cwpp_extension = $cwpp_settings['cwpp_custom_extension'];

    if ( !empty( $cwpp_extension ) ) {

        $wp_rewrite->page_structure = $wp_rewrite->root . '%pagename%';
        flush_rewrite_rules();
    }

    // debug
    //$plugin_option = 'cwpp_cus_extension';
    //delete_option( $plugin_option );
}

// register activation hook
register_activation_hook( __FILE__, 'cwpp_plugin_activation' );
// register deactivation hook
register_deactivation_hook( __FILE__, 'cwpp_plugin_deactivation' );

add_action( 'init', 'cwpp_enable_custom_page_ext', -1 );

function cwpp_enable_custom_page_ext() {

    global $wp_rewrite;

    $cwpp_settings = get_option( 'cwpp_cus_extension' );
    $cwpp_extension = $cwpp_settings['cwpp_custom_extension'];

    if ( !empty( $cwpp_extension ) ) {

        $cwpp_extension = preg_replace( '#/+#', '/', '/' . str_replace( '#', '', $cwpp_extension ) );

        if ( strpos( $wp_rewrite->get_page_permastruct(), $cwpp_extension ) === false ) {
            $wp_rewrite->page_structure = $wp_rewrite->root . $cwpp_extension;
        }
    }
   
}

$cwpp_settings = get_option('cwpp_cus_extension');

function cwpp_custom_rewrite_rule() {

    $cwpp_settings = get_option('cwpp_cus_extension');
    $page_id = get_option( 'page_for_posts' );

    if ( 'page' != get_option( 'show_on_front' ) ) return;

    $cwpp_slug = $cwpp_settings['cwpp_add_rewrite_rule'];
    $cwpp_blog_url = str_replace( '/', '', $cwpp_slug );

    add_rewrite_rule( '^'. $cwpp_blog_url .'/?$', 'index.php?page_id=$matches['. $page_id .']', 'top');
    add_rewrite_rule( '^'. $cwpp_blog_url .'/page/([0-9]+)/?$', 'index.php?page_id=$matches['. $page_id .']&paged=$matches[1]', 'top');
}

if ( isset($cwpp_settings['cwpp_add_rewrite_rule_cb']) && ($cwpp_settings['cwpp_add_rewrite_rule_cb'] == 1 ) && !empty( $cwpp_settings['cwpp_add_rewrite_rule'] ) ) {
    
    add_action('init', 'cwpp_custom_rewrite_rule');
    add_filter('page_link', 'cwpp_filter_static_permalink', 10, 2); 
}

function cwpp_filter_static_permalink( $permalink, $post_id ) {

    $cwpp_settings = get_option('cwpp_cus_extension');
    $page_id = get_option( 'page_for_posts' );

    if ( 'page' != get_option( 'show_on_front' ) ) return $permalink;

    $cwpp_slug = $cwpp_settings['cwpp_add_rewrite_rule'];

    if( isset( $cwpp_settings['cwpp_hidden_static_cb']) && ($cwpp_settings['cwpp_hidden_static_cb'] == 1 ) || isset($cwpp_settings['cwpp_auto_add_slash']) && ($cwpp_settings['cwpp_auto_add_slash'] == 1) ) {
        $cwpp_blog_url = $cwpp_slug . '/';
    } else {
        $cwpp_blog_url = str_replace( '/', '', $cwpp_slug );
    }
    
    if ( empty( $post_id ) ) return $permalink;

    $post = get_post( $post_id );

    if( $post->ID == $page_id ) {
        return home_url( $cwpp_blog_url );
    }
    return $permalink;
}

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

add_filter( 'user_trailingslashit', 'cwpp_no_page_trailing_slash', 66, 2 );

// register settings
add_action( 'admin_init', 'cwpp_register_plugin_settings' );

function cwpp_register_plugin_settings() {

    global $wp_rewrite;
    $post_id = get_option( 'page_for_posts' );
    $post = get_post( $post_id );

    add_settings_section('cwpp_plugin_section', '', null, 'cwpp_plugin_option');

    add_settings_field('cwpp_custom_extension', __( 'Custom Permalink for Pages:', 'change-wp-page-permalinks' ), 'cwpp_custom_extension_display', 'cwpp_plugin_option', 'cwpp_plugin_section', array( 'label_for' => 'extension' ));
    // check if wp permalink includes traling slashes
    if ( $wp_rewrite->using_permalinks() && $wp_rewrite->use_trailing_slashes == true ) {
        add_settings_field('cwpp_auto_escape_slash_cb', __( 'Auto Escape Trailing Slashes on:', 'change-wp-page-permalinks' ), 'cwpp_auto_escape_slash_cb_display', 'cwpp_plugin_option', 'cwpp_plugin_section', array( 'label_for' => 'slash' ));
    }
    // check if post page is a static page
    if ( isset( $post->post_name ) && 'page' == get_option( 'show_on_front' ) ) {
        add_settings_field('cwpp_add_rewrite_rule_cb', __( 'Rewrite Static Posts Page Slug?', 'change-wp-page-permalinks' ), 'cwpp_add_rewrite_rule_cb_display', 'cwpp_plugin_option', 'cwpp_plugin_section', array( 'label_for' => 'rewrite-rule' ));
        add_settings_field('cwpp_add_rewrite_rule', __( 'Custom Slug for Posts Page:', 'change-wp-page-permalinks' ), 'cwpp_add_rewrite_rule_display', 'cwpp_plugin_option', 'cwpp_plugin_section', array( 'label_for' => 'rule', 'class' => 'custom-rule' ));
    }
    
    //register settings
	register_setting( 'cwpp-plugin-settings-group', 'cwpp_cus_extension' );
}

require_once plugin_dir_path( __FILE__ ) . 'admin/settings-fields.php';

// register admin menu
add_action( 'admin_menu', 'cwpp_admin_menu' );

function cwpp_admin_menu() {
    //Add admin menu option
    add_submenu_page( 'options-general.php', __( 'WP Page Permalink Extension', 'change-wp-page-permalinks' ), __( 'WP Page Permalink', 'change-wp-page-permalinks' ), 'manage_options', 'wp-page-permalink-extension', 'cwpp_plugin_settings_page' );
}

function cwpp_load_admin_css() {
    // get current screen
    $current_screen = get_current_screen();
    if ( strpos( $current_screen->base, 'wp-page-permalink-extension') !== false ) {
        wp_enqueue_style( 'cwpp_styles', plugins_url( 'admin/css/admin.min.css', __FILE__ ), array(), cwpp_print_version() );
    }
}

add_action( 'admin_enqueue_scripts', 'cwpp_load_admin_css' );

function cwpp_plugin_settings_page() { 
    // get plugin option
    $cwpp_settings = get_option( 'cwpp_cus_extension' ); 
    global $wp_rewrite;
    
    if ( isset( $_POST['cwpp_submit'] ) && $_POST['cwpp_submit'] == 'yes' ) {
        flush_rewrite_rules();
        echo '<div id="message" class="notice notice-success is-dismissible">';
			echo '<p><strong>' . __( 'Permalink structure updated.', 'change-wp-page-permalinks' ) . '</strong></p>';
		echo '</div>';
	}
    require_once plugin_dir_path( __FILE__ ) . 'admin/settings-page.php';
}

// add action links
function cwpp_add_action_links ( $links ) {
    $cwpplinks = array(
        '<a href="' . admin_url( 'options-general.php?page=wp-page-permalink-extension' ) . '">' . __( 'Settings', 'change-wp-page-permalinks' ) . '</a>',
    );
    return array_merge( $cwpplinks, $links );
}

function cwpp_plugin_meta_links( $links, $file ) {
    $plugin = plugin_basename(__FILE__);
    if ($file == $plugin) // only for this plugin
        return array_merge( $links, 
            array( '<a href="https://wordpress.org/support/plugin/change-wp-page-permalinks" target="_blank">' . __( 'Support', 'change-wp-page-permalinks' ) . '</a>' ),
            array( '<a href="http://bit.ly/2I0Gj60" target="_blank">' . __( 'Donate', 'change-wp-page-permalinks' ) . '</a>' )
        );
    return $links;
}

// plugin action links
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'cwpp_add_action_links', 10, 2 );

// plugin row elements
add_filter( 'plugin_row_meta', 'cwpp_plugin_meta_links', 10, 2 );

// turn off yoast seo sitemap caching 
// debug
//add_filter('wpseo_enable_xml_sitemap_transient_caching', '__return_false');