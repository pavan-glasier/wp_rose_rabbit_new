<?php

require_once "vendor/autoload.php";
//require_once 'vendor/dompdf/dompdf/autoload.inc.php';
// @since 1.6
/*if(get_option('gst_ac_invoice_temp') == 'template_2') {
	require_once "templates/invoice1.php";
} else if(get_option('gst_ac_invoice_temp') == 'template_3') {
	require_once "templates/invoice2.php";
} else {
	require_once "templates/invoice.php";
}*/

use Dompdf\Dompdf;

if( ! class_exists( 'WooGstInvoice' ) ) :

	/**
	 * WooGstInvoice class
	 * Generates PDF Invoices
	 */

	class WooGstInvoice {
		
		function __construct() {

			/**
			 * Add PDF actio to the order columns
			 */
			add_action( 'woocommerce_admin_order_actions_end', array( $this, 'add_invoice_listing_actions' ) );
			
			/**
			 * Add css on admin side
			 */
			add_action( 'admin_enqueue_scripts', array( $this, 'gst_invoice_admin_scripts' ) );

			/**
			 * AJAX to get the PDF invoice of product
			 */
			add_action( 'wp_ajax_generate_woo_gst_pdf', array( $this, 'generate_pdf_invoice_ajax' ) );

			/**
			 * Attach order invoice to email.
			 */

			$attach_order_invoice_to_mail = get_option( 'attach_order_invoice_to_mail' );
			if($attach_order_invoice_to_mail == "yes"){

				add_filter( 'woocommerce_email_attachments', array( $this, 'woogst_attach_order_invoice_to_emails' ), 10, 4 );
			}


			// add_action( 'init', array( $this, 'woo_gst_invoice_print_notices' ) );

			add_action('admin_menu' , array( $this, 'gst_pdf_invoice' ));


			add_filter( 'woocommerce_my_account_my_orders_actions', array($this, 'woo_gst_add_my_account_order_actions'), 10, 2 );

			add_action( 'add_meta_boxes', array($this, 'woo_gst_order_meta_boxes') );



			// Adding to admin order list bulk dropdown a custom action 'invoice_downloads'
			add_filter('bulk_actions-edit-shop_order', function($bulk_actions) {
				$bulk_actions['invoice_downloads'] = __('Download Invoice', 'generate_woo_gst_pdf');
				return $bulk_actions;
			});

			// Make the action from selected orders
			add_filter('handle_bulk_actions-edit-shop_order', function($redirect_url, $action, $post_ids) {

				if ($action == 'invoice_downloads') {

					

			     	$upload = wp_upload_dir();
					$upload_dir = $upload['basedir'];
					$upload_dir = $upload_dir . '/woogst-bulk-invoice/';
					if (! is_dir($upload_dir)) {
						wp_mkdir_p( $upload_dir );
					}

					/*30072021 star*/

					$cus_index_file_name = 'index.html';
					$testfileindex = $upload_dir.$cus_index_file_name;
					file_put_contents($testfileindex,"");
					$cus_ht_file_name = '.htaccess';
					$testfileht = $upload_dir.$cus_ht_file_name;
					file_put_contents($testfileht,"deny from all");

					/*30072021 end*/

			     	foreach ($post_ids as $post_id) {

						$order = wc_get_order( $post_id );
				
						// Redirect if order data is empty
						if ( empty($order) ) wp_safe_redirect( $page_url );

						$active_template = $this->get_active_template();
						$active_template_class = $active_template['class_name'];
						
						require_once($active_template['class_path']);

						$active_template_obj = new $active_template_class;
						$html = $active_template_obj->render_invoice_html($order);

						$options = array('isRemoteEnabled' => true);
						$dompdf = new Dompdf($options);

						$dompdf->loadHtml($html);

						// (Optional) Setup the paper size and orientation
						$dompdf->setPaper('A4');

						// Render the HTML as PDF
						$dompdf->render();

						$o_id = $order->id;
						
						$pdf_get = woo_gst_get_invoice_name($o_id);

						$filename = ' invoice-'.$pdf_get;
						
						$pdfroot = $upload_dir;
						if (! is_dir($pdfroot)) {
							wp_mkdir_p( $pdfroot );
						}
						$pdfroot_file = $pdfroot.$filename.'.pdf';
						$output = $dompdf->output();
						// $dompdf->stream($filename);

						array_push($files, $pdfroot_file);
		            	file_put_contents($pdfroot_file, $output);
		            	
					}

					$this->zip_the_folder($pdfroot,$o_id);
					die();
					wp_die();
				}
				
			}, 10, 3);

			// The results notice from bulk action on orders
			add_action('admin_notices', function() {
				if (!empty($_REQUEST['invoice_downloads'])) {
					$num_changed = (int) $_REQUEST['invoice_downloads'];
					printf('<div id="message" class="updated notice is-dismissable"><p>' . __('Download %d invoices.', 'generate_woo_gst_pdf') . '</p></div>', $num_changed);
				}
			});

		}


		public function zip_the_folder($pdfroot,$o_id) {
			$upload = wp_upload_dir();
			$upload_dir = $upload['basedir'];
			$upload_dir = $upload_dir . '/woogst-bulk-invoice/';
			if (! is_dir($upload_dir)) {
				wp_mkdir_p( $upload_dir );
			}

			// Get real path for our folder
			$rootPath = $pdfroot;

			$pdf_get = woo_gst_get_invoice_name($o_id);

			// Initialize archive object
			$filename_zip = $upload_dir.'invoice.zip';
			$zip = new ZipArchive();
			$zip->open($filename_zip, ZipArchive::CREATE | ZipArchive::OVERWRITE);

			// Initialize empty "delete list"
			$filesToDelete = array();

			// Create recursive directory iterator
			/** @var SplFileInfo[] $files */
			$files = new RecursiveIteratorIterator(
			    new RecursiveDirectoryIterator($rootPath),
			    RecursiveIteratorIterator::LEAVES_ONLY
			);

			foreach ($files as $name => $file)
			{
			    // Skip directories (they would be added automatically)
			    if (!$file->isDir())
			    {
			        // Get real and relative path for current file
			        $filePath = $file->getRealPath();
			        $relativePath = substr($filePath, strlen($rootPath) + 1);

			        // Add current file to archive
			        $zip->addFile($filePath, $relativePath);

			        // Add current file to "delete list"
			        // delete it later cause ZipArchive create archive only after calling close function and ZipArchive lock files until archive created)
			        $filesToDelete[] = $filePath;
			        
			    }
			}

			// Zip archive will be created only after closing object
			$zip->close();

			// Delete all files from "delete list"
			foreach ($filesToDelete as $file)
			{
			    unlink($file);
			}

			header("Content-type: application/zip"); 
			header("Content-Disposition: attachment; filename=invoice.zip");
			header("Content-length: " . filesize($filename_zip));
			header("Pragma: no-cache"); 
			header("Expires: 0"); 
			ob_clean();
			flush();
			readfile("$filename_zip");
			unlink($filename_zip);
			exit;

		}

		/*start 31-07-2018*/

		public function gst_pdf_invoice() {
		    add_submenu_page( 'gst-settings', 'GST PDF Invoice', 'GST PDF Invoice', 'manage_options', 'gst-custom-submenu-page', array( $this, 'gst_pdf_invoice_callback'  )); 
		}

		/*end 31-07-2018*/
		
		public function gst_invoice_admin_scripts(){
			wp_enqueue_style( 'gst-pdf-invoice', gst_RELATIVE_PATH.'css/admin.css' );
		}
		

		/**
		 * @hooked woocommerce_admin_order_actions_end
		 * @param $order [WC Object]
		 */
		public function add_invoice_listing_actions($order){

			$allowed_status = apply_filters('woogst_invoice_admin_allowed_status', array('completed','processing'));

			if( in_array($order->get_status(), $allowed_status) ){
				$listing_actions = array(
					'url'		=> wp_nonce_url( admin_url( "admin-ajax.php?action=generate_woo_gst_pdf&order_id=" . $order->get_id() ),'nonce', 'generate_woo_gst_pdf' ),
					'img'       => "<img src='".gst_RELATIVE_PATH ."/images/pdf.png' alt='invoice' />"
				);
				// echo "<a href='".$listing_actions['url']."' target='_blank'>PDF</a>";
				printf( "<a href='%s' title='PDF GST Invoice' class='button woo-gst-invoice' >%s</a>", esc_url( $listing_actions['url'] ),$listing_actions['img'] );
			}
		}

		/**
	     * woo_gst_add_my_account_order_actions
	     * Add the download PDF button on myaccount page
	     * @param $actions | array of actions
	     * @param $order | Object of the order
	     */
		public function woo_gst_add_my_account_order_actions( $actions, $order ) {

			$allowed_status = apply_filters('woogst_invoice_customer_allowed_status', array('completed'));

			if( in_array($order->get_status(), $allowed_status) ){
				$btn_label = ( get_option('gst_ac_btn_name') ) ? get_option('gst_ac_btn_name') : "PDF Invoice";
				$actions['woo_gst_pdf_invoice'] = array(
            	// adjust URL as needed
					'url'  => wp_nonce_url( admin_url( "admin-ajax.php?action=generate_woo_gst_pdf&order_id=" . $order->get_id() ),'nonce', 'generate_woo_gst_pdf' ),
					'name' => $btn_label,
				);
			}

			return $actions;
		}


	    /**
	     * woo_gst_order_meta_boxes
	     * Add the meta boxes required
	     */
	    public function woo_gst_order_meta_boxes() {
	        add_meta_box( 'woo_gst_order_gstin', __('Customer GSTIN Number','woo-gst'), array($this, 'woo_gst_customer_gstin_number'), 'shop_order', 'side', 'core' );

	        add_meta_box( 'woo_gst_order_pdfinvoice', __('Order PDF Invoice','woo-gst'), array($this, 'woo_gst_pdf_invoice'), 'shop_order', 'side', 'core' );
	    }

	    /**
		 * woo_gst_customer_gstin_number
		 * Displays the meta box for customer GSTIN number
		 */
		public function woo_gst_customer_gstin_number(){
		    global $post;

		    $meta_field_data = get_post_meta( $post->ID, 'gstin_number', true );
		    $gst_order_invoice_no = get_post_meta( $post->ID, 'gst_order_invoice_no', true );


		    echo '<label for="customer_gstin_number">Customer GSTIN Number</label><input type="hidden" name="woo_gst_customer_gst_nonce" value="' . wp_create_nonce() . '">
		    <p><input type="text" name="gstin_number" placeholder="' . $meta_field_data . '" value="' . $meta_field_data . '"></p>';

		    echo '<label for="customer_gstin_number">Order Invoice Number</label><input type="hidden" name="woo_gst_order_invoice_no_nonce" value="' . wp_create_nonce() . '">
		    <p><input type="text" name="order_invoice_no" placeholder="' . $gst_order_invoice_no . '" value="' . $gst_order_invoice_no . '"></p>';
		}

		/**
	     * woo_gst_pdf_invoice
	     * Displays the meta box for download PDF invoice button
	     */
	    public function woo_gst_pdf_invoice(){
	        global $post;
	        $order = wc_get_order( $post->ID );
	        if(empty($order)) {
	        	return;
	        }

	        $allowed_status = apply_filters('woogst_invoice_admin_allowed_status', array('completed','processing'));

	        if( in_array($order->get_status(), $allowed_status) ){
	        	echo '<p><a href="'.wp_nonce_url( admin_url( "admin-ajax.php?action=generate_woo_gst_pdf&order_id=" . $post->ID ),'nonce', 'generate_woo_gst_pdf' ).'" class="button-primary" data-id="'. $post->ID.'">Download PDF Invoice</a></p>';
	        }
	    }


	    public function get_active_template(){
	    	$arr_invoice = woo_gst_get_available_template();
	    	$selected_template = get_option('gst_ac_invoice_temp');
	    	return $arr_invoice[$selected_template];
	    }

		/**
		 * @hooked wp_ajax_generate_wpo_gst_pdf
		 * Generates the PDF
		 */
		public function generate_pdf_invoice_ajax(){

			$page_url = admin_url( 'edit.php?post_type=shop_order' );
			
			//Redirect if order id is empty
			if ( ! isset( $_GET['order_id'] ) || empty( $_GET['order_id'] ) ) wp_safe_redirect( $page_url );

			$order = wc_get_order( $_GET['order_id'] );
			
			//Redirect if order data is empty
			if ( empty($order) ) wp_safe_redirect( $page_url );

			$active_template = $this->get_active_template();
			$active_template_class = $active_template['class_name'];
			
			require_once($active_template['class_path']);

			$active_template_obj = new $active_template_class;
			$html = $active_template_obj->render_invoice_html($order);

			$options = array('isRemoteEnabled' => true);

			$dompdf = new Dompdf($options);

			//@since 1.6
			if(!extension_loaded('openssl')) {
			    $contxt = stream_context_create([ 
			        'ssl' => [ 
			            'verify_peer' => FALSE, 
			            'verify_peer_name' => FALSE,
			            'allow_self_signed'=> TRUE
			        ] 
			    ]);
			    $dompdf->setHttpContext($contxt);
			}

			$dompdf->loadHtml($html);

			// (Optional) Setup the paper size and orientation
			$dompdf->setPaper('A4');

			// Render the HTML as PDF
			$dompdf->render();
			$o_id = $order->id;
			$pdf_get = woo_gst_get_invoice_name($o_id);

			// Output the generated PDF to Browser
			$filename = 'invoice-'.$pdf_get;
			$dompdf->stream($filename);
			// header('Content-Description: File Transfer');
			// header('Content-type: application/pdf');
			// header('Content-Disposition: inline; filename="'.$filename.'.pdf"');
			$dompdf->output();

			wp_die();
		}

		/*27072021 start*/
		 
		public function woogst_attach_order_invoice_to_emails( $attachments, $email_id, $order, $email ) {

			// Avoiding errors and problems
			if ( ! is_a( $order, 'WC_Order' ) || ! isset( $email_id ) ) {
			    return $attachments;
			}

			$upload = wp_upload_dir();
			$upload_dir = $upload['basedir'];
			$upload_dir = $upload_dir . '/woo-gst-invoice/';
			if (! is_dir($upload_dir)) {
			    mkdir( $upload_dir, 0777 );
			}


			/*30072021 star*/

			$cus_index_file_name = 'index.html';
			$testfileindex = $upload_dir.$cus_index_file_name;
			file_put_contents($testfileindex,"");
			$cus_ht_file_name = '.htaccess';
			$testfileht = $upload_dir.$cus_ht_file_name;
			file_put_contents($testfileht,"deny from all");

			/*30072021 end*/

			$order_id = $order->get_id();
			$order = wc_get_order( $order_id );

			$active_template = $this->get_active_template();
			$active_template_class = $active_template['class_name'];
			
			require_once($active_template['class_path']);

			$active_template_obj = new $active_template_class;
			$html = $active_template_obj->render_invoice_html($order);

			$options = array('isRemoteEnabled' => true);

			$dompdf = new Dompdf($options);

			//@since 1.6
			if(!extension_loaded('openssl')) {
			    $contxt = stream_context_create([ 
			        'ssl' => [ 
			            'verify_peer' => FALSE, 
			            'verify_peer_name' => FALSE,
			            'allow_self_signed'=> TRUE
			        ] 
			    ]);
			    $dompdf->setHttpContext($contxt);
			}

			$dompdf->loadHtml($html);

			// (Optional) Setup the paper size and orientation
			$dompdf->setPaper('A4');

			// Render the HTML as PDF
			$dompdf->render();

			$output = $dompdf->output();
			
			$pdf_get = woo_gst_get_invoice_name($order_id);

			$filename = 'invoice-'.$pdf_get.'.pdf';

			file_put_contents($upload_dir.$filename, $output);

			$file_path = $upload_dir.$filename;

		    $allowed_statuses = apply_filters('woogst_attach_invoice_to_emails_allowed_status', array( 'customer_invoice', 'customer_completed_order' ));

		    	if( isset( $email_id ) && in_array ( $email_id, $allowed_statuses ) ) {
		        	$attachments[] = $file_path;
		    	}

		    return $attachments;
		}		

		/*27072021 end*/

		/**
		 * woo_gst_invoice_print_notices
		 * print notices
		 */
		public function woo_gst_invoice_print_notices() {
			add_action( 'admin_notices', function(){
				$class = 'notice notice-error';
				if ( isset( $_GET['wietype'] ) && $_GET['wietype'] == 'orderempty'  )
				$message = __( 'No order.', 'gst' );

				if ( isset( $_GET['wietype'] ) && $_GET['wietype'] == 'order'  )
				$message = __( 'Invalid Order Id.', 'gst' );

				printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message ); 
			} );

		}

		public function gst_pdf_invoice_callback() {

			$lastdata = get_option( 'gst_max_invoice' );
			$new_no = $lastdata+1;
			if( ! get_option('gst_shop_name') ) {
				update_option( 'gst_shop_name', get_bloginfo( 'name' ) );
			}
			$invoice_title = ( get_option('gst_invoice_heading') ) ? get_option('gst_invoice_heading') : "TAX INVOICE";
			$phone_number = ( get_option('gst_phone_no') ) ? get_option('gst_phone_no') : "";
			$btn_label = ( get_option('gst_ac_btn_name') ) ? get_option('gst_ac_btn_name') : "PDF Invoice";
			$prefix_label = ( get_option('gst_invoice_prefix') ) ? get_option('gst_invoice_prefix') : "";
			// $invoice_no = ( get_option('gst_invoice_no') ) ? get_option('gst_invoice_no') : "001";
			$next_invoice_no = ( get_option('gst_next_in_no') ) ? get_option('gst_next_in_no') : $new_no;
			?>
			<h3><?php _e('GST PDF Invoice Page','gst'); ?></h3>
			<form method="POST" enctype="multipart/form-data">
				<table class="form-table">
					<tbody>
						<tr>
							<th scope="row"><?php _e('Invoice Template','gst'); ?></th>
							<td>
								<?php 
									$arr_invoice = woo_gst_get_available_template();
								 ?>
								<select name="ac_invoice_temp" id="my_ac_invoice_temp" class="" >

									<?php 

										$selected_template = get_option('gst_ac_invoice_temp');

										foreach ($arr_invoice as $key => $value) { ?>
											<option value="<?php echo $key; ?>" <?php echo ($selected_template == $key) ? 'selected=""' : '' ?><?php echo ($key == 'template_2') ? 'disabled="disabled"' : '' ?>><?php echo $value['label']; ?></option>
											<?php
										}

									 ?>
								</select>

								<?php 

								foreach ($arr_invoice as $key => $value) {
									?>
									<a href="<?php echo $value['sample_invoice_url']; ?>" class="sample-invoice-url" id="<?php echo $key; ?>" target="_blank" <?php echo ($selected_template != $key) ? 'style="display:none;"' : '' ?>>Sample invoice</a>
									<?php
								}

								 ?>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php _e('PDF Invoice Title','gst'); ?></th>
							<td><input type="text" id="invoice_heading" name="invoice_heading" value="<?php echo $invoice_title;?>" size="72" placeholder="" /></td>
						</tr>

						<tr>
							<th scope="row"><?php _e('Invoice Prefix','gst'); ?></th>
							<td><input type="text" id="invoice_prefix" name="invoice_prefix" value="<?php echo $prefix_label;?>" size="72" placeholder="" /></td>
						</tr>

<!-- 						<tr>
							<th scope="row"><?php // _e('Invoice Start No','gst'); ?></th>
							<td><input type="text" id="invoice_no" name="invoice_no" value="<?php // echo $invoice_no;?>" size="72" placeholder="" /></td>
						</tr> -->

						<tr>
							<th scope="row"><?php _e('Next Invoice No (or) Skip No','gst'); ?></th>
							<td><input type="text" id="next_in_no" name="next_in_no" value="<?php echo $new_no;?>" size="72" placeholder="" /></td>
						</tr>

						<!-- <tr>
							<th scope="row"><?php // _e('Reset invoice number yearly','gst'); ?></th>
							<td>
								<select name="reset_no_yearly" id="reset_no_yearly">
									<option value="no" <?php // echo (get_option('gst_reset_no_yearly') == 'no') ? 'selected=""' : '' ?>>NO</option>
									<option value="yes" <?php // echo (get_option('gst_reset_no_yearly') == 'yes') ? 'selected=""' : '' ?>>YES</option>
								</select>
							</td>
						</tr> -->


						<tr>
							<th scope="row"><?php _e('Phone No','gst'); ?></th>
							<td><input type="text" id="phone_no" name="phone_no" value="<?php echo $phone_number;?>" size="72" placeholder="" /></td>
						</tr>
						<tr>
							<th scope="row"><?php _e('Shop Header/Logo','gst'); ?></th>
							<td>					
								<input type="file" name="profile_pic" id="profile-img" value="" style="width: 100%">
								<?php if(get_option('gst_profile_pic')) : ?>
									<img src="<?php echo get_option('gst_profile_pic');?>" id="profile-img-tag" width="100px" />
								<?php endif; ?>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php _e('Shop Name','gst'); ?></th>
							<td><input type="text" id="shop_name" name="shop_name" value="<?php echo get_option('gst_shop_name');?>" size="72" placeholder=""></td>
						</tr>
						<tr>
							<th scope="row"><?php _e('Shop Address','gst'); ?></th>
							<td>
								<textarea id="shop_address" name="shop_address" cols="72" rows="8" placeholder=""><?php echo get_option('gst_shop_address');?></textarea>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php _e('Footer Note ( Example : terms &amp; conditions, policies, etc.)','gst'); ?></th>
							<td>
								<textarea id="footer" name="footer_conditions" cols="72" rows="4" placeholder=""><?php echo get_option('gst_footer_conditions');?></textarea>
							</td>
						</tr>
						<!-- <tr>
							<th scope="row"><?php // _e('Footer Note ( Example : terms &amp; conditions, policies, etc.)','gst'); ?></th>
							<td>
								<textarea id="editor" name="editor" cols="72" rows="4" placeholder=""><?php // echo get_option('gst_editor');?></textarea>
							</td>
						</tr> -->
						<tr>
							<th scope="row"><?php _e('Download PDF invoice button label on My Orders section','gst'); ?></th>
							<td>
								<input type="text" id="my_ac_btn_name" name="ac_btn_name" value="<?php echo $btn_label;?>" size="72" placeholder="">
							</td>
						</tr>
						<tr>
							<th scope="row"><?php _e('Signature Logo','gst'); ?></th>
							<td>					
								<input type="file" name="sign_pic" id="sign-img" value="" style="width: 100%">
								<?php if(get_option('gst_sign_pic')) : ?>
									<img src="<?php echo get_option('gst_sign_pic');?>" id="sign-img-tag" width="100px" />
								<?php endif; ?>
							</td>
						</tr>
					</tbody>
				</table>
				<p class="submit"><input type="submit" name="submit-woo-gst-setting" id="submit" class="button button-primary" value="Save Changes"></p>
			</form>
			<script>
				jQuery(document).ready(function($){
					// CKEDITOR.replace('editor');
					$('body').on('change','#my_ac_invoice_temp',function(){
						$('.sample-invoice-url').hide();
						$('#' + $(this).val()).show();
					});
				});
			</script>
			<?php
		}
			
	}

	new WooGstInvoice();

endif;

