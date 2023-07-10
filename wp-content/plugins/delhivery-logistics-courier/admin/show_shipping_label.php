<?php
/**
 * Template Name: Create My Warehouse
 */
//session_start();
require_once('refresh_token.php');
global $wpdb;
$awb_no_list = sanitize_text_field($_GET['awb_no']);
//$awb_no = $list[$i];
//$get_awb_detail = $wpdb->get_row("SELECT order_id from ".$wpdb->prefix."dv_assign_awb where awb_no='".$awb_no['0']."'" );
//$order_id = $get_awb_detail->order_id;
//$shipping_cost = 0;
//$shipping_label_url =$shipping_label;
$shipping_label_url = $base_url."api/p/packing_slip/?wbns=".$awb_no_list;
$gst_no = $gst_no;
$accesstoken = 'Bearer '.$auth_token; 
$headers = array(
            'Content-Type' => 'application/json',
            'Accept' => 'application/json', 
            'Authorization' => $accesstoken
        );
$arg = array(
'headers' => $headers,
);
$res = wp_remote_get($shipping_label_url,$arg);
$outputs = wp_remote_retrieve_body($res);
$outputs = json_decode( $outputs, true );
//Insert into log table
$res_value = json_encode($outputs);
$data_header = json_encode($headers);
//$order = wc_get_order( $data->order_id );
//$logqry = "insert into ".$wpdb->prefix."dv_logs set order_id=$order_id, api_name='shipping_label',header_value='$data_header ' ,request_value='$data_json',url='$shipping_label_url',response_value=''";
//$wpdb->query($logqry); 
//$last_log_id = $wpdb->insert_id; 
$counts = count($outputs['packages']);
$pageNumber =1;
if (isset($_GET['pageNumber']))
{
  $pageNumber = sanitize_text_field($_GET['pageNumber']);
}
$back_url=site_url().'/wp-admin/admin.php?page=my_order&pageNumber='.$pageNumber;
?>   

<!DOCTYPE html>
<html lang="en">
   
   <body class="bg-color">
    <div class="slip-print-btn"id="printPageButton" style="padding: 30px 0px 30px;background: #fff;text-align: center;position: sticky;top: 0;z-index: 1;">
    <a href="<?php echo esc_url($back_url);?>" class="btn-reset" target="_top" style="padding: 12px 30px;">Back</a>
    <button class="btn-submit"style="padding: 12px 30px;color:#fff;text-decoration:none;" onclick="window.print();">Print
    </button>
    </div>
    <div id='printMe' class='printMe'>
   <?php

    for($i=0;$i<$counts;$i++)
    {
        $oid_barcode = $outputs['packages'] [$i] ['oid_barcode'];
        $barcode = $outputs['packages'] [$i] ['barcode'];
        $prod = $outputs['packages'] [$i] ['prd']; //die;
        $pin = $outputs['packages'] [$i] ['pin'];
        $delhivery_logo = $outputs['packages'][$i] ['delhivery_logo'];
        if($delhivery_logo=='')
        {
          $delhivery_logo = esc_url( plugins_url( '../images/delhivery_logo.png', __FILE__ ) );
        }

        $oid = $outputs['packages'] [$i] ['oid'];
        $address = $outputs['packages'] [$i] ['address'];
        $pt = $outputs['packages'] [$i] ['pt'];
        $snm = $outputs['packages'] [$i]  ['snm'];
        $cnm = $outputs['packages'] [$i]  ['name'];
        $sadd = $outputs['packages'] [$i]  ['sadd'];
        $sinv = $outputs['packages'] [$i]  ['si'];
        $radd = $outputs['packages'] [$i] ['radd'];
        $rcty = $outputs['packages'] [$i] ['rcty'];
        $rst = $outputs['packages'] [$i] ['rst'];
        $rpin = $outputs['packages'] [$i]  ['rpin'];
        $cod = $outputs['packages'] [$i] ['cod'];
        $total_amount = $outputs['packages'][$i]['rs'];
        $cst_no = $outputs['packages'] [$i]['cst'];
        $tin = $outputs['packages'] [$i] ['tin'];
        $gst = $outputs['packages'] [$i] ['seller_gst_tin'];
        $contact = $outputs['packages'] [$i] ['contact'];
        $cdate = $outputs['packages'] [$i]['cd'];
        $cl_logo = $outputs['packages'] [$i]['cl_logo'];
        $prod_desc = $outputs['packages'] [$i]['prd'];
        $prod_array = explode(",",$prod_desc);
        $item_values = "";
        foreach($prod_array as $prodname)
        {
          $prod_details = $wpdb->get_row("SELECT ID FROM `wp_posts` where post_title='$prodname' and post_status='publish'");
          $prod_id = $prod_details->ID;
          $prod = wc_get_product($prod_id);
          $color= $prod->attributes['color']['options']['0'];
          $size = $prod->attributes['size']['options']['0'];
          if ($color!='' && $size!='')
          {
            $pro_details = $prodname.'('.$size.','.$color.')';
          }
          else if($color!='' )
          {
            $pro_details = $prodname.'('.$color.')';
          }
          
          else if($size!='')
          {
            $pro_details = $prodname.'('.$size.')';
          }
          
          else
          {
            $pro_details = $prodname;
          }
          
          $item_values != "" && $item_values .= ",";
          $item_values .= $pro_details;
        }
        
         //Update log table
        //$lqry = "update dv_logs set response_value='$res_value' where id=$last_log_id";
        //$wpdb->query($lqry);

        $myrow = $wpdb->get_results("SELECT a.order_id,e.meta_value as shipping_pincode,f.meta_value as billing_pincode,g.meta_value as total_amount,i.meta_value as payment_method,j.meta_value as sname,k.meta_value as bname,l.meta_value as slname,m.meta_value as blname ,n.meta_value as shipping_cost FROM ".$wpdb->prefix."woocommerce_order_items a JOIN   ".$wpdb->prefix."postmeta e  ON a.order_id = e.post_id  JOIN  ".$wpdb->prefix."postmeta f ON a.order_id = f.post_id JOIN  ".$wpdb->prefix."postmeta g ON a.order_id = g.post_id JOIN ".$wpdb->prefix."postmeta i  ON a.order_id = i.post_id JOIN  ".$wpdb->prefix."postmeta j  ON a.order_id = j.post_id JOIN  ".$wpdb->prefix."postmeta k ON a.order_id = k.post_id JOIN  ".$wpdb->prefix."postmeta l ON a.order_id = l.post_id JOIN  ".$wpdb->prefix."postmeta m ON a.order_id = m.post_id  JOIN ".$wpdb->prefix."postmeta n ON a.order_id = n.post_id WHERE a.order_item_type = 'line_item' AND e.meta_key='_shipping_postcode' AND f.meta_key='_billing_postcode' AND g.meta_key='_order_total' and i.meta_key ='_payment_method' and j.meta_key ='_shipping_first_name' and k.meta_key ='_billing_first_name' and l.meta_key ='_shipping_last_name' and m.meta_key ='_billing_last_name' and n.meta_key='_order_shipping' AND a.order_item_type='line_item' AND a.order_id='".$oid."' group by a.order_id");
        
         foreach($myrow as $ods)
         {
            $shipping_cost=$ods->shipping_cost;
         }
        
        $total = $total_amount;
        if($cl_logo =='')
        {
          $clogo =esc_url( plugins_url( '../images/msg.png', __FILE__ ) );
        }
        else
        {
          $clogo = $cl_logo;
        }
      ?>
    
    <div class="a4">
         <table class="table-container" style="width:400px;background: #fff;margin:20px auto 20px;" cellpadding="0"
            cellspacing="0">
            <tr>
               <td>
                  <table width="100%" style="border: 1px solid #000000;" cellpadding="5" cellspacing="0">
                     <tr>
                        <td width="50%"
                           style="border-right: 1px solid #000000; color: #394263; vertical-align: middle"
                           align="center">
                           <img src="<?php echo esc_url($clogo);?>"  alt="image" style="padding:5px; height:65px;">
                        </td>
                        <td width="50%" align="center">
                           <img style="padding:5px;height: 35px;" src="<?php echo esc_url($delhivery_logo);?>">
                        </td>
                     </tr>
                  </table>
                  <table width="100%"
                     style="border-right: 1px solid #000000; border-left: 1px solid #000000;border-bottom: 1px solid #000000; "
                     cellpadding="5" cellspacing="0">
                     <tr>
                        <td width="100%">
                           <img style="margin: auto;display: block;" width="270" height="80" src="<?php echo $barcode; ?>">
                           <p class="" style="text-align: center;font-size:17px; margin:0;"><?//=$oid?></p>
                           
                        </td>
                     </tr>
                  </table>
                  <table width="100%"
                     style="border-right: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000;"
                     cellpadding="5" cellspacing="0">
                     <tr>
                        <td width="70%" style="border-right: 1px solid #000000;">
                           <p style="font-weight:bold; margin: 0">Shipping Address:</p>
                           <p style="font-weight:bold; margin: 0; font-size: 14px">
                              <?php echo esc_html(strtoupper($cnm)); ?>
                           </p>
                           <p style="margin: 0; line-height: 9pt; font-size:10px;">   
                               <?php echo esc_html($address); ?>
                           </p>
                           
                           <p style="margin: 0; font-size:10px;">
                              <strong>PIN:<?php echo esc_html($pin); ?></strong>
                           </p>
                        </td>
                        <td width="30%" align="center" style="vertical-align: middle;">
                           <p style="font-size:14px; font-weight:bold; margin: 0">
                               <?php echo esc_html($pt); ?>
                           </p>
                           <p style="font-size:14px;font-weight:bold; margin: 0">
                              <?php echo esc_html(get_woocommerce_currency_symbol());?> <?php echo esc_html($cod); ?>
                           </p>
                        </td>
                     </tr>
                  </table>
                  <table width="100%"
                     style="border-right: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000;"
                     cellpadding="5" cellspacing="0">
                     <tr>
                        <td width="60%" style="border-right: 1px solid #000000;">
                           <p style="margin: 0; line-height: 9pt; font-size:10px;"> <strong> Seller:</strong> 
                               <?php echo esc_html($snm); ?>
                           </p>
                           <!--<p style="margin: 0; line-height: 9pt; font-size:10px;"><strong> IRN:</strong>
                              06AAPCS9575E1ZR
                           </p>-->
                           <p style="margin: 0; line-height: 9pt; font-size:10px;"><strong> Address:</strong>
                              <?php echo esc_html($sadd); ?>
                           </p>
                        </td>
                        <td width="40%">
                           <p style="margin: 0; line-height: 9pt; font-size:10px;"> <strong>
                              TIN:
                              </strong> 
                             <?php echo esc_html($tin); ?>
                           </p>
                           <p style="margin: 0; line-height: 9pt; font-size:10px;"> <strong>
                              CST:
                              </strong> 
                              <?php echo esc_html($cst_no); ?>
                           </p>
                           <p style="margin: 0; line-height: 9pt; font-size:10px;"> 
                            <strong>Invoice No:</strong><?php echo esc_html($sinv); ?>
                           </p>
                        </td>
                     </tr>
                  </table>
                  <table width="100%"
                     style="border-right: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000;"
                     cellpadding="0" cellspacing="0">
                     <tr>
                        <td width="60%" style="border-right: 1px solid #000000; padding: 0;">
                           <table width="100%" cellpadding="5" cellspacing="0">
                              <tr>
                                 <td>
                                    <p style="margin: 0; line-height: 9pt; font-size:10px; font-weight: bold;">Product Descripition</p>
                                 </td>
                              </tr>
                           </table>
                        </td>
                        <td width="40%"style="padding: 0;">
                           <table width="100%" cellpadding="5" cellspacing="0">
                              <tr>
                                 <td width="50%" align="center" style="border-right: 1px solid #000000;">
                                    <p style="margin: 0; line-height: 9pt; font-size:10px;text-align: center; font-weight: bold;">Price</p>
                                 </td>
                                 <td width="50%" align="center">
                                    <p style="margin: 0; line-height: 9pt; font-size:10px; text-align: center; font-weight: bold;">Total</p>
                                 </td>
                              </tr>
                           </table>
                        </td>
                     </tr>
                  </table>
                  <table width="100%"
                     style="border-right: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000;"
                     cellpadding="0" cellspacing="0">
                     <tr>
                        <td width="60%" style="border-right: 1px solid #000000; padding: 0;">
                           <table width="100%" cellpadding="5" cellspacing="0">
                              <tr>
                                 <td>
                                    <p style="margin: 0; line-height: 9pt; font-size:10px;"><?php echo esc_html($item_values); ?></p>
                                 </td>
                              </tr>
                           </table>
                        </td>
                        <td width="40%"style="padding: 0;">
                           <table width="100%" cellpadding="5" cellspacing="0">
                              <tr>
                                 <td width="50%" align="center" style="border-right: 1px solid #000000;">
                                    <p style="margin: 0; line-height: 9pt; font-size:10px;text-align: center;"><?php echo esc_html(@$orders['0']['currency'])?> <?php echo esc_html(get_woocommerce_currency_symbol()).esc_html($total_amount-$shipping_cost)?></p>
                                 </td>
                                 <td width="50%" align="center">
                                    <p style="margin: 0; line-height: 9pt; font-size:10px; text-align: center;"><?php echo esc_html(@$orders['0']['currency'])?> <?php echo esc_html(get_woocommerce_currency_symbol()).esc_html($total_amount-$shipping_cost)?></p>
                                 </td>
                              </tr>
                           </table>
                        </td>
                     </tr>
                  </table>
                  <table width="100%"
                     style="border-right: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000;"
                     cellpadding="0" cellspacing="0">
                     <tr>
                        <td width="60%" style="border-right: 1px solid #000000; padding: 0;">
                           <table width="100%" cellpadding="5" cellspacing="0">
                              <tr>
                                 <td>
                                    <p style="margin: 0; line-height: 9pt; font-size:10px;">Shipping and handling</p>
                                 </td>
                              </tr>
                           </table>
                        </td>
                        <td width="40%"style="padding: 0;">
                           <table width="100%" cellpadding="5" cellspacing="0">
                              <tr>
                                 <td width="50%" align="center" style="border-right: 1px solid #000000;">
                                    <p style="margin: 0; line-height: 9pt; font-size:10px;text-align: center;"><?php echo esc_html(get_woocommerce_currency_symbol())?> <?php echo esc_html($shipping_cost)?></p>
                                 </td>
                                 <td width="50%" align="center">
                                    <p style="margin: 0; line-height: 9pt; font-size:10px; text-align: center;"><?php echo esc_html(get_woocommerce_currency_symbol())?> <?php echo esc_html($shipping_cost)?></p>
                                 </td>
                              </tr>
                           </table>
                        </td>
                     </tr>
                  </table>
                  <table width="100%"
                     style="border-right: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000;"
                     cellpadding="0" cellspacing="0">
                     <tr>
                        <td width="60%" style="border-right: 1px solid #000000;padding: 0;">
                           <table width="100%" cellpadding="5" cellspacing="0">
                              <tr>
                                 <td>
                                    <p style="margin: 0; line-height: 9pt; font-size:10px;">
                                       <strong>Total</strong>
                                    </p>
                                 </td>
                              </tr>
                           </table>
                        </td>
                        <td width="40%" style="padding: 0;">
                           <table width="100%" cellpadding="5" cellspacing="0">
                              <tr>
                                 <td width="50%" align="center" style="border-right: 1px solid #000000;">
                                    <p style="margin: 0; line-height: 9pt; font-size:10px; font-weight: 700;">
                                       <?php echo esc_html(get_woocommerce_currency_symbol())?> <?php echo esc_html($total) ?>
                                    </p>
                                 </td>
                                 <td width="50%" align="center">
                                    <p style="margin: 0; line-height: 9pt; font-size:10px; font-weight: 700;">
                                       <?php echo esc_html(get_woocommerce_currency_symbol())?> <?php echo esc_html($total) ?>
                                    </p>
                                 </td>
                              </tr>
                           </table>
                        </td>
                     </tr>
                  </table>
                  <table width="100%" style="border-right: 1px solid #000000; border-left: 1px solid #000000;"
                     cellpadding="5" cellspacing="0">
                     <tr>
                        <td width="100%">
                           <img style="margin: auto;display: block;" width="180" height="70" src="<?php echo $oid_barcode; ?>">
                           <p class="" style="text-align: center;font-size:17px; padding:0; margin:0;"><?//=$oid?></p>
                        </td>
                     </tr>
                  </table>
                  <table width="100%"
                     style="border-right: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000;border-top: 1px solid #000000;"
                     cellpadding="5" cellspacing="0">
                     <tr>
                        <td width="60%" align="left">
                           <p class="" style="width: 100%;margin:0;font-size: 10px;"> <strong >Return Address:</strong> <?php echo esc_html($radd).','.esc_html($rst).','.esc_html($rcty).','.esc_html($rpin)?>
                           </p>
                        </td>
                     </tr>
                  </table>
               </td>
            </tr>
         </table>
    </div>
    

<?php
}
?>
</div>   
</body>
</html>
<?php 
  wp_enqueue_style( 'bootstrap.min', plugins_url('/css/bootstrap.min.css',__FILE__) );
  wp_enqueue_style( 'stylees', plugins_url('/css/custom_styles.css',__FILE__) );
  wp_enqueue_script( 'bootstrap.min_js', plugins_url('/js/bootstrap.min.js',__FILE__));
  wp_enqueue_style( 'slip', plugins_url('/css/package_slips.css',__FILE__) ); 
?>

  <script>
    function printDiv(divName){
      var printContents = document.getElementById(divName).innerHTML;
      var originalContents = document.body.innerHTML;

      document.body.innerHTML = printContents;

      window.print();

      document.body.innerHTML = originalContents;

    }
  </script>
