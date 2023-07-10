<?php
  //session_start();
  require_once('refresh_token.php');
  $red_url = site_url().'/wp-admin/admin.php?page=my_warehouse&action=create';
  $table_name = $wpdb->prefix . 'dv_my_warehouse';
  $myrow = $wpdb->get_results("SELECT id,phone,city,state,name,pin,address,country,contact_person,email,registered_name,return_address,return_pin,return_city,return_state,return_country,status,created_at from $table_name ORDER BY id ASC " );
  $total = count($myrow);
  $perpage =50;
  if(isset($_REQUEST['pageNumber']))
  {
    $page = sanitize_text_field($_REQUEST['pageNumber']);
  }
  
  $totalPages = ceil($total / $perpage);
  $pagination_link = site_url().'/wp-admin/admin.php?page=my_warehouse';
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
         <?php echo'<img src="' . esc_url( plugins_url( '../images/warehouse.png', __FILE__ ) ) . '" >'; ?>
         <h1 class="comman-heading">No warehouse listed!</h1>
         <h2>Click below to add new warehouse</h2>
         <a href="#" onclick="dlc_warehouse('<?php echo esc_url($red_url); ?>')" class="waybill-btn">Add NEW</a>
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
                     <h1 class="comman-heading">My Warehouse</h1> 
                     <span class="no-of-items">(<?php echo esc_html($total); ?> items)</span>
                     
                     <a href="#" class="waybill-btn float-right"
                     onclick="dlc_warehouse('<?php echo esc_url($red_url); ?>')" >Add New</a>
                  </div>
                  <?php
                  if(isset($_SESSION["succmsg"]) && $_SESSION["succmsg"]!='')
                  {
                    $succmsg = $_SESSION["succmsg"];

                    echo '<div id="message" class="" style="color:green;">
                                <div class="shopify-sucess-msg">
                                      <img src="' . esc_url( plugins_url( '../images/checked.png', __FILE__ ) ) . '" >
                                      <p>'.esc_html($succmsg).'</p>
                                </div>
                          </div>';
                    $_SESSION["succmsg"] = '';
                  } 
                  ?>
                  <div class="data-card table-responsive" id="fetch_waybill">
                     <table class="table table-borderless warehouse-table">
                        <thead class="border-bottom">
                           <tr>
                              <th scope="col">ID</th>
                              <th scope="col">Name</th>
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
                        <tbody>
                           <?php foreach($myrows as $data)
                           {
                                $edit_url = site_url().'/wp-admin/admin.php?page=my_warehouse&action=edit&name='.$data->name;
                           ?>
                            <tr>
                              <td><?php echo esc_html($data->id); ?></td>
                              <td><?php echo esc_html($data->name); ?></td>
                              <td scope="row">
                                 <a class="text-black" href="#"><?php echo esc_html($data->email);?></a>
                                 <p class="text-grey"><?php echo esc_html($data->phone)?></p>
                              </td>
                              <td><?php if($data->contact_person==''){ echo esc_html('NA'); } else { echo
                              	esc_html($data->contact_person); }?></td>
                              <td><?php if($data->country==''){ echo esc_html('NA'); } else { echo esc_html($data->country); }?></td>
                              <td>
                                 <p><?php if($data->state==''){ echo esc_html('NA'); } else { echo esc_html($data->state); }?></p>
                                 <p class="text-grey"><?php echo esc_html($data->city); ?></p>
                              </td>
                              <td>
                                 <p>
                                   <?php echo esc_html($data->address);?>
                                 </p>
                              </td>
                              <td><?php if($data->registered_name==''){ echo esc_html('NA'); } else { echo esc_html($data->registered_name); }?></td>
                              <td><?php if($data->return_country==''){ echo esc_html('NA'); } else { echo esc_html($data->return_country); }?></td>
                              <td>
                                 <p><?php if($data->return_state==''){ echo esc_html('NA'); } else { echo
                                     esc_html($data->return_state); }?></p>
                                 <p class="text-grey"><?php if($data->return_city==''){ echo esc_html('NA'); } else { echo esc_html($data->return_city); }?></p>
                              </td>
                              <td>
                                 <p>
                                    <?php if($data->return_address==''){ echo esc_html('NA'); } else { echo esc_html($data->return_address); }?>
                                    
                                 </p>
                              </td>
                              <td>
                                 <?php if($data->created_at!='0000-00-00 00:00:00'){ echo esc_html(date("Y-M-d,H:i", strtotime($data->created_at)) ); } else { echo esc_html('NA'); }  ?>                              
                                 </td>
                              <td><?php if ($data->status=='1' ){ echo '<span class="badge badge-outline-success">active</span>'; } else { echo '<span class="badge badge-outline-danger">inactive</span>'; }?></td>
                              <td>
                                    <a href="#" onclick="dlc_warehouse('<?php echo esc_url($edit_url); ?>')" class="tooltip-hover" tooltip-toggle="tooltip" data-placement="left" title="Edit" ><i class="fas fa-pen gray-icon"></i></a>
                              </td>
                           </tr>
                            <?php }  
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
                        <li class="page-item"><a class="page-link <?php if(sanitize_text_field($_GET['pageNumber'])==$n) { echo 'active'; } ?>" target="_top" href="<?php echo $pagination_link.'&pageNumber='.$n;?>"><?php echo $n?></a></li>
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
?>
<script type="text/javascript">

function dlc_warehouse(url)
{
   url = url;
   window.open(url,'_top');
}
function dlc_loadmore()
   {
      var val = document.getElementById("result_no").value;
      document.getElementById("loader_rate").style.display = "block";
      var data = {
          'action': 'fetch_warehouse_list',
          'getresult' : val
        };
          $.ajax({
            type: "POST",
            url:ajaxurl,
            data: data,
            dataType: "json",
            success: function(response) {
              if(response['status']==1)
              {
                var count = Number(val)+50
                if(response['total_count']<=count)
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