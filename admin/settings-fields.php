<?php

/**
 * The admin-facing functionality of the plugin.
 *
 * @package    WP Page Permalink Extension
 * @subpackage Admin
 * @author     Sayan Datta
 * @license    http://www.gnu.org/licenses/ GNU General Public License
 */

function cwpp_custom_extension_display() {
    global $wp_rewrite;
    $cwpp_settings = get_option('cwpp_cus_extension');
    ?><input type="hidden" id="change-trigger" value="no"><code><?php echo get_site_url() ?></code>&nbsp;<input id="extension" class="cwpp-change" name="cwpp_cus_extension[cwpp_custom_extension]" type="text" size="40" required style="width:40%;" placeholder="/pages/%pagename%.html" value="<?php if (isset($cwpp_settings['cwpp_custom_extension'])) { echo $wp_rewrite->get_page_permastruct(); } ?>" />
    &nbsp;&nbsp;<span class="tooltip" title="<?php _e( 'Set custom page permalink here. Currently page permalink structure of this website is', 'change-wp-page-permalinks' ); ?> <?php echo $wp_rewrite->get_page_permastruct(); ?>."><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

function cwpp_auto_escape_slash_cb_display() {
    $cwpp_settings = get_option('cwpp_cus_extension');
    $post_id = get_option( 'page_for_posts' );
    $post = get_post( $post_id ); 
    
    ?> <label for="all-page"><input type="checkbox" id="all-page" name="cwpp_cus_extension[cwpp_auto_escape_slash_page_cb]" value="1" <?php checked(isset($cwpp_settings['cwpp_auto_escape_slash_page_cb']), 1); ?> /><?php _e( 'Pages', 'change-wp-page-permalinks' ); ?></label>
    
    <?php if ( isset( $post->post_name ) && 'page' == get_option( 'show_on_front' ) ) { ?>
    &nbsp;&nbsp;<label for="static-page"><input type="checkbox" id="static-page" name="cwpp_cus_extension[cwpp_auto_escape_slash_static_cb]" value="1" <?php checked(isset($cwpp_settings['cwpp_auto_escape_slash_static_cb']), 1); ?> /><?php _e( 'Static Posts Page', 'change-wp-page-permalinks' ); ?></label>
    <?php } ?>

    &nbsp;<span class="tooltip" title="<?php _e( 'Select on which page you want to remove trailing slashes automatically.', 'change-wp-page-permalinks' ); ?>"><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

function cwpp_add_rewrite_rule_cb_display() {
    $cwpp_settings = get_option('cwpp_cus_extension');
    ?> <input type="checkbox" id="rewrite-rule" name="cwpp_cus_extension[cwpp_add_rewrite_rule_cb]" value="1" <?php checked(isset($cwpp_settings['cwpp_add_rewrite_rule_cb']), 1); ?> /><label for="rewrite-rule" style="font-size:12px;"><?php _e( 'Yes, rewrite slug', 'change-wp-page-permalinks' ); ?> (<?php _e( 'For advanced users only', 'change-wp-page-permalinks' ); ?>)</label>
    &nbsp;&nbsp;<span class="tooltip" title="<?php _e( 'Set custom slug for your static posts page, if you want to set custom page slug for static posts page. This will add rewrite rules to make accessible static post page using custom slug. Be sure what you are doing!', 'change-wp-page-permalinks' ); ?>"><span title="" class="dashicons dashicons-editor-help"></span></span>
    <?php
}

function cwpp_add_rewrite_rule_display() {
    global $wp_rewrite;
    $cwpp_settings = get_option('cwpp_cus_extension');
    if(empty($cwpp_settings['cwpp_add_rewrite_rule'])){
        $cwpp_settings['cwpp_add_rewrite_rule'] = '';
    }
    $cwpp_slug_raw = $cwpp_settings['cwpp_add_rewrite_rule'];
    $cwpp_slug = !empty( $cwpp_slug_raw ) ? $cwpp_slug_raw : '';
    $cwpp_blog_url = str_replace( '/', '', $cwpp_slug );

    $post_id = get_option( 'page_for_posts' );
    $post = get_post( $post_id ); 
    $slug = $post->post_name;

    ?> <input id="rule" class="cwpp-change" name="cwpp_cus_extension[cwpp_add_rewrite_rule]" type="text" size="30" style="width:30%;" placeholder="<?php echo $slug; ?>" value="<?php if ( !empty( $cwpp_slug ) ) { echo $cwpp_blog_url; } ?>" />
    
    <?php if ( $wp_rewrite->using_permalinks() && $wp_rewrite->use_trailing_slashes == false ) { ?>
         &nbsp;&nbsp;<label for="add-slash"><input type="checkbox" id="add-slash" name="cwpp_cus_extension[cwpp_auto_add_slash]" value="1" <?php checked(isset($cwpp_settings['cwpp_auto_add_slash']), 1); ?> /><span style="font-size:12px;"><?php _e( 'Add Traling Slash?', 'change-wp-page-permalinks' ); ?></span></label>
        <?php 
    } ?> 

    &nbsp;&nbsp;<input type="checkbox" id="enable-redirect" name="cwpp_cus_extension[cwpp_enable_auto_redirect]" value="1" <?php checked(isset($cwpp_settings['cwpp_enable_auto_redirect']), 1); ?> /><label for="enable-redirect" style="font-size:12px;"><?php _e( 'Enable 301 Redirect?', 'change-wp-page-permalinks' ); ?></label>
    &nbsp;<a id="view-link" href="<?php echo get_home_url().'/'.$cwpp_slug; ?>" data-link="<?php echo get_home_url().'/'; ?>" target="_blank"><small><?php _e( 'View', 'change-wp-page-permalinks' ); ?></small></a>

    <?php
}