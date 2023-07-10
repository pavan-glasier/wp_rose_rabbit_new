<?php
//session_start();
require_once('refresh_token.php');
global $wpdb;
$shipping_red_url = site_url().'/wp-admin/admin.php?page=my_order&action=show_shipping_label';
$table_name = $wpdb->prefix . 'dv_my_warehouse';
$filter_url = site_url().'/wp-admin/admin.php?page=my_order&filter_status=';
$order_list_url = site_url().'/wp-admin/admin.php?page=my_order';
$succ_alert_img = esc_url( plugins_url( '../images/checked.png', __FILE__ ) );
$pageNumber=1;
$username=sanitize_text_field($_SESSION['username']);
if(isset($_GET['pageNumber']))
{
  $pageNumber = sanitize_text_field($_GET['pageNumber']);
  $filter_url = site_url().'/wp-admin/admin.php?page=my_order&pageNumber='.sanitize_text_field($_GET['pageNumber']).'&filter_status=';
}
$search_url = site_url().'/wp-admin/admin.php?page=my_order';

$link ='';
if(isset($_GET['search_order']))
{
  $search_order = sanitize_text_field($_GET['search_order']);
  
  $link ='&search_order='.$search_order;
  $myrow = $wpdb->get_results("SELECT a.order_id, e.meta_value as shipping_pincode ,f.meta_value as billing_pincode FROM ".$wpdb->prefix."woocommerce_order_items a  JOIN  ".$wpdb->prefix."postmeta e  ON a.order_id = e.post_id JOIN  ".$wpdb->prefix."postmeta f ON a.order_id = f.post_id WHERE a.order_item_type = 'line_item' AND  e.meta_key='_shipping_postcode' AND f.meta_key='_billing_postcode' AND   a.order_item_type='line_item' AND a.order_id='".$search_order."' group by a.order_id ORDER BY a.order_id ASC");
}
else if(isset($_GET['filter_status']))
{
  $filter_status = sanitize_text_field($_GET['filter_status']);
  
  $link ='&filter_status='.$filter_status;
  
  if ($filter_status=='Manifested' || $filter_status=='Not Picked' || $filter_status=='Pending' || $filter_status=='In Transit' || $filter_status=='Dispatched'  || $filter_status=='Delivered' || $filter_status=='RTO')
  {
    $myrow = $wpdb->get_results("SELECT a.order_id,e.meta_value as shipping_pincode,f.meta_value as billing_pincode FROM ".$wpdb->prefix."dv_assign_awb a JOIN   ".$wpdb->prefix."postmeta e  ON a.order_id = e.post_id  JOIN  ".$wpdb->prefix."postmeta f ON a.order_id = f.post_id WHERE e.meta_key='_shipping_postcode' AND f.meta_key='_billing_postcode' AND a.status='".$filter_status."'");
  }
  else
  {
    $q1 ="SELECT a.order_id,e.meta_value as shipping_pincode,f.meta_value as billing_pincode,g.meta_value as total_amount ,i.meta_value as payment_method ,j.meta_value as sname,k.meta_value as bname,l.meta_value as phoneno FROM ".$wpdb->prefix."woocommerce_order_items a JOIN   ".$wpdb->prefix."postmeta e  ON a.order_id = e.post_id  JOIN  ".$wpdb->prefix."postmeta f ON a.order_id = f.post_id JOIN  ".$wpdb->prefix."postmeta g ON a.order_id = g.post_id JOIN ".$wpdb->prefix."postmeta i ON a.order_id = i.post_id JOIN  ".$wpdb->prefix."postmeta j  ON a.order_id = j.post_id JOIN  ".$wpdb->prefix."postmeta k ON a.order_id = k.post_id JOIN  ".$wpdb->prefix."postmeta l ON a.order_id = l.post_id WHERE a.order_item_type = 'line_item'  AND e.meta_key='_shipping_postcode' AND f.meta_key='_billing_postcode' AND g.meta_key='_order_total' and j.meta_key ='_shipping_first_name' and k.meta_key ='_billing_first_name' AND l.meta_key ='_billing_phone' AND a.order_item_type='line_item'  and i.meta_key ='_payment_method' and a.order_id NOT IN (SELECT order_id FROM ".$wpdb->prefix."dv_assign_awb)";
    $myrow =$wpdb->get_results($q1);
  }
  
}
else if(isset($_GET['search_waybill']))
{
  $search_waybill = sanitize_text_field($_GET['search_waybill']);
  $link ='&search_waybill='.$search_waybill;
  $myrow = $wpdb->get_results("SELECT a.order_id,e.meta_value as shipping_pincode,f.meta_value as billing_pincode FROM ".$wpdb->prefix."dv_assign_awb a JOIN   ".$wpdb->prefix."postmeta e  ON a.order_id = e.post_id  JOIN  ".$wpdb->prefix."postmeta f ON a.order_id = f.post_id WHERE e.meta_key='_shipping_postcode' AND f.meta_key='_billing_postcode' AND a.awb_no='".$search_waybill."'");
}
else
{
  $myrow = $wpdb->get_results("SELECT a.order_id, e.meta_value as shipping_pincode , f.meta_value as billing_pincode FROM ".$wpdb->prefix."woocommerce_order_items a  JOIN  ".$wpdb->prefix."postmeta e  ON a.order_id = e.post_id JOIN  ".$wpdb->prefix."postmeta f ON a.order_id = f.post_id WHERE a.order_item_type = 'line_item' AND  e.meta_key='_shipping_postcode' AND f.meta_key='_billing_postcode' AND   a.order_item_type='line_item' group by a.order_id ORDER BY a.order_id ASC");
}

$total = 0;
/*foreach($myrow as $row)
{
  $o_id = $row->order_id;
  $order = wc_get_order( $o_id );
  $order_status  = $order->get_status();
  if($order_status=='processing' || $order_status=='pending')
  {  
    $total++; 
  }
}*/
$post_qry = $wpdb->get_row("select count(ID) as cnt from ".$wpdb->prefix."posts where post_status in ('wc-processing')");
$total = $post_qry->cnt;
$perpage = 20;
if(!isset($_REQUEST['pageNumber']))
{
  $page=1;
  $currentPage=1;
}
else
{
  $page = sanitize_text_field($_REQUEST['pageNumber']);
  $currentPage = sanitize_text_field($_REQUEST['pageNumber']);
}
$totalPages = ceil($total / $perpage);
$pagination_link = site_url().'/wp-admin/admin.php?page=my_order';
$warehouse = $wpdb->get_results("SELECT name from $table_name" );
//$return_address = $wpdb->get_results("SELECT id,name from  ".$wpdb->prefix."dv_my_return_address where status=1" );
$search_url = site_url().'/wp-admin/admin.php?page=my_order';
$url = site_url().'/wp-admin/admin.php?page=my_order';
$getawb = $wpdb->get_results("SELECT awb_no from ".$wpdb->prefix."dv_awb_no_details where status=0 and created_by='$username'" );
if(count($getawb)==0)
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
    //$alogqry = "insert into ".$wpdb->prefix."dv_logs set order_id='$order_id',api_name='fetch_awb_no',header_value='$awb_data_header',url='$url',response_value='',request_value=''";
    //$wpdb->query($alogqry);
    //$aws_last_log_id = $wpdb->insert_id;
    if(isset($output['error']) && $output['error']!='')
    {
      $error = $output['error'];
      $data['status'] = 0;
      $data['err_msg'] = $error;

    }
    else if(isset($output['detail']) && $output['detail']!='')
    {
      $error = $output['detail'];
      $data['status'] = 0;
      $data['err_msg'] = $error;

    }
    else
    {
      $k=0;
      $query="insert into ".$wpdb->prefix."dv_awb_no_details(awb_no,status,created_by) values";
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
      $wpdb->query($query); 
      $data['status'] = 1; 
    }
}

if(isset($_REQUEST['ware_house']))
{

    $getawb = $wpdb->get_results("SELECT awb_no from ".$wpdb->prefix."dv_awb_no_details where status=0 and created_by='$username'" );
    if(count($getawb)>0)
    {
      foreach($getawb as $dgetawb)
      {

        $awb_no = $dgetawb->awb_no;
      }
    }

    else
    {
      
          $order_id = sanitize_text_field($_POST['order_id']);
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
          //$alogqry = "insert into ".$wpdb->prefix."dv_logs set order_id='$order_id',api_name='fetch_awb_no',header_value='$awb_data_header',url='$url',response_value='',request_value=''";
          //$wpdb->query($alogqry);
          //$aws_last_log_id = $wpdb->insert_id;
          if(isset($output['error']) && $output['error']!='')
          {
            $error = $output['error'];
            $data['status'] = 0;
            $data['err_msg'] = $error;

          }
          else if(isset($output['detail']) && $output['detail']!='')
          {
            $error = $output['detail'];
            $data['status'] = 0;
            $data['err_msg'] = $error;

          }
          else
          {
            $k=0;
            $query="insert into ".$wpdb->prefix."dv_awb_no_details(awb_no,status,created_by) values";
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
            $wpdb->query($query); 
            $data['status'] = 1; 
          }
          //$alqry = "update ".$wpdb->prefix."dv_logs set response_value='$awb_res_value' where id=$aws_last_log_id";
          //$wpdb->query($alqry);
      
      $get_awbs = $wpdb->get_results("SELECT awb_no from ".$wpdb->prefix."dv_awb_no_details where status=0 and created_by='$username'" );
      foreach($get_awbs as $dget_awbs)
      {
        $awb_no = $dget_awbs->awb_no;
      }
    }
    $order_item_id = sanitize_text_field($_POST['order_item_id']); 
    $create_url = $base_url.'api/cmu/create.json';
    $edit_url = $base_url.'api/p/edit';
    // Get Ware house Detail
    $ware_house_name = sanitize_text_field($_POST['ware_house']);
    $return_add_id = sanitize_text_field($_POST['return_address']);
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
      // Get Return Address Detail

      
      //$get_return_address = $wpdb->get_row("SELECT * from $return_add_tbl where id=$return_add_id ");
      $rname ='';
      $rpin =sanitize_text_field($_POST['return_pin']);
      $rcity =sanitize_text_field($_POST['return_city']);
      $rphone ='';
      $raddress =sanitize_text_field($_POST['return_address']);
      $rstate =sanitize_text_field($_POST['return_state']);
      $rcountry =sanitize_text_field($_POST['return_country']);
      $e_waybill =sanitize_text_field($_POST['e_waybill']);
      //Get Order Detail
      $order_id = sanitize_text_field($_POST['order_id']);
      $gtinv = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."postmeta WHERE `meta_key` LIKE 'invoice' and post_id=$order_id");
      $rowcount = $wpdb->num_rows;
      
      if($rowcount)
      {
        
          $myorows = $wpdb->get_row("SELECT u.meta_value as lname,f.meta_value as shipping_pincode,g.meta_value as name,h.meta_value as country,i.meta_value as payment_method,j.meta_value as state,k.meta_value as city ,l.meta_value as shipping_address,s.meta_value as total_amount,t.meta_value as invoice FROM ".$wpdb->prefix."woocommerce_order_items a  JOIN ".$wpdb->prefix."postmeta h ON a.order_id = h.post_id JOIN ".$wpdb->prefix."postmeta g ON a.order_id = g.post_id JOIN ".$wpdb->prefix."postmeta f ON a.order_id = f.post_id JOIN ".$wpdb->prefix."postmeta i ON a.order_id = i.post_id JOIN ".$wpdb->prefix."postmeta j ON a.order_id = j.post_id JOIN ".$wpdb->prefix."postmeta k ON a.order_id = k.post_id JOIN ".$wpdb->prefix."postmeta l ON a.order_id = l.post_id JOIN ".$wpdb->prefix."postmeta u ON a.order_id = u.post_id JOIN ".$wpdb->prefix."postmeta s ON a.order_id = s.post_id JOIN ".$wpdb->prefix."postmeta t ON a.order_id = t.post_id   WHERE g.meta_key ='_shipping_first_name' and h.meta_key ='_shipping_country' and i.meta_key ='_payment_method' and j.meta_key ='_shipping_state' and k.meta_key ='_shipping_city' and l.meta_key ='_shipping_address_index' and s.meta_key='_order_total' and a.order_id=$order_id AND f.meta_key='_shipping_postcode' and  u.meta_key='_shipping_last_name' and t.meta_key='invoice' limit 1");

          $seller_invoice=$myorows->invoice;
         
      }
      else
      {
       $myorows = $wpdb->get_row("SELECT u.meta_value as lname,f.meta_value as shipping_pincode,g.meta_value as name,h.meta_value as country,i.meta_value as payment_method,j.meta_value as state,k.meta_value as city ,l.meta_value as shipping_address,s.meta_value as total_amount FROM ".$wpdb->prefix."woocommerce_order_items a  JOIN ".$wpdb->prefix."postmeta h ON a.order_id = h.post_id JOIN ".$wpdb->prefix."postmeta g ON a.order_id = g.post_id JOIN ".$wpdb->prefix."postmeta f ON a.order_id = f.post_id JOIN ".$wpdb->prefix."postmeta i ON a.order_id = i.post_id JOIN ".$wpdb->prefix."postmeta j ON a.order_id = j.post_id JOIN ".$wpdb->prefix."postmeta k ON a.order_id = k.post_id JOIN ".$wpdb->prefix."postmeta l ON a.order_id = l.post_id JOIN ".$wpdb->prefix."postmeta u ON a.order_id = u.post_id JOIN ".$wpdb->prefix."postmeta s ON a.order_id = s.post_id  WHERE g.meta_key ='_shipping_first_name' and h.meta_key ='_shipping_country' and i.meta_key ='_payment_method' and j.meta_key ='_shipping_state' and k.meta_key ='_shipping_city' and l.meta_key ='_shipping_address_index' and s.meta_key='_order_total' and a.order_id=$order_id AND f.meta_key='_shipping_postcode' and  u.meta_key='_shipping_last_name' limit 1");
          $seller_invoice='';
            
      }
      //print_r($myorows); die;
      //$prod_id = $myorows->product_id;
      $product  = $wpdb->get_row("select post_date from ".$wpdb->prefix."posts where ID='$order_id'");
      $order_date = $product->post_date;
      $item_values = "";
      $get_item =  $wpdb->get_results("select order_item_name from ".$wpdb->prefix."woocommerce_order_items where order_id=$order_id and order_item_type='line_item'");
          foreach ($get_item as $get_items) {
            $item_values != "" && $item_values .= ",";
            $item_values .= $get_items->order_item_name;
      }
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
      //echo "select b.ID as item_id from ".$wpdb->prefix."woocommerce_order_items a JOIN ".$wpdb->prefix."posts b on b.post_title=a.order_item_name where a.order_id=$order_id and a.order_item_type='line_item'";
      $get_items =  $wpdb->get_results("select b.ID as item_id from ".$wpdb->prefix."woocommerce_order_items a JOIN ".$wpdb->prefix."posts b on b.post_title=a.order_item_name where a.order_id=$order_id and a.order_item_type='line_item'");
      if(!empty($get_items))
      {
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
          if(!empty($gt_weight))
          {
            $wgt = $gt_weight->weight;
            $item_total_weight = $item_total_weight+$wgt;
            
          }
          
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
      }
      
      $prod_desc = $item_values;
      $pin = $myorows->shipping_pincode;
      $name = $myorows->name.' '.$myorows->lname;
      $country = $myorows->country;
      $payment_method = $myorows->payment_method; 
      $total_amount = $myorows->total_amount;
      if(strtoupper($myorows->payment_method)=='COD' || strtoupper($myorows->payment_method)=='CODPF' )
      {
        $payment_method = 'cod';
        $cod_amount =  $total_amount;
      }
      else if($myorows->payment_method==' ' || $myorows->payment_method=='')
      {
        $payment_method = '';
        $cod_amount='';
      }
      else
      {
        $payment_method = 'prepaid';
        $cod_amount='';
      }
      
      $state = $myorows->state;
      $city = $myorows->city;
      $saddress = $myorows->shipping_address;
      
      $consignee_tin_no = $consignee_tin_no;
      $cst_no = $cst_no;
      $gst_no = $gst_no;
      
      $cdate = date('Y-m-d h:i:s');
      //Get Invoice and Ewaybill 
      
      $getdt = $wpdb->get_row("SELECT e_waybill_no from ".$wpdb->prefix."dv_assign_awb where order_id='".$order_id."'" );
      if($seller_invoice=='')
      {
        $seller_invoice=$order_id;
      }
      $e_waybill_no='';
      if(!empty($getdt))
      {
         $e_waybill_no = $getdt->e_waybill_no;
      }
      $getphone = $wpdb->get_row("SELECT meta_value as phoneno from ".$wpdb->prefix."postmeta where post_id='".$order_id."' and meta_key ='_billing_phone'" );
      $phone = $getphone->phoneno;
      $data_ship = array('return_name'=> preg_replace('/[; & # % ]+/', ' ', trim($rname)),
                      'return_pin'=> preg_replace('/[; & # % ]+/', ' ', trim($rpin)),
                      'return_city'=> preg_replace('/[; & # % ]+/', ' ', trim($rcity)),
                      'return_phone'=> preg_replace('/[; & # % ]+/', ' ', trim($rphone)),
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
                      'add'=>preg_replace('/[; & # % ]+/', ' ', trim($saddress)),
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
      $data_pin_json = json_encode($data_pin);
      $data_ship_json = json_encode($data_ship);
      $data_json = 'format=json&data={
        "pickup_location": '.$data_pin_json.',
        "shipments": [
          '.$data_ship_json.'
        ]
      }'; 
      
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
          $output  = wp_remote_retrieve_body($res);
          $output = json_decode( $output, true );
          //print_r($output); die;
          $res_value = json_encode($output); 
          //Update order log table
          $data_header = json_encode($headers);
          //$logqry = "insert into ".$wpdb->prefix."dv_logs set order_id='$order_id',api_name='create_manifest',header_value='$data_header ' ,request_value='$data_json',url='$create_url',response_value=''";
          //$wpdb->query($logqry);
          //$last_log_id = $wpdb->insert_id; 

         
          if($output['success']==1)
          {
              $waybill = $output['success']['packages']['waybill'];
              //track order 
              $track_order_url =$base_url.'api/packages/json/';
              $auth_token = $auth_token;
              $accesstoken = 'Token '.$auth_token; 
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
              $toutputs = wp_remote_retrieve_body($res);
              $toutputs = json_decode( $toutputs, true );
             
          
              $statusType = 'UD';
             //Update way bill no status
              $qry1 = "update ".$wpdb->prefix."dv_awb_no_details set status=1 ,updated_at=now() where awb_no='".$awb_no."'";
              $wpdb->query($qry1); 

              //Update Status of order item
            if(!empty($getdt))
            {
              $qry2 = "update ".$wpdb->prefix."dv_assign_awb set status='Manifested', awb_no='".$awb_no."',shipment_status=1,status_type='".$statusType."',warehouse_name='".$wname."',consignee_tin='".$consignee_tin_no."' where order_id='".$order_id."'";
              
            }
            else
            {
              $qry2 = "insert into ".$wpdb->prefix."dv_assign_awb set status='Manifested', awb_no='".$awb_no."',shipment_status=1,status_type='".$statusType."',warehouse_name='".$wname."',consignee_tin='".$consignee_tin_no."' , order_id='".$order_id."'";
            }
            $order = wc_get_order(  $order_id );
            // The text for the note
            $note = __('Order is shipped by Delhivery_Logistic_Courier.Please click here to track <a href="https://www.delhivery.com/track/#/package/'.$awb_no.'" target="_blank" >'.$awb_no.'</a>');
            // Add the note
            $order->add_order_note( $note,true );
            $wpdb->query($qry2); 
            $_SESSION["succmsg"] = 'Order Manifested Successfully, Please create a Pickup Request';
            
          }
          else
          {
            $error1 =  $output ["rmk"];
            $error2 = $output ["packages"][0]["remarks"][0];
            if(strpos($error2,'Duplicate waybill')==true)
            {
              $error2='It seems like a duplicate waybill issue. Please enter the correct waybill that is unique. In case issue still persists, please reach out to integrations@delhivery.com';
            }
            else if(strpos($error2,'COD amount 0 for COD/Cash package')==true)
            {
              $error2='You seem to have entered the COD amount value as 0. Please enter the correct value to proceed further. In case issue still persists, please reach out to integrations@delhivery.com';
            }
            else if(strpos($error2,'User is not authorized to manifest packages')==true)
            {
              $error2='Dear user, it seems that you are not authorised to manifest shipments. Please check if you are using the right credentials. In case issue still persists, please reach out to integrations@delhivery.com';
            }
            else if(strpos($error2,'Invalid client. Client does not exist')==true)
            {
              $error2='Dear user, it seems that you do not still have an active business account created with delhivery. Please get an account created by visiting cl.delhivery.com';
            }
            else if(strpos($error2,'client is not active')==true)
            {
              $error2='Dear user, it seems that you do not still have an active business account created with delhivery. Please get an account created by visiting cl.delhivery.com';
            }
            else if(strpos($error2,'client is required key')==true)
            {
              $error2='It seems like a Technical error has occured. Please try once again. In case issue still persists, please reach out to integrations@delhivery.com';
            }
            else if(strpos($error2,'format key missing in POST')==true)
            {
              $error2='It seems like a Technical error has occured. Please try once again. In case issue still persists, please reach out to integrations@delhivery.com';
            }
            else if(strpos($error2,'celint is not active')==true)
            {
              $error2='Dear user, it seems that you do not still have an active business account created with delhivery. Please get an account created by visitng cl.delhivery.com';
            }
            else if(strpos($error2,'client does not belong to this client master')==true)
            {
              $error2 = 'It seems like a Technical error has occured. Please try once again. In case issue still persists, please reach out to integrations@delhivery.com';
            }
            else if(strpos($error2,'ClientWarehouse matching query does not exists')==true)
            {
              $error2 = 'It seems like an active Warehouse is not available for your account. Please create one warehouse and proceed further. In case issue still persists, please reach out to integrations@delhivery.com';
            }
            else if(strpos($error2,'Client-Warehouse is not active')==true)
            {
              $error2 = 'It seems like an active Warehouse is not available for your account. Please create one warehouse and proceed further. In case issue still persists, please reach out to integrations@delhivery.com';
            }
            else if(strpos($error2,'Invalid pincode.Please pass a valid pincode')==true)
            {
              $error2='It seems like you entered an invalid pincode. Please enter the correct pincode. In case issue still persists, please reach out to integrations@delhivery.com';
            }
            else if(strpos($error2,'COD amount 0 for COD/Cash package')==true)
            {
              $error2='You seem to have entered the COD amount value as 0. Please enter the correct value to proceed further. In case issue still persists, please reach out to integrations@delhivery.com';
            }
            else if(strpos($error2,'Package creation API error.Package might be saved.Package might be saved')==true)
            {
              $error2='It seems like a Technical error has occured. Please try logging in once again. In case issue still persists, please reach out to integrations@delhivery.com';
            }
            else if(strpos($error2,'wallet balance is 0.0, less than the minimum balance of 500.0 required to manifest a package. Package might have been partially saved')==true)
            {
              $error2='Your account seems to be enabled for prepaid manifestation but the wallet balance is less than 500 that is required to manifest a shipment. Please recharge your wallet to proceed further. In case issue still persists, please reach out to integrations@delhivery.com';
            }
            else if(strpos($error2,"Crashing while saving package due to exception 'Dear Customer please recharge your wallet as your current '")==true)
            {
              $error2='Your account seems to be enabled for prepaid manifestation but the wallet balance is less than 500 that is required to manifest a shipment. Please recharge your wallet to proceed further. In case issue still persists, please reach out to integrations@delhivery.com';
            }
            else if(strpos($error2,'Authentication credentials were not provided')==true)
            {
              $error2='It seems like valid Authentication credentials has not been entered. Please enter valid credentials to proceed further. In case issue still persists, please reach out to integrations@delhivery.com';
            }
            else if(strpos($error2,'Consignee pin code is invalid')==true)
            {
              $error2='It seems like valid pincode details for the shipment has not been entered. Please enter valid pincode details that are required. In case issue still persists, please reach out to integrations@delhivery.com';
            }
            else if(strpos($error2,'Weight 0 or not found')==true)
            {
              $error2='It seems like weight for the shipment has not been entered. Please enter weight details that are required. In case issue still persists, please reach out to integrations@delhivery.com';
            }
            else if(strpos($error2,'Unable to consume waybill')==true)
            {
              $error2='You seem to have used all the existing waybills.Please generate more waybills to continue and try again';
            }
            else if(strpos($error2,'Unsupported format')==true)
            {
              $error2='It seems like a Technical error has occured. Please try once again with only the following supported formats (xml, .xls, .xlsx, json). In case issue still persists, please reach out to integrations@delhivery.com';
            }
            else if(strpos($error2,'Invalid warehouse pincode')==true)
            {
              $error2='It seems like valid warehouse pincode details for the shipment has not been entered. Please enter valid warehouse pincode details that are required. In case issue still persists, please reach out to integrations@delhivery.com';
            }
             else if(strpos($error2,'Time bound and Quality checks are valid only for Pickup packages')==true)
            {
              $error2='It seems like valid pickup details for the shipment has not been entered. Please enter valid pickup details that are required. In case issue still persists, please reach out to integrations@delhivery.com';
            }
             else if(strpos($error2,'invalid consignee name provided')==true)
            {
              $error2='It seems like valid consignee details for the shipment has not been entered. Please enter valid consignee details that are required. In case issue still persists, please reach out to integrations@delhivery.com';
            }
             else if(strpos($error2,'consignee name not provided')==true)
            {
              $error2='It seems like consignee details for the shipment has not been entered. Please enter consignee details that are required. In case issue still persists, please reach out to integrations@delhivery.com';
            }
            $error = $error2;
            $_SESSION["errmsg"]=$error;
            /*echo '<div id="woocommerce_errors" class="error"><div class="shopify-error">
                  <img src="' . esc_url( plugins_url( '../images/alert.png', __FILE__ ) ) . '" ><pid="err_msg" id="err_msg">'.esc_html($error).'</p></div></div>';*/
          }
          //Update order log table
            //$lqry = "update ".$wpdb->prefix."dv_logs set response_value='$res_value' where id=$last_log_id";
            //$wpdb->query($lqry); 
}


?>

<!DOCTYPE html>
  <html lang="en">
    <body class="bg-color">
      <div class="main-shopify-wrapper">
        <?php //require_once('menus.php'); ?>  
          <div class="container-fluid">
            <div style="display:none" id="loader_rate">
            <div class="shopify-loader">
            <div class="loader" id="loader-1"></div>
            </div>
            </div>
                <div class="row">
                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <?php require_once('wallet.php'); ?>
                    <div class="table-top-section">
                       <h1 class="comman-heading">My Orders <small>(<?php echo esc_html($total)?> items)</small></h1>
                       <span class="no-of-items"></span>
                       <span  class="tooltip-hover" id="track_orders" tooltip-toggle="tooltip" data-placement="bottom" title="Refresh"><i class="fas fa-redo-alt refresh-icon"></i></span> 
                       
                       <a href="#" class="waybill-btn" onclick="dlc_bulk_packing_slip();"><i class="fas fa-print print-icon"></i>Print Packaging Slip</a>

                       <a href="<?php echo esc_url(site_url().'/wp-admin/admin.php?page=my_order&action=bulk_ship&pageNumber='.$pageNumber);?>" class="waybill-btn float-right">Bulk Shipping</a>
                       <div class="order-buttons-wrapper">
                       <div class="custom-select" onclick="dlc_filter();">
                       <select name="status_filter" id="status_filter"  class="waybill-btn waybill-sel" style="height: 38px;">
                        <option value=''>Filter</option>
                        <option value='0' <?php if(sanitize_text_field($_GET['filter_status'])=='Not Manifested'){ echo esc_html('selected'); }  ?>>Not Manifested</option>
                        <option value='1' <?php if(sanitize_text_field($_GET['filter_status'])=='Manifested'){ echo esc_html('selected'); }  ?> >Manifested</option>
                        <option value='2' <?php if(sanitize_text_field($_GET['filter_status'])=='Not Picked'){ echo esc_html('selected'); }  ?> >Not Picked</option>
                        <option value='3' <?php if(sanitize_text_field($_GET['filter_status'])=='Pending'){ echo esc_html('selected'); }  ?> >Pending</option>
                        <option value='4' <?php if(sanitize_text_field($_GET['filter_status'])=='In transit'){ echo esc_html('selected'); }  ?> >In transit</option>
                        <option value='5' <?php if(sanitize_text_field($_GET['filter_status'])=='Dispatched'){ echo esc_html('selected'); }  ?> >Dispatched</option>
                        
                        <option value='7' <?php if(sanitize_text_field($_GET['filter_status'])=='RTO'){ echo esc_html('selected'); }  ?> >RTO</option>
                       </select>
                      </div>
                        <input type="text" placeholder="order number" onfocus="this.placeholder=''" name="search_order" id="search_order" class="waybill-btn" onblur="dlc_search();">
                        <button onclick="dlc_reset();" class="waybill-btn">Reset</button>
                       </div>
                    </div>
                    <div id="msg"></div>
                    <div id="message">
                    <?php 
                      if(isset($_SESSION["succmsg"]) && $_SESSION["succmsg"]!='')
                      {
                        $succmsg = sanitize_text_field($_SESSION["succmsg"]);
                        echo '
                         <div class="shopify-sucess-msg" id="smsg">
                          <img src="' . esc_url( plugins_url( '../images/checked.png', __FILE__ ) ) . '" ><p>'.esc_html($succmsg).'</p></div>';
                        $_SESSION["succmsg"] = '';
                      }
                      else if(isset($_SESSION["errmsg"]) && $_SESSION["errmsg"]!='')
                      {
                        $errmsg = sanitize_text_field($_SESSION["errmsg"]);
                        echo '
                          <div id="woocommerce_errors" class="error"><div class="shopify-error">
                          <img src="' . esc_url( plugins_url( '../images/alert.png', __FILE__ ) ) . '" ><p>'.esc_html($errmsg).'
                          </p></div></div>';
                        $_SESSION["errmsg"] = '';
                      }

                    ?>
                   </div>
                   <div id="woocommerce_errors" class="error" style="display:none;">
                       <div class="shopify-error">
                             <?php echo '<img src="' . esc_url( plugins_url( '../images/alert.png', __FILE__ ) ) . '" >'; ?>
                             <p id="err_msg"></p>
                       </div>
                      
                    </div>
                    
                    <div class="data-card table-responsive" id="fetch_order">
                       <table class="table table-borderless my-order-table">
                          <thead class="border-bottom">
                             <tr>
                                <th>
                                  <label class="checkbox">
                                  <input type="checkbox" class="selectall" />
                                  <span class="checkmark all-select-checkbox"></span>
                                </th>
                                <th scope="col">Actions</th>
                                <th scope="col">Order ID</th>
                                <th scope="col">Creation Date</th>
                                <th scope="col">Product Description</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Customer Name</th>
                                <th scope="col">Shipping Pincode</th>
                                <!--<th scope="col">Item Total Price</th>-->
                                <th scope="col">Total Amount</th>
                                <th scope="col">Payment Type</th>
                                <th scope="col">Status</th>
                                <th scope="col">Status Type</th>
                                <th scope="col">Instructions</th>
                                
                             </tr>
                          </thead>

                          <tbody>
                            
                          <?php
                           
                           foreach($myrows as $data)
                           {
                                global $wpdb;
                                if(isset($_GET['search_waybill']))
                                {
                                  $search_waybill = sanitize_text_field($_GET['search_waybill']);
                                }
                                else if(isset($_GET['filter_status']))
                                {
                                  $filter_status = sanitize_text_field($_GET['filter_status']);
                                }
                                $scost_detail  = $wpdb->get_row("select count(id) as cnt,shipping_cost,awb_no,status,shipment_status,seller_invoice,e_waybill_no,status_type,instructions from ".$wpdb->prefix."dv_assign_awb where order_id='$data->order_id'");
                                $order = new WC_Order($data->order_id);
                                $order_date = date("d-m-Y", strtotime($order->order_date));
                                $shipping_cost = $scost_detail->shipping_cost; 
                                $awbno = $scost_detail->awb_no; 
                                $status = $scost_detail->status;
                                $instructions = $scost_detail->instructions;
                                $shipment_status = $scost_detail->shipment_status;
                                $e_waybill_no = $scost_detail->e_waybill_no;
                                $seller_invoice = $scost_detail->seller_invoice;
                                $cnt = $scost_detail->cnt;
                                $status_type = $scost_detail->status_type;
                                if($status_type=='')
                                {
                                  $status_type='NA';
                                }
                                $item_values = "";
                                $get_item =  $wpdb->get_results("select order_item_name from ".$wpdb->prefix."woocommerce_order_items where order_id=$data->order_id and order_item_type='line_item'");
                                foreach ($get_item as $get_items) 
                                {

                                    $prod_details = $wpdb->get_row('SELECT ID FROM '.$wpdb->prefix  .'posts where post_title="'.$get_items->order_item_name.'" and post_status="publish"');
                                    $prod_id = $prod_details->ID;
                                    $prod = wc_get_product($prod_id);
                                    $color= $prod->attributes['color']['options']['0'];
                                    $size = $prod->attributes['size']['options']['0'];
                                    if ($color!='' && $size!='')
                                    {
                                      $pro_details = $get_items->order_item_name.'('.$size.','.$color.')';
                                    }
                                    else if($color!='' )
                                    {
                                      $pro_details = $get_items->order_item_name.'('.$color.')';
                                    }
                                    
                                    else if($size!='')
                                    {
                                      $pro_details = $get_items->order_item_name.'('.$size.')';
                                    }
                                    
                                    else
                                    {
                                      $pro_details = $get_items->order_item_name;
                                    }
                                  $item_values != "" && $item_values .= ",";
                                  $item_values .= $pro_details;
                                }

                                $quantity = 0;
                                $get_item_qty =  $wpdb->get_results("select b.meta_value as qty FROM ".$wpdb->prefix."woocommerce_order_items a JOIN ".$wpdb->prefix."woocommerce_order_itemmeta b ON a.order_item_id = b.order_item_id where a.order_id=$data->order_id and meta_key='_qty'");
                                foreach ($get_item_qty as $get_item_qtys) 
                                {
                                  $quantity = $quantity+$get_item_qtys->qty;
                                }
                                $item_total_amount = 0;
                                $get_item_price =  $wpdb->get_results("select b.meta_value as prc FROM ".$wpdb->prefix."woocommerce_order_items a JOIN ".$wpdb->prefix."woocommerce_order_itemmeta b ON a.order_item_id = b.order_item_id where a.order_id=$data->order_id and meta_key='_line_subtotal'");
                                foreach ($get_item_price as $get_item_prices) 
                                {
                                  $item_total_amount = $item_total_amount+$get_item_prices->prc;
                                }
                                if(@$data->shipping_pincode=='')
                                {
                                  $pincode = $data->billing_pincode;
                                } 
                                else
                                {
                                  $pincode = $data->shipping_pincode;
                                } 
                                if(@$data->sname=='')
                                {
                                  $customer_fname = $data->bname;
                                } 
                                else
                                {
                                  $customer_fname = $data->sname;
                                } 
                                if(@$data->slname=='')
                                {
                                  $customer_lname = $data->blname;
                                } 
                                else
                                {
                                  $customer_lname = $data->slname;
                                } 
                                $customer_name  = $customer_fname.' '.$customer_lname; 
                                
                                $edit_url = site_url()."/wp-admin/admin.php?page=my_order&action=edit&order_id=".$data->order_id."&awb_no=".$awbno."&pageNumber=".$currentPage;
                                $track_url = site_url()."/wp-admin/admin.php?page=my_order&action=track&order_id=".$data->order_id."&awb_no=".$awbno."&pageNumber=".$currentPage;
                                $edit_order_url = site_url().'/wp-admin/post.php?post='.$data->order_id.'&action=edit';
                                $order = wc_get_order( $data->order_id );
                                $order_status  = $order->get_status();
                                if($order_status=='processing' || $order_status=='pending'  )
                                {  
                                  
                                ?>
                                  <tr>
                                    <td>
                                       <label class="checkbox">
                                       <input type="checkbox"  name='order_id'  id="order_id[]" value="<?php echo  esc_html($awbno); ?>" <?php if($status=='') { ?>disabled="disabled" <?php } ?> >
                                       <span class="checkmark"></span>
                                     
                                    </td>
                                    <td>
                                      <div id="actioncls<?php echo esc_html($data->order_id); ?>" ><?php if($instructions!='Seller cancelled the order'){ ?><?php if($cnt>0 && $shipment_status==1 )  { ?>
                                       <a href="<?php echo esc_url($edit_url); ?>" class="tooltip-hover" tooltip-toggle="tooltip" data-placement="left" title="Edit" target="_top"><i class="fas fa-pen gray-icon"></i></a>
                                       <a  href="<?php echo esc_url($track_url); ?>" class="tooltip-hover" tooltip-toggle="tooltip" data-placement="top" title="Track" target="_top"><i class="fas fa-search gray-icon"></i></a>
                                       <a href="#" class="tooltip-hover" tooltip-toggle="tooltip" data-placement="bottom" title="Cancel" onclick="dlc_cancel_order(<?php echo esc_html($data->order_id); ?>,<?php echo esc_html($awbno); ?>);"><i class="fas fa-trash-alt gray-icon"></i></a>
                                       <a href="#" class="tooltip-hover" tooltip-toggle="tooltip" data-placement="bottom" title="Shpping Label" onclick="do_ship(<?php echo esc_html($data->order_id);?>,<?php echo esc_html($shipping_cost);?>,<?php echo esc_html($awbno);?>);"><i class="fas fa-tag gray-icon"></i></a>
                                    <?php } else if($pincode=='' || $data->phoneno=='' || $data->payment_method==''){ ?>
                                    <button class="btn btn-link btn-sm btn-reset order-btn" tooltip-toggle="tooltip"  data-placement="left" title="click to show error"  onclick="dlc_show_err('<?php echo esc_html($pincode) ?>','<?php echo esc_html($data->phoneno); ?>','<?php echo esc_html($data->payment_method);?>');">incomplete order</button>
                                    <a href="<?php esc_url($edit_order_url);?>" class="tooltip-hover" tooltip-toggle="tooltip" data-placement="left" title="Edit Order" target="_top"><i class="fas fa-pen gray-icon icon-font-15"></i></a> 
                                    <?php }
                                     else {?>
                                    <button class="btn btn-link btn-sm btn-reset order-btn" data-toggle="modal" data-target="" onclick="show_modal(<?php echo esc_html($data->total_amount);?>,'<?php echo esc_html($pincode); ?>',<?php echo esc_html($data->order_id); ?>,'<?php echo esc_html($seller_invoice); ?>','<?php echo esc_html($e_waybill_no); ?>');">ship order</button> 
                                    <?php } ?>
                                    <?php } else { echo esc_html('NA'); }?></div>
                                    <div id="ajax_actioncls<?php echo $order_id?>" style="display:none;"></div>
                                  </td>
                                    <td><?php echo esc_html($data->order_id); ?></td>
                                    <td><?php echo esc_html($order_date); ?></td>
                                    <td><?php echo esc_html($item_values);?></td>
                                    <td><?php echo esc_html($quantity); ?></td>
                                    <td><?php echo esc_html($customer_name);?></td>
                                    <td><?php echo esc_html($pincode);?></td>
                                    <td><?php echo esc_html(get_woocommerce_currency_symbol());?><?php echo esc_html($data->total_amount);?></td>
                                    <td><?php echo esc_html(ucwords($data->payment_method));?></td>
                                    <td><?php echo esc_html($status);?></td>
                                    <td><?php echo esc_html($status_type);?></td>
                                    <td><?php echo esc_html($instructions);?></td>
                                    
                                 </tr>
                             <?php 
                                } 
                             }?>
                          </tbody>
                       </table>
                    </div>
                  <?php 
                  if($totalPages>1)
                  {
                  ?>
                  <div class="loadmore-wrapper" id="loadmore_wrapper">
                  <input type="hidden" id="result_no" value="20">
                  <a href="#" class="btn-lg btn-block waybill-btn" onclick="dlc_loadmore();">loadmore</a>
                  </div>
                  <?php } ?>

                  <?php if($totalPages>1) 
                  { 
                    if(isset($_GET['pageNumber']))
                    {
                      $j = sanitize_text_field($_GET['pageNumber']);
                      $i = ($j-1);
                      $k = ($j+1);
                    }

                   
                  ?>
                  <div class="row">
                  <div class="comman-btn-div">
                  <div class="pagination-wrapper">
                  <nav aria-label="Page navigation example">
                     <ul class="pagination">
                        <li class="page-item">
                           <a class="page-link" target="_top" href="<?php echo esc_url($pagination_link.'&pageNumber='.$i.$link);?>" aria-label="Previous">
                           <span aria-hidden="true"><i class="fa fa-step-backward color-darkgray" aria-hidden="true"></i>
                           </span>
                           </a>
                        </li>
                        <li class="page-item">
                           <a class="page-link" target="_top" href="<?php echo esc_url($pagination_link.'&pageNumber='.$i.$link);?>" aria-label="Previous">
                           <span aria-hidden="true"><i class="fa fa-chevron-left color-darkgray" aria-hidden="true"></i></span>
                           </a>
                        </li>
                        <?php 
                        
                        for($n=1;$n<=$totalPages;$n++) 
                        { 
                           if(!isset( $_GET['pageNumber']))
                           {
                              $_GET['pageNumber'] = 1;
                           }
                        ?>
                        <li class="page-item"><a class="page-link <?php if(sanitize_text_field($_GET['pageNumber'])==$n) { echo esc_html('active'); } ?>" target="_top" href="<?php echo esc_url($pagination_link.'&pageNumber='.$n.$link);?>"><?php echo $n?></a></li>
                        <?php } ?>
                        
                        <li class="page-item">
                           <a class="page-link" target="_top" href="<?php echo esc_url($pagination_link.'&pageNumber='.$k.$link);?>" aria-label="Next">
                           <span aria-hidden="true"><i class="fa fa-chevron-right color-darkgray" aria-hidden="true"></i></span>
                           </a>
                        </li>
                        <li class="page-item">
                           <a class="page-link" target="_top" href="<?php echo esc_url($pagination_link.'&pageNumber='.$k.$link);?>" aria-label="Next">
                           <span aria-hidden="true"><i class="fa fa-step-backward color-darkgray" aria-hidden="true" style="
                              transform: rotate(180deg);
                              "></i></span>
                           </a>
                        </li>
                     </ul>
                  </nav>
                  </div>
                  </div>
                  </div>
             <?php } ?>
                           </div>
                        </div>
                  </div>
      </div>

      <!-- ********************************************************* Start shipping label pop-up ******************************************** -->
      <div id="myshipModal" class="shopify-modal">
            <div class="modal fade my-order-modal" id="shipping-label-Modal" tabindex="-1" role="dialog" aria-labelledby="shippingModalLabel" aria-hidden="true">
               <div class="modal-dialog" role="document">
                  <div class="modal-content">
                     <div class="modal-header">
                        <div class="table-top-section ml-0">
                           <h5 class="comman-heading" id="shippingModalLabel">Assign Shipping Cost</h5>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                     </div>
                     <div class="modal-body">
                        
                           <form action="" method="post" class="form-container" id="modalshipform">
                            <input type="hidden" name="order_id" id="orderids" >
                            <input type="hidden" name="awb_no" id="awbno">
                                <div class="row">
                                        <div class="col-md-12">
                                           <div class="form-group">
                                              <label for="ShippingCost">Enter Shipping Cost<span class="span-color">*</span></label>
                                              <input type="name" value="0" class="form-control" name="shipping_cost" id="shipping_cost" onkeyup="remove_err_msg('shipping_cost_err')">
                                              <div class="form-inp-err" id="shipping_cost_err"></div>
                                           </div>
                                        </div>
                                     </div>
                                     <div class="row">
                                            <div class="col-md-12">
                                               <div class="button-right mt-0">
                                                  <button class="btn btn-primary btn-lg btn-block btn-submit m-0" onclick="save_ship_cost();">Save</button>
                                               </div>
                                            </div>
                                         </div>
                                        </form>
                                    
                     </div>
                  </div>
               </div>
            </div>
      </div>
      <!--******************************************************** end shipping label pop-up ******************************************** -->
      
      <!-- ********************************************************* Start assign-seller button pop-up ******************************************** -->
      <div id="myinvModal" class="shopify-modal">
            <div class="modal fade my-order-modal" id="aasign-seller-Modal" tabindex="-1" role="dialog" aria-labelledby="AasignSellerModal" aria-hidden="true">
               <div class="modal-dialog" role="document">
                  <div class="modal-content">
                     <div class="modal-header">
                        <div class="table-top-section ml-0">
                           <h5 class="comman-heading" id="aasign-seller-Modal">Assign Seller Invoice</h5>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                     </div>
                     <div class="modal-body">
                        
                           <form action="" method="post" class="form-container" id="modalinvform">
                            <input type="hidden" name="orderid" id="orderid">
                            <input type="hidden" name="id" id="id">
                            <input type="hidden" name="stotal" id="stotal">
                            <input type="hidden" name="pncode" id="pncode">
                                <div class="row">
                                        <div class="col-md-12">
                                           <div class="form-group">
                                              <label for="SellerInvoice">Seller Invoice<span class="span-color">*</span></label>
                                              <input type="name" class="form-control"  name="seller_invoice" id="seller_invoice" onkeyup="remove_err_msg('seller_invoice_err')">
                                              <div class="form-inp-err" id="seller_invoice_err"></div>
                                           </div>
                                        </div>
                                        <div id="e_waybill_detail" style="display:none;">
                                        <div class="col-md-12">
                                           <div class="form-group">
                                              <label for="e_waybill_no">E_Waybill No<span class="span-color">*</span></label>
                                              <input type="name"  value="0" class="form-control"  name="e_waybill_no" id="e_waybill_no" onkeyup="remove_err_msg('e_waybill_no_err')">
                                              <div class="form-inp-err" id="e_waybill_no_err"></div></div>
                                        </div>
                                        </div>
                                  </div>
                                     
                                     <div class="row">
                                            <div class="col-md-12">
                                               <div class="button-right mt-0">
                                                  <button class="btn btn-primary btn-lg btn-block btn-submit m-0" onclick="save_invoice();">Save</button>
                                               </div>
                                            </div>
                                      </div>
                            </form>
                                    
                     </div>
                  </div>
               </div>
            </div>
         </div>
      <!--******************************************************** end assign-seller button pop-up ******************************************** -->

      <!-- ********************************************************* Start ship-order button pop-up ******************************************** -->
      <div id="myModal" class="shopify-modal">
            <div class="modal fade my-order-modal" id="ship-order-Modal" tabindex="-1" role="dialog" aria-labelledby="ShipOrderModal" aria-hidden="true">
               <div class="modal-dialog" role="document">
                  <div class="modal-content">
                     <div class="modal-header">
                        <div class="table-top-section ml-0">
                           <h5 class="comman-heading" id="aasign-seller-Modal">Check Warehouse</h5>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                     </div>
                     <div class="modal-body">
                        
                           <form action="" method="post" class="form-wrapper" id="modalform">
                              <input type="hidden" name="pincode" id="pincode">
                              <input type="hidden" name="order_item_id" id="order_item_id">
                              <input type="hidden" name="order_id" id="order_id">
                              <input type="hidden" name="seller_inv" id="seller_inv">
                              <input type="hidden" name="e_waybill" id="e_waybill">
                                <div class="row">
                                        <div class="col-md-12">
                                           <div class="form-group">
                                              <label for="SelectWarehouse">Select Warehouse<span class="span-color">*</span></label>
                                              
                                              <div class="custom-select" id="custom-select">
                                              <select name="ware_house" id="ware_house" onchange="check_status('wh');">
                                                 <option value="" selected>Select</option>
                                                 <?php
                                                  foreach($warehouse as $datawarehouse)
                                                  {
                                                  ?>
                                                      <option value="<?php echo esc_html($datawarehouse->name)?>"><?php echo esc_html($datawarehouse->name)?>
                                                        
                                                      </option>
                                                  <?php 
                                                  }
                                                  ?>
                                              </select>
                                            </div>
                                               
                                        </div>
                                        </div>
                                    </div>
                                                <div id="rt_detail" style="display:none;">
                                                  <div class="row">
                                                  <div class="col-md-12">
                                                  <div class="form-group">
                                                    <label for="return_pin">Return Pin</label>
                                                    <input type="text" class="form-control" name="return_pin" id="return_pin" readonly>
                                                  </div>
                                                  </div>
                                                  </div>
                                                  <div class="row">
                                                  <div class="col-md-12">
                                                  <div class="form-group">
                                                      <label for="return_address">Return City</label>
                                                      <input type="text" class="form-control" name="return_city" id="return_city" readonly>
                                                  </div>
                                                  </div>
                                                  </div>
                                                <div class="row">
                                                <div class="col-md-12">
                                                <div class="form-group">
                                                  <label for="return_state">Return State </label>
                                                  <input type="text" class="form-control" name="return_state" id="return_state" readonly>
                                                </div>
                                                </div>
                                                </div>
                                                <div class="row">
                                                <div class="col-md-12">
                                                <div class="form-group">
                                                  <label for="return_country">Return Country</label>
                                                  <input type="text" class="form-control" name="return_country" id="return_country" readonly>
                                                </div>
                                                </div>
                                               </div>
                                                <div class="row">
                                                <div class="col-md-12">
                                                <div class="form-group">
                                                  <label for="return_address">Return Address</label>
                                                  <input type="text" class="form-control" name="return_address" id="return_address" readonly>
                                                </div>
                                                </div>
                                                </div>
                                          </div>

                                           
                                
                                          <div class="row">
                                            <div class="col-md-12">
                                               <div class="button-right mt-0">
                                                  <button class="btn btn-primary btn-lg btn-block btn-submit m-0 " name="proceed" id="proceed" disabled onclick="dlc_manifest_order();" >Proceed <div style="display:none" id="loader_rate1" >
                                                  <div class="shopify-loader" >
                                                  <div class="loader" id="loader-1"></div>
                                                  </div>
                                                  </div></button>
                                                  <div id="loader"></div>
                                               </div>
                                            </div>
                                         </div>
                                    </form>
                                    </div>
                    
                     </div>
               </div>
            </div>
      </div>
      <!--******************************************************** end ship-order button pop-up ******************************************** -->
         
        </body>

</html>
<?php
wp_enqueue_style( 'bootstrap.min', plugins_url('/css/bootstrap.min.css',__FILE__) );
wp_enqueue_style( 'stylees', plugins_url('/css/custom_styles.css',__FILE__) );
wp_enqueue_script( 'bootstrap.min_js', plugins_url('/js/bootstrap.min.js',__FILE__)); 
wp_enqueue_script( 'custom_js', plugins_url('/js/custom.js',__FILE__), array( 'jquery' ), null, true );
?>
<script type = "text/javascript">

function show_modal(subtotal,pincode,order_id,seller_invoice,e_waybill_no)
{
  document.getElementById('woocommerce_errors').style.display = "none";
  document.getElementById('message').innerHTML = "";
  var subtotal = subtotal;
  var pin = pincode;
  var order_ids = order_id;
  var seller_invoice = seller_invoice;
  var e_waybill_no = e_waybill_no;
  if (pin=='')
  {
    alert("shipping pincode not available");
    return false;
  }
  else
  {
    jQuery('#ship-order-Modal').modal('show');
    var pincode = document.getElementById('pincode');
    pincode.value= pin;
    var order_id = document.getElementById('order_id');
    order_id.value= order_ids;
    var seller_inv = document.getElementById('seller_inv');
    seller_inv.value= seller_invoice;
    var e_waybill = document.getElementById('e_waybill');
    e_waybill.value= e_waybill_no;
   
  }
 
}
function close_modal()
{
  jQuery('#ship-order-Modal').modal('hide');
  document.getElementById("loadimg").style.visibility = "hidden";
  document.getElementById("rt_detail").style.display = "none";
  document.getElementById('modalform').reset();
}

function do_ship(order_id,shipping_cost,awb_no)
{
  var red_url = "<?php echo $shipping_red_url; ?>";
  var pageNumber = "<?php echo $currentPage; ?>";
  var red_url = red_url+'&awb_no='+awb_no+'&pageNumber='+pageNumber;;
  window.location.href = red_url;
}

</script>
<?php 
add_action( 'admin_footer', 'check_pincode' );
function check_pincode() { ?>
<script type="text/javascript" >
jQuery(document).ready(function($) 
{
  jQuery('#custom-select').click(function() 
  {
      document.getElementById("rt_detail").style.display = "none";
      var e = document.getElementById("ware_house");
      var ware_house_name = e.options[e.selectedIndex].value;
      var pincode = document.getElementById('pincode').value;
      //var seller_inv = document.getElementById('seller_inv').value;
      var order_id = document.getElementById('order_id').value;
     
      if(ware_house_name == 0)
      {
        alert("Please select Warehouse!");
        return false;
      }
      else
      {
        
        document.getElementById("loader_rate1").style.display = "block";
          var data = {
            'action': 'check_pincode',
            'name' : ware_house_name,
            'pincode': pincode,
            'order_id' : order_id

          };
          jQuery.ajax({
          url: ajaxurl,
          type: 'post',
          data: data,
          dataType: 'json',
          success: function(response){
                if(response['status']==1)
                {
                  document.getElementById("loader_rate1").style.display = "none";
                  jQuery('#proceed').removeAttr('disabled');
                  jQuery('#return_address').val(response['return_address']);
                  jQuery('#return_pin').val(response['return_pin']);
                  jQuery('#return_city').val(response['return_city']);
                  jQuery('#return_state').val(response['return_state']);
                  jQuery('#return_country').val(response['return_country']);
                }
                else
                {
                  jQuery('#ship-order-Modal').modal('hide');
                  document.getElementById("loader_rate1").style.display = "none";
                  var errmsg = response['err_msg'];
                  document.getElementById('woocommerce_errors').style.display = "block";
                  document.getElementById("err_msg").innerHTML=errmsg;

                }
            }
          
          });


      }
  });
});
</script>
<?php } ?>
<script>
function dlc_manifest_order()
{
  document.getElementById("loader_rate1").style.display = "block";
  jQuery('#proceed').attr("disabled", true);
  document.getElementById("modalform").submit();
}

jQuery(document).ready(function($) 
{
  jQuery('#track_orders').click(function() {
  var page = '<?php echo esc_html($page);?>';
  document.getElementById("loader_rate").style.display = "block";
    var data = {
            'action': 'track_order',
            'page' : page,
    };
  
      jQuery.ajax({
          type: "GET",
          url:ajaxurl,
          data: data,
          dataType: "json",
          success: function( data ) {
            
            if(data['status']==1)
            {
              location.reload();
              return false;
            }
              

          }
      }); 
    });
    
});
function remove_err_msg(id)
{
  document.getElementById(id).innerHTML = "";
}
function dlc_bulk_packing_slip()
{
  ( function( $ ) {

    var myCheckboxes = new Array();
    var checkboxes = document.getElementsByName('order_id');
    var selected = [];
    var count_checked = $("[name='order_id']:checked").length;
    for (var i=0; i<checkboxes.length; i++) {
        if (checkboxes[i].checked) {
            selected.push(checkboxes[i].value);
        }
    }
  if(count_checked>0)
    {
      
      var red_url = "<?php echo $shipping_red_url; ?>";
      var red_url = red_url+'&awb_no='+selected;
      window.open(red_url,'_top');
      
    }

    else
    {
      var errmsg = 'Please check atleast one order';
      document.getElementById('woocommerce_errors').style.display = "block";
      document.getElementById("err_msg").innerHTML=errmsg;
      
      
    }
  } )( jQuery );
}

function dlc_search()
{
  var search_url = "<?php echo esc_url($search_url); ?>";
  var search_order = document.getElementById("search_order").value;
  
  document.getElementById("search_order").placeholder = "ORDER NUMBER";
  if(search_order!='')
  {
    search_url = search_url+'&search_order='+search_order;
    window.location.href = search_url;
  }
  
}
function dlc_reset()
{
  var url = "<?php echo $url; ?>";
  window.location.href = url;
}
function dlc_cancel_order(order_id,awb_no)
{  
    document.getElementById("loader_rate").style.display = "block";
      var result = confirm("Do you want to cancel order?");
      var red_url = '<?php echo $order_list_url; ?>';
      if (result) 
      {

          var awb_no = awb_no;
          var order_id = order_id;
          var succ_err = '<?php echo $succ_alert_img; ?>';
         
          var data = {
            'action': 'cancel_order',
            'waybill_no' : awb_no,
            'order_id' : order_id

          };
          
              jQuery.ajax({
                  type: "POST",
                  url:ajaxurl,
                  data: data,
                  dataType: "json",
                  success: function( response ) {
                      
                      if(response['status']==1)
                      {
                        //var search_url = "<?php echo $search_url; ?>";
                        document.getElementById("loader_rate").style.display = "none";

                        document.getElementById("message").innerHTML='<div class="shopify-sucess-msg" id="smsg"><img src="'+succ_err+'"><p>Order is cancelled succefully</p></div>';
                        //window.location.href = search_url;
                      }
                      else
                      {
                        document.getElementById("loader_rate").style.display = "none";
                        //alert(response['err_msg']);
                        document.getElementById('woocommerce_errors').style.display = "block";
                        document.getElementById("err_msg").innerHTML=response['err_msg'];
                      }
                      window.location.reload();
                      /*setInterval(function() {
                          window.location.reload();
                        }, 1200); */

                  }
              });
      }
}
function dlc_show_err(pin,phone,payment_mode)
{
    if(pin=='')
    {
      
      var errmsg = 'Pincode in Shipping details is missing.';
      document.getElementById('woocommerce_errors').style.display = "block";
      document.getElementById("err_msg").innerHTML=errmsg;
      return false;
    }
    else if(phone=='')
    {
      //alert("Phone No. in Shipping details is missing.");
      var errmsg = 'Phone No. in Shipping details is missing.';
      document.getElementById('woocommerce_errors').style.display = "block";
      document.getElementById("err_msg").innerHTML=errmsg;

      return false;
    }
    else if(payment_mode=='')
    {
      
      var errmsg = 'Payment mode is missing.';
      document.getElementById('woocommerce_errors').style.display = "block";
      document.getElementById("err_msg").innerHTML=errmsg;


    }
}
 jQuery( document ).ready(function(){
   
    jQuery(".selectall").click(function () {
    document.getElementById('woocommerce_errors').style.display = "none";
    jQuery('input:checkbox:enabled').not(this).prop('checked', this.checked);
   });
 });

function dlc_filter()
{
  var filter_url ="<?php echo htmlspecialchars_decode($filter_url); ?>";
  var status_filter = document.getElementById("status_filter").value;
  //alert(filter_url)
  if(status_filter=='')
  {
   var filter_url ="<?php echo esc_html($search_url); ?>";
  }
  else if(status_filter==0)
  {
    var status='Not Manifested';
    filter_url = filter_url+status;
  }
  else if(status_filter==1)
  {
    var status='Manifested';
    filter_url = filter_url+status;
  }
  else if(status_filter==2)
  {
    var status='Not Picked';
    filter_url = filter_url+status;
  }
  else if(status_filter==3)
  {
    var status='Pending';
    filter_url = filter_url+status;
  }
  else if(status_filter==4)
  {
    var status='In Transit';
    filter_url = filter_url+status;
  }
  else if(status_filter==5)
  {
    var status='Dispatched';
    filter_url = filter_url+status;
  }
  else if(status_filter==6)
  {
    var status='Delivered';
    filter_url = filter_url+status;
  }
  else if(status_filter==7)
  {
    var status='RTO';
    filter_url = filter_url+status;
  }
  
  window.open(filter_url,'_top');
  
}
function dlc_loadmore()
{
    var val = document.getElementById("result_no").value;
    document.getElementById("loader_rate").style.display = "block";
      var data = {
          'action': 'fetch_order_list',
          'getresult' : val

        };
            jQuery.jQueryajax({
                type: "POST",
                url:ajaxurl,
                data: data,
                dataType: "json",
                success: function( response ) {
                    
                  if(response['status']==1)
                  {
                    var count = Number(val)+20
                    if(response['total_count']<=count)
                    {
                      document.getElementById('loadmore_wrapper').style.display = "none";
                    }
                    var fetchdata = response['fetchdata'];
                    document.getElementById("fetch_order").innerHTML=fetchdata;
                    document.getElementById("result_no").value = Number(val)+20;
                    document.getElementById("loader_rate").style.display = "none";
                  }
                  else
                  {
                   var errmsg = response['err_msg'];
                   document.getElementById('woocommerce_errors').style.display = "block";
                   document.getElementById("err_msg").innerHTML=errmsg;

                  }

                }
            });

}

jQuery( document ).ready(function(){
  var page = '<?php echo $page;?>';
  ( function( $ ) {
      var data = {
            'action': 'track_order',
            'page' : page,
      };
  
      jQuery.ajax({
          type: "GET",
          url:ajaxurl,
          data: data,
          dataType: "json",
          success: function( data ) {
            
            if(data['status']==1)
            {
              return false;
            }
              

          }
      }); 
    
    
    })( jQuery );
});
jQuery("#search_order").keypress(function(event) {
    if (event.which == 13) {
      var search_url = "<?php echo esc_url($search_url); ?>";
      var search_order = document.getElementById("search_order").value;
      
      document.getElementById("search_order").placeholder = "ORDER NUMBER";
      if(search_order!='')
      {
        search_url = search_url+'&search_order='+search_order;
        window.location.href = search_url;
      }

  }
});
</script>



