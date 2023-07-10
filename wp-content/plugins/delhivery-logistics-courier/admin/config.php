<?php
$source_key = 'Woocommerce';
$enabled_prod_mode = 1;
if (isset($_SESSION['token']))
{
	$auth_token = sanitize_text_field($_SESSION['token']);	
}
$awb_no_count = 100;
$setting_count = 1; 
$consignee_tin_no = ''; 
$cst_no = '';
$gst_no = '';
$heavy_shipment= 0;
$client_name= '';
if($enabled_prod_mode==1)
{
  //Url for api
  $login_api = 'https://api-ums.delhivery.com/login/';
  $base_url = "https://track.delhivery.com/";
  $refresh_token_api = 'https://api-ums.delhivery.com/v2/refresh_token/';
  
}
else
{
  //Url for api
   $login_api = 'https://api-stage-ums.delhivery.com/login/';
   $base_url = "https://staging-express.delhivery.com/";
   $refresh_token_api = 'https://api-stage-ums.delhivery.com/v2/refresh_token/';
   
}
?>

