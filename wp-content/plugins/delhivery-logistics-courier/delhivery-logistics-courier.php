<?php
/**
   * Plugin Name:  Delhivery Logistics Courier
   * Plugin URI: https://wordpress.org/plugins/delhivery-logistics-courier/
   * Description: Delhivery
   * Author: Delhivery
   * Version: 1.0.107
   * Domain Path: /languages
   * Requires at least: 4.8
   * Tested up to: 6.1.1
   * WC requires at least: 3.2
   * WC tested up to: 3.5
   * Text Domain: delhivery-logistics-courier
   * Author URI:  https://wordpress.org/plugins/delhivery-logistics-courier/
   */
 
//session_start();
if(session_status() == PHP_SESSION_NONE) {
    session_start();

}
defined( 'ABSPATH' ) or die( 'Keep Silent' );
  
if ( ! class_exists( 'Delhivery_Logistic_Courier' ) ):
    
class Delhivery_Logistic_Courier 
{

    public function __construct() 
    {
        if (is_admin()) {
            register_activation_hook(__FILE__, array(&$this, 'activate'));
            register_deactivation_hook( __FILE__, array(&$this, 'my_plugin_remove_database'));

        }


        add_filter( 'manage_edit-shop_order_columns', 'DEL_MY_COLUMNS_FUNCTION' );
        function DEL_MY_COLUMNS_FUNCTION( $columns ) {
          $new_columns = ( is_array( $columns ) ) ? $columns : array();
            unset( $new_columns[ 'order_actions' ] );
          
            //edit this for your column(s)
            //all of your columns will be added before the actions column
            //$new_columns['Shipping Address'] = 'Shipping Address';
            //$new_columns['Billing Address'] =  'Billing Address';
            $new_columns['Shipping Cost'] =    'Shipping Cost';
            $new_columns['Tax'] =    'Tax';
            $new_columns['Discount'] =    'Discount';
            $new_columns['Payment Method'] =    'Payment Method';

            //stop editing
            return $new_columns;
          }


          add_action( 'manage_shop_order_posts_custom_column', 'DEL_MY_COLUMNS_VALUES_FUNCTION', 2 );
          function DEL_MY_COLUMNS_VALUES_FUNCTION( $column ) {
            global $post;
            $data = get_post_meta( $post->ID );
            
            
            //start editing, I was saving my fields for the orders as custom post meta
            //if you did the same, follow this code
            
            if ( $column == 'Shipping Address' ) {
              echo ( isset( $data[ '_shipping_address_index' ][0] ) ? esc_html($data[ '_shipping_address_index' ][0]) : '' );
            }
            
            if ( $column == 'Billing Address' ) {
              echo ( isset( $data[ '_billing_address_index' ][0] ) ? esc_html($data[ '_billing_address_index' ][0]) : '' );
            }
            if ( $column == 'Shipping Cost' ) {
              echo ( isset( $data[ '_order_shipping' ][0] ) ? esc_html($data[ '_order_shipping' ][0]) : '' );
            }
            if ( $column == 'Tax' ) {
              echo ( isset( $data[ '_order_tax' ][0] ) ? esc_html($data[ '_order_tax' ][0]) : '' );
            }
            if ( $column == 'Discount' ) {
              echo ( isset( $data[ '_cart_discount' ][0] ) ? esc_html($data[ '_cart_discount' ][0]) : '' );
            }
            if ( $column == 'Payment Method' ) {
              echo ( isset( $data[ '_payment_method' ][0] ) ? esc_html($data[ '_payment_method' ][0]) : '' );
            }
            
            
            
          }

        /*
          |--------------------------------------------------------------------------
          | APPLY ACTIONS & FILTERS IS WOOCOMMERCE IS ACTIVE
          |--------------------------------------------------------------------------
          */
          /* woocommerce dependency check */
          if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) 
          {
            /********** action to menu  *********/
            add_action('admin_menu',  array(&$this,'woo_order_detail_menu'), 10);
                        
          } 
           
           add_action( 'admin_enqueue_scripts', 'script_list' );

           
        function script_list() {
        wp_enqueue_style( 'custom',  plugins_url('/css/all.css',__FILE__) );
        wp_enqueue_style( 'font', plugins_url('/css/font.css',__FILE__) );
        wp_enqueue_style( 'owl.carousel.min', plugins_url('/css/owl.carousel.min.css',__FILE__) );
        wp_enqueue_style( 'datetimepicker',  plugins_url('/css/jquery.datetimepicker.min.css',__FILE__) );
        wp_enqueue_script('jQuery');
        wp_enqueue_script( 'owl.carousel_min_js', plugins_url('/js/owl.carousel.min.js',__FILE__));
        wp_enqueue_script( 'popper.min_js', plugins_url('/js/popper.min.js',__FILE__));
        
      
      }

      
    add_action( 'wp_ajax_save_aws', 'save_aws_callback' );
    add_action('wp_ajax_nopriv_save_aws', 'save_aws_callback');
    function save_aws_callback() 
    {
        require_once( WP_PLUGIN_DIR . '/delhivery-logistics-courier/admin/config.php' );
        global $wpdb; // this is how you get access to the database
        $table_name = $wpdb->prefix . 'dv_awb_no_details'; 
        
        $table_name = $wpdb->prefix . 'dv_awb_no_details';
        $username= sanitize_text_field($_SESSION['username']);
             
              $getawb = $wpdb->get_results("SELECT awb_no from ".$wpdb->prefix."dv_awb_no_details where status=0 and created_by='$username'" );
              if(count($getawb)>100)
              {
                $data['status'] = 0;
                $data['err_msg'] = 'You can not fetch AWB No,First use Unused AWB No.';
                echo  json_encode($data);
              }
              else
              {
                $count = $awb_no_count; 
                $accesstoken = 'Bearer '.$auth_token; 
                $url = $base_url.'api/wbn/bulk.json?count='.$count;
                $headers = array(
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json', 
                        'Authorization' => $accesstoken
                    );
                $arg = array(
                  'headers' => $headers,
                  'timeout'     => 45,
                  'redirection' => 5,
                  'httpversion' => '1.0',
                  'blocking'    => true
                );
                $res = wp_remote_get($url,$arg);
                $output = wp_remote_retrieve_body($res);
                $output = json_decode( $output, true );
                //print_r($output);
                $res_value = json_encode($output);
                $data_header = json_encode($headers);
                $logqry = "insert into ".$wpdb->prefix."dv_logs set api_name='fetch_awb_no',header_value='$data_header',url='$url',response_value='',order_id=0,request_value=''";
                $wpdb->query($logqry);
                $last_log_id = $wpdb->insert_id;
                if(isset($output['error']) &&  $output['error']!='')
                {
                  $error = $output['error'];
                  $jdata['status'] = 0;
                  $jdata['err_msg'] = $error;

                }
                else if(isset($output['detail']) && $output['detail']!='')
                {
                  $error = $output['detail'];
                  $jdata['status'] = 0;
                  $jdata['err_msg'] = $error;

                }
                else
                {
                  $k=0;
                  $query="insert into $table_name(awb_no,status,created_by) values";
                  foreach($output['wbns'] as $datas)
                  {
                    $aws_no = $datas;
                    if($k==0)
                    {
                      $comma_sep='';
                    }
                    else
                    {
                      $comma_sep=',';
                    }
                    $query .=$comma_sep."('$aws_no','0','$username')";
                    $k++;
                  }
                  $savedata = $wpdb->query($query); 
                  if($savedata) 
                  {
                    $jdata['status'] = 1;
                  }
                  else
                  {
                    $jdata['status'] = 0;
                    $jdata['err_msg'] = 'Problem in saving AWB no in database';
                  }
                  
                }
                $lqry = "update ".$wpdb->prefix."dv_logs set response_value='$res_value' where id=$last_log_id";
                $wpdb->query($lqry);
                echo json_encode($jdata);
                
              }
              
            wp_die(); // this is required to terminate immediately and return a proper response
    }
    add_action( 'wp_ajax_get_invoice', 'get_invoice_callback' );
    function get_invoice_callback()
    {
        require_once( WP_PLUGIN_DIR . '/delhivery-logistics-courier/admin/config.php' );
        global $wpdb;
        $client_name = sanitize_text_field($_GET['client_name']);
        $o_pincode = sanitize_text_field($_GET['org_pincode']);
        $d_pincode = sanitize_text_field($_GET['d_pincode']); 
        $wgt_in_gram = sanitize_text_field($_GET['wgt_in_gram']);
        if($wgt_in_gram=='')
        {
          $wgt_in_gram =0;
        }
        $shipment_mode = sanitize_text_field($_GET['shipment_mode']);
        
        $shipment_status = sanitize_text_field($_GET['shipment_status']);
        $payment_mode = sanitize_text_field($_GET['payment_mode']);
        if(isset($_GET['cod']))
        {
          $cod = sanitize_text_field($_GET['cod']);
        }
        else
        {
          $cod = '';
        }
        $_SESSION['o_pincode'] = $o_pincode;
        $_SESSION['d_pincode'] = $d_pincode;
        $_SESSION['wgt_in_gram'] = $wgt_in_gram;
        $_SESSION['shipment_mode'] = $shipment_mode;
        $_SESSION['shipment_status'] = $shipment_status;
        $_SESSION['payment_mode'] = $payment_mode;
        
        if($cod=='')
        {
          $url =$base_url.'api/kinko/v1/invoice/charges/.json?cl='.$client_name.'&ss='.$shipment_status.'&md='.$shipment_mode.'&pt='.$payment_mode.'&d_pin='.$d_pincode.'&o_pin='.$o_pincode.'&cgm='.$wgt_in_gram;
        }
        else
        {
           $url =$base_url.'api/kinko/v1/invoice/charges/.json?cl='.$client_name.'&ss='.$shipment_status.'&md='.$shipment_mode.'&pt='.$payment_mode.'&d_pin='.$d_pincode.'&o_pin='.$o_pincode.'&cgm='.$wgt_in_gram.'&cod='.$cod; 
        }


          $accesstoken = 'Bearer '.$auth_token; 
          $headers = array(
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json', 
                        'Authorization' => $accesstoken
                    );
          $arg = array(
            'headers' => $headers,
            'timeout'     => 45,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking'    => true
          );
          $res = wp_remote_get($url,$arg);
          $outputs = wp_remote_retrieve_body($res);
          $outputs = json_decode( $outputs, true );
          $data_header = json_encode($headers);
          $logqry = "insert into ".$wpdb->prefix ."dv_logs set order_id=0, api_name='rate_calculator',header_value='$data_header',url='$url' ,request_value='',response_value=''";
          $wpdb->query($logqry);
          $last_log_id = $wpdb->insert_id; 
          //Update order log table
          $res_value = json_encode($outputs);
          $lqry = "update ".$wpdb->prefix ."dv_logs set response_value='$res_value' where id=$last_log_id";
          $wpdb->query($lqry); 
          $gst = $outputs['0']['tax_data']['SGST']+$outputs['0']['tax_data']['IGST']+$outputs['0']['tax_data']['CGST'];
          $total =$gst+$outputs['0']['charge_DL']+$outputs['0']['charge_RTO']+$outputs['0']['charge_COD']+$outputs['0']['charge_FS']+$outputs['0']['charge_DTO'];
          $data = '<div class="row">
                           <div class="col-md-12">
                                 <div class="data-card"><table class="table table-bordered">
                                        <thead>
                                          <tr>
                                            <th scope="col">Fwd. Amount</th>
                                            <td scope="col">'.esc_html($outputs['0']['charge_DL']).'</td>
                                          </tr>
                                          <tr>
                                            <th scope="col">Charge RTO</th>
                                            <td scope="col">'.esc_html($outputs['0']['charge_RTO']).'</td>
                                          </tr>
                                          <tr>
                                            <th scope="col">COD Charge</th>
                                            <td scope="col">'.esc_html($outputs['0']['charge_COD']).'</td>
                                          </tr>
                                          <tr>
                                              <th scope="col">Fuel Surcharge</th>
                                              <td scope="col">'.esc_html($outputs['0']['charge_FS']).'</td>
                                                      
                                          </tr>
                                          <tr>
                                            <th scope="col">DTO Charge</th>
                                            <td scope="col">'.esc_html($outputs['0']['charge_DTO']).'</td>
                                          </tr>
                                          <tr>
                                              <th scope="col">GST</th>
                                              <td scope="col">'.esc_html($gst).'</td>
                                          </tr>
                                          <tr>
                                              <th scope="col">Total</th>
                                              <td scope="col">'.esc_html($total).'</td>
                                          </tr>
                                          </thead>
                                          
                                      </table>
                                  </div>
                                </div>
                           </div>';
          $jdata['data'] = $data; 
          $jdata['status'] = 1; 
          echo  json_encode($jdata);
          wp_die();
    }

    add_action( 'wp_ajax_check_pincode', 'check_pincode_callback' );
    function check_pincode_callback()
    {
        require_once( WP_PLUGIN_DIR . '/delhivery-logistics-courier/admin/config.php' );
        global $wpdb;
        $username=sanitize_text_field($_SESSION['username']);
        $table_name = $wpdb->prefix . 'dv_my_warehouse';
        $name = sanitize_text_field($_POST['name']);
        $order_id = sanitize_text_field($_POST['order_id']);
        $warehouse = $wpdb->get_row("SELECT return_address,return_pin,return_city,return_state,return_country from $table_name where name='$name'" );
        $return_address = $warehouse->return_address;
        $return_pin= $warehouse->return_pin;
        $return_city = $warehouse->return_city;
        $return_state = $warehouse->return_state;
        $return_country = $warehouse->return_country;
        $pincode = sanitize_text_field($_POST['pincode']); 
        $pin_url =$base_url.'c/api/pin-codes/json/?filter_codes='.$pincode;
        $url = $base_url.'api/backend/clientwarehouse/status/'; 
          $accesstoken = 'Bearer '.$auth_token; 
          $headers = array(
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json', 
                        'Authorization' => $accesstoken
                    );
          $arg = array(
            'headers' => $headers,
            'timeout'     => 45,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking'    => true
          );
          $res = wp_remote_get($pin_url,$arg);
          $outputs = wp_remote_retrieve_body($res);
          $outputs = json_decode( $outputs, true );
          $chk_pin_data_header = json_encode($headers);
          $pnlogqry = "insert into ".$wpdb->prefix."dv_logs set order_id=$order_id, header_value='$chk_pin_data_header ',api_name='check_pincode',url='$pin_url',response_value='',request_value=''";
          $wpdb->query($pnlogqry);
          $chk_pin_last_log_id = $wpdb->insert_id; 
          $chk_pin_res_value = json_encode($outputs);
          //var_dump($outputs);
          $chk_pin = $outputs["delivery_codes"]['0']["postal_code"]["pickup"]; 
          
          /* Check warehouse status */
          $name = sanitize_text_field($_POST['name']);
          $data = array(
                      'name'=> $name,
                      ); 
        
          $data_json = json_encode($data); 
          $headers = array(
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json', 
                        'Authorization' => $accesstoken
                    );
          $arg = array(
            'headers' => $headers,
            'timeout'     => 45,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking'    => true,
            'body'    =>  $data_json
          );
          $response = wp_remote_post( $url, $arg);
          $output = wp_remote_retrieve_body( $response );
          $output = json_decode( $output, true );
          $chk_warehouse_status_data_header = json_encode($headers);
          $logqry = "insert into ".$wpdb->prefix."dv_logs set header_value='$chk_warehouse_status_data_header',request_value='$data_json',api_name='check_warehouse_status',
          url='$url',order_id=$order_id,response_value='' ";
          $wpdb->query($logqry);
          $chk_warehouse_status_last_log_id = $wpdb->insert_id; 
          $chk_warehouse_status_res_value = json_encode($output);
          
          if($output['error']!='')
          {
            $jdata['status'] = 0;
            $jdata['err_msg'] = $output['error'];
            echo  json_encode($jdata);
          }
          else
          {
           
            if($chk_pin=='Y')
            {
              $jdata['status'] = 1;
              $jdata['return_address'] =$return_address;
              $jdata['return_pin'] =$return_pin;
              $jdata['return_city'] =$return_city;
              $jdata['return_state'] =$return_state;
              $jdata['return_country'] =$return_country;
              echo  json_encode($jdata);
             
            }
            else
            {
              $jdata['status'] = 0;
              $jdata['err_msg'] = 'Pincode is not serviceable for order '.$order_id;
              echo  json_encode($jdata);
            }
            
          }
          //Update Log table
          $chk_pin_res_value = str_replace("'", "",$chk_pin_res_value);
          $up_lqry = "update ".$wpdb->prefix."dv_logs set response_value='$chk_pin_res_value' where id=$chk_pin_last_log_id";
          $wpdb->query($up_lqry);
          
          $chk_warehouse_status_res_value = str_replace("'", "",$chk_warehouse_status_res_value);
          $up_whlqry = "update ".$wpdb->prefix."dv_logs set response_value='$chk_warehouse_status_res_value' where id=$chk_warehouse_status_last_log_id";
           $wpdb->query($up_whlqry);
           wp_die();
    } 
    add_action( 'wp_ajax_check_bulk_pincode', 'check_bulk_pincode_callback' );
    function check_bulk_pincode_callback()
    {
        require_once( WP_PLUGIN_DIR . '/delhivery-logistics-courier/admin/config.php' );
        global $wpdb;
        $table_name = $wpdb->prefix . 'dv_my_warehouse';
        $name = sanitize_text_field($_POST['name']);
        $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
       
        $warehouse = $wpdb->get_row("SELECT return_address,return_pin,return_city,return_state,return_country from $table_name where name='$name'" );
        //print_r($warehouse); die;
        $return_address = $warehouse->return_address;
        $return_pin= $warehouse->return_pin;
        $return_city = $warehouse->return_city;
        $return_state = $warehouse->return_state;
        $return_country = $warehouse->return_country;
        $url = $base_url.'api/backend/clientwarehouse/status/'; 
         
          $prefix = $pinList = '';
          foreach($_POST['order_id'] as $arr)
          {
            $order_ids = sanitize_text_field($arr);
            $order_ids_array = explode("|",$order_ids) ;
            $oid = $order_ids_array[0];
            $pincode = $order_ids_array[1];
            $pinList .= $prefix.$pincode;
            $prefix = ',';
          }
         
            $pin_urls =$base_url.'c/api/pin-codes/json/?filter_codes='.$pinList;
            $accesstoken = 'Bearer '.$auth_token; 
            $headers = array(
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json', 
                        'Authorization' => $accesstoken
                    );
            $arg = array(
            'headers' => $headers,
            'timeout'     => 45,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking'    => true
            );
            $res = wp_remote_get($pin_urls,$arg);
            $outputs = wp_remote_retrieve_body($res);
            $outputs = json_decode( $outputs, true ); 
            //print_r($outputs); wp_die();
            $chk_pin_data_header = json_encode($headers);
            $logqry = "insert into ".$wpdb->prefix."dv_logs set header_value='$chk_pin_data_header ',api_name='check_pincode',url='$pin_urls',order_id=0,request_value='',response_value='' ";
            $wpdb->query($logqry);
            $chk_pin_last_log_id = $wpdb->insert_id; 
            $chk_pin_res_value = json_encode($outputs);
            $name = sanitize_text_field($_POST['name']);
            $data = array(
                        'name'=> $name,
                        ); 
            $data_json = json_encode($data); 
            $header = array(
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json', 
                        'Authorization' => $accesstoken
                    );
            $arg = array(
              'headers' => $header,
              'timeout'     => 45,
              'redirection' => 5,
              'httpversion' => '1.0',
              'blocking'    => true,
              'body'    =>  $data_json
            );

            $response = wp_remote_post( $url, $arg);
            $output = wp_remote_retrieve_body( $response );
            $output = json_decode( $output, true );
            //print_r($output);
            $chk_warehouse_status_data_header = json_encode($header);
            $wlogqry = "insert into ".$wpdb->prefix."dv_logs set header_value='$chk_warehouse_status_data_header',request_value='$data_json',api_name='check_warehouse_status',url='$url',order_id=0,response_value='' ";
            $wpdb->query($wlogqry);
            $chk_warehouse_status_last_log_id = $wpdb->insert_id; 
            $chk_warehouse_status_res_value = json_encode($output);
            
            if($output['error']!='')
            {
              $jdata['status'] = 0;
              $jdata['err_msg'] = $output['error'];
              echo  json_encode($jdata);
              
            }
            else if(count($outputs['delivery_codes'])==0)
            {
              $jdata['status'] = 0;
              $jdata['err_msg'] = 'Pincode is not available for all selected orders!';
              echo  json_encode($jdata);
              
            }
            else
            {
              $res_pn_arr = array();
              foreach($outputs["delivery_codes"] as $pnarr)
              {
                $pn = $pnarr["postal_code"]["pin"];
                $res_pn_arr[] = $pn;
              }
              $succ_order_id = array();
              $err_order_id = array();
              //$chk_pin = $outputs[delivery_codes][0][postal_code][pickup];
              foreach($_POST['order_id'] as $arr)
              {
                $order_ids = sanitize_text_field($arr);
                $order_ids_array = explode("|",$order_ids) ;
                $oid = $order_ids_array[0];
                $pincode = $order_ids_array[1];
                
                if (in_array($pincode, $res_pn_arr))
                {
                  array_push($succ_order_id,$oid);
                  //$succ_order_id = $oid;
                }
                else
                {
                  array_push($err_order_id,$oid);
                  //$err_order_id = $oid;
                }
                $prefix = $err_order_id_list = '';
                foreach($err_order_id as $arr)
                {
                  $err_order_ids = $arr;
                  $err_order_id_list .= $prefix.$err_order_ids;
                  $prefix = ',';
                }
                 $prefix = $succ_order_id_list = '';
                foreach($succ_order_id as $arr)
                {
                  $succ_order_ids = $arr;
                  $succ_order_id_list .= $prefix.$succ_order_ids;
                  $prefix = ',';
                }
                  
              }
                
                $jdata['succ_order_id'] =  $succ_order_id_list;
                $jdata['err_order_id'] =  $err_order_id_list;
                $jdata['status'] = 1;
                $jdata['return_address'] =$return_address;
                $jdata['return_pin'] =$return_pin;
                $jdata['return_city'] =$return_city;
                $jdata['return_state'] =$return_state;
                $jdata['return_country'] =$return_country;
                echo  json_encode($jdata);
                
            }
        //Update Log table
        $chk_pin_res_value = str_replace( "'","",$chk_pin_res_value);
        $lqry = "update ".$wpdb->prefix."dv_logs set response_value='$chk_pin_res_value' where id=$chk_pin_last_log_id";
        $wpdb->query($lqry);
        $chk_warehouse_status_res_value = str_replace( "'","",$chk_warehouse_status_res_value);
        $wlqry = "update ".$wpdb->prefix."dv_logs set response_value='$chk_warehouse_status_res_value' where id=$chk_warehouse_status_last_log_id";
        $wpdb->query($wlqry);
        wp_die();
                
    }
    add_action( 'wp_ajax_track_order', 'track_order_callback' );
    function track_order_callback()
    {
        require_once( WP_PLUGIN_DIR . '/delhivery-logistics-courier/admin/config.php' );
        global $wpdb;
        $myorowss = $wpdb->get_results("SELECT awb_no from ".$wpdb->prefix."dv_assign_awb where status!='Delivered' order by id desc");
        $PERPAGE_LIMIT=50;
        $total=  count($myorowss);
        $n = ceil($total / $PERPAGE_LIMIT);

        //echo $n = count($myorows)%$PERPAGE_LIMIT;

        for($i=0;$i<$n;$i++)
        {
          if($i==0)
          {
            $spage = 0;
          }  
          else
          {
            $spage = $i*$PERPAGE_LIMIT;
          }
          //echo "SELECT awb_no from ".$wpdb->prefix."dv_assign_awb where status!='Delivered' order by id desc limit $spage,$PERPAGE_LIMIT";
          $myorows = $wpdb->get_results("SELECT awb_no from ".$wpdb->prefix."dv_assign_awb where status!='Delivered' order by id desc limit $spage,$PERPAGE_LIMIT");
          
          $prefix = $awbList = '';
          foreach($myorows as $myorowsdt)
          {
            if($myorowsdt->awb_no!='')
            {
              $awbList .= $prefix . $myorowsdt->awb_no;
              $prefix = ',';
            }
          }
          //echo $awbList;
          $token = $auth_token;
          $track_order_url = $base_url.'api/v1/packages/json/';
          $track_order_url = $track_order_url.'?token='.$token.'&waybill='.$awbList.'&verbose=2'; 
          
            $accesstoken = 'Bearer '.$auth_token; 
            $headers = array(
                          'Content-Type' => 'application/json',
                          'Accept' => 'application/json', 
                          'Authorization' => $accesstoken
                      );
            $arg = array(
              'headers' => $headers,
              'timeout'     => 45,
              'redirection' => 5,
              'httpversion' => '1.0',
              'blocking'    => true
              
            );
            $res = wp_remote_get($track_order_url,$arg);
            $outputs = wp_remote_retrieve_body($res);
            $outputs = json_decode( $outputs, true );
            $tdata = $outputs["ShipmentData"];
            //print_r($data);
            foreach($tdata as $trackdata)
            {
              $order_id = $trackdata["Shipment"]["ReferenceNo"];
              $awb_no = $trackdata["Shipment"]["AWB"];
              $status = $trackdata["Shipment"]["Status"]["Status"];
              $statustype = $trackdata["Shipment"]["Status"]["StatusType"];
              $instructions = $trackdata["Shipment"]["Status"]["Instructions"];
              $gtdata = $wpdb->get_row("SELECT status,instructions from ".$wpdb->prefix."dv_assign_awb where awb_no='$awb_no'" );
              $gt_status= $gtdata->status;
              $gt_instructions= $gtdata->instructions;
              
              if($gt_instructions!=$instructions)
              {
              //Update Status of order
                $qry2 = "update ".$wpdb->prefix."dv_assign_awb set status='$status',status_type='$statustype',instructions='$instructions' where awb_no='$awb_no'"; 
                $wpdb->query($qry2);
                if($status=='Delivered')
                {

                  $order = new WC_Order($order_id);

                  if (!empty($order)) {
                    $order->update_status( 'completed' );
                  }
                  $orders = wc_get_order(  $order_id );
                  // The text for the note
                  $note = __("Order is delivered by Delhivery_Logistic_Courier");
                  // Add the note
                  $orders->add_order_note( $note,true );
                }
                /*else if($status=='Manifested')
                {
                  $order = new WC_Order($order_id);

                  if (!empty($order)) {
                    //$order->update_status( 'shipped' );
                    $up_lqry = "update ".$wpdb->prefix."posts set post_status='wc-shipped' where ID=$order_id and post_type='shop_order'";
                    $wpdb->query($up_lqry);
                  }
                  $orders = wc_get_order(  $order_id );
                  // The text for the note
                  $note = __("Package is picked up");
                  // Add the note
                  $orders->add_order_note( $note,true );
                }*/
              }
            }
            $res_vlaue = json_encode($outputs);
            $data_header = json_encode($headers);
            $logqry = "insert into ".$wpdb->prefix."dv_logs set api_name='track_bulk_order',header_value='$data_header ',url='$track_order_url',response_value='',order_id=0,request_value=''";
            $wpdb->query($logqry);
            $last_log_id = $wpdb->insert_id; 
            //Update log table
            $up_lqry = "update ".$wpdb->prefix."dv_logs set response_value='$res_vlaue' where id=$last_log_id";
            $wpdb->query($up_lqry);
        }
          $jdata['status'] = 1;
          echo  json_encode($jdata);
          wp_die();
    }
    add_action( 'wp_ajax_cancel_order', 'cancel_order_callback' );
    function cancel_order_callback()
    {
        require_once( WP_PLUGIN_DIR . '/delhivery-logistics-courier/admin/config.php' );
        global $wpdb;
        $token = $auth_token;
        $waybill_no = sanitize_text_field($_POST['waybill_no']);
        $order_id = sanitize_text_field($_POST['order_id']);
        $track_order_url = $base_url.'api/v1/packages/json/';
        $cancel_order_url = $base_url.'api/p/edit';
        $track_order_url = $track_order_url.'?token='.$token.'&waybill='.$waybill_no.'&verbose=2';
        $accesstoken = 'Bearer '.$auth_token; 
        $headers = array(
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json', 
                        'Authorization' => $accesstoken
                    );
        $arg = array(
          'headers' => $headers,
          'timeout'     => 45,
          'redirection' => 5,
          'httpversion' => '1.0',
          'blocking'    => true
          
        );
        $res = wp_remote_get($track_order_url,$arg);
        $outputs = wp_remote_retrieve_body($res);
        $outputs = json_decode( $outputs, true );
        $status = $outputs["ShipmentData"][0]["Shipment"]["Status"]["Status"];
        $instructions = $outputs["ShipmentData"][0]["Shipment"]["Status"]["Instructions"]; 
        if($outputs["Error"]=='')
        {
            if($status=='Manifested' || $status=='In Transit' || $status=='Pending' || $status=='Scheduled' || $status=='Open')
            {
              $data = array('waybill'=> $waybill_no,
                             'cancellation'=> 'true'
                            ); 
              
                $data_json = json_encode($data); 
                $headers = array(
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json', 
                        'Authorization' => $accesstoken
                    );
                $arg = array(
                  'headers' => $headers,
                  'timeout'     => 45,
                  'redirection' => 5,
                  'httpversion' => '1.0',
                  'blocking'    => true,
                  'body'    =>  $data_json
                );
                $response = wp_remote_post( $cancel_order_url, $arg);
                $output = wp_remote_retrieve_body( $response );
                $output = json_decode( $output, true );
                //print_r($output);
                //Insert into log table
                $res_vlaue = json_encode($output);
                $data_header = json_encode($headers);
                $logqry = "insert into ".$wpdb->prefix."dv_logs set order_id='$order_id',api_name='cancel_order',header_value='$data_header ' ,request_value='$data_json',url='$cancel_order_url',response_value=''";
                $wpdb->query($logqry);
                $last_log_id = $wpdb->insert_id; 
                //Update log table
                $up_lqry = "update ".$wpdb->prefix."dv_logs set response_value='$res_vlaue' where id=$last_log_id";
                $wpdb->query($up_lqry);
                if($output['status'])
                {
                 //Update Status of order item
                  $remark = $output['remark'];
                  if($remark=='Shipment has been cancelled.')
                  {
                    $instruction='Seller cancelled the order';
                  }
                  $qry2 = "update ".$wpdb->prefix."dv_assign_awb set status='$status',instructions='$instruction' where order_id=$order_id";
                  $wpdb->query($qry2); 
                  $jdata['status'] = 1; 
                  echo json_encode($jdata);
                }
                
            }
            else
            {
              $jdata['status'] = 0;
              $jdata['err_msg'] = 'Order can not be cancelled beacause its status is '.$status;
              echo  json_encode($jdata);
              
            }
        }
        else
        {
          $jdata['status'] = 0;
          $jdata['err_msg'] = "Error is ".$outputs["Error"];
          echo  json_encode($jdata);
        }
        wp_die();
    }
    add_action( 'wp_ajax_fetch_order_list', 'fetch_order_list_callback' );
    function fetch_order_list_callback()
    {
        require_once( WP_PLUGIN_DIR . '/delhivery-logistics-courier/admin/config.php' );
        global $wpdb;
        $startPage = sanitize_text_field($_POST['getresult']);
        $PERPAGE_LIMIT = 20;
        $myrow = $wpdb->get_results("SELECT a.order_id, e.meta_value as shipping_pincode , f.meta_value as billing_pincode FROM ".$wpdb->prefix."woocommerce_order_items a  JOIN  ".$wpdb->prefix."postmeta e  ON a.order_id = e.post_id JOIN  ".$wpdb->prefix."postmeta f ON a.order_id = f.post_id WHERE a.order_item_type = 'line_item' AND  e.meta_key='_shipping_postcode' AND f.meta_key='_billing_postcode' AND   a.order_item_type='line_item' group by a.order_id ORDER BY a.order_id ASC");
        $count = 0;
        foreach($myrow as $row)
        {
          $o_id = $row->order_id;
          $order = wc_get_order( $o_id );
          $order_status  = $order->get_status();
          if($order_status=='processing' || $order_status=='pending')
          {  
            $count++; 
          }
        }
        
        $orders = $wpdb->get_results("SELECT a.order_id,e.meta_value as shipping_pincode,f.meta_value as billing_pincode,g.meta_value as total_amount ,i.meta_value as payment_method ,j.meta_value as sname,k.meta_value as bname,l.meta_value as phoneno FROM ".$wpdb->prefix."woocommerce_order_items a JOIN   ".$wpdb->prefix."postmeta e  ON a.order_id = e.post_id  JOIN  ".$wpdb->prefix."postmeta f ON a.order_id = f.post_id JOIN  ".$wpdb->prefix."postmeta g ON a.order_id = g.post_id JOIN ".$wpdb->prefix."postmeta i ON a.order_id = i.post_id JOIN  ".$wpdb->prefix."postmeta j  ON a.order_id = j.post_id JOIN  ".$wpdb->prefix."postmeta k ON a.order_id = k.post_id JOIN  ".$wpdb->prefix."postmeta l ON a.order_id = l.post_id WHERE a.order_item_type = 'line_item'  AND e.meta_key='_shipping_postcode' AND f.meta_key='_billing_postcode' AND g.meta_key='_order_total' and j.meta_key ='_shipping_first_name' and k.meta_key ='_billing_first_name' AND l.meta_key ='_billing_phone' AND a.order_item_type='line_item'  and i.meta_key ='_payment_method' group by a.order_id ORDER BY a.order_id DESC limit $startPage,$PERPAGE_LIMIT");
        

        //print_r($orders);
    
          $datas='<table class="table table-borderless">
                  <thead class="border-bottom">
                 <tr>
                    <th>
                      <label class="checkbox">
                      <input type="checkbox" class="selectall" />
                      <span class="checkmark all-select-checkbox"></span>
                    </th>
                    <th scope="col">Actions</th>
                    <th scope="col">Order ID</th>
                    <th scope="col">Product Description</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Shipping Pincode</th>
                    
                    <th scope="col">Total Amount</th>
                    <th scope="col">Payment Type</th>
                    <th scope="col">Status</th>
                    <th scope="col">Status Type</th>
                    <th scope="col">Instructions</th>

                 </tr>
              </thead><tbody>';
            foreach($orders as $data)
            {
              $scost_detail  = $wpdb->get_row("select count(*) as cnt,shipping_cost,awb_no,status,shipment_status,seller_invoice,e_waybill_no,status_type from ".$wpdb->prefix."dv_assign_awb where order_id='$data->order_id'");
              $shipping_cost = $scost_detail->shipping_cost; 
              $awbno = $scost_detail->awb_no; 
              $status = $scost_detail->status;
              $shipment_status = $scost_detail->shipment_status;
              $e_waybill_no = $scost_detail->e_waybill_no;
              if($e_waybill_no=='')
              {
                $e_waybill_no=0;
              }
              $seller_invoice = $scost_detail->seller_invoice;
              if($seller_invoice=='')
              {
                $seller_invoice=0;
              }
              $cnt = $scost_detail->cnt;
              $status_type = $scost_detail->status_type;
              if($status_type=='')
              {
                $status_type='NA';
              }
              $item_values = "";
              $get_item =  $wpdb->get_results("select order_item_name from ".$wpdb->prefix."woocommerce_order_items where order_id=$data->order_id and order_item_type='line_item'");
              foreach ($get_item as $get_items) {
              $item_values != "" && $item_values .= ",";
              $item_values .= $get_items->order_item_name;
              }
           
              $quantity = 0;
              $get_item_qty =  $wpdb->get_results("select b.meta_value as qty FROM ".$wpdb->prefix."woocommerce_order_items a JOIN ".$wpdb->prefix."woocommerce_order_itemmeta b ON a.order_item_id = b.order_item_id where a.order_id=$data->order_id and meta_key='_qty'");
              foreach ($get_item_qty as $get_item_qtys) 
              {
                $quantity = $quantity+$get_item_qtys->qty;
              }
              $total_amount = 0;
              $get_item_price =  $wpdb->get_results("select b.meta_value as prc FROM ".$wpdb->prefix."woocommerce_order_items a JOIN ".$wpdb->prefix."woocommerce_order_itemmeta b ON a.order_item_id = b.order_item_id where a.order_id=$data->order_id and meta_key='_line_subtotal'");
              foreach ($get_item_price as $get_item_prices) 
              {
                $total_amount = $total_amount+$get_item_prices->prc;
              }
              if(@$data->shipping_pincode=='')
              {
                $pincode = $data->billing_pincode;
              } 
              else
              {
                $pincode = $data->shipping_pincode;
              }
              if($instructions!='Seller cancelled the order')
              { 
                     if($cnt>0 && $shipment_status==1 )  
                     { 
                      $action =  '<a href="'.$edit_url.'" class="tooltip-hover" tooltip-toggle="tooltip" data-placement="left" title="Edit" target="_top"><i class="fas fa-pen gray-icon"></i></a>
                        <a  href="'.$track_url.'" class="tooltip-hover" tooltip-toggle="tooltip" data-placement="top" title="Track" target="_top"><i class="fas fa-search gray-icon"></i></a>
                        <a href="#" class="tooltip-hover" tooltip-toggle="tooltip" data-placement="bottom" title="Cancel" onclick="cancel_order('.$data->order_id.','.$awbno.');"><i class="fas fa-trash-alt gray-icon"></i></a>
                        <a href="#" class="tooltip-hover" tooltip-toggle="tooltip" data-placement="bottom" title="Shpping Label" onclick="do_ship('.$data->order_id.','.$shipping_cost.','.$awbno.');"><i class="fas fa-tag gray-icon"></i></a>';

                     } 
                     
                     else 
                     {
                        $action = '<button class="btn btn-link btn-sm btn-reset order-btn" data-toggle="modal" 
                        data-target="#ship-order-Modal" onclick="show_modal('.$data->total_amount.','.$pincode.','.$data->order_id.','.$seller_invoice.','.$e_waybill_no.');">ship order</button>';
                     } 

                } 
                else 
                { 
                  $action = 'NA'; 
                }

                  
                $edit_url = site_url()."/wp-admin/admin.php?page=my_order&action=edit&order_id=".$data->order_id."&awb_no=".$awbno;
                $track_url = site_url()."/wp-admin/admin.php?page=my_order&action=track&order_id=".$data->order_id."&awb_no=".$awbno;
                $edit_order_url = site_url().'/wp-admin/post.php?post='.$data->order_id.'&action=edit';
                $order = wc_get_order( $data->order_id );
                $order_status  = $order->get_status();
                if($order_status=='processing' || $order_status=='pending')
                {  
                  $datas .= '<tr>
                      <td><label class="checkbox">
                         <input type="checkbox"  name="order_id"  id="order_id[]" 
                         value="'.esc_html($awbno).'|'.esc_html($pincode).'">
                        <span class="checkmark"></span>
                      </td>
                      <td><div id="actioncls'.esc_html($order_id).'" >'.esc_html($action).'
                      </div>
                      <div id="ajax_actioncls'.esc_html($order_id).'" style="display:none;"></div>
                      </td>
                      <td>'.esc_html($data->order_id).'</td>
                      <td>'.esc_html($item_values).'</td>
                      <td>'.esc_html($quantity).'</td>
                      <td>'.esc_html($pincode).'</td>
                      <td>'.esc_html($data->total_amount).'</td>
                     
                      <td>'.esc_html(ucwords($data->payment_method)).'</td>
                      <td>'.esc_html($status).'</td>
                      <td>'.esc_html($status_type).'</td>
                    </tr>';
              }
            }
          
              $datas .='</tbody></table>'; 
              

              $jdatas['status'] = 1;
              $jdatas['total_count'] = $count;
              $jdatas['fetchdata'] = $datas;
              echo  json_encode($jdatas);
              wp_die();
    }
    add_action( 'wp_ajax_create_bulk_order_list', 'create_bulk_order_list_callback' );
    function create_bulk_order_list_callback()
    {
        require_once( WP_PLUGIN_DIR . '/delhivery-logistics-courier/admin/config.php' );
        global $wpdb;
        $username= sanitize_text_field($_SESSION['username']);
        $table_name = $wpdb->prefix . 'dv_my_warehouse';
        //session_start();
        if(session_status() == PHP_SESSION_NONE) {
          session_start();
        }
        $order_id_list = sanitize_text_field($_POST['order_id_list']);
        $err_order_id_list = sanitize_text_field($_POST['err_order_id_list']);
        $ware_house_name = sanitize_text_field($_POST['ware_house']);
        $order_id_array = explode(",",$order_id_list) ;
        $statusval = sanitize_text_field($_POST['statusval']);
        if($statusval==1)
        {
            if($err_order_id_list=='')
            {
              $smsg = 'Orders Manifested Successfully,create a Pickup Request';
            }
            else
            {
              $smsg = 'Selected Orders have been processed Successfully. Following orders are not processed as the mentioned Pincode is not serviceable.';
              $err_order_id_list_arr = explode(",",$err_order_id_list);
              
              foreach($err_order_id_list_arr as $eoid)
              {
                if($i==0)
                {
                  $smsg.= $eoid;
                }
                else
                {
                  $smsg.= ','.$eoid;
                }
                
                $i++;
              }
             

            }
            $_SESSION['succmsg'] = $smsg;
            $jdatas['status'] = 1; 
            $jdatas['suc_msg'] = $smsg;
            echo  json_encode($jdatas);
        }
        else
        {
            $get_warehouse = $wpdb->get_row("SELECT id,phone,city,name,pin,address,country,contact_person,state,email,registered_name,status,created_at from $table_name where name='$ware_house_name'" );
            $wpincode = $get_warehouse->pin;
            $waddress= $get_warehouse->address;
            $wphone = $get_warehouse->phone;
            $wcity = $get_warehouse->city;
            $wstate = $get_warehouse->state;
            $wcountry = $get_warehouse->country;
            $wname = $get_warehouse->name;
            $data_pin = array('pin'=> preg_replace('/[; & # % ]+/', ' ', trim($wpincode)),
                        'add'=> preg_replace('/[; & # % ]+/', ' ', trim($waddress)),
                        'phone'=> preg_replace('/[; & # % ]+/', ' ', trim($wphone)),
                        'state'=> preg_replace('/[; & # % ]+/', ' ', trim($wstate)),
                        'city'=> preg_replace('/[; & # % ]+/', ' ', trim($waddress)),
                        'country'=>preg_replace('/[; & # % ]+/', ' ', trim($wcountry)),
                        'name'=> $wname
                        );
            $prefix = $data_ship_json = '';
            foreach($order_id_array as $oarr) 
            {
            $getawb = $wpdb->get_results("SELECT awb_no from ".$wpdb->prefix."dv_awb_no_details where status=0  and created_by='$username'" );

            if(count($getawb)>0)
            {
              foreach($getawb as $dgetawb)
              {
                $awb_no = $dgetawb->awb_no;
              }
            }
            else
            {
                $count = $awb_no_count; 
                $accesstoken = 'Bearer '.$auth_token; 
                $url = $base_url.'api/wbn/bulk.json?count='.$count;
                $headers = array(
                          'Content-Type' => 'application/json',
                          'Accept' => 'application/json', 
                          'Authorization' => $accesstoken
                      );
                $arg = array(
                  'headers' => $headers,
                  'timeout'     => 45,
                  'redirection' => 5,
                  'httpversion' => '1.0',
                  'blocking'    => true
                );
                $res = wp_remote_get($url,$arg);
                $output = wp_remote_retrieve_body($res);
                $output = json_decode( $output, true );
                $awb_res_value = json_encode($output);
                $awb_data_header = json_encode($headers);
                $alogqry = "insert into ".$wpdb->prefix."dv_logs set order_id='$order_id',api_name='fetch_awb_no',header_value='$awb_data_header',url='$url',response_value='',request_value=''";
                $wpdb->query($alogqry);
                $aws_last_log_id = $wpdb->insert_id;
                if($output['error']!='')
                {
                  $error = $output['error'];
                  

                }
                else if($output['detail']!='')
                {
                  $error = $output['detail'];
                  

                }
                else
                {
                  $k=0;
                  $query="insert into ".$wpdb->prefix."dv_awb_no_details(awb_no,status,created_by) values";
                  foreach($output[wbns] as $datas)
                    {
                      $aws_no = $datas;
                      if($k==0)
                      {
                        $comma_sep='';
                      }
                      else
                      {
                        $comma_sep=',';
                      }
                      $query .=$comma_sep."('$aws_no','0','$username')";
                      $k++;
                    }
                    $wpdb->query($query); 
                  
                }
               
                
                $alqry = "update ".$wpdb->prefix."dv_logs set response_value='$awb_res_value' where id=$aws_last_log_id";
                $wpdb->query($alqry);

              $get_awbs = $wpdb->get_results("SELECT awb_no from ".$wpdb->prefix."dv_awb_no_details where status=0 and created_by='$username'" );
              foreach($get_awbs as $dget_awbs)
              {
                $awb_no = $dget_awbs->awb_no;
              }

            }
            //Update way bill no status
            $qry1 = "update ".$wpdb->prefix."dv_awb_no_details set status=1,updated_at=now() where awb_no=$awb_no";
            $wpdb->query($qry1); 
                
            $order_id = $oarr;
            $gtinv = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."postmeta WHERE `meta_key` LIKE 'invoice' and post_id=$order_id");
            $rowcount = $wpdb->num_rows;
            if($rowcount)
            {
              $myorows = $wpdb->get_row("SELECT u.meta_value as lname,f.meta_value as shipping_pincode,g.meta_value as name,h.meta_value as country,i.meta_value as payment_method,j.meta_value as state,k.meta_value as city ,l.meta_value as shipping_address,s.meta_value as total_amount,t.meta_value as invoice FROM ".$wpdb->prefix."woocommerce_order_items a  JOIN ".$wpdb->prefix."postmeta h ON a.order_id = h.post_id JOIN ".$wpdb->prefix."postmeta g ON a.order_id = g.post_id JOIN ".$wpdb->prefix."postmeta f ON a.order_id = f.post_id JOIN ".$wpdb->prefix."postmeta i ON a.order_id = i.post_id JOIN ".$wpdb->prefix."postmeta j ON a.order_id = j.post_id JOIN ".$wpdb->prefix."postmeta k ON a.order_id = k.post_id JOIN ".$wpdb->prefix."postmeta l ON a.order_id = l.post_id JOIN ".$wpdb->prefix."postmeta u ON a.order_id = u.post_id JOIN ".$wpdb->prefix."postmeta s ON a.order_id = s.post_id JOIN ".$wpdb->prefix."postmeta t ON a.order_id = t.post_id  WHERE g.meta_key ='_shipping_first_name' and h.meta_key ='_shipping_country' and i.meta_key ='_payment_method' and j.meta_key ='_shipping_state' and k.meta_key ='_shipping_city' and l.meta_key ='_shipping_address_index' and s.meta_key='_order_total' and a.order_id=$order_id AND f.meta_key='_shipping_postcode' and  u.meta_key='_shipping_last_name' and t.meta_key='invoice' limit 1");
                $seller_invoice=$myorows->invoice;
            }
            else
            {
              $myorows = $wpdb->get_row("SELECT u.meta_value as lname,f.meta_value as shipping_pincode,g.meta_value as name,h.meta_value as country,i.meta_value as payment_method,j.meta_value as state,k.meta_value as city ,l.meta_value as shipping_address,s.meta_value as total_amount FROM ".$wpdb->prefix."woocommerce_order_items a  JOIN ".$wpdb->prefix."postmeta h ON a.order_id = h.post_id JOIN ".$wpdb->prefix."postmeta g ON a.order_id = g.post_id JOIN ".$wpdb->prefix."postmeta f ON a.order_id = f.post_id JOIN ".$wpdb->prefix."postmeta i ON a.order_id = i.post_id JOIN ".$wpdb->prefix."postmeta j ON a.order_id = j.post_id JOIN ".$wpdb->prefix."postmeta k ON a.order_id = k.post_id JOIN ".$wpdb->prefix."postmeta l ON a.order_id = l.post_id JOIN ".$wpdb->prefix."postmeta u ON a.order_id = u.post_id JOIN ".$wpdb->prefix."postmeta s ON a.order_id = s.post_id  WHERE g.meta_key ='_shipping_first_name' and h.meta_key ='_shipping_country' and i.meta_key ='_payment_method' and j.meta_key ='_shipping_state' and k.meta_key ='_shipping_city' and l.meta_key ='_shipping_address_index' and s.meta_key='_order_total' and a.order_id=$order_id AND f.meta_key='_shipping_postcode' and  u.meta_key='_shipping_last_name' limit 1");
                $seller_invoice='';
        
            }
            
            $scost_detail  = $wpdb->get_row("select count(*) as cnt,seller_invoice,e_waybill_no from ".$wpdb->prefix."dv_assign_awb where order_id='$order_id'");
            //$gtinv = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."postmeta WHERE `meta_key` LIKE 'invoice' and post_id=$order_id");
             
            $e_waybill_no = $scost_detail->e_waybill_no;
            //$seller_invoice = $gtinv->invoice;
            $cnt = $scost_detail->cnt;
              
            $item_values = "";
            $get_item =  $wpdb->get_results("select order_item_name from ".$wpdb->prefix."woocommerce_order_items where order_id=$order_id and order_item_type='line_item'");
            foreach ($get_item as $get_items) {
                $item_values != "" && $item_values .= ",";
                $item_values .= $get_items->order_item_name;
            }
            $product  = $wpdb->get_row("select post_date from ".$wpdb->prefix."posts where ID='$order_id'");
            $quantity = 0;
            $get_item_qty =  $wpdb->get_results("select b.meta_value as qty FROM ".$wpdb->prefix."woocommerce_order_items a JOIN ".$wpdb->prefix."woocommerce_order_itemmeta b ON a.order_item_id = b.order_item_id where a.order_id=$order_id and meta_key='_qty'");
            foreach ($get_item_qty as $get_item_qtys) 
            {
              $quantity = $quantity+$get_item_qtys->qty;
            }
            $item_total_amount = 0;
            $get_item_price =  $wpdb->get_results("select b.meta_value as prc FROM ".$wpdb->prefix."woocommerce_order_items a JOIN ".$wpdb->prefix."woocommerce_order_itemmeta b ON a.order_item_id = b.order_item_id where a.order_id=$order_id and meta_key='_line_subtotal'");
            foreach ($get_item_price as $get_item_prices) 
            {
              $item_total_amount = $item_total_amount+$get_item_prices->prc;
            }
            $item_total_weight = 0;
            $get_items =  $wpdb->get_results("select b.ID as item_id from ".$wpdb->prefix."woocommerce_order_items a JOIN ".$wpdb->prefix."posts b on b.post_title=a.order_item_name where a.order_id=$order_id and a.order_item_type='line_item'");
            foreach ($get_items as $items) 
            {
              $product_id = $items->item_id;
              $check_product_type = ( get_post( $product_id  ) )->post_type;
          
              if($check_product_type=='product_variation')
              {
                $parent_product_id = wp_get_post_parent_id($product_id);
              }
              else{
                $parent_product_id = $product_id;
              }
              $gt_weight = $wpdb->get_row("select b.meta_value as weight from ".$wpdb->prefix."postmeta b where b.meta_key='_weight' and b.post_id=$parent_product_id ") ;
              $wgt = $gt_weight->weight;
              $item_total_weight = $item_total_weight+$wgt;
              
            }
            //$item_total_weight = $item_total_weight*1000;
            $weight_unit = get_option('woocommerce_weight_unit');
            if ($weight_unit=='kg')
            {
              $item_total_weight = $item_total_weight*1000;
            }
            else if ($weight_unit=='g')
            {
              $item_total_weight = $item_total_weight;
            }
            else if ($weight_unit=='lbs')
            {
              $item_total_weight = $item_total_weight*453.592;
            }
            else if ($weight_unit=='oz')
            {
              $item_total_weight = $item_total_weight* 28.3495;
            }
            $prod_desc = $item_values;
            $order_date = $product->post_date;
            //$phone = $myorows->phoneno;
            $total_amount = $myorows->total_amount;
            
            $pin = $myorows->shipping_pincode;
            
            $name = $myorows->name.' '.$myorows->lname;
            
            $country = $myorows->country;
            

            if(strtoupper($myorows->payment_method)=='COD' || strtoupper($myorows->payment_method)=='CODPF')
            {
              $payment_method = 'cod';
            }
            else if($myorows->payment_method==' ' || $myorows->payment_method=='')
            {
              $payment_method = '';
            }
            else
            {
              $payment_method = 'prepaid';
            }
            
            $state = $myorows->state;
            
            $city = $myorows->city;
            
            $saddress = $myorows->shipping_address;
            

            $consignee_tin_no = $consignee_tin_no;
            $cst_no = $cst_no;
            $gst_no = $gst_no;
            if(strtoupper($payment_method)=='COD' || strtoupper($payment_method)=='CODPF')
            {
               $cod_amount =  $total_amount;
            }
            else
            {
                $cod_amount =  "";
            }
            $getphone = $wpdb->get_row("SELECT meta_value as phoneno from ".$wpdb->prefix."postmeta where post_id='".$order_id."' and meta_key ='_billing_phone'" );
            $phone = $getphone->phoneno;
            $getdt = $wpdb->get_row("SELECT seller_invoice,e_waybill_no from ".$wpdb->prefix."dv_assign_awb where order_id='".$order_id."'" );
            //$seller_invoice = $getdt->seller_invoice;
            $e_waybill_no = $getdt->e_waybill_no;
            $rname ='';
            $rpin =sanitize_text_field($_POST['return_pin']);
            $rcity =sanitize_text_field($_POST['return_city']);
            $rphone ='';
            $raddress =sanitize_text_field($_POST['return_address']);
            $rstate =sanitize_text_field($_POST['return_state']);
            $rcountry =sanitize_text_field($_POST['return_country']);
            $cdate = date('Y-m-d h:i:s');
            $data_ship = array('return_name'=> preg_replace('/[; & # % ]+/', ' ', trim($rname)),
                          'return_pin'=> $rpin,
                          'return_city'=> preg_replace('/[; & # % ]+/', ' ', trim($rcity)),
                          'return_phone'=> $rphone,
                          'return_add'=> preg_replace('/[; & # % ]+/', ' ', trim($raddress)),
                          'return_state'=>preg_replace('/[; & # % ]+/', ' ', trim($rstate)),
                          'return_country'=> preg_replace('/[; & # % ]+/', ' ', trim($rcountry)),
                          'order'=> $order_id,
                          'phone'=> $phone,
                          'products_desc'=> preg_replace('/[; & # % ]+/', ' ', trim($prod_desc)),
                          'product_type'=>'',
                          'cod_amount'=> $cod_amount,
                          'name'=> preg_replace('/[; & # % ]+/', ' ', trim($name)) ,
                          'waybill'=> $awb_no,
                          'country'=>preg_replace('/[; & # % ]+/', ' ', trim($country)),
                          'order_date'=> $order_date,
                          'total_amount'=> $total_amount,
                          'seller_add'=>preg_replace('/[; & # % ]+/', ' ', trim($waddress)),
                          'seller_cst'=> $cst_no,
                          'add'=>preg_replace('/[; & # % ]+/', ' ', trim($saddress)) ,
                          'seller_name'=> $wname,
                          'seller_inv'=>$seller_invoice,
                          'seller_tin'=> $consignee_tin_no,
                          /*'seller_gst_tin' => $gst_no,*/
                          'seller_inv_date'=> $cdate,
                          'pin'=>preg_replace('/[; & # % ]+/', ' ', trim($pin)),
                'quantity'=> $quantity,
                'weight' => $item_total_weight,
                'payment_mode'=> $payment_method,
                'state'=> preg_replace('/[; & # % ]+/', ' ', trim($state)),
                'city'=> preg_replace('/[; & # % ]+/', ' ', trim($city)),
                'supplier' => '',
                'extra_parameters' => '',
                'shipment_width' => '',
                'shipment_height' => '',
                'consignee_tin' => '',
                'tax_value' => '',
                'sales_tax_form_ack_no' => '',
                'category_of_goods' => '',
                'commodity_value'=> '',
                'e_waybill'=>$e_waybill_no,
                'source' =>$source_key
                          );
              $data_ship_js = json_encode($data_ship);
              $data_ship_json .= $prefix.$data_ship_js;
              $prefix = ','; 
             
            }
            $data_pin_json = json_encode($data_pin);

            $data_json = 'format=json&data={
            "pickup_location": '.$data_pin_json.',
            "shipments": [
            '.$data_ship_json.'
            ]
            }';
            //print_r($data_json);
            //wp_die();
            $create_url = $base_url.'api/cmu/create.json';
            $accesstoken = 'Bearer '.$auth_token; 
            $headers = array(
                          'Content-Type' => 'application/json',
                          'Accept' => 'application/json', 
                          'Authorization' => $accesstoken
                      );
            $arg = array(
                      'headers' => $headers,
                      'body'    =>  $data_json,
                      'timeout'     => 45,
                      'redirection' => 5,
                      'httpversion' => '1.0',
                      'blocking'    => true
                    );
            $res = wp_remote_post($create_url,$arg);
            $outputs = wp_remote_retrieve_body($res);
            $outputs = json_decode( $outputs, true );
            //print_r($outputs); wp_die();
            $res_value = json_encode($outputs);
            //Update order log table
            $data_header = json_encode($headers);
            $logqry = "insert into ".$wpdb->prefix."dv_logs set api_name='create_manifest',header_value='$data_header',request_value='$data_json',url='$create_url',response_value='',order_id=0";
            $wpdb->query($logqry);
            $last_log_id = $wpdb->insert_id; 
            $pcdata = $outputs['packages'];
            
             
            if($outputs['rmk']=='')
            {
                $qry2 = "insert into ".$wpdb->prefix."dv_assign_awb (status,awb_no,shipment_status,status_type,warehouse_name,consignee_tin,order_id) VALUES ";
                $countt=0;
                $tcount = count($pcdata);
                foreach($pcdata as $datas)
                {
                    $countt++;
                    $manifest_status=$datas['status'];
                    $awb_no = $datas['waybill'];
                    $order_id = $datas['refnum'];
                    $statusType = 'UD';
                    if($manifest_status=='Success')
                    {
                      $qry2 .= "('Manifested',$awb_no,1,'$statusType','$wname','$consignee_tin_no',$order_id)";
                      if($tcount!=$countt)
                      {
                        $qry2 .=",";
                      }
                      $order = wc_get_order(  $order_id );
                        // The text for the note
                        $note = __('Order is shipped by Delhivery_Logistic_Courier.Please click  here to track <a href="https://www.delhivery.com/track/#/package/'.$awb_no.'" target="_blank" >'.$awb_no.'</a>');
                       // Add the note
                        $order->add_order_note( $note,true);
                    }
                    
                }
                if($err_order_id_list=='')
                {
                   $smsg = 'Orders Manifest successfully, Please create a Pickup Request';
                }
                else
                {
                  
                    $smsg = 'Selected Orders have been processed Successfully. Following orders are not processed as the mentioned Pincode is not serviceable.';
                    $err_order_id_list_arr = explode(",",$err_order_id_list);
                    
                    foreach($err_order_id_list_arr as $eoid)
                    {
                      if($i==0)
                      {
                        $smsg.= $eoid;
                      }
                      else
                      {
                        $smsg.= ','.$eoid;
                      }
                      
                      $i++;
                    }
                }
               //echo $qry2;
               $wpdb->query($qry2); 
               $_SESSION['succmsg'] = $smsg;
               $jdatas['status'] = 1; 
               $jdatas['suc_msg'] = $smsg;
               if($error2!='')
               {
                  $jdatas['err_msg'] = $error2;
               }
              
               echo  json_encode($jdatas);
                  
            }
            else
            {
                $error2 =  $outputs ["rmk"];
                //$error2 = $outputs ["packages"][0]["remarks"][0];
                $error = $error2;
                $jdatas['status'] = 0;
                $jdatas['err_msg'] = $error;
                echo  json_encode($jdatas);
            }
              //Update order log table
            $lqry = "update ".$wpdb->prefix."dv_logs set response_value='$res_value' where id=$last_log_id";
            $wpdb->query($lqry); 
        }
        wp_die();
    }
    add_action( 'wp_ajax_fetch_bulk_order_list', 'fetch_bulk_order_list_callback' );
    function fetch_bulk_order_list_callback()
    {
         require_once( WP_PLUGIN_DIR . '/delhivery-logistics-courier/admin/config.php' );
         global $wpdb;
         $startPage = sanitize_text_field($_POST['getresult']);
         $PERPAGE_LIMIT = 20;
         $myrow = $wpdb->get_results("SELECT a.order_id, e.meta_value as shipping_pincode , f.meta_value as billing_pincode FROM ".$wpdb->prefix."woocommerce_order_items a  JOIN  ".$wpdb->prefix."postmeta e  ON a.order_id = e.post_id JOIN  ".$wpdb->prefix."postmeta f ON a.order_id = f.post_id WHERE a.order_item_type = 'line_item' AND  e.meta_key='_shipping_postcode' AND f.meta_key='_billing_postcode' AND a.order_item_type='line_item' group by a.order_id ORDER BY a.order_id ASC");
          $count = 0;
          foreach($myrow as $row)
          {
            $o_id = $row->order_id;
            $order = wc_get_order( $o_id );
            $order_status  = $order->get_status();
            if($order_status=='processing' || $order_status=='pending')
            {  
              $count++; 
            }
          }
        
          $orders = $wpdb->get_results("SELECT a.order_id,e.meta_value as shipping_pincode,f.meta_value as billing_pincode,g.meta_value as total_amount ,i.meta_value as payment_method ,j.meta_value as sname,k.meta_value as bname,l.meta_value as phoneno FROM ".$wpdb->prefix."woocommerce_order_items a JOIN   ".$wpdb->prefix."postmeta e  ON a.order_id = e.post_id  JOIN  ".$wpdb->prefix."postmeta f ON a.order_id = f.post_id JOIN  ".$wpdb->prefix."postmeta g ON a.order_id = g.post_id JOIN ".$wpdb->prefix."postmeta i ON a.order_id = i.post_id JOIN  ".$wpdb->prefix."postmeta j  ON a.order_id = j.post_id JOIN  ".$wpdb->prefix."postmeta k ON a.order_id = k.post_id JOIN  ".$wpdb->prefix."postmeta l ON a.order_id = l.post_id WHERE a.order_item_type = 'line_item'  AND e.meta_key='_shipping_postcode' AND f.meta_key='_billing_postcode' AND g.meta_key='_order_total' and j.meta_key ='_shipping_first_name' and k.meta_key ='_billing_first_name' AND l.meta_key ='_billing_phone' AND a.order_item_type='line_item'  and i.meta_key ='_payment_method' group by a.order_id ORDER BY a.order_id DESC limit $startPage,$PERPAGE_LIMIT");
          $datas='<table class="table table-borderless">
                    <thead class="border-bottom">
                   <tr>
                      <th>
                        <label class="checkbox">
                        <input type="checkbox" class="selectall" />
                        <span class="checkmark all-select-checkbox"></span>
                      </th>
                      <th scope="col">Order ID</th>
                      <th scope="col">Product Description</th>
                      <th scope="col">Quantity</th>
                      <th scope="col">Shipping Pincode</th>
                      <th scope="col">Total Amount</th>
                      <th scope="col">Payment Type</th>
                      <th scope="col">Status</th>
                      <th scope="col">Status Type</th>
                      </tr>
                </thead><tbody>';
            foreach($orders as $data)
            {
              $scost_detail  = $wpdb->get_row("select count(*) as cnt,shipping_cost,awb_no,status,shipment_status,seller_invoice,e_waybill_no,status_type from ".$wpdb->prefix."dv_assign_awb where order_id='$data->order_id'");
              $shipping_cost = $scost_detail->shipping_cost; 
              $awbno = $scost_detail->awb_no; 
              $status = $scost_detail->status;
              $shipment_status = $scost_detail->shipment_status;
              $e_waybill_no = $scost_detail->e_waybill_no;
              if($e_waybill_no=='')
              {
                $e_waybill_no=0;
              }
              $seller_invoice = $scost_detail->seller_invoice;
              if($seller_invoice=='')
              {
                $seller_invoice=0;
              }
              $cnt = $scost_detail->cnt;
              $status_type = $scost_detail->status_type;
              if($status_type=='')
              {
                $status_type='NA';
              }
              $item_values = "";
                $get_item =  $wpdb->get_results("select order_item_name from ".$wpdb->prefix."woocommerce_order_items where order_id=$data->order_id and order_item_type='line_item'");
                foreach ($get_item as $get_items) {
                $item_values != "" && $item_values .= ",";
                $item_values .= $get_items->order_item_name;
                }
             
                $quantity = 0;
                $get_item_qty =  $wpdb->get_results("select b.meta_value as qty FROM ".$wpdb->prefix."woocommerce_order_items a JOIN ".$wpdb->prefix."woocommerce_order_itemmeta b ON a.order_item_id = b.order_item_id where a.order_id=$data->order_id and meta_key='_qty'");
                foreach ($get_item_qty as $get_item_qtys) 
                {
                  $quantity = $quantity+$get_item_qtys->qty;
                }
                $total_amount = 0;
                $get_item_price =  $wpdb->get_results("select b.meta_value as prc FROM ".$wpdb->prefix."woocommerce_order_items a JOIN ".$wpdb->prefix."woocommerce_order_itemmeta b ON a.order_item_id = b.order_item_id where a.order_id=$data->order_id and meta_key='_line_subtotal'");
                foreach ($get_item_price as $get_item_prices) 
                {
                  $total_amount = $total_amount+$get_item_prices->prc;
                }
                if(@$data->shipping_pincode=='')
                {
                  $pincode = $data->billing_pincode;
                } 
                else
                {
                  $pincode = $data->shipping_pincode;
                }
              $order = wc_get_order( $data->order_id );
              $order_status  = $order->get_status();
              if($order_status=='processing' || $order_status=='pending')
              {  
                $datas .= '<tr>
                    <td><label class="checkbox">
                       <input type="checkbox"  name="order_id"  id="order_id[]" 
                       value="'.esc_html($awbno).'|'.esc_html($pincode).'">
                      <span class="checkmark"></span>
                    </td>
                    <td><div id="actioncls'.esc_html($order_id).'" >'.esc_html($action).'
                    </div>
                    <div id="ajax_actioncls'.esc_html($order_id).'" style="display:none;"></div>
                    </td>
                    <td>'.esc_html($data->order_id).'</td>
                    <td>'.esc_html($item_values).'</td>
                    <td>'.esc_html($quantity).'</td>
                    <td>'.esc_html($pincode).'</td>
                    <td>'.esc_html($data->total_amount).'</td>
                    <td>'.esc_html(ucwords($data->payment_method)).'</td>
                    <td>'.esc_html($status).'</td>
                    <td>'.esc_html($status_type).'</td>
                  </tr>';
                }
              }
              $datas .='</tbody></table>'; 
              $jdatas['status'] = 1;
              $jdatas['total_count'] = $count;
              $jdatas['fetchdata'] = $datas;
              echo  json_encode($jdatas);
              wp_die();
    }
    add_action( 'wp_ajax_fetch_waybill_list', 'fetch_waybill_list_callback' );
    function fetch_waybill_list_callback()
    {
        require_once( WP_PLUGIN_DIR . '/delhivery-logistics-courier/admin/config.php' );
        global $wpdb;
        $username = sanitize_text_field($_SESSION['username']);
        $startPage = sanitize_text_field($_POST['getresult']);
        $PERPAGE_LIMIT = 50;
        $table_name = $wpdb->prefix . 'dv_awb_no_details';
        $myrow = $wpdb->get_results("SELECT t1.id,t1.awb_no,t1.status as state,t1.created_at,t2.order_id,t2.updated_at,t2.order_id from ".$wpdb->prefix."dv_awb_no_details as t1 left join ".$wpdb->prefix."dv_assign_awb as t2 on t1.awb_no=t2.awb_no  where t1.created_by='$username' ORDER BY t1.status, t1.created_at, t1.updated_at" );
        $count = count($myrow);
        $rows = $wpdb->get_results("SELECT t1.id,t1.awb_no,t1.status as state,t1.created_at,t2.order_id,t1.updated_at,t2.status,t2.order_id from ".$wpdb->prefix."dv_awb_no_details as t1 left join ".$wpdb->prefix."dv_assign_awb as t2 on t1.awb_no=t2.awb_no  where t1.created_by='$username' ORDER BY t1.status, t1.created_at, t1.updated_at desc limit $startPage,$PERPAGE_LIMIT" );
        //print_r($rows); die;

        $datas='<table class="table table-borderless">
                  <thead class="border-bottom">
                     <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Order ID</th>
                        <th scope="col">AWB NO.(Waybill)</th>
                        <th scope="col">Status</th>
                        <th scope="col">Shipping Name</th>
                        <th scope="col">Created On</th>
                        <th scope="col">Updated on</th>
                        <th scope="col">State</th>
                     </tr>
                  </thead>
                  <tbody>';
              $k=$startPage; 
              foreach($rows as $data)
              {
                $order_id = $data->order_id;
                $odata = $wpdb->get_row("
                  SELECT g.meta_value as name  FROM ".$wpdb->prefix."woocommerce_order_items a JOIN  ".$wpdb->prefix."postmeta g  ON a.order_id = g.post_id where g.meta_key ='_shipping_first_name' and  a.order_id=$order_id");
                $name = $odata->name;

                $k++;
                if($data->order_id!='')
                { 
                  $order_id = $data->order_id; 
                } 
                else
                { 
                  $order_id = 'NA'; 
                }
                if($data->status!='')
                { 
                  $status = $data->status; 
                } 
                else
                { 
                  $status = 'NA'; 
                }
                if($name!='')
                { 
                  $name = $name; 
                } 
                else
                { 
                  $name = 'NA'; 
                }
                if($data->created_at!='')
                { 
                  $created_at = date("Y-M-d,H:i", strtotime($data->created_at));
                } 
                else
                { 
                  $created_at = 'NA'; 
                }
                if($data->updated_at!='')
                { 
                  $updated_at = date("Y-M-d,H:i", strtotime($data->updated_at)); 
                } 
                else
                { 
                  $updated_at = 'NA'; 
                }
                if($data->state==1)
                { 
                  $state = '<span class="badge badge-outline-danger">Used</span>'; 
                } 
                else
                { 
                  $state = '<span class="badge badge-outline-success">Unused</span>'; 
                }
                $datas .= '<tr>
                      <td>'.esc_html($k).'</td>
                      <td>'.esc_html($order_id).'</td>
                      <td>'.esc_html($data->awb_no).'</td>
                      <td>'.esc_html($status).'</td>
                      <td>'.esc_html($name).'</td>
                      <td>'.esc_html($created_at).'</td>
                      <td>'.esc_html($updated_at).'</td>
                      <td>'.esc_html($state).'</td>
                      </tr>';
              }
              $datas .='</tbody></table>'; 
              

            $jdatas['status'] = 1;
            $jdatas['total_count'] = $count;
            $jdatas['fetchdata'] = $datas;
            echo  json_encode($jdatas);
            wp_die();
    }
    add_action( 'wp_ajax_fetch_warehouse_list', 'fetch_warehouse_list_callback' );
    function fetch_warehouse_list_callback()
    {
        require_once( WP_PLUGIN_DIR . '/delhivery-logistics-courier/admin/config.php' );
        global $wpdb;
        $startPage = sanitize_text_field($_POST['getresult']);
        $PERPAGE_LIMIT = 50;
        $table_name = $table_name = $wpdb->prefix . 'dv_my_warehouse';
        $myrow = $wpdb->get_results("SELECT id,phone,city,state,name,pin,address,country,contact_person,email,registered_name,return_address,return_pin,return_city,return_state,return_country,status,created_at from $table_name ORDER BY id ASC " );
        $count = count($myrow);
        $myrows = $wpdb->get_results("SELECT id,phone,city,state,name,pin,address,country,contact_person,email,registered_name,return_address,return_pin,return_city,return_state,return_country,status,created_at from $table_name limit $startPage,$PERPAGE_LIMIT" );
        $datas='<table class="table table-borderless">
                  <thead class="border-bottom">
                     <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Email/Phone</th>
                        <th scope="col">Contact Person</th>
                        <th scope="col">Country</th>
                        <th scope="col">State/City</th>
                        <th scope="col">Address</th>
                        <th scope="col">Registered Name</th>
                        <th scope="col">Return Country</th>
                        <th scope="col">Return State/City</th>
                        <th scope="col">Return Address</th>
                        <th scope="col">Created On</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                     </tr>
                  </thead>
                  <tbody>';
                  foreach($myrows as $data)
                  {
                    if($data->contact_person=='')
                    { 
                      $contact_person = 'NA'; 
                    } 
                    else
                    { 
                      $contact_person = $data->contact_person; 
                    }
                    if($data->country=='')
                    { 
                      $country = 'NA'; 
                    } 
                    else
                    { 
                      $country = $data->country; 
                    }
                    if($data->state=='')
                    { 
                      $state = 'NA'; 
                    } 
                    else
                    { 
                      $state = $data->state; 
                    }
                    if($data->registered_name=='')
                    { 
                      $registered_nameregistered_name = 'NA'; 
                    } 
                    else
                    { 
                      $registered_name = $data->registered_name; 
                    }
                    if($data->return_country=='')
                    { 
                      $return_country = 'NA'; 
                    } 
                    else
                    { 
                      $return_country = $data->return_country; 
                    }
                    if($data->return_state=='')
                    { 
                      $return_state = 'NA'; 
                    } 
                    else
                    { 
                      $return_state = $data->return_state; 
                    }
                    if($data->return_city=='')
                    { 
                      $return_city = 'NA'; 
                    } 
                    else
                    { 
                      $return_city = $data->return_city; 
                    }
                    if($data->return_address=='')
                    { 
                      $return_address = 'NA'; 
                    } 
                    else
                    { 
                      $return_address = $data->return_address; 
                    }
                    if ($data->status=='1' )
                    {  
                      $status= '<span class="badge badge-outline-success">active</span>'; 
                    } 
                    else 
                    {  
                      $status = '<span class="badge badge-outline-danger">inactive</span>';
                    }

                    $edit_url = 'https://'.$shop.STORE_URL.'create_my_warehouse.php?action=edit&name='.$data->name; 
                  
                    $datas .= '<tr>
                      <td>'.esc_html($data->id).'</td>
                      <td>
                        <a class="text-black" href="#">'.esc_html($data->email).'</a>
                        <p class="text-grey">'.esc_html($data->phone).'</p>
                      </td>
                      <td>'.esc_html($contact_person).'</td>
                      <td>'.esc_html($country).'</td>
                      <td>
                          <p>'.esc_html($state).'</p>
                          <p class="text-grey">'.esc_html($data->city).'</p>
                      </td>
                      <td>
                         <p>
                           '.esc_html($data->address).'
                         </p>
                      </td>
                      <td>'.esc_html($registered_name).'</td>
                      <td>'.esc_html($return_country).'</td>
                      
                      <td>
                        <p>'.esc_html($return_state).'</p>
                        <p class="text-grey">'.esc_html($return_city).'</p>
                      </td>
                      <td><p>'.esc_html($return_address).'</p></td>
                      <td>'.esc_html($created_at).'</td>
                      <td>'.esc_html($status).'</td>
                      <td><a href="'.esc_html($edit_url).'" target="_top" ><i class="fas fa-pen gray-icon"></i></a></td>
                      </tr>';
                  }
              $datas .='</tbody></table>'; 
              

            $jdatas['status'] = 1;
            $jdatas['total_count'] = $count;
            $jdatas['fetchdata'] = $datas;
            echo  json_encode($jdatas);
            wp_die();
      }
    }

   
    public function activate() 
    {
      global $wpdb;
      $table1 = $wpdb->prefix . 'dv_settings';
      $table2 = $wpdb->prefix . 'dv_awb_no_details';
      $table3 = $wpdb->prefix . 'dv_my_warehouse';
      $table4 = $wpdb->prefix . 'dv_assign_awb';
      $table7 = $wpdb->prefix . 'dv_logs';
      $charset = $wpdb->get_charset_collate();
      $charset_collate = $wpdb->get_charset_collate();
      require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
      $sql1 = "CREATE TABLE $table1 (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `awb_no_count` int(11) NOT NULL,
            `client_name` varchar(50) NOT NULL,
            `method_name` varchar(50) NOT NULL,
            `carrier_title` varchar(50) NOT NULL,
            `login_id` varchar(50) NOT NULL,
            `licence_key` varchar(50) NOT NULL,
            `gst_no` varchar(50) NOT NULL,
            `consignee_tin_no` varchar(50) NOT NULL,
            `cst_no` varchar(50) NOT NULL,
            `sale_tax` varchar(50) NOT NULL,
            `heavy_shipment` varchar(50) NOT NULL,
            `status` int(11) NOT NULL COMMENT 'o for No,1 for Yes',
            `shipment_mode` VARCHAR(10) NOT NULL COMMENT 'E for express,S for surface',`enabled_prod_mode` int(11) NOT NULL COMMENT 'o for No,1 for Yes',
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` datetime default CURRENT_TIMESTAMP,
            PRIMARY KEY  (id)
        ) $charset_collate;";
      dbDelta( $sql1 );
      $sql2 = "CREATE TABLE $table2 (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `awb_no` varchar(50) NOT NULL,
            `status` int(11) NOT NULL COMMENT 'o for unused,1 for used',
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` datetime default CURRENT_TIMESTAMP,
            PRIMARY KEY  (id)
        ) $charset_collate;";
      dbDelta( $sql2 );
      $sql3 = "CREATE TABLE $table3 (
            `id` INT NOT NULL AUTO_INCREMENT , `phone` VARCHAR(12) NOT NULL , `city` VARCHAR(20) NOT NULL , `state` VARCHAR(20) NOT NULL , `name` VARCHAR(50) NOT NULL , `pin` VARCHAR(10) NOT NULL , `address` TEXT NOT NULL , `country` VARCHAR(50) NOT NULL , `contact_person` VARCHAR(50) NOT NULL , `email` VARCHAR(50) NOT NULL , `registered_name` VARCHAR(50) NOT NULL , `return_address` TEXT NOT NULL, `return_pin` VARCHAR(10) NOT NULL, `return_city` VARCHAR(20) NOT NULL, `return_state` VARCHAR(20) NOT NULL, `return_country` VARCHAR(20) NOT NULL, `status` INT NOT NULL , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `updated_at`datetime default CURRENT_TIMESTAMP , PRIMARY KEY (`id`)
        ) $charset_collate;";
      dbDelta( $sql3 );
      $sql4 = "CREATE TABLE $table4 (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `order_id` int(11) NOT NULL,
            `awb_no` varchar(50) NOT NULL,
            `shipping_cost` int(11) NOT NULL,
            `status` varchar(50) NOT NULL DEFAULT 'Processing',
            `shipment_status` int(11) NOT NULL  COMMENT 'o for Not shipped,1 for shipped',
            `status_type` varchar(50) NOT NULL,
            `instructions` TEXT NOT NULL,
            `seller_invoice` varchar(100) NOT NULL,
            `e_waybill_no` varchar(100) NOT NULL,
            `warehouse_name` varchar(100) NOT NULL,
            `consignee_tin` varchar(50) NOT NULL ,
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` datetime default CURRENT_TIMESTAMP,
            PRIMARY KEY  (id)
        ) $charset_collate;";
      dbDelta( $sql4 );
     
      
      $sql7 = "CREATE TABLE $table7 (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `order_id` int(11) NOT NULL,
          `api_name` varchar(50) NOT NULL,
          `url` varchar(200) NOT NULL,
          `header_value` text NOT NULL,
          `request_value` text NOT NULL,
          `response_value` text NOT NULL,
          `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
          `updated_at` datetime default CURRENT_TIMESTAMP,
           PRIMARY KEY  (id)
        ) $charset_collate;";
      dbDelta( $sql7 );
      $sql15 = "ALTER TABLE $table3 drop updated_at";
      $wpdb->query( $sql15 );  
      $sql16 = "ALTER TABLE $table2 drop updated_at";
      $wpdb->query( $sql16 );
      $sql17 = "ALTER TABLE $table4 drop updated_at";
      $wpdb->query( $sql17 );
      $sql18 = "ALTER TABLE $table3 ADD COLUMN updated_at datetime default CURRENT_TIMESTAMP";
      $wpdb->query( $sql18 );  
      $sql19 = "ALTER TABLE $table2 ADD COLUMN updated_at datetime default CURRENT_TIMESTAMP";
      $wpdb->query( $sql19 );
      $sql20 = "ALTER TABLE $table4 ADD COLUMN updated_at datetime default CURRENT_TIMESTAMP";
      $wpdb->query( $sql20 );

      
      $sql8 = "ALTER TABLE $table4 ADD COLUMN shipping_name varchar(255)";
      $wpdb->query( $sql8 );
      $sql9 = "ALTER TABLE $table4 ADD COLUMN shipping_phone varchar(25)";
      $wpdb->query( $sql9 );
      $sql10 = "ALTER TABLE $table4 ADD COLUMN shipping_pincode varchar(10)";
      $wpdb->query( $sql10 );
      $sql11 = "ALTER TABLE $table4 ADD COLUMN shipping_address varchar(255)";
      $wpdb->query( $sql11 );
      $sql12 = "ALTER TABLE $table4 ADD COLUMN shipping_payment_method varchar(10)";  
      $wpdb->query( $sql12 );
      $sql13 = "ALTER TABLE $table3 ADD COLUMN created_by varchar(255)";
      $wpdb->query( $sql13 );
      $sql14 = "ALTER TABLE $table2 ADD COLUMN created_by varchar(255)";
      $wpdb->query( $sql14 );      
      
    } 


    public function my_plugin_remove_database() {
      
      
    }
    
    /**
     * Override any of the template functions from woocommerce/woocommerce-template.php
     * with our own template functions file
     */
    function woo_order_detail_menu() 
    { 
      if($_SESSION['token']=='')
      {
        add_menu_page('DELHIVERY', 'DELHIVERY', 'manage_options', 'home','',plugins_url( 'delhivery-logistics-courier/images/delivery_icon.png' ),
        4);
        add_submenu_page( 'home', __('Login', ''), __('Login', ''), 'manage_options', 'home',array(&$this,'home'));
        
        add_submenu_page( 'home', __('My Orders', ''), __('My Orders', ''), 'manage_options', 'my_order',array(&$this,'list_order'));
        add_submenu_page( 'home', __('My Warehouse', ''), __('My Warehouse', ''), 'manage_options', 'my_warehouse', 
        array(&$this,'list_my_warehouse'));
        add_submenu_page( 'home', __('AWB No Listing', ''), __('AWB No Listing', ''), 'manage_options', 'list_awb_no', 
        array(&$this,'list_awb_no'));
        add_submenu_page( 'home', __('Rate Calculator', ''), __('Rate Calculator', ''), 'manage_options', 'rate_calculator',array(&$this,'invoice'));
        add_submenu_page( 'home', __('Pickup Request', ''), __('Pickup Request', ''), 'manage_options', 'create_request',array(&$this,'create_request'));
        //add_submenu_page( 'home', __('Logs', ''), __('Logs', ''), 'manage_options', 'logs',array(&$this,'logs'));
        add_submenu_page( 'home', __('FAQ', ''), __('FAQ', ''), 'manage_options', 'faq',array(&$this,'faq'));
      }
      else
      {
        add_menu_page('DELHIVERY', 'DELHIVERY', 'manage_options', 'my_order','',plugins_url( 'delhivery-logistics-courier/images/delivery_icon.png' ),4);
        add_submenu_page( 'my_order', __('My Orders', ''), __('My Orders', ''), 'manage_options', 'my_order',array(&$this,'list_order'));
        add_submenu_page( 'my_order', __('My Warehouse', ''), __('My Warehouse', ''), 'manage_options', 'my_warehouse', 
        array(&$this,'list_my_warehouse'));
        add_submenu_page( 'my_order', __('AWB No Listing', ''), __('AWB No Listing', ''), 'manage_options', 'list_awb_no', 
        array(&$this,'list_awb_no'));
        add_submenu_page( 'my_order', __('Rate Calculator', ''), __('Rate Calculator', ''), 'manage_options', 'rate_calculator',array(&$this,'invoice'));
        add_submenu_page( 'my_order', __('Pickup Request', ''), __('Pickup Request', ''), 'manage_options', 'create_request',array(&$this,'create_request'));
        //add_submenu_page( 'home', __('Logs', ''), __('Logs', ''), 'manage_options', 'logs',array(&$this,'logs'));
        add_submenu_page( 'my_order', __('FAQ', ''), __('FAQ', ''), 'manage_options', 'faq',array(&$this,'faq'));
        add_submenu_page( 'my_order', __('Logout', ''), __('Logout', ''), 'manage_options', 'logout',array(&$this,'logout'));
      }
  }
  function logout() 
  {
      //extract($myrows);
      ob_start();
      include('admin/logout.php');
   
  }
  function list_delhivery_setting() 
  {
    global $wpdb;
    $table_name = $wpdb->prefix . 'dv_settings';
    $myrows = $wpdb->get_results("SELECT id,client_name,licence_key,gst_no,consignee_tin_no,cst_no,sale_tax,status,enabled_prod_mode,created_at from $table_name" );
    extract($myrows);
    ob_start();
    include('admin/create_delhivery_setting.php');
    
  }
  function home() 
  {
    ob_start();
    include('admin/home.php');
   
      
  }
  function faq() 
  {
        
    ob_start();
    include('admin/faq.php');
   
      
  }

  
  function invoice()
  {
      include('admin/invoice.php');
  }
  function create_request()
  {
    include('admin/create_pickup_request.php');
  }
  function list_my_warehouse() 
  {
    global $wpdb;
    $currentPage = 1;
    $PERPAGE_LIMIT =50;
    if(isset($_GET['pageNumber'])){
    $currentPage = sanitize_text_field($_GET['pageNumber']);
    }
    $startPage = ($currentPage-1)*$PERPAGE_LIMIT;
    if($startPage < 0) $startPage = 0;
    $table_name = $wpdb->prefix . 'dv_my_warehouse';
    if(isset($_REQUEST['action'])) 
    {
      $action = sanitize_text_field($_REQUEST['action']);
    }
    else
    {
      $action = '';
    }
    
    
    if($action=='create')
    {
      include('admin/create_my_warehouse.php');
    }
    else if($action=='edit')
    {
      $name = sanitize_text_field($_REQUEST['name']);
      $myrows = $wpdb->get_results("SELECT id,phone,city,state,name,pin,address,country,contact_person,email,registered_name,return_address,return_pin,return_city,return_state,return_country,status,created_at from $table_name where name='$name'" );
      extract($myrows);
      ob_start();
      include('admin/create_my_warehouse.php');
      
    }
    else
    {

      $myrows = $wpdb->get_results("SELECT id,phone,city,state,name,pin,address,country,contact_person,email,registered_name,return_address,return_pin,return_city,return_state,return_country,status,created_at from $table_name limit $startPage,$PERPAGE_LIMIT" );
      extract($myrows);
      ob_start();
      include('admin/my_warehouse.php');
      
    }
     
  }

  
  function list_order() 
  {
    global $wpdb;
    $currentPage = 1;
    $PERPAGE_LIMIT =20;
    $startPage = 0;
    if(isset($_GET['pageNumber'])){
      $currentPage = sanitize_text_field($_GET['pageNumber']);
      $startPage = ($currentPage-1)*$PERPAGE_LIMIT;
      if($startPage < 0) $startPage = 0;
    }
    
    if(isset($_REQUEST['action'])) 
    {
      $action = sanitize_text_field($_REQUEST['action']);
    }
    else
    {
      $action = '';
    }
    if($action=='edit')
    {
      include('admin/edit_order.php');
    }
    else if($action=='track')
    {
      include('admin/track_order.php');
    }
    else if($action=='show_shipping_label')
    {
      include('admin/show_shipping_label.php');
    }
    else if($action=='bulk_ship')
    {
      $myrows = $wpdb->get_results("SELECT a.order_id,f.meta_value as billing_pincode,g.meta_value as total_amount,i.meta_value as payment_method,k.meta_value as bname,l.meta_value as phoneno ,n.meta_value as blname FROM  ".$wpdb->prefix."posts ps JOIN ".$wpdb->prefix."woocommerce_order_items a on ps.ID=a.order_id JOIN  ".$wpdb->prefix."postmeta f ON a.order_id = f.post_id JOIN  ".$wpdb->prefix."postmeta g ON a.order_id = g.post_id JOIN ".$wpdb->prefix."postmeta i  ON a.order_id = i.post_id  JOIN  ".$wpdb->prefix."postmeta k ON a.order_id = k.post_id JOIN  ".$wpdb->prefix."postmeta l ON a.order_id = l.post_id  JOIN  ".$wpdb->prefix."postmeta n ON a.order_id = n.post_id WHERE a.order_item_type = 'line_item' AND f.meta_key='_shipping_postcode' AND g.meta_key='_order_total' and i.meta_key ='_payment_method'  and k.meta_key ='_shipping_first_name' AND l.meta_key ='_billing_phone' AND n.meta_key ='_shipping_last_name' AND a.order_item_type='line_item' AND ps.post_status in ('wc-processing') group by a.order_id ORDER BY a.order_id DESC limit $startPage,$PERPAGE_LIMIT");
    
    
      extract($myrows);
      ob_start();
      include('admin/order_list.php');
      
      
    }
    else
    {
      if(isset($_GET['search_order']))
      {
        $search_order = sanitize_text_field($_GET['search_order']);
        $query ="SELECT a.order_id,e.meta_value as shipping_pincode,f.meta_value as billing_pincode,g.meta_value as total_amount ,i.meta_value as payment_method ,j.meta_value as sname,k.meta_value as bname,l.meta_value as phoneno,m.meta_value as slname ,n.meta_value as blname FROM ".$wpdb->prefix."woocommerce_order_items a JOIN   ".$wpdb->prefix."postmeta e  ON a.order_id = e.post_id  JOIN  ".$wpdb->prefix."postmeta f ON a.order_id = f.post_id JOIN  ".$wpdb->prefix."postmeta g ON a.order_id = g.post_id JOIN ".$wpdb->prefix."postmeta i ON a.order_id = i.post_id JOIN  ".$wpdb->prefix."postmeta j  ON a.order_id = j.post_id JOIN  ".$wpdb->prefix."postmeta k ON a.order_id = k.post_id JOIN  ".$wpdb->prefix."postmeta l ON a.order_id = l.post_id JOIN  ".$wpdb->prefix."postmeta m ON a.order_id = m.post_id JOIN  ".$wpdb->prefix."postmeta n ON a.order_id = n.post_id WHERE a.order_item_type = 'line_item'  AND e.meta_key='_shipping_postcode' AND f.meta_key='_billing_postcode' AND g.meta_key='_order_total' and j.meta_key ='_shipping_first_name' and k.meta_key ='_billing_first_name' AND l.meta_key ='_billing_phone' AND a.order_item_type='line_item' AND a.order_id='".$search_order."' and i.meta_key ='_payment_method'  AND m.meta_key ='_shipping_last_name' AND n.meta_key ='_billing_last_name' group by a.order_id ORDER BY a.order_id DESC limit $startPage,$PERPAGE_LIMIT";
      }
      else if(isset($_GET['search_waybill']))
      {
        $search_waybill = sanitize_text_field($_GET['search_waybill']);
        $query ="SELECT a.order_id,e.meta_value as shipping_pincode,f.meta_value as billing_pincode,g.meta_value as total_amount,i.meta_value as payment_method,j.meta_value as sname,k.meta_value as bname,l.meta_value as phoneno,m.meta_value as slname ,n.meta_value as blname FROM ".$wpdb->prefix."dv_assign_awb a JOIN ".$wpdb->prefix."postmeta e  ON a.order_id = e.post_id  JOIN  ".$wpdb->prefix."postmeta f ON a.order_id = f.post_id JOIN  ".$wpdb->prefix."postmeta g ON a.order_id = g.post_id JOIN ".$wpdb->prefix."postmeta i  ON a.order_id = i.post_id JOIN  ".$wpdb->prefix."postmeta j  ON a.order_id = j.post_id JOIN  ".$wpdb->prefix."postmeta k ON a.order_id = k.post_id JOIN  ".$wpdb->prefix."postmeta l ON a.order_id = l.post_id JOIN  ".$wpdb->prefix."postmeta m ON a.order_id = m.post_id JOIN  ".$wpdb->prefix."postmeta n ON a.order_id = n.post_id WHERE e.meta_key='_shipping_postcode' AND f.meta_key='_billing_postcode' AND g.meta_key='_order_total' and i.meta_key ='_payment_method'and j.meta_key ='_shipping_first_name' and k.meta_key ='_billing_first_name' AND l.meta_key ='_billing_phone'  AND m.meta_key ='_shipping_last_name' AND n.meta_key ='_billing_last_name' AND a.awb_no='".$search_waybill."' ORDER BY a.order_id DESC limit $startPage,$PERPAGE_LIMIT";
      }
      else if(isset($_GET['filter_status']))
      {
        $filter_status = sanitize_text_field($_GET['filter_status']);
        if ($filter_status=='Manifested' || $filter_status=='Not Picked' || $filter_status=='Pending' || $filter_status=='In Transit' || $filter_status=='Dispatched'  || $filter_status=='Delivered' || $filter_status=='RTO')
        {
          $query ="SELECT a.order_id,f.meta_value as billing_pincode,g.meta_value as total_amount,i.meta_value as payment_method,k.meta_value as bname,l.meta_value as phoneno,n.meta_value as blname FROM ".$wpdb->prefix."dv_assign_awb a  JOIN  ".$wpdb->prefix."postmeta f ON a.order_id = f.post_id JOIN  ".$wpdb->prefix."postmeta g ON a.order_id = g.post_id JOIN ".$wpdb->prefix."postmeta i  ON a.order_id = i.post_id  JOIN  ".$wpdb->prefix."postmeta k ON a.order_id = k.post_id JOIN  ".$wpdb->prefix."postmeta l ON a.order_id = l.post_id  JOIN  ".$wpdb->prefix."postmeta n ON a.order_id = n.post_id WHERE f.meta_key='_shipping_postcode' AND g.meta_key='_order_total' and i.meta_key ='_payment_method' and k.meta_key ='_shipping_first_name' AND l.meta_key ='_billing_phone' AND n.meta_key ='_shipping_last_name' AND a.status='".$filter_status."' ORDER BY a.order_id DESC limit $startPage,$PERPAGE_LIMIT";
       }
       else
       {
         $query ="SELECT a.order_id,f.meta_value as billing_pincode,g.meta_value as total_amount,i.meta_value as payment_method,k.meta_value as bname,l.meta_value as phoneno ,n.meta_value as blname FROM  ".$wpdb->prefix."posts ps JOIN ".$wpdb->prefix."woocommerce_order_items a on ps.ID=a.order_id JOIN  ".$wpdb->prefix."postmeta f ON a.order_id = f.post_id JOIN  ".$wpdb->prefix."postmeta g ON a.order_id = g.post_id JOIN ".$wpdb->prefix."postmeta i  ON a.order_id = i.post_id  JOIN  ".$wpdb->prefix."postmeta k ON a.order_id = k.post_id JOIN  ".$wpdb->prefix."postmeta l ON a.order_id = l.post_id  JOIN  ".$wpdb->prefix."postmeta n ON a.order_id = n.post_id WHERE a.order_item_type = 'line_item' AND f.meta_key='_shipping_postcode' AND g.meta_key='_order_total' and i.meta_key ='_payment_method'  and k.meta_key ='_shipping_first_name' AND l.meta_key ='_billing_phone' AND n.meta_key ='_shipping_last_name' AND a.order_item_type='line_item' and a.order_id NOT IN (SELECT order_id FROM ".$wpdb->prefix."dv_assign_awb ) AND ps.post_status in ('wc-processing') ORDER BY a.order_id DESC limit $startPage,$PERPAGE_LIMIT";
       }
      }
      else
      {
          $query ="SELECT a.order_id,f.meta_value as billing_pincode,g.meta_value as total_amount,i.meta_value as payment_method,k.meta_value as bname,l.meta_value as phoneno ,n.meta_value as blname FROM  ".$wpdb->prefix."posts ps JOIN ".$wpdb->prefix."woocommerce_order_items a on ps.ID=a.order_id JOIN  ".$wpdb->prefix."postmeta f ON a.order_id = f.post_id JOIN  ".$wpdb->prefix."postmeta g ON a.order_id = g.post_id JOIN ".$wpdb->prefix."postmeta i  ON a.order_id = i.post_id  JOIN  ".$wpdb->prefix."postmeta k ON a.order_id = k.post_id JOIN  ".$wpdb->prefix."postmeta l ON a.order_id = l.post_id  JOIN  ".$wpdb->prefix."postmeta n ON a.order_id = n.post_id WHERE a.order_item_type = 'line_item' AND f.meta_key='_shipping_postcode' AND g.meta_key='_order_total' and i.meta_key ='_payment_method'  and k.meta_key ='_shipping_first_name' AND l.meta_key ='_billing_phone' AND n.meta_key ='_shipping_last_name' AND a.order_item_type='line_item' AND ps.post_status in ('wc-processing') group by a.order_id ORDER BY a.order_id DESC limit $startPage,$PERPAGE_LIMIT";
      }
      $myrows = $wpdb->get_results($query);
    
    extract($myrows);
    ob_start();
    include('admin/order.php');
    
    }

        
  }


     
  function list_awb_no()
  {
    global $wpdb;
    $table_name = $wpdb->prefix . 'dv_awb_no_details';
    $currentPage = 1;
    $PERPAGE_LIMIT =50;
    $username=sanitize_text_field($_SESSION['username']);
    if(isset($_GET['pageNumber'])){
     $currentPage = sanitize_text_field($_GET['pageNumber']);
    }
    $startPage = ($currentPage-1)*$PERPAGE_LIMIT;
    if($startPage < 0) $startPage = 0;
    $myrows = $wpdb->get_results("SELECT t1.id,t1.awb_no,t1.status as state,t1.created_at,t2.order_id,t1.updated_at,t2.status,t2.order_id from ".$wpdb->prefix."dv_awb_no_details as t1 left join ".$wpdb->prefix."dv_assign_awb as t2 on t1.awb_no=t2.awb_no where t1.created_by='$username'  ORDER BY t1.status, t1.created_at, t1.updated_at desc limit $startPage,$PERPAGE_LIMIT" );
    extract($myrows);
    ob_start();
    include('admin/awb_no_detail.php');
    
  }

}
 
// finally instantiate our plugin class and add it to the set of globals

$GLOBALS['delhivery_logistic_courier'] = new Delhivery_Logistic_Courier();
endif;