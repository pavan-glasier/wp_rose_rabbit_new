<?php
/**
 * Plugin Name:     Mail SMTP Phpmailer 
 * Plugin URI:      https://glasierinc.com/
 * Description:     Send mail via phpmailer.
 * Author:          GlasierInc
 * Author URI:      https://glasierinc.com/
 * Text Domain:     mail-smtp-phpmailer
 */

 if( ! defined('ABSPATH') ){
    die('-1');
 }
 define('SMTP_PLUGIN_DIR', plugins_url('', __FILE__));

 include_once('inc/backend.php');


register_activation_hook( __FILE__,  'mail_smtp_install_default_value' );
function mail_smtp_install_default_value() {
    update_option('_mail_smtp_enabled', '1');
    update_option('_mail_smtp_host', 'smtp.gmail.com');
    update_option('_mail_smtp_port', '587');
    update_option('_mail_smtp_secure', 'tls');
    update_option('_mail_smtp_auth', '0');
    update_option('_mail_smtp_username', '');
    update_option('_mail_smtp_password','');
    update_option('_mail_smtp_from', '');
    update_option('_mail_smtp_from_name', bloginfo( 'name' ));
    update_option('_mail_smtp_content_type', 'html');
}

function mail_smtp_styles(){
   wp_enqueue_style( 'mail-smtp-style', SMTP_PLUGIN_DIR. '/assets/css/backend.css', array(), true );
}
add_action( 'admin_enqueue_scripts', 'mail_smtp_styles' );
