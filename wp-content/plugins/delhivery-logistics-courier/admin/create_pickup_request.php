<?php
//session_start();
global $wpdb;
$warehouse = $wpdb->get_results("SELECT name from ".$wpdb->prefix."dv_my_warehouse" );
$red_login_url = site_url().'/wp-admin/admin.php?page=home';
require_once('refresh_token.php');
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
            <h1 class="comman-heading">Pickup Request</h1>
         </div>
        <?php
    if(isset($_REQUEST['save']))
    {
          $pickup_location = sanitize_text_field($_REQUEST['pickup_location']);
          $package_count = sanitize_text_field($_REQUEST['package_count']);
          $datetime = sanitize_text_field($_REQUEST['datetime']); 

          $splitdatetime = explode(" ", $datetime);
          $dt =  $splitdatetime[0]; // piece1
          $splitdate=explode("-", $dt);
          $dte = $splitdate[2].$splitdate[1].$splitdate[0]; 
          $tm =  $splitdatetime[1]; // piece2
          $accesstoken = 'Bearer '.$auth_token; 
          $create_pickup = $base_url.'fm/request/new/';
          $data =   array ('pickup_location'=> $pickup_location,
                          'pickup_time'=> $tm,
                          'pickup_date'=> $dte,
                          'expected_package_count'=> $package_count
                          ); 
            
          $data_json = json_encode($data); //die;
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
          $res = wp_remote_post($create_pickup,$arg);
          $output = wp_remote_retrieve_body($res);
          $output = json_decode( $output, true );
          //print_r($output); die;
          $res_value = json_encode($output);
          //Update order log table
          $data_header = json_encode($headers);
          //$logqry = "insert into ".$wpdb->prefix."dv_logs set api_name='create_pickup_request',header_value='$data_header ' ,request_value='$data_json' ,url='$create_url',response_value='',order_id=0";
          //$wpdb->query($logqry);
          //$last_log_id = $wpdb->insert_id; 
          //print_r($output); 
          $err='';
          if(isset($output['pickup_time']))
          {
            $err = $output['pickup_time']; //die;

          }
          if($err!='Pickup time cannot be in past')
          {
            //$lqry = "update ".$wpdb->prefix."dv_logs set response_value='$res_value' where id=$last_log_id";
            //$wpdb->query($lqry);
            echo '<div id="message" class="" style="color:green;">
                        <div class="shopify-sucess-msg">
                             <img src="' . esc_url( plugins_url( '../images/checked.png', __FILE__ ) ) . '" >
                              <p>'.esc_html('Request sent successfully').'</p>
                        </div>
                  </div>';
            
          }
          else
          {
             echo '<div id="woocommerce_errors" class="error"><div class="shopify-error">
                          <img src="' . esc_url( plugins_url( '../images/alert.png', __FILE__ ) ) . '" ><pid="err_msg" id="err_msg">'.esc_html($err).'</p></div></div>';
          }
    }
   ?>
                 
                     <form name = "myForm" onsubmit = "return(validate('0'));" method="post" enctype="multipart/form-data" id="form-module" class="form-wrapper">
                        <div class="row">
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label for="PickupLocation">Pickup Location<span
                                    class="span-color">*</span></label>
                                    <div class="custom-select" onclick="remove_err_msg('pickup_location_err')">
                                      <select class="form-control" name="pickup_location" id="pickup_location"  onblur = "return(validate('pcloc'));">
                                      <option value="">Select</option>
                                       <?php 
                                          foreach($warehouse as $datawarehouse)
                                          {
                                          ?>
                                          <option value="<?php echo esc_html($datawarehouse->name); ?>"><?php echo esc_html($datawarehouse->name); ?></option>
                                          <?php } ?>
                                      </select>
                                    </div>
                                
                                    <div class="form-inp-err" id="pickup_location_err"></div>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label for="ExpectedPackageCount">Expected Package Count<span class="span-color">*</span></label>
                                 <input type="name" class="form-control" name="package_count" id="package_count"  onkeyup="remove_err_msg('package_count_err')" onblur = "return(validate('pkgcnt'));">
                                 <div class="form-inp-err" id="package_count_err"></div>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label for="from_date">Date and Time<span class="span-color">*</span></label>
                                 <input class="form-control" name="datetime" id="datetimepicker" type="text" onblur = "return(validate('dt'));" onchange="remove_err_msg('datetimepicker_err')" readonly><i class="far fa-calendar-alt datepicker-cal-icon gray-icon" id="datetimepicker_icon"></i>
                                 <div class="form-inp-err" id="datetimepicker_err"></div>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-md-8">
                              <div class="button-right float-right">
                                 <button type="submit" class="btn btn-primary btn-submit" name="save">Submit</button>
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
?>

<script type = "text/javascript">
   <!--
      // Form validation code will come here.
      function validate($id) {
        
        var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
        var pat1=/^([0-9](6,6))+$/;
        var iChars = ";\\#%&";
        var alpha = /^[a-zA-Z-,]+(\s{0,1}[a-zA-Z-, ])*$/
        if($id=='pcloc' || $id=='0')
        {
          if( document.myForm.pickup_location.value == "" ) {
            document.getElementById("pickup_location_err").innerHTML = "Please select pickup location!";
            document.myForm.pickup_location.focus() ;
            return false;
          }
        }
        if($id=='pkgcnt' || $id=='0')
        {
          if( document.myForm.package_count.value == "" ) {
            document.getElementById("package_count_err").innerHTML = "Please enter package count!";
            document.myForm.package_count.focus() ;
            return false;
          }
          if( document.myForm.package_count.value <= 0 ) {
            document.getElementById("package_count_err").innerHTML = "Please enter valid package count!";
            document.myForm.package_count.focus() ;
            return false;
          }
        }  
        if($id=='dt' || $id=='0')
        {
          if( document.myForm.datetimepicker.value == "" ) {
            document.getElementById("datetimepicker_err").innerHTML = "Please select date and time!";
            document.myForm.datetimepicker.focus() ;
            return false;
          }
        }

          
         return( true );
      }
      function remove_err_msg(id)
      {
        document.getElementById(id).innerHTML = "";
      }
   
</script>
<?php 
  wp_enqueue_script( 'custom_js', plugins_url('/js/custom.js',__FILE__), array( 'jquery' ), null, true );
  wp_enqueue_script( 'jquery.datetimepicker', plugins_url('/js/jquery.datetimepicker.js',__FILE__)); 
?>

<script>
var checkPastTime = function(currentDateTime) {

var d = new Date();
var todayDate = d.getDate();
var todayYear = d.getYear();
// 'this' is jquery object datetimepicker
if (currentDateTime.getDate() == todayDate) { // check today date
    this.setOptions({
        minTime: d.getHours() + ':00' //here pass current time hour
    });
} 
else if (currentDateTime.getDate()< todayDate) { // check today date
    this.setOptions({
        minTime: '24:00' //here pass current time hour
    });
} 
else
    this.setOptions({
        minTime: false
    });
};
var checkTime = function(currentDateTime) {

var d = new Date();
var todayMonth = d.getMonth();
var todayDate = d.getDate();
var todayYear = d.getYear();
// 'this' is jquery object datetimepicker
if (currentDateTime.getYear() < todayYear) { // check today date
    this.setOptions({
        minTime: '24:00' //here pass current time hour
    });
} 
else if (currentDateTime.getYear() <= todayYear && currentDateTime.getMonth()< todayMonth) { // check today date
    this.setOptions({
        minTime: '24:00' //here pass current time hour
    });
}
else if (currentDateTime.getDate() == todayDate ) { // check today date
    this.setOptions({
         minTime: d.getHours() + ':00' //here pass current time hour
    });
}  
else
    this.setOptions({
        minTime: false
    });
};
jQuery(document).ready(function($) {
  jQuery('#datetimepicker').datetimepicker(
    { 
      minDate: 0,
      format: "d-m-Y H:i:s",
      onChangeDateTime:checkPastTime,
      onChangeMonth:checkTime,
      onChangeYear:checkTime,
      onShow:checkPastTime,

    }
  );
});
</script>
