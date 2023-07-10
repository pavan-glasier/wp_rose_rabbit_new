<?php
/**
 * Template Name: Create My Warehouse
 */
//session_start();
global $wpdb;
$red_login_url = site_url().'/wp-admin/admin.php?page=home';
require_once('refresh_token.php');
if (isset($_GET['pageNumber']))
{
  $pageNumber = sanitize_text_field($_GET['pageNumber']);
}
$back_url = site_url().'/wp-admin/admin.php?page=my_order&pageNumber='.$pageNumber;
$order_id = sanitize_text_field($_GET['order_id']);
//$order_item_id = sanitize_text_field($_GET['order_item_id']); 
$get_awb_detail = $wpdb->get_row("SELECT awb_no,consignee_tin,shipping_name,shipping_phone,shipping_address,shipping_pincode,shipping_payment_method  from ".$wpdb->prefix."dv_assign_awb where order_id=$order_id" );
$gtawb_no = sanitize_text_field($_GET['awb_no']);
$gtconsignee_tin_no =$get_awb_detail->consignee_tin;  
$shipping_name = @$get_awb_detail->shipping_name;
$shipping_phone = @$get_awb_detail->shipping_phone;
$shipping_pincode = @$get_awb_detail->shipping_pincode;
$shipping_address = @$get_awb_detail->shipping_address;
$shipping_payment_method = @$get_awb_detail->shipping_payment_method;

$order_id = sanitize_text_field($_GET['order_id']);
$myrows = $wpdb->get_row("SELECT u.meta_value as lname,f.meta_value as shipping_pincode,g.meta_value as name,h.meta_value as country,i.meta_value as payment_method,j.meta_value as state,k.meta_value as city ,l.meta_value as shipping_address,s.meta_value as total_amount FROM ".$wpdb->prefix."woocommerce_order_items a  JOIN ".$wpdb->prefix."postmeta h ON a.order_id = h.post_id JOIN ".$wpdb->prefix."postmeta g ON a.order_id = g.post_id JOIN ".$wpdb->prefix."postmeta f ON a.order_id = f.post_id JOIN ".$wpdb->prefix."postmeta i ON a.order_id = i.post_id JOIN ".$wpdb->prefix."postmeta j ON a.order_id = j.post_id JOIN ".$wpdb->prefix."postmeta k ON a.order_id = k.post_id JOIN ".$wpdb->prefix."postmeta l ON a.order_id = l.post_id JOIN ".$wpdb->prefix."postmeta u ON a.order_id = u.post_id JOIN ".$wpdb->prefix."postmeta s ON a.order_id = s.post_id  WHERE g.meta_key ='_shipping_first_name' and h.meta_key ='_shipping_country' and i.meta_key ='_payment_method' and j.meta_key ='_shipping_state' and k.meta_key ='_shipping_city' and l.meta_key ='_shipping_address_index' and s.meta_key='_order_total' and a.order_id=$order_id AND f.meta_key='_shipping_postcode' and  u.meta_key='_shipping_last_name' limit 1");
  $getphone = $wpdb->get_row("SELECT meta_value as phoneno from ".$wpdb->prefix."postmeta where post_id='".$order_id."' and meta_key ='_billing_phone'" );
  $phone = $getphone->phoneno;
      if($shipping_pincode=='')
      {
        $gtpin = $myrows->shipping_pincode;
      }
      else
      {
        $gtpin = $shipping_pincode;
      }
      
      if($shipping_phone=='')
      {
        $gtphone = $phone;
      }
      else
      {
        $gtphone = $shipping_phone;
      }
      
      if($shipping_name=='')
      {
        $gtname = $myrows->name.' '.$myrows->lname;
        
      }
      else
      {
        $gtname = $shipping_name;
      }
      if($shipping_address=='')
      {
        
        $gtaddress = $myrows->shipping_address;
        
      }
      else
      {
        $gtaddress = trim($shipping_address);
      }
      
      if($shipping_payment_method=='')
      {
        if($myrows->payment_method=='cod' || $myrows->payment_method=='codpf' )
        {
          $gtpayment_mode = 'cod';
        }
        else if($myrows->payment_method==' ' || $myrows->payment_method=='')
        {
          $gtpayment_mode = '';
        }
        else
        {
          $gtpayment_mode = 'prepaid';
        }
      }
      else
      {
        $gtpayment_mode = $shipping_payment_method;
      }
      $prod_id = $myrows->product_id;
    $get_item =  $wpdb->get_results("select order_item_name from ".$wpdb->prefix."woocommerce_order_items where order_id=$order_id and order_item_type='line_item'");
        foreach ($get_item as $get_items) {
          $item_values != "" && $item_values .= ",";
          $item_values .= $get_items->order_item_name;
    }
    $gtprod_desc = $item_values;
     
?>
<!DOCTYPE html>
<html lang="en">
   
   <body class="bg-color">
      <div class="main-shopify-wrapper">
        <div class="container-fluid">
            
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <?php require_once('wallet.php'); ?>
               <div class="table-top-section">
                  <h1 class="comman-heading">Edit Orders</h1>
                  
                  </form>

               </div>
  <?php
    if(isset($_REQUEST['save']))
    {
      $token = $auth_token;
      $waybill_no = sanitize_text_field($_POST['waybill']);
      $order_item_id = sanitize_text_field($_GET['order_item_id']);
      // check waybill no status
      
      $get_awb_detail = $wpdb->get_row("SELECT t2.order_id,t1.status from ".$wpdb->prefix."dv_awb_no_details as t1 left join ".$wpdb->prefix."dv_assign_awb as t2 on t1.awb_no=t2.awb_no  where t1.awb_no=$waybill_no" );
      $awb_no_status = $get_awb_detail->status;
      $gt_oitem_id = $get_awb_detail->order_id;
      if($awb_no_status == 1 && $order_id!=$gt_oitem_id)
      {
        $error = 'This AWB No is already in use';
        echo '<div id="woocommerce_errors" class="error"><div class="shopify-error">
                          <img src="' . esc_url( plugins_url( '../images/alert.png', __FILE__ ) ) . '" ><pid="err_msg" id="err_msg">'.esc_html($error).'</p></div></div>';
      }
      else
      {
      $waybillno = sanitize_text_field($_REQUEST['awb_no']);
      //$consignee_tin = $_POST['consignee_tin'];
      $phone = sanitize_text_field($_POST['phone']);
      $name = sanitize_text_field($_POST['name']);
      $prod_desc = sanitize_text_field($_POST['prod_desc']);
      $address = sanitize_text_field($_POST['address']);
      $pincode = sanitize_text_field($_POST['pin']);
      $payment_mode = sanitize_text_field($_POST['payment_mode']);
      $track_order_url = $base_url.'api/v1/packages/json/';
      $edit_order_url =  $base_url.'api/p/edit';
      $track_order_url = $track_order_url.'?token='.$token.'&waybill='.$waybillno.'&verbose=2';
      
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
          //echo $outputs["Error"];
          if($outputs["Error"]=='')
          {
             $status = $outputs["ShipmentData"][0]["Shipment"]["Scans"][0]["ScanDetail"]["Scan"]; 
          
            if($status=='Manifested' || $status=='In Transit' || $status=='Pending' || $status=='Scheduled')
            {

            $data = array(
                         'tax_value'=> '',
                         'product_category'=> '',
                         'waybill'=> $waybill_no,
                         'consignee_tin'=> '',
                         'name'=> $name,
                         'phone'=> $phone,
                         'add'=> $address,
                         'product_details'=>$prod_desc,
                         'commodity_value'=> '',
                         'gm' => 0,
                         'payment_mode'=>$payment_mode
                         );           
                $data_json = json_encode($data);                  
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
                $response = wp_remote_post( $edit_order_url, $arg);
                $output = wp_remote_retrieve_body( $response );
                $output = json_decode( $output, true );
                $res_value = json_encode($output);
                $data_header = json_encode($headers);
                //$logqry = "insert into ".$wpdb->prefix."dv_logs set api_name='edit_manifest',order_id='$order_id',header_value='$data_header ' ,request_value='$data_json',url='$edit_order_url',response_value=''"; 
                //$wpdb->query($logqry);
                //$last_log_id = $wpdb->insert_id;
                
                if($output['error']!='')
                {
                  $error = $output['error'];
                  echo '<div id="woocommerce_errors" class="error"><div class="shopify-error">
                          <img src="' . esc_url( plugins_url( '../images/alert.png', __FILE__ ) ) . '" ><pid="err_msg" id="err_msg">'.esc_html($error).'</p></div></div>';
                }
                
                else if($output['status']==1)
                {
                  
                  $qry = "update ".$wpdb->prefix."dv_assign_awb set consignee_tin='$consignee_tin',shipping_name='$name',shipping_phone='$phone',shipping_pincode='$pincode',shipping_address='$address',shipping_payment_method='$payment_mode' where order_id='$order_id'"; //die;
                  $wpdb->query($qry);
                  
                  echo '<div id="message" class="" style="color:green;">
                          <div class="shopify-sucess-msg">
                            <img src="' . esc_url( plugins_url( '../images/checked.png', __FILE__ ) ) . '" >
                            <p>'.esc_html('Order updated successfully').'</p>
                          </div>
                        </div>';
                  
                   
                }
                else
                {
                  
                  $qry = "update ".$wpdb->prefix."dv_assign_awb set consignee_tin='$consignee_tin',shipping_name='$name',shipping_phone='$phone',shipping_pincode='$pincode',shipping_address='$address',shipping_payment_method='$payment_mode' where order_id='$order_id'"; //die;
                  $wpdb->query($qry);
                  
                  echo '<div id="message" class="" style="color:green;">
                          <div class="shopify-sucess-msg">
                            <img src="' . esc_url( plugins_url( '../images/checked.png', __FILE__ ) ) . '" >
                            <p>'.esc_html('Order updated successfully').'</p>
                          </div>
                        </div>';
                  
                   
                }
               
            }
            else
            {
              $error = 'Order can not be edited because its status is '.$status;
              echo '<div id="woocommerce_errors" class="error"><div class="shopify-error">
                           <img src="' . esc_url( plugins_url( '../images/alert.png', __FILE__ ) ) . '" ><pid="err_msg" id="err_msg">'.esc_html($error).'</p></div></div>';
              
            }
         }
         else
         {
            $error = $outputs["Error"];
            echo '<div id="woocommerce_errors" class="error"><div class="shopify-error">
                           <img src="' . esc_url( plugins_url( '../images/alert.png', __FILE__ ) ) . '" ><pid="err_msg" id="err_msg">'.esc_html($error).'</p></div></div>';
         }
      }
      
    }

  $order_id = sanitize_text_field($_GET['order_id']);
  //$order_item_id = sanitize_text_field($_GET['order_item_id']); 
  $get_awb_detail = $wpdb->get_row("SELECT awb_no,consignee_tin,shipping_name,shipping_phone,shipping_address,shipping_pincode,shipping_payment_method  from ".$wpdb->prefix."dv_assign_awb where order_id=$order_id" );
  $gtawb_no = sanitize_text_field($_GET['awb_no']);
  $gtconsignee_tin_no =$get_awb_detail->consignee_tin;  
  $shipping_name = @$get_awb_detail->shipping_name;
  $shipping_phone = @$get_awb_detail->shipping_phone;
  $shipping_pincode = @$get_awb_detail->shipping_pincode;
  $shipping_address = @$get_awb_detail->shipping_address;
  $shipping_payment_method = @$get_awb_detail->shipping_payment_method;

  $order_id = sanitize_text_field($_GET['order_id']);
  $myrows = $wpdb->get_row("SELECT u.meta_value as lname,f.meta_value as shipping_pincode,g.meta_value as name,h.meta_value as country,i.meta_value as payment_method,j.meta_value as state,k.meta_value as city ,l.meta_value as shipping_address,s.meta_value as total_amount FROM ".$wpdb->prefix."woocommerce_order_items a  JOIN ".$wpdb->prefix."postmeta h ON a.order_id = h.post_id JOIN ".$wpdb->prefix."postmeta g ON a.order_id = g.post_id JOIN ".$wpdb->prefix."postmeta f ON a.order_id = f.post_id JOIN ".$wpdb->prefix."postmeta i ON a.order_id = i.post_id JOIN ".$wpdb->prefix."postmeta j ON a.order_id = j.post_id JOIN ".$wpdb->prefix."postmeta k ON a.order_id = k.post_id JOIN ".$wpdb->prefix."postmeta l ON a.order_id = l.post_id JOIN ".$wpdb->prefix."postmeta u ON a.order_id = u.post_id JOIN ".$wpdb->prefix."postmeta s ON a.order_id = s.post_id  WHERE g.meta_key ='_shipping_first_name' and h.meta_key ='_shipping_country' and i.meta_key ='_payment_method' and j.meta_key ='_shipping_state' and k.meta_key ='_shipping_city' and l.meta_key ='_shipping_address_index' and s.meta_key='_order_total' and a.order_id=$order_id AND f.meta_key='_shipping_postcode' and  u.meta_key='_shipping_last_name' limit 1");
  $getphone = $wpdb->get_row("SELECT meta_value as phoneno from ".$wpdb->prefix."postmeta where post_id='".$order_id."' and meta_key ='_billing_phone'" );
  $phone = $getphone->phoneno;
      if($shipping_pincode=='')
      {
        $gtpin = $myrows->shipping_pincode;
      }
      else
      {
        $gtpin = $shipping_pincode;
      }
      
      if($shipping_phone=='')
      {
        $gtphone = $phone;
      }
      else
      {
        $gtphone = $shipping_phone;
      }
      
      if($shipping_name=='')
      {
        $gtname = $myrows->name.' '.$myrows->lname;
        
      }
      else
      {
        $gtname = $shipping_name;
      }
      if($shipping_address=='')
      {
        
        $gtaddress = $myrows->shipping_address;
        
      }
      else
      {
        $gtaddress = trim($shipping_address);
      }
      
      if($shipping_payment_method=='')
      {
        if($myrows->payment_method=='cod' || $myrows->payment_method=='codpf' )
        {
          $gtpayment_mode = 'cod';
        }
        else if($myrows->payment_method==' ' || $myrows->payment_method=='')
        {
          $gtpayment_mode = '';
        }
        else
        {
          $gtpayment_mode = 'prepaid';
        }
      }
      else
      {
        $gtpayment_mode = $shipping_payment_method;
      }      $prod_id = $myrows->product_id;
      //$product  = $wpdb->get_row("select post_content,post_date from ".$wpdb->prefix."posts where ID='$prod_id'");
      //$gtprod_desc = $product->post_content; 
      $get_item =  $wpdb->get_results("select order_item_name from ".$wpdb->prefix."woocommerce_order_items where order_id=$order_id and order_item_type='line_item'");
          foreach ($get_item as $get_items) {
            $item_values != "" && $item_values .= ",";
            $item_values .= $get_items->order_item_name;
      }
      $gtprod_desc = $item_values;
    ?>
                     <form name = "myForm" onsubmit = "return(validate(0));" method="post" enctype="multipart/form-data" id="form-module" class="form-wrapper">
                      
                      <input type="hidden" name="prod_desc" id="input-prod_desc" class="form-control"  value="<?php echo esc_html($gtprod_desc); ?>" >
                      
                        <div class="row">
                            <div class="col-md-4">
                              <div class="form-group">
                                 <label for="Waybill Number">Waybill Number<span
                                    class="span-color">*</span></label>
                                  <input type="text" name="waybill" id="input-waybill" class="form-control" onkeyup="remove_err_msg('waybill_err')" value="<?php echo esc_html($gtawb_no)?>" readonly >
                                  <div class="form-inp-err" id="waybill_err"></div>
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                 <label for="ConsigneeTin">Name<span class="span-color">*</span></label>
                                 <input type="text" name="name" id="name" class="form-control" onkeyup="remove_err_msg('name_err')" value="<?php echo esc_html($gtname); ?>" onblur = "return(validate('nm'));" >
                                 <div class="form-inp-err" id="name_err"></div>
                              </div>
                            </div>
                          
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                              <div class="form-group">
                                 <label for="ConsigneeTin">Phone<span class="span-color">*</span></label>
                                 <input type="text" name="phone" id="phone" class="form-control" onkeyup="remove_err_msg('phone_err')" value="<?php echo esc_html($gtphone); ?>" onblur = "return(validate('ph'));">
                                 <div class="form-inp-err" id="phone_err"></div>
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                 <label for="ConsigneeTin">Payment Mode<span class="span-color">*</span></label>
                                <div class="custom-select"  onclick = "return(validate('mode'));">
                                 
                                   <select class="form-control" name="payment_mode" id="payment_mode" onchange="remove_err_msg('payment_mode_err')" disabled="true">
                                    
                                    <option value="cod" <?php if($gtpayment_mode=='cod'){ echo esc_html('selected'); } ?>>Cod</option>
                                    <option value="prepaid" <?php if($gtpayment_mode=='prepaid'){ echo esc_html('selected'); } ?>>Prepaid</option>
                                   </select>
                                </div>
                                 <div class="form-inp-err" id="payment_mode_err"></div>
                              </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                              <div class="form-group">
                                 <label for="ConsigneeTin">Pincode<span class="span-color">*</span></label>
                                 <input type="text" name="pin" id="pin" class="form-control" onkeyup="remove_err_msg('pin_err')" value="<?php echo esc_html($gtpin); ?>" onblur = "return(validate('pn'));" readonly>
                                 <div class="form-inp-err" id="pin_err"></div>
                              </div>
                            </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label for="ConsigneeTin">Address<span class="span-color">*</span></label>
                                 <input type="name" class="form-control" name="address" id="input-address" class="form-control" onkeyup="remove_err_msg('address_err')" onblur = "return(validate('add'));" value=" <?php echo esc_html(@$gtaddress); ?>" readonly>
                                 <div class="form-inp-err" id="address_err"></div>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                                <div class="col-md-4">
                                   <div class="button-right float-right">
                                     <a href="<?php echo esc_url($back_url);?>" class="btn-reset" target="_top">back</a>
                                      <button class="btn btn-primary btn-submit" type="submit" name="save">Save</button>
                                   </div>
                                </div>
                        </div>
                        
                     </form>
                  </div>
               </div>
          </div>
      </div>

   </body>
</html>
<?php 
wp_enqueue_style( 'bootstrap.min', plugins_url('/css/bootstrap.min.css',__FILE__) );
wp_enqueue_style( 'stylees', plugins_url('/css/custom_styles.css',__FILE__) );
wp_enqueue_script( 'bootstrap.min_js', plugins_url('/js/bootstrap.min.js',__FILE__));
wp_enqueue_script( 'custom_js', plugins_url('/js/custom.js',__FILE__), array( 'jquery' ), null, true ); ?>
<script type="text/javascript">
  function validate($id)
  {
    if($id=='nm' || $id=='0')
    {
        var name = document.myForm.name.value;
        if( document.myForm.name.value == "" ) {
          document.getElementById("name_err").innerHTML = "Enter name";
          document.myForm.name.focus() ;
          return false;
        }
        for (var i = 0; i < name.length; i++)

        { 
          if (iChars.indexOf(name.charAt(i)) != -1)
          { 
            document.getElementById("name_err").innerHTML = "Name has special characters.\nSpecial characters(\\,;,&,#,%) are not allowed.\n Remove them and try again.";
            document.myForm.name.focus() ;
            return false;
          }
        }
    }
    if($id=='phn' || $id=='0')
        {
          if( document.myForm.phone.value == "" ) {
            document.getElementById("phone_err").innerHTML = "Enter phone no.";
            document.myForm.phone.focus() ;
            return false;
          }
          
          if(isNaN( document.myForm.phone.value )) {
            document.getElementById("phone_err").innerHTML = "Enter digits for phone no.";
            document.myForm.phone.focus() ;
            return false;
          } 
          num= document.myForm.phone.value;
          if (mob.test(num) == false) 
          {
            document.getElementById("phone_err").innerHTML = "Enter valid phone no.";
                document.myForm.phone.focus() ;
            return false;
          }
          if (num.length > 15) 
          {
            //alert("Only 15 characters allowed for Phone Number field");
            document.getElementById("phone_err").innerHTML = "Only 15 characters allowed for phone no field";
                document.myForm.phone.focus() ;
            return false;
          }
          
        }
         if($id=='pn' || $id=='0')
        {
          pin= document.myForm.pin.value;
          if( document.myForm.pin.value == "" ) 
          {
            document.getElementById("pin_err").innerHTML = "Enter pincode";
            document.myForm.pin.focus() ;
            return false;
          }
         
          if (zip.test(pin) == false) 
          {
              document.getElementById("pin_err").innerHTML = "Enter valid pincode";
              document.myForm.pin.focus() ;
              return false;
          }
            if ((document.myForm.pin.value).length >10)  {
              document.getElementById("pin_err").innerHTML = "Pincode should not be greater than 10 characters";
              document.myForm.pin.focus() ;
              return false;
            }
        }
        if($id=='add' || $id=='0')
        { 
          var address = document.getElementById('input-address').value;
          if(document.getElementById('input-address').value == '') 
          {      
            document.getElementById("address_err").innerHTML = "Enter address";
            document.getElementById('input-address').focus();
            return false;      
          }
          for (var i = 0; i < address.length; i++)

          { 
            if (iChars.indexOf(address.charAt(i)) != -1)
            { 
              document.getElementById("address_err").innerHTML = "Address has special characters. \n Special characters(\\,;,&,#,%) are not allowed.\n Remove them and try again.";
              document.getElementById('input-address').focus() ;
              return false;
            }
          }
        }
  }

  function remove_err_msg(id)
  {
    document.getElementById(id).innerHTML = "";
  }
</script>