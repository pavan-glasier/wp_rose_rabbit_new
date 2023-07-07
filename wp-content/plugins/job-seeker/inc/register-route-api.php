<?php
// Register custom API endpoint
function register_job_listing_api_endpoint() {
    register_rest_route( 'job-listing/v1', '/filter', array(
        'methods'  => 'POST',
        'callback' => 'job_listing_filter',
    ) );
}
add_action( 'rest_api_init', 'register_job_listing_api_endpoint' );

// Handle AJAX request and filter job listings
function job_listing_filter( $request ) {
    $job_type = $request->get_param( 'job_type' );
    $industry = $request->get_param( 'industry' );
    $skills   = $request->get_param( 'skills' );
// return $job_type;
    // Build WP_Query arguments based on the filter parameters
    $args = array(
        'post_type'      => 'job_listing',
        'posts_per_page' => -1,
        'tax_query'     => array(),
    );
    if ( ! empty( $job_type ) ) {
        $args['tax_query'][] = array(
            'taxonomy'   => 'job_type',
            'field'      => 'slug',
            'terms'      => array($job_type),
        );
    }
    
    if ( ! empty( $industry ) ) {
        $args['tax_query'][] = array(
            'taxonomy'   => 'industry',
            'field'      => 'slug',
            'terms'      => array($industry),
        );
    }
    
    if ( ! empty( $skills ) ) {
        $args['tax_query'][] = array(
            'taxonomy'   => 'skill',
            'field' => 'slug',
            'terms' => array($skills),
        );
    }
    
    // return $args;
    // Perform the query
    $query = new WP_Query( $args );

    // Prepare the response
    $jobs = array();
    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            $jobs[] = array(
                'id' => get_the_ID(),
                'title'       => get_the_title(),
                'description' => get_the_content(),
                // Add other job fields you want to include in the response
            );
        }
    }
    // Restore global post data
    wp_reset_postdata();
    // Return the filtered job listings as JSON
    return rest_ensure_response( $jobs );
}
