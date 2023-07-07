<?php 
/**
 * Plugin Name: Job Seeker
 * Description: A plugin to manage job openings and display them on the Career page.
 * Version: 1.0
 * Author: Pavan Vishwakarma
 * Text Domain: job-seeker
 */

if( ! defined('ABSPATH') ){
    die();
}

define("JS_PLUGIN_PATH", plugin_dir_path(__FILE__)); // Plugin path
define("JS_PLUGIN_URL", plugins_url('', __FILE__)); // plugin url

include_once('inc/enqueue.php');
include_once('inc/custom-post-type.php');
include_once('inc/register-route-api.php');
include_once('inc/shortcodes.php');
include_once('inc/functions.php');

// Activation 
function activation_function(){
    job_seeker_register_post_type();
    job_seeker_register_custom_taxonomy_job_type();
    job_seeker_register_custom_taxonomy_industry();
    job_seeker_register_custom_tag_skills();
    create_career_page();
}

// Uninstall Actions
// unregister post type
function delete_custom_post_type() {
    // Unregister the custom post type
    unregister_post_type( 'job_listing' );
    // Delete the associated post data
    $args = array(
        'post_type'      => 'job_listing',
        'posts_per_page' => -1,
    );
    $query = new WP_Query( $args );
    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            wp_delete_post( get_the_ID(), true );
        }
    }
}
// 
// unregister all taxonomies
function unregister_taxonomies() {
    $taxonomies = array(
        'job_type',
        'industry',
        'skill',
    );
    foreach ( $taxonomies as $taxonomy ) {
        // Unregister the taxonomy
        unregister_taxonomy( $taxonomy );
        // Get all terms belonging to the taxonomy
        $terms = get_terms( array(
            'taxonomy' => $taxonomy,
            'hide_empty' => false,
        ) );

        // Delete each term and its associated data
        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
            foreach ( $terms as $term ) {
                wp_delete_term( $term->term_id, $taxonomy );
            }
        }
    }
}


// uninstall_function function call on uninstall
function uninstall_function() {
    // Delete the custom post type and its associated post data
    delete_custom_post_type();
    unregister_taxonomies();
}

register_activation_hook( __FILE__, 'activation_function' );
register_uninstall_hook( __FILE__, 'uninstall_function' );
