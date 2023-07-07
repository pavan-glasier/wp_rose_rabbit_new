<?php
function enqueue_job_listing_scripts() {
     // Enqueue css file
    wp_enqueue_style( 'job-listing-style', JS_PLUGIN_URL. '/assets/css/job-listing-style.css', array(), true );

    // Enqueue JavaScript file with AJAX code
    wp_enqueue_script( 'job-listing-filter', JS_PLUGIN_URL . '/assets/js/job-listing-filter.js', array( 'jquery' ), '1.0', true );

    // Localize AJAX URL for the JavaScript file
    wp_localize_script( 'job-listing-filter', 'jobListingAjax', array(
        'ajaxUrl' => admin_url( 'admin-ajax.php' )
    ) );
}
add_action( 'wp_enqueue_scripts', 'enqueue_job_listing_scripts' );
