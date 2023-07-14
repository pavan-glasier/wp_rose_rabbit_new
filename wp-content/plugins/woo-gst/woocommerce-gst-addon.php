<?php
/**
 * Plugin Name: WooCommerce GST PRO
 * Description: WooCommerce GST PRO addon.
 * Plugin URI: https://www.woocommercegst.co.in/
 * Author: Stark Digital
 * Author URI: http://starkdigital.net/
 * Version: 2.1.2
 * WC requires at least: 4.0
 * WC tested up to: 4.9.1
 */

if (!defined('ABSPATH'))
{
    exit; // Exit if accessed directly
}
require_once('inc/functions.php');
require_once('inc/interface-invoice.php');

/**
 * Check WooCommerce exists
 */
if ( fn_is_woocommerce_active() ) {
	define('gst_RELATIVE_PATH', plugin_dir_url( __FILE__ ));
	define('gst_ABS_PATH', plugin_dir_path(__FILE__));
	define( 'gst_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

	
	define('GST_SERVER_URL', 'https://www.woocommercegst.co.in');
	define('GST_LICENSE_VERIFICATION_KEY', '5b67e199026bd7.95803195');
	define('GST_VERSION', '2.0');

	require_once( 'class-gst-woocommerce-addon.php' );

	// plugin update checker
	require_once 'plugin-update-checker/plugin-update-checker.php';
	$woo_gst_update_checker = Puc_v4_Factory::buildUpdateChecker(
		GST_SERVER_URL. '/plugin.json',
		__FILE__, //Full path to the main plugin file or functions.php.
		'woo-gst'
	);
	$gst_settings = new WC_GST_Settings();
	$gst_settings->init();

	/**
	 * woogst_activation_hook 
	 * @return [type] [description]
	 * @since 1.5.3
	 */
	function woogst_activation_hook() {
		$gst_settings = new WC_GST_Settings();
		$gst_settings->check_woo_gst_tax_slabs();
	}
	register_activation_hook( __FILE__, 'woogst_activation_hook' );


} else {
	add_action( 'admin_notices', 'fn_gst_admin_notice__error' );
}

/**
 * Include setting page JS
 */
add_action( 'admin_enqueue_scripts', 'gst_pdf_script' );
function gst_pdf_script($hook) {
	if ('woocommerce_page_gst-custom-submenu-page' != $hook) return;

	wp_enqueue_script( 'gst_script', plugin_dir_url( __FILE__ ) . 'js/custom.js' );
}


// add_action( 'admin_enqueue_scripts', 'gst_pdf_script_new' );
function gst_pdf_script_new($hook) {
	if ('woocommerce_page_gst-custom-submenu-page' == $hook) return;
	wp_enqueue_script( 'gst_new_chosen_script', 'https://cdn.ckeditor.com/4.15.1/standard/ckeditor.js' );
}


add_action( 'admin_enqueue_scripts', 'gst_multiselect_style_script' );

function gst_multiselect_style_script( $hook ) {
	if( isset($_GET['page']) && $_GET['page'] == 'wc-settings' && isset($_GET['tab']) && $_GET['tab'] == 'settings_gst_tab' ) :
	
		wp_enqueue_script( 'gst_chosen_script', plugin_dir_url( __FILE__ ) . 'js/chosen.jquery.min.js' );
		wp_enqueue_style( 'gst_chosen_style', plugin_dir_url( __FILE__ ) . 'css/chosen.min.css' );
	endif;
}

/**
 * Call a method which saves the settings
 */
add_action('init', 'save_woo_gst_settings');
function save_woo_gst_settings()
{
	if(isset($_POST['submit-woo-gst-setting'])) {
		
		$options = $_POST;
		if( isset( $_FILES["profile_pic"]["name"] ) && !empty( $_FILES["profile_pic"]["name"] ) ) :

			$upload = wp_upload_bits($_FILES["profile_pic"]["name"], null, file_get_contents($_FILES["profile_pic"]["tmp_name"]));
		$filename = $upload['url'];
		update_option( 'gst_profile_pic', $filename );
		endif;
		
		if( isset( $_FILES["sign_pic"]["name"] ) && !empty( $_FILES["sign_pic"]["name"] ) ) :

			$upload = wp_upload_bits($_FILES["sign_pic"]["name"], null, file_get_contents($_FILES["sign_pic"]["tmp_name"]));
		$filename = $upload['url'];
		update_option( 'gst_sign_pic', $filename );
		endif;

		unset($_POST['submit-woo-gst-setting']);
		foreach ($options as $key => $value)
			update_option( 'gst_'.$key, trim($value));
	}
}

/**
 * Add css for Download PDF button on My Account page
 */
add_action( 'wp_enqueue_scripts', 'woo_gst_css' );
function woo_gst_css() {
	wp_enqueue_style( 'woo-gst-front',plugin_dir_url( __FILE__ ) . 'css/woo-gst.css' );
}


add_action( 'woocommerce_checkout_order_processed', 'woo_gst_generate_invoice_number',  1, 1  );
function woo_gst_generate_invoice_number($o_id){
	$order = wc_get_order($o_id);
	
	$in_prefix = ( get_option( 'gst_invoice_prefix' ) ) ? get_option( 'gst_invoice_prefix' ) : "" ;
	$skip_no = get_option( 'gst_next_in_no' );
	$maxinvoiceno = 'gst_max_invoice';
	$getin_num = get_post_meta( $o_id, 'gst_order_invoice_no', true );

	$increment = get_option( 'gst_max_invoice' );
	$num = $o_id;

	// if ($order->get_status() === 'processing') {
		if (empty($getin_num)) {

			if(!empty($skip_no)){
				update_option( $maxinvoiceno, $skip_no );
				delete_option('gst_next_in_no');
				$num = $skip_no;
			} else {

				if ( $increment == false ) {
					$num = $o_id;
				} else {

					$nextval = $increment+1;
					update_option( $maxinvoiceno, $nextval );
					$num = $nextval;
				}
			}

		} else {
			$num = $getin_num;
		}
	// }
	$label = $in_prefix.'_'.$num;
	add_post_meta( $o_id, 'gst_order_invoice_no', $label, true );
	return $label;
}




function woo_gst_get_invoice_name($o_id){

	$label = ( get_post_meta( $o_id, 'gst_order_invoice_no', true ) ) ? get_post_meta( $o_id, 'gst_order_invoice_no', true ) : 'invoice_'.$o_id ; 

	return $label;
}

function woo_gst_get_available_template(){

	$default_Templates = [
		'default' => [
			'label' => 'Template 1',
			'class_name' => 'WGPTemplateDefault',
			'class_path' => plugin_dir_path( __FILE__ ).'invoice/templates/class-default.php',
			'sample_invoice_url' => 'https://www.woocommercegst.co.in/wp-content/uploads/2019/08/invoice-single-inc.pdf'
		],
		'template_2' => [
			'label' => 'Template 2',
			'class_name' => 'WGPTemplate2',
			'class_path' => plugin_dir_path( __FILE__ ).'invoice/templates/class-template2.php',
			'sample_invoice_url' => 'https://www.woocommercegst.co.in/wp-content/uploads/2019/12/sample-template-2.pdf'
		],
		'template_3' => [
			'label' => 'Template 3',
			'class_name' => 'WGPTemplate3',
			'class_path' => plugin_dir_path( __FILE__ ).'invoice/templates/class-template3.php',
			'sample_invoice_url' => 'https://www.woocommercegst.co.in/wp-content/uploads/2021/01/sample-template-3.pdf'
		]
	];

	$template_format = apply_filters('woogst_available_invoice_templates', $default_Templates);

	return $template_format;
	
}


