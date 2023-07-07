<?php
/**
* Plugin Name: Custom OTP Login Woocommerce
* Plugin URI: https://glasierinc.com/
* Author: GlasierInc
* Version: 1.0
* Text Domain: otp-login-woocommerce
* Domain Path: /languages
* Author URI: https://glasierinc.com/
* Description: Allow users to login with OTP ( sent on their phone ) therefore removing the need to remember a password.
* Tags: woocommerce, sms login, sms, phone
*/


if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
// Enqueue scripts and styles
function custom_otp_login_enqueue_scripts() {
    wp_enqueue_script( 'otp-verification', plugin_dir_url( __FILE__ ) . 'assets/js/otp-verification.js', array( 'jquery' ), '1.0.0', true );
    wp_enqueue_style( 'otp-verification', plugin_dir_url( __FILE__ ) . 'assets/css/otp-verification.css' );
}
add_action( 'wp_enqueue_scripts', 'custom_otp_login_enqueue_scripts' );


// Replace default WooCommerce login form
function custom_otp_login_form() {
    wc_get_template( 'custom-login-form.php', array(), 'custom-otp-login', plugin_dir_path( __FILE__ ) . 'inc/' );
}
add_action( 'woocommerce_login_form', 'custom_otp_login_form' );
