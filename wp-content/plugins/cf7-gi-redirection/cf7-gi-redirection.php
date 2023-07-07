<?php
/*
* Plugin Name: 			CF7 Redirection Thank You Page
* Description: 			This is the cf7 redirection on thank you page plugin. 
* Plugin URI:         	https://www.glasierinc.com/
* Author:             	GlasierInc
* Author URI:         	https://www.glasierinc.com/
* Version:            	1.0
* Licence:            	GPL v2 or later
*/
if ( ! defined( 'ABSPATH' ) ) {
	die();
}
class CF7Redirection{
    function __construct(){
        // check to make sure contact form 7 is installed and active
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		if ( is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) {
			// public includes
			include_once('includes/redirect-methods.php');
			include_once('includes/enqueue.php');
			// admin includes
			if (is_admin()) {
				include_once('includes/admin/tab-content.php');
			}
		} else {
			// give warning if contact form 7 is not active
			function cf7_gi_admin_notice() { ?>
			<div class="error">
				<p><?php _e( '<b>Contact Form 7 - Redirect Page:</b> Contact Form 7 is not installed and / or active! Please install or activate: <a target="_blank" href="https://wordpress.org/plugins/contact-form-7/">Contact Form 7</a>.', 'cf7_gi' ); ?>
				</p>
			</div>
			<?php }
			add_action( 'admin_notices', 'cf7_gi_admin_notice' );
		}
    }    
}

if( class_exists('CF7Redirection') ){
    $CF7Redirection = new CF7Redirection();
}
// register_activation_hook( __FILE__,  array($CF7Redirection, 'activate') );
// register_deactivation_hook( __FILE__,  array($CF7Redirection, 'deactivate') );