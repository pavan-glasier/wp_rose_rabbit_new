<?php

/**
 * Class for wc gst checkout.
 * Creates the GSTIN number field on checkout
 */
if( ! class_exists( 'WC_GST_Checkout' ) ) :
	class WC_GST_Checkout {
		
		function __construct() {
			
			// Add one field to checkout page
			add_action( 'woocommerce_after_order_notes', array( $this,'woo_gst_custom_checkout_field' ) );
			// Validate and process the custom field
			add_action( 'woocommerce_checkout_process', array( $this, 'woo_gst_customised_checkout_field_process' ) );
			// Validate and update the custom field
			add_action( 'woocommerce_checkout_update_order_meta', array( $this, 'woo_gst_update_order_meta' ) );
			//Enqueue scripts
			add_action( 'wp_enqueue_scripts', array( $this, 'woo_gst_enqueue_scripts' ) );
		}

		/**
		 * woo_gst_custom_checkout_field
		 *
		 * @param  $checkout
		 */
		public function woo_gst_custom_checkout_field( $checkout ) {
			echo '<div id="has_gstin_number"><h2>' . __('GSTIN Number') . '</h2>';

			$checkbox = ( get_user_meta( get_current_user_id(), 'gstin_number', true ) ) ? true : $checkout->get_value('woo_gst_has_gstin_number');

			$gstin = ( get_user_meta( get_current_user_id(), 'gstin_number', true ) ) ? get_user_meta( get_current_user_id(), 'gstin_number', true ) : $checkout->get_value('woo_gst_gstin_number');
			
			woocommerce_form_field('woo_gst_has_gstin_number', array(

					'type' => 'checkbox',
					'class' => array(
						'has_gstin_number form-row-wide'
					) ,
					'label' => __(' Have GSTIN Number ?') ,

				) ,
				$checkbox
			);

			woocommerce_form_field('woo_gst_gstin_number', array(

					'type' => 'text',
					'class' => array(
						'my-field-class form-row-wide'
					) ,
					'label' => __('GSTIN Number') ,
					'placeholder' => __('GSTIN Number') ,
					'required' => true,

				) ,
				$gstin
			);

			echo '</div>';

		}

		/**
		 * woo_gst_customised_checkout_field_process
		 * Validate the custom fields
		 * @hooked woocommerce_checkout_process
		 */
		public function woo_gst_customised_checkout_field_process() {

			if ( isset( $_POST['woo_gst_has_gstin_number'] ) ) {
				if( ! $_POST['woo_gst_gstin_number'] )
					wc_add_notice(__('Please enter GSTIN number') , 'error');
			}
		}

		public function woo_gst_enqueue_scripts() {

			wp_enqueue_script( 'woo-gst-custom-script', gst_RELATIVE_PATH . '/js/custom-front.js' , array( 'jquery' ), '' );
		}

		/**
		 * woo_gst_update_order_meta
		 *
		 * @param  $order_id  The order identifier
		 */
		public function woo_gst_update_order_meta( $order_id ) {
			
			if ( ! empty( $_POST['woo_gst_gstin_number'] ) ) {
				update_post_meta( $order_id, 'gstin_number',sanitize_text_field( $_POST['woo_gst_gstin_number'] ) );

				if ( is_user_logged_in() ) {

					update_user_meta( get_current_user_id(), 'gstin_number', sanitize_text_field( $_POST['woo_gst_gstin_number'] ) );

				}
			}
		}
	}

	new WC_GST_Checkout();
endif;

