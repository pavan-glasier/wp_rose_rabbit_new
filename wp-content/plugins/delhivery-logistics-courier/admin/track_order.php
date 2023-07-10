<?php
  //session_start();
  $red_login_url = site_url().'/wp-admin/admin.php?page=home';
  require_once('refresh_token.php');
  global $wpdb;
  require_once('config.php'); 
  if (isset($_GET['pageNumber']))
  {
    $pageNumber = sanitize_text_field($_GET['pageNumber']);
  }
  $back_url = site_url().'/wp-admin/admin.php?page=my_order&pageNumber='.$pageNumber;
  $token = $auth_token;
  $waybill_no = sanitize_text_field($_GET['awb_no']);
  $order_id =  sanitize_text_field($_GET['order_id']);
  $track_order_url = $base_url.'api/v1/packages/json/';
  $track_order_url = $track_order_url.'?token='.$token.'&waybill='.$waybill_no.'&verbose=2';
  $accesstoken = 'Bearer '.$auth_token; 
  $args = array(
        'headers' => array(
            'Authorization' => $accesstoken
        )
  );
  $res = wp_remote_get($track_order_url,$args);
  $outputs = wp_remote_retrieve_body($res);
  $outputs = json_decode( $outputs, true );
  //Insert into log table
  $res_value = json_encode($outputs);
  //$data_header = json_encode($header);
  //$logqry = "insert into ".$wpdb->prefix."dv_logs set order_id='$order_id',api_name='track_order',header_value='$data_header ' ,request_value='$data_json',url='$track_order_url',response_value=''";
  //$wpdb->query($logqry);
  //$last_log_id = $wpdb->insert_id; 
  $final_data = $outputs["ShipmentData"][0]["Shipment"]["Scans"];
  //Update log table
  //$lqry = "update ".$wpdb->prefix."dv_logs set response_value='$res_value' where id=$last_log_id";
  //$wpdb->query($lqry);
  wp_enqueue_style( 'bootstrap.min', plugins_url('/css/bootstrap.min.css',__FILE__) );
  wp_enqueue_style( 'stylees', plugins_url('/css/custom_styles.css',__FILE__) );
  wp_enqueue_script( 'bootstrap.min_js', plugins_url('/js/bootstrap.min.js',__FILE__));
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
                     <h1 class="comman-heading">Tracking Details</h1>
                  </div>
                  <div class="data-card table-responsive">
                     <table class="table table-borderless">
                        <thead class="border-bottom">
                           <tr>
                              <th scope="col">Scan Date Time</th>
                              <th scope="col">Status Type</th>
                              <th scope="col">Status</th>
                              <th scope="col">Status Date Time</th>
                              <th scope="col">Location</th>
                              <th scope="col">Instructions</th>
                              <th scope="col">Status Code</th>
                           </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach($final_data as $fdata)
                        {
                        ?>
                          <tr>
                            <td><?php echo esc_html(date("d-m-Y,H:i", strtotime($fdata['ScanDetail']['ScanDateTime'])) ); ?></td>
                            <td><?php echo esc_html($fdata['ScanDetail']['ScanType']);?></td>
                            <td><?php echo esc_html($fdata['ScanDetail']['Scan']);?></td>
                            <td><?php echo esc_html(date("d-m-Y,H:i", strtotime($fdata['ScanDetail']['StatusDateTime'])) ); ?></td>
                            <td><?php echo esc_html($fdata['ScanDetail']['ScannedLocation']);?></td>
                            <td><?php echo esc_html($fdata['ScanDetail']['Instructions']);?></td>
                            <td><?php echo esc_html($fdata['ScanDetail']['StatusCode']);?></td>
                          </tr>
                          <?php
                          }
                          ?> 
                        </tbody>
                     </table>
                  </div>
                  <div class="row">
                    <div class="comman-btn-div">
                      <div class="cancel-button">
                      <a href="<?php echo esc_url($back_url); ?>" class="btn btn-link btn-reset" target="_top">back</a>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
         </div>
      </div>
  </body>
</html>

