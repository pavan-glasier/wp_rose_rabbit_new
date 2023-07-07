<?php
/**
 * Template Name: Custom Checkout Template
 */
// Include necessary WooCommerce files
// require_once ABSPATH . 'wp-content/plugins/woocommerce/includes/class-wc-shortcode-checkout.php';
// require_once ABSPATH . 'wp-content/plugins/woocommerce/includes/wc-template-functions.php';

get_header(); // Include your custom header if needed

echo do_shortcode('[woocommerce_checkout]');

get_footer(); // Include your custom footer if needed
