<?php

/**
 * The admin-facing functionality of the plugin.
 *
 * @package    WP Page Permalink Extension
 * @subpackage Includes
 * @author     Sayan Datta
 * @license    http://www.gnu.org/licenses/ GNU General Public License
 */

add_action( 'add_attachment', 'flush_rewrite_rules' );
add_action( 'admin_init', 'cwpp_flush_rules_upon_attach_deattach' );

add_filter( 'attachment_link', 'cwpp_fix_media_attachment_link', 20, 2 );
add_filter( 'query_vars', 'cwpp_insert_attachment_query_vars' );
add_filter( 'rewrite_rules_array', 'cwpp_rewrite_rules_array' );

function cwpp_fix_media_attachment_link( $link, $attachment_id ) {
    global $wp_rewrite;
    $front_page = get_option( 'page_on_front' );

    $get_ext = explode( '.', $wp_rewrite->get_page_permastruct() );
    $extension = '.' . end( $get_ext );

    $attachment = get_post( $attachment_id );

    // Only for attachments actually attached to a parent post
    if( ! empty( $attachment->post_parent ) && get_post_type( $attachment->post_parent ) == 'page' ) {

        $parent_link = str_replace( $extension, '', get_permalink( $attachment->post_parent ) );
        // make the link compatible with permalink settings with or without "/" at the end
        $parent_link = rtrim( $parent_link, "/" );
        $link =  $parent_link . '/' . $attachment->post_name . $extension;
    }

    if( ! empty( $attachment->post_parent ) && $attachment->post_parent == $front_page ) {

        $get_slug = explode( '%', $wp_rewrite->get_page_permastruct() );
        $slug = current( $get_slug );
        $front_page_name = get_post_field( 'post_name', $front_page );

        $parent_link = str_replace( $extension, '', get_permalink( $attachment->post_parent ) );
        // make the link compatible with permalink settings with or without "/" at the end
        $parent_link = rtrim( $parent_link, "/" );
        $link =  $parent_link . $slug . $front_page_name . '/' . $attachment->post_name . $extension;
    }

    return $link;
}

function cwpp_insert_attachment_query_vars( $query_vars ) {
    array_push( $query_vars, 'attachment_id' );
    return $query_vars;
}

function cwpp_rewrite_rules_array( $rules ) {
    $my_rules = array();
    $attachments = get_posts( array(
        'post_type'      => 'attachment',
        'posts_per_page' => -1,
    ) );

    if ( $attachments ) {
        foreach ( $attachments as $post ) {
            if( empty( $post->post_parent ) ) {
                $my_rules['^'. $post->post_name. '/?$'] = 'index.php?attachment_id='. $post->ID;
            }
        }
    }
    return $my_rules + $rules;
}

function cwpp_flush_rules_upon_attach_deattach() {
    global $pagenow;
    if( $pagenow == 'upload.php' ) {
        if ( ( isset( $_GET['attached'] ) && $_GET['attached'] == '1' ) || ( isset( $_GET['detach'] ) && $_GET['detach'] == '1' ) ) {
            flush_rewrite_rules();
        }
    }
}