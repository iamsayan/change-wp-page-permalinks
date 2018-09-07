<?php

/**
 * The admin-facing functionality of the plugin.
 *
 * @package    WP Page Permalink Extension
 * @subpackage Admin
 * @author     Sayan Datta
 * @license    http://www.gnu.org/licenses/ GNU General Public License
 */
?>

<div class="wrap">
    <h1><?php _e( 'WP Page Permalink Extension', 'change-wp-page-permalinks' ); ?> <span style="font-size:12px;"><?php _e( 'Ver', 'change-wp-page-permalinks' ); ?> <?php echo cwpp_print_version(); ?></span></h1>
    <div><?php _e( 'Customize wordpress page permalink structure very easily.', 'change-wp-page-permalinks' ); ?></div>
    <div id="poststuff">
        <div id="post-body" class="metabox-holder columns-2">
            <div id="post-body-content">
                <div class="postbox">
                    <h3 class="hndle" style="cursor:default;">
                        <span class="cwpp-heading">
                            <?php _e( 'Configure Settings', 'change-wp-page-permalinks' ); ?>
                        </span>
                    </h3>
                    <div class="inside">
                        <form id="saveForm" method="post" action="options.php">
                            <?php settings_fields( 'cwpp-plugin-settings-group' ); ?>
                            <?php do_settings_sections( 'cwpp_plugin_option' ); ?>
                            <p><?php submit_button( __( 'Save Changes', 'change-wp-page-permalinks' ), 'primary save-settings', '', false ); ?>&nbsp;&nbsp;<input type="submit" name="submit" id="submit" class="button flush-rules" form="flushRules" value="<?php _e( 'Flush Permalinks', 'change-wp-page-permalinks' ); ?>" />&nbsp;<span class="spinner is-active" style="float: none;margin: -2px 5px 0; display:none;"></span>&nbsp;&nbsp;<small><?php _e( 'Save Changes first before flushing permalinks!', 'change-wp-page-permalinks' ); ?></small></p>
                        </form>
                        <form id="flushRules" method="post" action="">
                            <input id="cwpp-submit" name="cwpp_submit" type="hidden" value="yes"/>
                        </form>
                        <script>
                            jQuery(document).ready(function ($) {
                                $('.flush-rules').click(function () {
                                    $(".flush-rules").addClass("button-large disabled");
                                    $(".spinner").show();
                                });
                                $("#rewrite-rule").change(function () {
                                    if ($('#rewrite-rule').is(':checked')) {
                                        $('.custom-rule').show();
                                        $('#rule').attr('required', 'required');
                                    }
                                    if (!$('#rewrite-rule').is(':checked')) {
                                        $('.custom-rule').hide();
                                        $('#rule').removeAttr('required');
                                    }
                                });
                                $("#rewrite-rule").trigger('change');
                                $("#static-page").change(function () {
                                    if ($('#static-page').is(':checked')) {
                                        $('#static-hidden').val('0');
                                    }
                                    if (!$('#static-page').is(':checked')) {
                                        $('#static-hidden').val('1');
                                    }
                                });
                                $("#static-page").trigger('change');
                            });
                        </script>
                        <br>
                        <span>
                            Developed with <span style="color:#e25555;">♥</span> by <a href="https://profiles.wordpress.org/infosatech/" target="_blank" style="font-weight: 500;">Sayan Datta</a> | <a href="https://wordpress.org/support/plugin/change-wp-page-permalinks" target="_blank" style="font-weight: 500;">Support</a> | <a href="http://bit.ly/2I0Gj60" target="_blank" style="font-weight: 500;">Donate</a> | <a href="https://wordpress.org/support/plugin/change-wp-page-permalinks/reviews/?rate=5#new-post" target="_blank" style="font-weight: 500;">Rate it</a> (<span style="color:#ffa000;">&#9733;&#9733;&#9733;&#9733;&#9733;</span>), if you like this plugin. Thank You!
                        </span>
                    </div>
                </div>
            </div>
            <div id="postbox-container-1" class="postbox-container">
                <div class="postbox">
                    <h3 class="hndle" style="cursor:default;">My Other Plugins!</h3>
                    <div class="inside">
                        <div class="misc-pub-section">
                            <span class="dashicons dashicons-clock"></span>
                            <label>
                                <strong><a href="https://wordpress.org/plugins/wp-last-modified-info/" target="_blank">WP Last Modified Info</a>: </strong>
                                    Display last modified date and time on pages and posts very easily.
                            </label>
                        </div>
                        <hr>
                        <div class="misc-pub-section">
                            <span class="dashicons dashicons-admin-links"></span>
                            <label>
                                <strong><a href="https://wordpress.org/plugins/ultimate-facebook-comments/" target="_blank">Ultimate Facebook Comments</a>: </strong>
                                    Ultimate Facebook Comments solution for WordPress.
                            </label>
                        </div>
                        <hr>
                        <div class="misc-pub-section">
                            <span class="dashicons dashicons-admin-generic"></span>
                            <label>
                                <strong><a href="https://wordpress.org/plugins/remove-wp-meta-tags/" target="_blank">Ultimate WP Header Footer</a>: </strong>
                                    The Header Footer solution for WordPress.
                            </label>
                        </div>
                    </div>
                </div>
            </diV>
        </div>
    </div>
</div>