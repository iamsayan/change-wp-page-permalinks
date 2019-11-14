<?php

/**
 * The admin-facing functionality of the plugin.
 *
 * @package    WP Page Permalink Extension
 * @subpackage Admin
 * @author     Sayan Datta
 * @license    http://www.gnu.org/licenses/ GNU General Public License
 */

add_action( 'admin_notices', 'cwpp_rating_admin_notice' );
add_action( 'admin_init', 'cwpp_dismiss_rating_admin_notice' );

function cwpp_rating_admin_notice() {
    // Show notice after 240 hours (10 days) from installed time.
    if ( cwpp_plugin_get_installed_time() > strtotime( '-240 hours' )
        || '1' === get_option( 'cwpp_plugin_dismiss_rating_notice' )
        || ! current_user_can( 'manage_options' )
        || apply_filters( 'cwpp_plugin_show_sticky_notice', false ) ) {
        return;
    }

    $dismiss = wp_nonce_url( add_query_arg( 'cwpp_rating_notice_action', 'cwpp_dismiss_rating_true' ), 'cwpp_dismiss_rating_true' ); 
    $no_thanks = wp_nonce_url( add_query_arg( 'cwpp_rating_notice_action', 'cwpp_no_thanks_rating_true' ), 'cwpp_no_thanks_rating_true' ); ?>
    
    <div class="notice notice-success">
        <p><?php _e( 'Hey, I noticed you\'ve been using WP Page Permalink Extension for more than 1 week – that’s awesome! Could you please do me a BIG favor and give it a <strong>5-star</strong> rating on WordPress? Just to help me spread the word and boost my motivation.', 'change-wp-page-permalinks' ); ?></p>
        <p><a href="https://wordpress.org/support/plugin/change-wp-page-permalinks/reviews/?filter=5#new-post" target="_blank" class="button button-secondary"><?php _e( 'Ok, you deserve it', 'change-wp-page-permalinks' ); ?></a>&nbsp;
        <a href="<?php echo $dismiss; ?>" class="already-did"><strong><?php _e( 'I already did', 'change-wp-page-permalinks' ); ?></strong></a>&nbsp;<strong>|</strong>
        <a href="<?php echo $no_thanks; ?>" class="later"><strong><?php _e( 'Nope&#44; maybe later', 'change-wp-page-permalinks' ); ?></strong></a>&nbsp;<strong>|</strong>
        <a href="<?php echo $dismiss; ?>" class="dismiss"><strong><?php _e( 'I don\'t want to rate', 'change-wp-page-permalinks' ); ?></strong></a></p>
    </div>
<?php
}

function cwpp_dismiss_rating_admin_notice() {

    if( get_option( 'cwpp_plugin_no_thanks_rating_notice' ) === '1' ) {
        if ( get_option( 'cwpp_plugin_dismissed_time' ) > strtotime( '-168 hours' ) ) {
            return;
        }
        delete_option( 'cwpp_plugin_dismiss_rating_notice' );
        delete_option( 'cwpp_plugin_no_thanks_rating_notice' );
    }

    if ( ! isset( $_GET['cwpp_rating_notice_action'] ) ) {
        return;
    }

    if ( 'cwpp_dismiss_rating_true' === $_GET['cwpp_rating_notice_action'] ) {
        check_admin_referer( 'cwpp_dismiss_rating_true' );
        update_option( 'cwpp_plugin_dismiss_rating_notice', '1' );
    }

    if ( 'cwpp_no_thanks_rating_true' === $_GET['cwpp_rating_notice_action'] ) {
        check_admin_referer( 'cwpp_no_thanks_rating_true' );
        update_option( 'cwpp_plugin_no_thanks_rating_notice', '1' );
        update_option( 'cwpp_plugin_dismiss_rating_notice', '1' );
        update_option( 'cwpp_plugin_dismissed_time', time() );
    }

    wp_redirect( remove_query_arg( 'cwpp_rating_notice_action' ) );
    exit;
}

function cwpp_plugin_get_installed_time() {
    $installed_time = get_option( 'cwpp_plugin_installed_time' );
    if ( ! $installed_time ) {
        $installed_time = time();
        update_option( 'cwpp_plugin_installed_time', $installed_time );
    }
    return $installed_time;
}