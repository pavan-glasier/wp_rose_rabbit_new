<?php
//session_start();
$red_login_url = site_url().'/wp-admin/admin.php?page=home';
require_once('refresh_token.php');
global $wpdb;
$table_name = $wpdb->prefix . 'dv_awb_no_details';
if (isset($_SESSION['username']))
{
  $username = sanitize_text_field($_SESSION['username']); 
}
$myrow = $wpdb->get_results("SELECT t1.id,t1.awb_no,t1.status as state,t1.created_at,t2.order_id,t2.updated_at,t2.order_id from ".$wpdb->prefix."dv_awb_no_details as t1 left join ".$wpdb->prefix."dv_assign_awb as t2 on t1.awb_no=t2.awb_no where t1.created_by='$username'  ORDER BY t1.status, t1.created_at, t1.updated_at" );
 $total = count($myrow);
 $perpage = 50;
 if(isset($_REQUEST['pageNumber']))
 {
  $page = sanitize_text_field($_REQUEST['pageNumber']);
 }
 
 $totalPages = ceil($total / $perpage);
 $pagination_link = site_url().'/wp-admin/admin.php?page=list_awb_no';
 if (isset($_GET['action']) && sanitize_text_field($_GET['action'])=='truncate')
 {
    $truncate_warehouse =$wpdb->query("truncate table ".$table_name);
 }
?>
<!DOCTYPE html>
<html lang="en">
  
   <body class="bg-color">
    <?php if($total==0){?>
    <div class="main-shopify-wrapper">
    </div>
     
    <section class="waybill-without-list-wrapper">
                
      <?php echo '<img src="' . esc_url( plugins_url( '../images/waybill.png', __FILE__ ) ) . '" >'; ?>
      <h1 class="comman-heading">You have no Waybill Numbers!</h1>
      <h2>Click below to see the list of waybill numbers.</h2>
      <button class="waybill-btn" id ="cl_link_buttons" type='submit' >Generate Waybill No.</button>
        
    </section>
      <?php } 
      else
      {?>
      <div class="main-shopify-wrapper">
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
                     <h1 class="comman-heading">Waybill No.</h1>
                     <span class="no-of-items">(<?php echo esc_html($total); ?> items)</span>
                     <button class="waybill-btn float-right" id="cl_link_buttons" type='submit'>Generate Waybill No.</button>
                     
                  </div>
                 
                  <div id="woocommerce_errors" class="error" style="display:none;">
                     <div class="shopify-error">
                           <?php echo '<img src="' . esc_url( plugins_url( '../images/alert.png', __FILE__ ) ) . '" >'; ?>
                           <p id="err_msg"></p>
                     </div>
                    
                  </div>
                  <div class="data-card table-responsive" id="fetch_waybill">
                     <table class="table table-borderless">
                        <thead class="border-bottom">
                           <tr>
                              <th scope="col">ID</th>
                              <th scope="col">Order ID</th>
                              <th scope="col">AWB NO.(Waybill)</th>
                              <th scope="col">Status</th>
                              <th scope="col">Shipping Name</th>
                              <th scope="col">Created On</th>
                              <th scope="col">Updated On</th>
                              <th scope="col">State</th>
                           </tr>
                        </thead>
                        <tbody>
                        <?php 
                        $k=0; 
                        foreach($myrows as $data)
                        {
                           $k++;
                           if(!isset($_GET['pageNumber']))
                           {
                             $pg = 1;
                           }
                           else
                           {
                             $pg = sanitize_text_field($_GET['pageNumber']);
                           }
                           
                           $l = ($pg-1)*50+$k;
                            $order_id = $data->order_id;
                            if($order_id!='')
                            {
                              $odata = $wpdb->get_row("
                                SELECT g.meta_value as name  FROM ".$wpdb->prefix."woocommerce_order_items a JOIN  ".$wpdb->prefix."postmeta g  ON a.order_id = g.post_id where g.meta_key ='_shipping_first_name' and  a.order_id=$order_id 
                                 ");
                              $name = $odata->name;
                            }
                            else
                            {
                              $name = '';
                            }
                            

                        ?>
                           <tr>
                              <td><?php echo $l; ?></td>
                              <td><?php if($data->order_id!=''){ echo esc_html($data->order_id); } else{ echo esc_html('NA'); }?></td>
                              <td><?php echo $data->awb_no; ?></td>
                              <td><?php if($data->status!=''){ echo esc_html($data->status); } else {
                                 echo 'NA'; }?></td>
                              <td><?php if($name!=''){ echo esc_html($name); } else { echo 'NA'; } ?></td>
                              <td><?php if($data->created_at!='0000-00-00 00:00:00'){ echo esc_html(date("Y-M-d,H:i", strtotime($data->created_at) )); } else { echo esc_html('NA'); }?></td>
                              <td><?php if($data->updated_at!='0000-00-00 00:00:00'){ echo esc_html(date("Y-M-d,H:i", strtotime($data->updated_at)) ); } else { echo esc_html('NA'); }?></td>
                              <td>
                              <?php if ($data->state=='1' )
                              { echo '<span class="badge badge-outline-danger">Used</span>'; } else{ echo '<span class="badge badge-outline-success">Unused</span>';} ?>
                              </td>
                           </tr>
                        <?php 
                        } 
                        ?>
                           
                        </tbody>
                     </table>
                  </div>
                  <?php 
                  if($totalPages>1)
                  {
                  ?>
                  <div class="loadmore-wrapper" id="loadmore_wrapper">
                  <input type="hidden" id="result_no" value="50">
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
                           <a class="page-link" target="_top" href="<?php echo esc_url($pagination_link.'&pageNumber='.$i);?>" aria-label="Previous">
                           <span aria-hidden="true"><i class="fa fa-step-backward color-darkgray" aria-hidden="true"></i>
                           </span>
                           </a>
                        </li>
                        <li class="page-item">
                           <a class="page-link" target="_top" href="<?php echo esc_url($pagination_link.'&pageNumber='.$i);?>" aria-label="Previous">
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
                        <li class="page-item"><a class="page-link <?php if(sanitize_text_field($_GET['pageNumber'])==$n) { echo 'active'; } ?>" target="_top" href="<?php echo esc_url($pagination_link.'&pageNumber='.$n);?>"><?php echo $n;?></a></li>
                        <?php } ?>
                        
                        <li class="page-item">
                           <a class="page-link" target="_top" href="<?php echo esc_url($pagination_link.'&pageNumber='.$k);?>" aria-label="Next">
                           <span aria-hidden="true"><i class="fa fa-chevron-right color-darkgray" aria-hidden="true"></i></span>
                           </a>
                        </li>
                        <li class="page-item">
                           <a class="page-link" target="_top" href="<?php echo esc_url($pagination_link.'&pageNumber='.$k);?>" aria-label="Next">
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
    <?php } ?>
     
   </body>
</html>

<?php
wp_enqueue_style( 'bootstrap.min', plugins_url('/css/bootstrap.min.css',__FILE__) );
wp_enqueue_style( 'stylees', plugins_url('/css/custom_styles.css',__FILE__) );
wp_enqueue_script( 'bootstrap.min_js', plugins_url('/js/bootstrap.min.js',__FILE__));
 // Write our JS below here
add_action( 'admin_footer', 'get_aws' );
function get_aws() { ?>
  <script type="text/javascript" >
  jQuery(document).ready(function($) 
  {
    jQuery('#cl_link_buttons').click(function() {

    var data = {
      'action': 'save_aws',
    };

     // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
      jQuery.ajax({
        url: ajaxurl,
        type: 'post',
        data: data,
        dataType: 'json',
        success: function(response){
        
          if(response['status']==1)
          {
            location.reload();
          }
          else
          {
           var errmsg = response['err_msg'];
           document.getElementById('woocommerce_errors').style.display = "block";
           document.getElementById("err_msg").innerHTML=errmsg;

          }
        }
      });  
    });
  });
  </script> 
<?php 
}
?>
<script type="text/javascript">

  function dlc_loadmore()
  {
      var val = document.getElementById("result_no").value;
      document.getElementById("loader_rate").style.display = "block";
          var data = {
          'action': 'fetch_waybill_list',
          'getresult' : val
        };
          jQuery.ajax({
            type: "POST",
            url:ajaxurl,
            data: data,
            dataType: "json",
            success: function(response) {
              if(response['status']==1)
              {
                var count = Number(val)+50;
                if(response['total_count']==count)
                {
                  document.getElementById('loadmore_wrapper').style.display = "none";
                }
                var fetchdata = response['fetchdata'];
                document.getElementById("fetch_waybill").innerHTML=fetchdata;
                document.getElementById("result_no").value = Number(val)+50;
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
   
</script>