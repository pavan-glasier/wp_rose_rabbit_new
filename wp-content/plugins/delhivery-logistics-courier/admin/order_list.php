<?php
//session_start();
$red_login_url = site_url().'/wp-admin/admin.php?page=home';
require_once('refresh_token.php');
global $wpdb;
require_once('config.php');
$err_alert_img = esc_url( plugins_url( '../images/alert.png', __FILE__ ) );
$err_info_img = esc_url( plugins_url( '../images/info.png', __FILE__ ) );
$table_name = $wpdb->prefix . 'dv_my_warehouse';
$post_qry = $wpdb->get_row("select count(ID) as cnt from ".$wpdb->prefix."posts where post_status in ('wc-processing','wc-pending')");
$total = $post_qry->cnt;
$perpage = 20;
$page = sanitize_text_field($_REQUEST['pageNumber']);
$totalPages = ceil($total / $perpage);
$pagination_link = site_url().'/wp-admin/admin.php?page=my_order&action=bulk_ship';
$warehouse = $wpdb->get_results("SELECT name from $table_name" );
$back_url = site_url().'/wp-admin/admin.php?page=my_order';
if (isset($_SESSION['username']))
{
  $username=$_SESSION['username'];
}

$getawb = $wpdb->get_results("SELECT awb_no from ".$wpdb->prefix."dv_awb_no_details where status=0 and created_by='$username'" );
  if(count($getawb)<20)
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
    
    if($output['error']!='')
    {
      $error = $output['error'];
      $data['status'] = 0;
      $data['err_msg'] = $error;

    }
    else if($output['detail']!='')
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
?>
<!DOCTYPE html>
<html lang="en">
  <body class="bg-color">
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
                     <h1 class="comman-heading">Bulk Shipping</h1>
                     <span class="no-of-items"></span>
                     <a href="#" onclick="manifest();" class="waybill-btn float-right">Manifest
                     </a>
                    
                  </div>
                <div id="msg"></div>
            
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
                              <th scope="col">Order ID</th>
                              <th scope="col">Product Description</th>
                              <th scope="col">Quantity</th>
                              <th scope="col">Customer Name</th>
                              <th scope="col">Shipping Pincode</th>
                              <!--<th scope="col">Item Total Price</th>-->
                              <th scope="col">Total Amount</th>
                              <th scope="col">Payment Type</th>
                              <th scope="col">Status</th>
                              <th scope="col">Status Type</th>
                           </tr>
                        </thead>
                        <tbody>
          <?php
          foreach($myrows as $data)
          {
            global $wpdb;
            $scost_detail  = $wpdb->get_row("select count(*) as cnt,shipping_cost,awb_no,status,shipment_status,seller_invoice,e_waybill_no,status_type from ".$wpdb->prefix."dv_assign_awb where order_id='$data->order_id'");
            $shipping_cost = $scost_detail->shipping_cost; 
            $awbno = $scost_detail->awb_no; 
            $status = $scost_detail->status;
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
                $prod_details = $wpdb->get_row("SELECT ID FROM ".$wpdb->prefix  ."posts where post_title='$get_items->order_item_name' and post_status='publish'");
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
            

              $get_dv_assign_awb = $wpdb->get_row("select count(*) as cnt,shipment_status  from ".$wpdb->prefix."dv_assign_awb where order_id = $data->order_id ");
              if($get_dv_assign_awb)
              {
                $c_dv_assign_awb = $get_dv_assign_awb->cnt;
              }
              
              if($total_amount>50000 && $c_dv_assign_awb==0) 
              {
                $flag=0;
              }
              else if($c_dv_assign_awb)
              {
                if($get_dv_assign_awb->shipment_status==1)
                {
                  $flag=0;
                }
                else
                {
                  $flag=1;
                }
                
              }
              else
              {
               $flag=1; 
              }
             
            if($flag==1)  
            { 
              if (($pincode!='') && ($data->phoneno!='') && ($data->payment_method!=''))
              {
                /*$order = wc_get_order( $data->order_id );
                $order_status  = $order->get_status();
                if($order_status=='processing')
                { */ 
              ?>       
               <tr>
                  
                  <td>
                    <label class="checkbox">
                    <input type="checkbox"  name='order_id' class="checkBoxClass" id="order_id[]" value="<?php echo esc_html($data->order_id); ?>|<?php echo esc_html($pincode); ?>">
                    <span class="checkmark"></span>
                  </td>
                  <td><?php echo esc_html($data->order_id);?></td>
                  <td><?php echo esc_html($item_values);?></td>
                  <td><?php echo esc_html($quantity);?></td>
                  <td><?php echo esc_html($customer_name);?></td>
                  <td><?php echo esc_html($pincode);?></td>
                  <td><?php echo esc_html(get_woocommerce_currency_symbol()).esc_html($data->total_amount);?></td>
                  <td><?php echo esc_html(ucwords($data->payment_method));?></td>
                  <td><?php if($status==0){ echo esc_html('Processing');} else if($status==1){ echo esc_html('Manifested');} else if($status==2){  echo esc_html('Deliverd');} else if($status==3){ echo esc_html('Confirmed');} else {
                    echo esc_html('Cancelled'); }
                    ?></td>
                  <td><?php echo esc_html($status_type); ?></td>
               </tr>
          <?php
                //}
              }   
            }
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
                  <input type="hidden" id="result_no" value="20">
                  <a href="#" class="btn-lg btn-block waybill-btn" onclick="dlc_loadmore();">loadmore</a>
                  </div>
                  <?php } ?>
                  <div class="row">
                  <div class="comman-btn-div">
                  <div class="cancel-button">
                  <a href="<?php echo esc_url($back_url); ?>" class="btn btn-link btn-reset" target="_top">back</a>
                  </div>
                  <?php if($totalPages>1) 
                  { 
                    if(isset($_GET['pageNumber']))
                    {
                      $j = sanitize_text_field($_GET['pageNumber']);
                      $i = ($j-1);
                      $k = ($j+1);
                    }

                  ?>
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
                  <li class="page-item"><a class="page-link <?php if(sanitize_text_field($_GET['pageNumber'])==$n) { echo 'active'; } ?>" target="_top" href="<?php echo esc_url ($pagination_link.'&pageNumber='.$n);?>"><?php echo $n?></a></li>
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
                <?php } ?>
                  </div>
                  </div>
                  
               </div>
            </div>
         </div>
      </div>
     
   </body>
   <div id="myModal" class="shopify-modal">
            <div class="modal fademy-order-modal my-order-modal" id="ship-order-Modal" tabindex="-1" role="dialog" aria-labelledby="ShipOrderModal" aria-hidden="true">
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
                        
                           <form action="" method="post" class="form-container" id="modalform">
                              <input type="hidden" name="pincode" id="pincode">
                              <input type="hidden" name="order_id_list" id="order_id_list">
                              <input type="hidden" name="id_list" id="id_list">
                              <input type="hidden" name="err_order_id_list" id="err_order_id_list">
                              <input type="hidden" name="err_id_list" id="err_id_list">

                              <input type="hidden" name="seller_inv" id="seller_inv">
                              <input type="hidden" name="e_waybill" id="e_waybill">
                                <div class="row">
                                        <div class="col-md-12">
                                           <div class="form-group">
                                              <label for="SelectWarehouse">Select Warehouse<span class="span-color">*</span></label>
                                              
                                              <div class="custom-select">
                                              <select name="ware_house" id="ware_house" onchange="check_status('wh');">
                                                 <option value="" selected>Select</option>
                                                 <?php
                                                  foreach($warehouse as $datawarehouse)
                                                  {
                                                  ?>
                                                      <option value="<?php echo esc_html($datawarehouse->name); ?>"><?php echo esc_html($datawarehouse->name);?>
                                                        
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
                                                  <button class="btn btn-primary btn-lg btn-block btn-submit m-0 " name="proceed" id="proceed" disabled onclick="dlc_manifest_order(0);">Proceed <div style="display:none" id="loader_rate1">
                                                  <div class="shopify-loader">
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
</html>
<?php 
wp_enqueue_style( 'bootstrap.min', plugins_url('/css/bootstrap.min.css',__FILE__) );
wp_enqueue_style( 'stylees', plugins_url('/css/custom_styles.css',__FILE__) );
wp_enqueue_script( 'bootstrap.min_js', plugins_url('/js/bootstrap.min.js',__FILE__));
wp_enqueue_script( 'custom_js', plugins_url('/js/custom.js',__FILE__), array( 'jquery' ), null, true );
?>
<script>
/*function manifest_order(status)
{
  document.getElementById("loader_rate1").style.display = "block";
  $('#proceed').attr("disabled", true);
  document.getElementById("modalform").submit();
}*/
( function( $ ) {
  $(document).ready(function () {  
    $(".selectall").click(function () {
    document.getElementById('woocommerce_errors').style.display = "none";
    $('input:checkbox').not(this).prop('checked', this.checked);
});
 });
} )( jQuery );
function manifest()
{
  document.getElementById("rt_detail").style.display = "none";
  document.getElementById('woocommerce_errors').style.display = "none";
  var count_checked = jQuery("[name='order_id']:checked").length;
  if(count_checked>0)
  {
    jQuery('#ship-order-Modal').modal('show');
    
  }
  else
  {
    var errmsg='Select atleast one order to manifest'
    document.getElementById('woocommerce_errors').style.display = "block";
    document.getElementById("err_msg").innerHTML=errmsg;


  }


}
function close_modal()
{
  jQuery('#ship-order-Modal').modal('hide');
  document.getElementById("loadimg").style.visibility = "hidden";
  document.getElementById("rt_detail").style.display = "none";
  document.getElementById('modalform').reset();
}
</script>
<?php 
add_action( 'admin_footer', 'check_bulk_pincode' );
function check_bulk_pincode() { ?>
<script type="text/javascript" >
jQuery(document).ready(function($) 
{
  jQuery('.custom-select').click(function() 
  { 
    document.getElementById("rt_detail").style.display = "none";
    var e = document.getElementById("ware_house");
    var ware_house_name = e.options[e.selectedIndex].value;
    var myCheckboxes = new Array();
    var checkboxes = document.getElementsByName('order_id');
    document.getElementById("msg").innerHTML='';
    var selected = [];
    for (var i=0; i<checkboxes.length; i++) {
        if (checkboxes[i].checked) {
            selected.push(checkboxes[i].value);
        }
    }
    
    if(ware_house_name == 0)
    {
      alert("Please select Warehouse!");
      return false;
    }

    else
    {
        document.getElementById("loader_rate1").style.display = "block";
        var data = {
            'action': 'check_bulk_pincode',
            'name' : ware_house_name,
            'order_id' : selected

          };
            jQuery.ajax({
              type: "post",
              url:ajaxurl,
              data : data,
              dataType: "json",
              success: function( response ) {
            
                  if(response['status']==1)
                  {
                      document.getElementById("loader_rate1").style.display = "none";
                      jQuery('#proceed').removeAttr('disabled');
                      var e = document.getElementById("proceed");
                      e.style.cssText = 'background-color: #ef4136;';
                      jQuery('#return_address').val(response['return_address']);
                      jQuery('#return_pin').val(response['return_pin']);
                      jQuery('#return_city').val(response['return_city']);
                      jQuery('#return_state').val(response['return_state']);
                      jQuery('#return_country').val(response['return_country']);
                      jQuery('#order_id_list').val(response['succ_order_id']);
                      jQuery('#err_order_id_list').val(response['err_order_id']);
                      jQuery('#id_list').val(response['succ_id']);
                      jQuery('#err_id_list').val(response['err_id']);
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
<?php }?>
<script>
function dlc_manifest_order(statusval)
{

  var order_id_list = document.getElementById('order_id_list').value;
  var err_order_id_list = document.getElementById('err_order_id_list').value;
  var ware_house  = document.getElementById("ware_house");
  var ware_house_name = ware_house.options[ware_house.selectedIndex].value;
  document.getElementById("loader_rate").style.display = "block";
  jQuery('#proceed').attr("disabled", true);
  jQuery('#ship-order-Modal').modal('hide');
  var info_err = '<?php echo $err_info_img; ?>';
  var alert_err = '<?php echo $err_alert_img; ?>';
  document.getElementById("msg").innerHTML='<div class="shopify-info-msg"><img src="'+info_err+'" alt="info"><p>Your Bulk Order creation is currently in progress and shall take some time to complete. We will notify you as soon as the orders are processed for shipping.</p></div>';
  var create_bulk_order = "<?php //echo $create_bulk_order; ?>";
  if(order_id_list=='')
  {
  	var data = {
          'action': 'create_bulk_order_list',
          'statusval' : statusval,
          'ware_house' :ware_house_name,
          'err_order_id_list' : err_order_id_list
        };
    
  }
  else if(err_order_id_list=='')
  {
  	var data = {
          'action': 'create_bulk_order_list',
          'statusval' : statusval,
          'ware_house' :ware_house_name,
          'order_id_list' : order_id_list
        };
    
  }
  else
  {
  	var data = {
          'action': 'create_bulk_order_list',
          'statusval' : statusval,
          'ware_house' :ware_house_name,
          'order_id_list' : order_id_list,
          'err_order_id_list' : err_order_id_list
        };
    
  }
  var red_url = '<?php echo $back_url; ?>';
  var page_number = '<?php echo $page; ?>';
  if(page_number!='')
  {
    var red_url = '<?php echo $back_url; ?>&pageNumber='+page_number;
  }
  
    
        jQuery.ajax({
                type: "POST",
                url:ajaxurl,
                data: data,
                dataType: "json",
                cache:false,
                async:true,
                timeout:50000,

          /*error: function(jqXHR, textStatus, errorThrown)
          {
            if(textStatus === 'timeout')
            {
                manifest_order(1);
                
            }
          },*/
          success: function( response ) 
          {
            if(response['status']==1)
            {
              document.getElementById("msg").innerHTML='';
              //window.open(red_url,'_top');
              window.location = red_url;
            }
            
            else
            {
              document.getElementById("loader_rate").style.display = "none";
              document.getElementById("msg").innerHTML='';
              document.getElementById("msg").innerHTML='<div id="woocommerce_errors" class="error"><div class="shopify-error"><img src="'+alert_err+'" alt=""><p id="err_msg">'+response['err_msg']+'</p></div></div>';
              
            }
          }

          
      });
}
function dlc_loadmore()
{
    var val = document.getElementById("result_no").value;
    
    document.getElementById("loader_rate").style.display = "block";
    
    var data = {
          'action': 'fetch_bulk_order_list',
          'getresult' : val

        };
            jQuery.ajax({
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

</script>