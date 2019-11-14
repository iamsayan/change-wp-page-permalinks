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
    <div class="head-wrap">
        <h1 class="title">WP Page Permalink Extension<span class="title-count"><?php echo CWPP_PLUGIN_VERSION ?></span></h1>
        <div><?php _e( 'Customize wordpress page permalink structure very easily.', 'change-wp-page-permalinks' ); ?></div><hr>
        <div class="top-sharebar">
            <a class="share-btn rate-btn" href="https://wordpress.org/support/plugin/change-wp-page-permalinks/reviews/?filter=5#new-post" target="_blank" title="<?php _e( 'Please rate 5 stars if you like WP Page Permalink Extension', 'change-wp-page-permalinks' ); ?>"><span class="dashicons dashicons-star-filled"></span> <?php _e( 'Rate 5 stars', 'change-wp-page-permalinks' ); ?></a>
            <a class="share-btn twitter" href="https://twitter.com/intent/tweet?text=Checkout%20WP%20Page%20Permalink%20Extension,%20a%20%23WordPress%20%23plugin%20that%20helps%20to%20customize%20wordpress%20page%20permalink%20structure%20very%20easily.&tw_p=tweetbutton&url=https://wordpress.org/plugins/change-wp-page-permalinks/&via=im_sayaan" target="_blank"><span class="dashicons dashicons-twitter"></span> <?php _e( 'Tweet about WP Page Permalink Extension', 'change-wp-page-permalinks' ); ?></a>
        </div>
    </div>
    <div id="poststuff" style="padding-top: 0;">
        <div id="post-body" class="metabox-holder columns-2">
            <div id="post-body-content">
                <div class="postbox">
                    <h3 class="hndle" style="cursor:default;">
                        <span class="cwpp-heading">
                            <?php _e( 'Configure Settings', 'change-wp-page-permalinks' ); ?>
                        </span>
                    </h3>
                    <div class="inside">
                        <form id="saveForm" method="post" action="options.php" style="padding-left: 8px;">
                            <?php settings_fields( 'cwpp_plugin_settings_fields' ); ?>
                            <?php do_settings_sections( 'cwpp_plugin_option' ); ?>
                            <?php submit_button( __( 'Save Settings', 'change-wp-page-permalinks' ), 'primary save-settings', 'save-permalinks' ); ?>
                            <div id="progressMessage" class="progressModal" style="display:none;">
                                <?php _e( 'Please wait...', 'change-wp-page-permalinks' ); ?>
                            </div>
                            <div id="saveMessage" class="successModal" style="display:none;">
                                <p class="fbak-success-msg">
                                    <?php _e( 'Settings Saved Successfully!', 'change-wp-page-permalinks' ); ?>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="coffee-box">
                    <div class="coffee-amt-wrap">
                        <p><select class="coffee-amt">
                            <option value="5usd">$5</option>
                            <option value="6usd">$6</option>
                            <option value="7usd">$7</option>
                            <option value="8usd">$8</option>
                            <option value="9usd">$9</option>
                            <option value="10usd" selected="selected">$10</option>
                            <option value="11usd">$11</option>
                            <option value="12usd">$12</option>
                            <option value="13usd">$13</option>
                            <option value="14usd">$14</option>
                            <option value="15usd">$15</option>
                            <option value=""><?php _e( 'Custom', 'change-wp-page-permalinks' ); ?></option>
                        </select></p>
                        <a class="button button-primary buy-coffee-btn" href="https://www.paypal.me/iamsayan/10usd" data-link="https://www.paypal.me/iamsayan/" target="_blank"><?php _e( 'Buy me a coffee!', 'change-wp-page-permalinks' ); ?></a>
                    </div>
                    <span class="coffee-heading"><?php _e( 'Buy me a coffee!', 'change-wp-page-permalinks' ); ?></span>
                    <p style="text-align: justify;"><?php printf( __( 'Thank you for using %s. If you found the plugin useful buy me a coffee! Your donation will motivate and make me happy for all the efforts. You can donate via PayPal.', 'change-wp-page-permalinks' ), '<strong>WP Page Permalink Extension v' . CWPP_PLUGIN_VERSION . '</strong>' ); ?></strong></p>
                    <p style="text-align: justify; font-size: 12px; font-style: italic;">Developed with <span style="color:#e25555;">♥</span> by <a href="https://about.me/iamsayan" target="_blank" style="font-weight: 500;">Sayan Datta</a> | <a href="https://github.com/iamsayan/change-wp-page-permalinks" target="_blank" style="font-weight: 500;">GitHub</a> | <a href="https://wordpress.org/support/plugin/change-wp-page-permalinks" target="_blank" style="font-weight: 500;">Support</a> | <a href="https://wordpress.org/support/plugin/change-wp-page-permalinks/reviews/?filter=5#new-post" target="_blank" style="font-weight: 500;">Rate it</a> (<span style="color:#ffa000;">&#9733;&#9733;&#9733;&#9733;&#9733;</span>) on WordPress.org, if you like this plugin.</p>
                </div>
            </div>
            <div id="postbox-container-1" class="postbox-container">
                <div class="postbox">
                    <h3 class="hndle" style="cursor: default;text-align: center;"><?php _e( 'My Other Plugins!', 'change-wp-page-permalinks' ); ?></h3>
                    <div class="inside">
                        <div class="misc-pub-section">
                            <span class="dashicons dashicons-clock"></span>
                            <label>
                                <strong><a href="https://wordpress.org/plugins/wp-last-modified-info/" target="_blank">WP Last Modified Info</a>: </strong>
                                <?php _e( 'Display last updated info on posts and pages with \'dateModified\' Schema Markup.', 'change-wp-page-permalinks' ); ?>
                            </label>
                        </div>
                        <hr>
                        <div class="misc-pub-section">
                            <span class="dashicons dashicons-admin-comments"></span>
                            <label>
                                <strong><a href="https://wordpress.org/plugins/ultimate-facebook-comments/" target="_blank">Ultimate Social Comments</a>: </strong>
                                <?php _e( 'Ultimate Facebook Comment Solution with instant email notification.', 'change-wp-page-permalinks' ); ?>
                            </label>
                        </div>
                        <hr>
                        <div class="misc-pub-section">
                            <span class="dashicons dashicons-megaphone"></span>
                            <label>
                                <strong><a href="https://wordpress.org/plugins/simple-posts-ticker/" target="_blank">Simple Posts Ticker</a>: </strong>
                                <?php _e( 'Simple Posts Ticker is a small tool that shows your most recent posts in a marquee style.', 'change-wp-page-permalinks' ); ?>
                            </label>
                        </div>
                        <hr>
                        <div class="misc-pub-section">
                            <span class="dashicons dashicons-admin-generic"></span>
                            <label>
                                <strong><a href="https://wordpress.org/plugins/wp-auto-republish/" target="_blank">WP Auto Republish</a>: </strong>
                                <?php _e( 'Automatically republish you old evergreen content to grab better SEO.', 'change-wp-page-permalinks' ); ?>
                            </label>
                        </div>
                        <hr>
                        <div class="misc-pub-section">
                            <span class="dashicons dashicons-admin-generic"></span>
                            <label>
                                <strong><a href="https://wordpress.org/plugins/remove-wp-meta-tags/" target="_blank">Easy Header Footer</a>: </strong>
                                <?php _e( 'Add custom code and remove the unwanted meta tags, links from the source code and many more.', 'change-wp-page-permalinks' ); ?>
                            </label>
                        </div>
                    </div>
                </div>
            </diV>
        </div>
    </div>
</div>