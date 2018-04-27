<?php
/*
Plugin Name: WordPress Page Extension
Plugin URI: https://wordpress.org/plugins/change-wp-page-permalinks/
Description: This plugin helps to add anything like .html, .php as WordPress Page Extention.
Version: 1.3.0
Author: Sayan Datta
License: GPLv3
Text Domain: change-wp-page-permalinks
*/

/*  This plugin helps to add anything as WordPress Page Extention.

    Copyright 2018 Sayan Datta

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
	
*/

//Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


//Define custom permalink
add_action( 'init', 'cwpp_enable_custom_page_ext' );


function cwpp_enable_custom_page_ext() {

    // check if variable contains value
    if (!empty ( get_option('cwpp_cus_extension') ) ) {
    
        global $wp_rewrite;
        $wp_rewrite->page_structure = $wp_rewrite->root . esc_attr( get_option('cwpp_cus_extension') );
    } 
    
    flush_rewrite_rules();

}


add_action( 'admin_menu', 'cwpp_admin_menu' );

function cwpp_admin_menu() {

    //Add admin menu option
    add_submenu_page( 'options-general.php', 'WordPress Page Extension', 'Page Extension', 'manage_options', 'wp-page-extension', 'cwpp_plugin_settings_page' );

    //call register settings function
    add_action( 'admin_init', 'cwpp_register_plugin_settings' );

}

function cwpp_register_plugin_settings() {
	
    //register settings
	register_setting( 'cwpp-plugin-settings-group', 'cwpp_cus_extension' );

}

function cwpp_plugin_settings_page() { ?>

<div class="wrap">

    <h1>WordPress Page Extention</h1>

    <form method="post" action="options.php">
    
	    <?php settings_fields( 'cwpp-plugin-settings-group' ); ?>
        <?php do_settings_sections( 'cwpp-plugin-settings-group' ); ?>
	
	    <table class="form-table">
				<tr><th scope="row"><label for="cwpp-field">Custom WP Page Structure:</label></th>
					<td><input name="cwpp_cus_extension" id="cwpp-field" type="text" size="65"
						       value="<?php echo esc_attr( get_option('cwpp_cus_extension') ); ?>" placeholder="pages/%pagename%.html" />
						<p><b>Note:</b> Use trailing slash only if it has been set in the <a href="./options-permalink.php" target="_blank">permalink structure</a>.
						</p></td></tr>
		</table>

		<?php submit_button(); ?>
    
	</form>

    <span>If you like this plugin, please rate (&#9733;&#9733;&#9733;&#9733;&#9733;) this plugin. <a href="https://wordpress.org/plugins/change-wp-page-permalinks/#reviews" target="_blank">Click here</a>  to rate this plugin. Thank you!</span>
		
</div> <?php

}
