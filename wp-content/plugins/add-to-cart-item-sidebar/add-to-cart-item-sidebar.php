<?php
/**
 * Plugin Name:     Mini Cart Sidebar For Woocommerce 
 * Plugin URI:      https://glasierinc.com/
 * Description:     Mini cart sidebar for woocommerce and ajax add to cart and product slider.
 * Author:          GlasierInc
 * Author URI:      https://glasierinc.com/
 * Text Domain:     mini-cart-sidebar-for-woocommerce
 */

 if( ! defined('ABSPATH') ){
    die('-1');
 }
// define for base name
define('MCSFW_BASE_NAME', plugin_basename(__FILE__));

// define for plugin file
define('MCSFW_PLUGIN_FILE', __FILE__);

// define for plugin dir path
define('MCSFW_PLUGIN_DIR', plugins_url('', __FILE__));


include_once('inc/cart-count.php');
// include_once('inc/ajax-add-to-cart.php');

include_once('inc/sidebar-backend.php');
include_once('inc/sidebar-frontend.php');


function MCSFW_load_script_style(){
   wp_enqueue_script('jquery', false, array(), false, false);
   
   // wp_enqueue_script( 'jquery-cartsidebar', MCSFW_PLUGIN_DIR. '/assets/js/mcsfw_fronted.js', array('jquery'), '1.0');
   wp_enqueue_script( 'jquery-effects-core' );
   // $passarray =  array( 
   //     'ajaxurl' => admin_url( 'admin-ajax.php' ),
   //     'product' => get_option('atcaiofw_product'),
   //     'basekt_position' => get_option('basekt_position'),
   // );
   // wp_localize_script( 'jquery-cartsidebar', 'addtocart_sidebar', $passarray);
   wp_enqueue_style( 'jquery-cartsidebar-style', MCSFW_PLUGIN_DIR. '/assets/css/mcsfw_fronted.css', array(), true );
   wp_enqueue_script( 'jquery-cartsidebars', MCSFW_PLUGIN_DIR. '/assets/js/mcsfw_fontawesome.js', array('jquery'), '1.0');
}
add_action( 'wp_enqueue_scripts', 'MCSFW_load_script_style' );

function MCSFW_load_admin_script(){
   wp_enqueue_script('jquery', false, array(), false, false);
   wp_enqueue_style( 'wp-color-picker' );
   wp_enqueue_script( 'wp-color-picker-alpha', MCSFW_PLUGIN_DIR . '/assets/js/wp-color-picker-alpha.js', array( 'wp-color-picker' ), '1.0', true );
   wp_enqueue_style( 'jquery-admin-style', MCSFW_PLUGIN_DIR. '/assets/css/mcsfw_backend.css', array(), true );
   wp_enqueue_style( 'jquery-admin-select', MCSFW_PLUGIN_DIR. '/assets/css/select2.min.css', array(), true );
   wp_enqueue_script( 'jquery-admin-select', MCSFW_PLUGIN_DIR. '/assets/js/select2.min.js', array(), true );
   wp_enqueue_script( 'jquery-admin-cartsidebar', MCSFW_PLUGIN_DIR. '/assets/js/mcsfw_backend.js', array('jquery'), '1.0');
   
}
add_action( 'admin_enqueue_scripts', 'MCSFW_load_admin_script' );



