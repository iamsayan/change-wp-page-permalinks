<?php
/**
 * Plugin Name: WP Page Permalink Extension
 * Plugin URI: https://wordpress.org/plugins/change-wp-page-permalinks/
 * Description: WP Page Permalink Extension plugin will help you to add anything like .html, .php, .aspx, .htm, .asp, .shtml as WordPress Page Extention.
 * Version: 1.5.4
 * Author: Sayan Datta
 * Author URI: https://about.me/iamsayan
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

define( 'CWPP_PLUGIN_VERSION', '1.5.4' );

// debug scripts
//define( 'CWPP_PLUGIN_ENABLE_DEBUG', 'true' );

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

// register activation hook
register_activation_hook( __FILE__, 'cwpp_plugin_activation' );
// register deactivation hook
register_deactivation_hook( __FILE__, 'cwpp_plugin_deactivation' );

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
    
    delete_option( 'cwpp_plugin_dismiss_rating_notice' );
    delete_option( 'cwpp_plugin_no_thanks_rating_notice' );
    delete_option( 'cwpp_plugin_installed_time' );

    $cwpp_settings = get_option( 'cwpp_cus_extension' );
    $cwpp_extension = $cwpp_settings['cwpp_custom_extension'];
    if ( !empty( $cwpp_extension ) ) {
        $wp_rewrite->page_structure = $wp_rewrite->root . '%pagename%';
        flush_rewrite_rules();
    }
}

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

require_once plugin_dir_path( __FILE__ ) . 'includes/media.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/redirect.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/rewrite.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/trailing-slash.php';

add_action( 'admin_enqueue_scripts', 'cwpp_load_admin_css' );

function cwpp_load_admin_css() {
    $ver = CWPP_PLUGIN_VERSION;
    if( defined( 'CWPP_PLUGIN_ENABLE_DEBUG' ) ) {
        $ver = time();
    }

    // get current screen
    $current_screen = get_current_screen();
    if ( strpos( $current_screen->base, 'wp-page-permalink-extension') !== false ) {
        wp_enqueue_style( 'cwpp_style', plugins_url( 'admin/css/admin.min.css', __FILE__ ), array(), $ver );
        wp_enqueue_script( 'cwpp-admin', plugins_url( 'admin/js/admin.min.js', __FILE__ ), array(), $ver, true );
        wp_localize_script( 'cwpp-admin', 'CWPPLocalizeScript', array(
            'ajaxurl'  => admin_url( 'admin-ajax.php' ),
            'saving'   => __( 'Saving...', 'change-wp-page-permalinks' ),
            'savemsg'  => __( 'Save Settings', 'change-wp-page-permalinks' ),
        ) );
    }
}

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
    register_setting( 'cwpp_plugin_settings_fields', 'cwpp_cus_extension' );
}

require_once plugin_dir_path( __FILE__ ) . 'admin/settings-fields.php';

// register admin menu
add_action( 'admin_menu', 'cwpp_admin_menu' );

function cwpp_admin_menu() {
    //Add admin menu option
    add_submenu_page( 'options-general.php', __( 'WP Page Permalink Extension', 'change-wp-page-permalinks' ), __( 'WP Page Extension', 'change-wp-page-permalinks' ), 'manage_options', 'wp-page-permalink-extension', 'cwpp_plugin_settings_page' );
}

function cwpp_plugin_settings_page() { 
    require_once plugin_dir_path( __FILE__ ) . 'admin/settings-page.php';
}

function cwpp_ajax_save_admin_scripts() {
    if ( is_admin() ) { 
        // Embed the Script on our Plugin's Option Page Only
        if ( isset($_GET['page']) && $_GET['page'] == 'wp-page-permalink-extension' ) {
            wp_enqueue_script( 'jquery' );
            wp_enqueue_script( 'jquery-form' );
        }
    }
}

add_action( 'admin_init', 'cwpp_ajax_save_admin_scripts' );
add_action( 'wp_ajax_cwpp_trigger_flush_rewrite_rules', 'cwpp_trigger_flush_rewrite_rules' );

function cwpp_trigger_flush_rewrite_rules() {
    flush_rewrite_rules();
}

require_once plugin_dir_path( __FILE__ ) . 'admin/donate.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/notice.php';

// add action links
function cwpp_add_action_links ( $links ) {
    $cwpplinks = array(
        '<a href="' . admin_url( 'options-general.php?page=wp-page-permalink-extension' ) . '">' . __( 'Settings', 'change-wp-page-permalinks' ) . '</a>',
    );
    return array_merge( $cwpplinks, $links );
}

function cwpp_plugin_meta_links( $links, $file ) {
    $plugin = plugin_basename(__FILE__);
    if ( $file == $plugin ) // only for this plugin
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