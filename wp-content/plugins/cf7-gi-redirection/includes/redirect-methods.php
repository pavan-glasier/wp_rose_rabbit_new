<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// returns the form id of the forms that have redirect enabled - used for redirect method 1 and method 2
function cf7_gi_forms_enabled() {
	// array that will contain which forms redirect is enabled on
	$enabled = array();
	$args = array(
		'posts_per_page'   => -1,
		'post_type'        => 'wpcf7_contact_form',
		'post_status'      => 'publish',
	);
	$posts_array = get_posts($args);
	
	// loop through them and find out which ones have redirect enabled
	foreach($posts_array as $post) {
		$post_id = $post->ID;
		// url
		$enable = get_post_meta( $post_id, "_cf7_gi_enable", true);
		if ($enable == "1") {
			$cf7_gi_redirect_type = get_post_meta( $post_id, "_cf7_gi_redirect_type", true);
			if($cf7_gi_redirect_type == 'url'){
				$cf7_gi_url = get_post_meta( $post_id, "_cf7_gi_url", true);
				$cf7_gi_tab = get_post_meta( $post_id, "_cf7_gi_tab", true);
			}
			else{
				$cf7_gi_url = get_post_meta( $post_id, "_cf7_gi_page", true);
				$cf7_gi_tab = get_post_meta( $post_id, "_cf7_gi_page_tab", true);
			}
			$enabled[] = '|'.$post_id.'|'.$cf7_gi_redirect_type.'|'.$cf7_gi_url.'|'.$cf7_gi_tab.'|';
		}
	}
	return json_encode($enabled);
}

