<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// admin enqueue
function cf7_gi_admin_enqueue() {
	// admin css
	wp_register_style('cf7_gi-admin-css', plugins_url('../assets/css/admin.css', __FILE__), false, false);
	wp_enqueue_style('cf7_gi-admin-css');
	// admin js
	wp_enqueue_script('cf7_gi-admin', plugins_url('../assets/js/admin.js', __FILE__), array('jquery'), false);
}
add_action('admin_enqueue_scripts', 'cf7_gi_admin_enqueue');


// public enqueue
function cf7_gi_public_enqueue() {
	// redirect method js
	wp_enqueue_script('cf7_gi-redirect_method', plugins_url('../assets/js/redirect_method.js', __FILE__), array('jquery'), null);
	wp_localize_script('cf7_gi-redirect_method', 'cf7_gi_ajax_object',
		array (
			'cf7_gi_ajax_url' 		=> admin_url('admin-ajax.php'),
			'cf7_gi_forms' 			=> cf7_gi_forms_enabled(),
		)
	);
}
add_action('wp_enqueue_scripts', 'cf7_gi_public_enqueue', 10);