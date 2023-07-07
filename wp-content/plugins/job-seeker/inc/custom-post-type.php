<?php
// register job seeker post type
add_action('init', 'job_seeker_register_post_type');
function job_seeker_register_post_type() {
    register_post_type('job_listing', array(
        'labels' => array(
            'name' => __('Job Listings'),
            'singular_name' => __('Job Listing'),
            'parent_item_colon' => __( 'Parent Job', 'job-seeker' ),
            'all_items' => __( 'All Jobs', 'job-seeker' ),
            'view_item' => __( 'View Jobs', 'job-seeker' ),
            'add_new_item' => __( 'Add New Job', 'job-seeker' ),
            'add_new' => __( 'Add New Job', 'job-seeker' ),
            'edit_item' => __( 'Edit Job', 'job-seeker' ),
            'update_item' => __( 'Update Job', 'job-seeker' ),
            'search_items' => __( 'Search Job', 'job-seeker' ),
        ),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-id',
        'supports' => array('title', 'editor', 'excerpt', 'author', 'thumbnail'),
    ));
}


if ( ! function_exists('job_listing_admin_page') ) :
    add_action( 'admin_menu' , 'job_listing_admin_page' );
    function job_listing_admin_page() {
        add_submenu_page(
            'edit.php?post_type=job_listing',
            __('Shortcode', 'job-seeker'),
            __('Shortcode', 'job-seeker'),
            'manage_options',
            'job_listing_archive',
            'job_listing_options_display');
    }
    function job_listing_options_display(){ ?>
        <div class="wrap">
            <h1 class="wp-heading-inline"> Job Listings Shortcode</h1>
            <hr class="wp-header-end">
            <br>
            <code style="font-size: 16px">[job_listing]</code>
            <br>
            <p>This is the job listing shortcode with the filters</p>
        </div>
    <?php }
endif;



// register job type taxonomy
add_action('init', 'job_seeker_register_custom_taxonomy_job_type');
function job_seeker_register_custom_taxonomy_job_type() {
    $labels = array(
        'name'              => __('Job Type'),
        'singular_name'     => __('Job Type'),
        'search_items'      => __('Search Job Type'),
        'all_items'         => __('All Job Type'),
        'parent_item'       => __('Parent Job Type'),
        'parent_item_colon' => __('Parent Job Type:'),
        'edit_item'         => __('Edit Job Type'),
        'update_item'       => __('Update Job Type'),
        'add_new_item'      => __('Add New Job Type'),
        'new_item_name'     => __('New Job Type Name'),
        'menu_name'         => __('Job Type'),
    );

    $args = array(
        'hierarchical'      => true, // Set to true for category-like behavior
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'job-type'), // Customize the rewrite slug as desired
    );

    register_taxonomy('job_type', 'job_listing', $args); // Replace 'job_listing' with your custom post type slug
}

// register industry taxonomy
add_action('init', 'job_seeker_register_custom_taxonomy_industry');
function job_seeker_register_custom_taxonomy_industry() {
    $labels = array(
        'name'              => __('Industries'),
        'singular_name'     => __('Industry'),
        'search_items'      => __('Search Industries'),
        'all_items'         => __('All Industries'),
        'parent_item'       => __('Parent Industry'),
        'parent_item_colon' => __('Parent Industry:'),
        'edit_item'         => __('Edit Industry'),
        'update_item'       => __('Update Industry'),
        'add_new_item'      => __('Add New Industry'),
        'new_item_name'     => __('New Industry Name'),
        'menu_name'         => __('Industries'),
    );

    $args = array(
        'hierarchical'      => true, // Set to true for category-like behavior
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'industries'), // Customize the rewrite slug as desired
    );

    register_taxonomy('industry', 'job_listing', $args); // Replace 'job_listing' with your custom post type slug
}

// register skills tag
add_action('init', 'job_seeker_register_custom_tag_skills');
function job_seeker_register_custom_tag_skills() {
    $labels = array(
        'name'                       => __( 'Skills' ),
        'singular_name'              => __( 'Skill' ),
        'search_items'               => __( 'Search Skills' ),
        'popular_items'              => __( 'Popular Skills' ),
        'all_items'                  => __( 'All Skills' ),
        'parent_item'                => null,
        'parent_item_colon'          => null,
        'edit_item'                  => __( 'Edit Skill' ),
        'update_item'                => __( 'Update Skill' ),
        'add_new_item'               => __( 'Add New Skill' ),
        'new_item_name'              => __( 'New Skill Name' ),
        'separate_items_with_commas' => __( 'Separate skill with commas' ),
        'add_or_remove_items'        => __( 'Add or remove skill' ),
        'choose_from_most_used'      => __( 'Choose from the most used skill' ),
        'menu_name'                  => __( 'Skills' ),
    );

    $args = array(
        'hierarchical'          => false,
        'labels'                => $labels,
        'show_ui'               => true,
        'show_admin_column'     => true,
        'update_count_callback' => '_update_post_term_count',
        'query_var'             => true,
        'rewrite'               => array( 'slug' => 'skill' ), // Replace with your desired slug
    );

    register_taxonomy( 'skill', 'job_listing', $args ); // 'post' indicates that the custom tags will be associated with the 'post' post type
}

// create career page with shortcode
function create_career_page() {
    // Create the career page if it doesn't exist
    $page_title = 'Career';
    $page_content = '<p>[job_listing]</p>';
    $page_template = 'default'; // Optional: Set the desired page template

    // Check if the career page already exists
    $career_page = get_page_by_title( $page_title );

    if ( ! $career_page ) {
        // Page doesn't exist, create it
        $career_page_args = array(
            'post_title'   => $page_title,
            'post_content' => $page_content,
            'post_status'  => 'publish',
            'post_type'    => 'page',
        );

        $career_page_id = wp_insert_post( $career_page_args );

        if ( ! is_wp_error( $career_page_id ) ) {
            // Set the page template if provided
            if ( $page_template ) {
                update_post_meta( $career_page_id, '_wp_page_template', $page_template . '.php' );
            }
        }
    }
}

