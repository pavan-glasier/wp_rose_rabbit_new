<?php
/**
 * Add admin menu for GST lincense
 */

if ( !class_exists( 'WooGstLicensing' ) ) :

	class WooGstLicensing {
		
		public $is_activated;

		function __construct() {
			//Add admin menu
			add_action( 'admin_menu', array( $this, 'GST_license_menu' ) );
			add_action( 'admin_menu', array( $this, 'GST_settings_submenu' ), 100 );
			add_action( 'init', array( $this, 'GST_refresh_transient' ), 10 );
			add_action( 'init', array( $this, 'GST_license_form_handler' ) );
			add_action( 'init', array( $this, 'GST_license_check_activation' ), 15 );
			
		}

		/**
		 * GST_license_menu
		 * Creates the admin menu for license
		 */
		public function GST_license_menu() {

		    add_menu_page( 'GST Settings', 'GST Settings', 'manage_options', 'gst-settings', array( $this, 'GST_license_management_page' ), plugins_url( 'woo-gst/images/gst.png' ) );
		    add_submenu_page( 'gst-settings', 'GST Settings', 'GST Settings', 'manage_options', 'gst-settings', array( $this, 'GST_license_management_page' ) );
	    
		}

		/**
		 * GST_settings_submenu 
		 * Create tools submenu under woogst
		 */
		public function GST_settings_submenu() {
			add_submenu_page( 'gst-settings', 'Tools', 'Tools', 'manage_options', 'gst-settings-tools', array( $this, 'gst_tools_settings_callback'  )); 
		}

		/**
		 * GST_license_management_page
		 * Creates the form for the License key
		 */
		public function GST_license_management_page() {

			ob_start();
			?>
			<div class="wrap">
				<h2><?php _e( 'GST Licensing', 'gst' ); ?></h2>
				<form action="" method="post">
				    <table class="form-table">
				        <tr>
				            <th style="width:100px;"><label for="GST_license_key"><?php _e( 'License Key', 'gst' ); ?></label></th>
				            <td ><input class="regular-text" type="text" id="GST_license_key" name="GST_license_key"  value="<?php echo get_option('GST_license_key'); ?>" required ></td>
				        </tr>
				    </table>
				    <p class="submit">
				        <input type="submit" name="activate_license" value="<?php _e( 'Activate', 'gst' ); ?>" class="button-primary" />
				    </p>
				</form>
			</div>
			<?php
			echo ob_get_clean();
		}

		/**
		  * GST_license_form_handler
		  * Handles the license key form
		  */
		public function GST_license_form_handler() {
			$this->GST_license_check_activation();
			
			if ( isset( $_REQUEST[ 'activate_license' ] ) ) :
				
				if ( isset( $_REQUEST['GST_license_key'] ) ) :
					
					$license_key = $_REQUEST['GST_license_key'];
					$api_params = array(
					    'slm_action' => 'slm_activate',
					    'secret_key' => GST_LICENSE_VERIFICATION_KEY,
					    'license_key' => $license_key,
					    'registered_domain' => $_SERVER['SERVER_NAME'],
					);

					// Send query to the license manager server
					$query = esc_url_raw(add_query_arg($api_params, GST_SERVER_URL));

					$response = wp_remote_get($query);
					if (is_wp_error($response)) :
						add_action( 'admin_notices', function(){
						    $class = 'notice notice-error is-dismissible';
						    $message = __( 'Unexpected Error! The query returned with an error.', 'gst' );

						    printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message ); 
						});
					
					else :

						$license_data = json_decode( wp_remote_retrieve_body( $response ) );
						
						if ( $license_data->result == 'success' ) :
							update_option( 'GST_license_key', $_REQUEST['GST_license_key'] );
							add_action( 'admin_notices', function(){

								$class = 'notice notice-success is-dismissible';
								$message = __( 'License activated successfully.', 'gst' );

								$link = "<a href='".admin_url() . "admin.php?page=wc-settings&amp;tab=settings_gst_tab" . "'> Go to settings.</a>";

								printf( '<div class="%1$s"><p>%2$s %3$s</p></div>', $class, $message, $link );
							});

							$this->GST_license_check_activation();
							
						elseif( $license_data->error_code == 50 ):
							add_action( 'admin_notices', function(){
								$class = 'notice notice-error is-dismissible';
								$message = __( 'This license key is already in use on another domain. To activate please increase the domain.', 'gst' );

								printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );
							});

						elseif( $license_data->error_code == 40 || $license_data->error_code == 110 ) :
							update_option( 'GST_license_key', $_REQUEST['GST_license_key'] );
							add_action( 'admin_notices', function(){
								$class = 'notice notice-success is-dismissible';
								$message = __( 'This license key is already activated.', 'gst' );

								printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );
							});

						endif;

					endif;

				else :
					add_action( 'admin_notices', function(){
						$class = 'notice notice-error is-dismissible';
						$message = __( 'Please insert lincense key', 'gst' );

						printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );
					});
				endif;

			endif;
		}

		/**
		 * GST_license_check_activation
		 * Check license is active or not
		 */
		public function GST_license_check_activation() {
			
			if( get_option('GST_license_key') ) :
				$api_params = array(
			        'slm_action' => 'slm_check',
			        'secret_key' => GST_LICENSE_VERIFICATION_KEY,
			        'license_key' => get_option('GST_license_key'),
			        'version' => GST_VERSION
			);
			if ( false === ( $response = get_transient( 'GST_transient_response' ) ) ) {
				$response = wp_remote_get(add_query_arg($api_params, GST_SERVER_URL));
			}
			    if ( is_wp_error( $response ) ) :

			        add_action( 'admin_notices', function(){

			        	$class = 'notice notice-error is-dismissible';
			        	$message = __( 'Unexpected Error! The query returned with an error.', 'gst' );

			        	printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message ); 
			        } );

				else :

					set_transient( 'GST_transient_response', $response,  8 * HOUR_IN_SECONDS);
				    $data = json_decode( $response['body'],true );
				    $license_key = get_option('GST_license_key');
				    
				    if ( isset( $data['status'] ) ) :

				    	switch ( $data['status'] ) {
				    		case 'active' : 
				    				$key = $this->my_simple_crypt('is_active');
				    				
				    				update_option( 'GST_is_active', $key );

							    	require_once('invoice/class-gst-woocommerce-invoice.php');
							    	
							    	break;

							case 'pending' :

									add_action( 'admin_notices', function(){

										$class = 'notice notice-error is-dismissible';
										$message = __( 'Your license key is pending for approval.', 'gst' );
										printf( '<div class="%1$s"><p>%2$s </p></div>', $class, $message ); 

									} );
									update_option( 'GST_is_active', $this->my_simple_crypt('is_inactive') );
									break;

							case 'blocked' :

									add_action( 'admin_notices', function(){

										$class = 'notice notice-error is-dismissible';
										$message = __( 'Your license key is blocked.', 'gst' );
										printf( '<div class="%1$s"><p>%2$s </p></div>', $class, $message ); 

									} );
									update_option( 'GST_is_active', $this->my_simple_crypt('is_inactive') );
									break;

							case 'expired' :

									add_action( 'admin_notices', function(){
									
										$class = 'notice notice-error is-dismissible';
										$message = __( 'Your license key is expired.', 'gst' );
										printf( '<div class="%1$s"><p>%2$s </p></div>', $class, $message ); 				
									} );

									update_option( 'GST_is_active', $this->my_simple_crypt('is_inactive') );
									break;
				    	}

					endif;
				endif;
			else :
				add_action( 'admin_notices', function(){

					$class = 'notice notice-error is-dismissible';
					$message = __( 'Please enter the GST license key.', 'gst' );
					
					$link = "<a href='".admin_url() . "admin.php?page=gst-settings" . "'> Click here to enter license key</a>";
					printf( '<div class="%1$s"><p>%2$s %3$s</p></div>', $class, $message, $link ); 
				} );				
				update_option( 'GST_is_active', $this->my_simple_crypt('is_inactive') );
			endif;
		}

		/**
		 * Check license is active
		 * 
		 * @return     <boolean>  ( true | false )
		 */
		public function GST_is_active() {
			
			$status = $this->my_simple_crypt( get_option( 'GST_is_active', true ), 'd' );
			if( $status == 'is_active' )
				return true;
			
			if( $status == 'is_inactive' )
				return false;
		}


		/**
		 * Encrypts the string
		 *
		 * @param      string   $string  The string
		 * @param      string   $action  The action
		 *
		 * @return     $output  ( encrypted or decrypted key )
		 */
		public function my_simple_crypt( $string, $action = 'e' ) {
		    // you may change these values to your own
		    $secret_key = 'gst_license';
		    $secret_iv = 'gst_pro_woocommerce';
		 
		    $output = false;
		    $encrypt_method = "AES-256-CBC";
		    $key = hash( 'sha256', $secret_key );
		    $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
		 
		    if( $action == 'e' ) {
		        $output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
		    }
		    else if( $action == 'd' ){
		        $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
		    }
		 
		    return $output;
		}


		/**
		 * gst_tools_settings_callback
		 * GST tools submenu content
		 */
		public function gst_tools_settings_callback() {
			?>
		 	<h3><?php _e('Refresh Transients','gst'); ?></h3>
			<form method="POST">
				<p class="submit"><input type="submit" name="submit_woo_gst_refresh_trans" id="submit" class="button button-primary" value="Refresh"></p>
			</form>
			<?php
		} 

		/**
		 * GST_refresh_transient
		 * GST settings refresh the transients
		 */
		public function GST_refresh_transient() {
			if ( isset( $_REQUEST['submit_woo_gst_refresh_trans'] ) ) :
				if ( false !== ( $response = get_transient( 'GST_transient_response' ) ) ) :
					delete_transient( 'GST_transient_response' );
					add_action( 'admin_notices', function(){
						$class = 'notice notice-success is-dismissible';
						$message = __( 'Woogst transients refreshed.', 'gst' );

						printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message ); 
					});
				endif;
			endif;
		}

	}


endif;
