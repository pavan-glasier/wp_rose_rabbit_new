<?php
/**
 * Plugin Name: Save ACF Group Json
 */

define( 'MY_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'TEMPLATE_PATH', get_template_directory() );
define( 'STYLESHEET_PATH', get_stylesheet_directory() );
add_filter('acf/settings/save_json', 'my_acf_json_save_point');
function my_acf_json_save_point( $path ) {
    // print_r($path);
    $new_path = STYLESHEET_PATH. '/acf-json';
    if ( ! is_dir( $new_path ) ) {
        wp_mkdir_p( $new_path );
        chmod( $new_path, 0777 );
    }
    // Update path
    $path = $new_path;
    // Return path
    return $path;
}

add_filter('acf/settings/load_json', 'my_acf_json_load_point');
function my_acf_json_load_point( $paths ) {
   // Remove original path
   unset( $paths[0] );// Append our new path
   $paths[] = STYLESHEET_PATH. '/acf-json';
   return $paths;
} ?>