<?php
  //session_start();
  $red_login_url = site_url().'/wp-admin/admin.php?page=home';
  require_once('refresh_token.php');
  global $wpdb;

  if(isset($_POST['reset']))
  {
    $_SESSION['o_pincode'] = '';
    $_SESSION['d_pincode'] = '';
    $_SESSION['wgt_in_gram'] = '';
    $_SESSION['shipment_mode'] = '';
    $_SESSION['shipment_status'] = '';
    $_SESSION['payment_mode'] = '';
  }

  if(isset($_SESSION['o_pincode'])) 
  { 
   $s_o_pincode = sanitize_text_field($_SESSION['o_pincode']); 
  }
  else
  {
    $s_o_pincode='';
  }

  if(isset($_SESSION['d_pincode'])) 
  { 
   $s_d_pincode = sanitize_text_field($_SESSION['d_pincode']); 
  }
  else
  {
    $s_d_pincode='';
  }

  if(isset($_SESSION['wgt_in_gram'])) 
  { 
   $s_wgt_in_gram = sanitize_text_field($_SESSION['wgt_in_gram']); 
  }
  else
  {
    $s_wgt_in_gram='';
  }

  if(isset($_SESSION['shipment_mode'])) 
  { 
   $s_shipment_mode = sanitize_text_field($_SESSION['shipment_mode']); 
  }
  else
  {
    $s_shipment_mode='';
  }

  if(isset($_SESSION['shipment_status'])) 
  { 
   $s_shipment_status = sanitize_text_field($_SESSION['shipment_status']); 
  }
  else
  {
    $s_shipment_status='';
  }

  if(isset($_SESSION['payment_mode'])) 
  { 
   $s_payment_mode = sanitize_text_field($_SESSION['payment_mode']); 
  }
  else
  {
    $s_payment_mode='';
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
                        <h1 class="comman-heading">Rate Calculator</h1>
                     </div>
                     <form name = "myForm" id="myForm"  method="post" enctype="multipart/form-data" id="form-module" class="form-wrapper" >
                     <input type="hidden" value="<php echo $auth_token; ?>" readonly="readonly" name="authorization" id="authorization">
                     <input type="hidden" value="<php echo $client_name; ?>" readonly="readonly" name="client_name" id="client_name">
                  <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                           <label for="destinationPincode">Destination pincode<span
                              class="span-color">*</span></label>
                           <input type="name" class="form-control" name="d_pincode" id="d_pincode" onkeyup="remove_err_msg('d_pincode_err')" onblur = "return(validate('dpin'));" maxlength="10" value="<?php echo esc_html($s_d_pincode)?>">
                           <div class="form-inp-err" id="d_pincode_err"></div>
                        </div>
                     </div>
                    <div class="col-md-4">
                        <div class="form-group">
                           <label for="originPincode">Origin Pincode<span class="span-color">*</span></label>
                           <input type="name" class="form-control" name="org_pincode" id="org_pincode" onkeyup="remove_err_msg('org_pincode_err')" onblur = "return(validate('opin'));" maxlength="10" value="<?php echo esc_html($s_o_pincode)?>">
                           <div class="form-inp-err" id="org_pincode_err"></div>
                        </div>
                    </div>
                     
                  </div>
                  <div class="row">
                     <div class="col-md-4">
                        <div class="form-group">
                           <label for="weight">Weight (in grams)</label>
                           <input type="number" class="form-control" name="wgt_in_gram" id="wgt_in_gram" onkeyup="remove_err_msg('wgt_in_gram_err')" onblur = "return(validate('wgtgm'));" value="<?php echo esc_html($s_wgt_in_gram)?>">
                           <div class="form-inp-err" id="wgt_in_gram_err"></div>
                        </div>
                     </div>
                     <div class="col-md-4">
                        <div class="form-group">
                           <label for="shipmentMode">Shipment Mode<span class="span-color">*</span></label>
                              <div class="custom-select"  onclick="remove_err_msg('shipment_mode_err')">
                                <select name="shipment_mode" id="shipment_mode" class="form-control"  onblur = "return(validate('smode'));">
                                   <option value="" >Select</option>
                                   <option value="E" <?php if($s_shipment_mode=='E') { 
                                      echo esc_html('selected'); } ?>>Express</option>
                                   <option value="S" <?php if($s_shipment_mode=='S') { 
                                      echo esc_html('selected'); } ?>>Surface</option>
                                </select>
                              </div>
                              
                            
                            <div class="form-inp-err" id="shipment_mode_err"></div>
                          
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-4">
                        <div class="forware_housem-group">
                           <label for="shipmentStatus">Shipment Status<span class="span-color">*</span></label>
                              <div>
                                <div class="custom-select" onclick="remove_err_msg('shipment_status_err')">
                                  <select name="shipment_status" id="input-shipment_status" class="form-control"  onblur = "return(validate('sts'));">
                                     <option value="" >Select</option>
                                     <option value="Delivered" <?php if($s_shipment_status=='Delivered') { echo esc_html('selected'); } ?>>Forward (Delivered)</option>
                                     <option value="RTO" <?php if($s_shipment_status=='RTO') { echo esc_html('selected'); } ?>>RTO</option>
                                     <option value="DTO" <?php if($s_shipment_status=='DTO') { echo esc_html('selected'); } ?>>DTO</option>
                                  </select>
                                </div>
                              </div>
                            
                            <div class="form-inp-err" id="shipment_status_err"></div>
                           </div>
                        </div>
                     
                     <div class="col-md-4">
                        <div class="form-group">
                           <label for="EnabledProductionMode">Payment Mode<span class="span-color">*</span></label>
                              <div class="custom-select" onclick="remove_err_msg('payment_mode_err')">
                                <select name="payment_mode" id="input-payment_mode" class="form-control"  onblur = "return(validate('pmd'));" onclick="remove_err_msg('payment_mode_err')">
                                   <option value="" >Select</option>
                                   <option value="Pre-paid" >Pre-paid</option>
                                   <option value="COD" >COD</option>
                                </select>
                              </div>
                            
                            <div class="form-inp-err" id="payment_mode_err"></div>
                           </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4">
                        <div class="form-group" id="cash_col" style="display:none">
                           <label for="cash_collected">Cash to be Collected<span class="span-color">*</span></label>
                           <input type="name" class="form-control" name="cash_collected" id="cash_collected" onkeyup="remove_err_msg('cash_collected_err')"  maxlength="10" value="" onblur = "return(validate('csh_col'));">
                           <div class="form-inp-err" id="cash_collected_err"></div>
                        </div>
                    </div>
                  </div>
                  <div class="row">
                     <div class="col-md-8">
                        <div class="button-right float-right">
                           <button class="btn btn-link btn-reset" name="reset" >RESET</button>
                          
                           <button type="button" class="btn btn-primary btn-submit" name="save" onclick = "validate('0');">Submit</button>
                        </div>
                     </div>
                  </div>
               </form>
                  </div>
               </div>
            </div>
         </div>
         <div class="shopify-modal">
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
               <div class="modal-dialog" role="document">
                  <div class="modal-content">
                     <div class="modal-header">
                        <div class="table-top-section">
                           <h5 class="comman-heading" id="exampleModalLabel">Estimated Data</h5>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                     </div>
                     <div class="modal-body" id="rate_output">
                        
                     </div>
                  </div>
               </div>
            </div>
         </div>
    
      
   </body>
</html>
<?php 
wp_enqueue_style( 'bootstrap.min', plugins_url('/css/bootstrap.min.css',__FILE__) );
wp_enqueue_style( 'stylees', plugins_url('/css/custom_styles.css',__FILE__,__FILE__) );
wp_enqueue_script( 'bootstrap.min_js', plugins_url('/js/bootstrap.min.js',__FILE__,__FILE__,__FILE__));
wp_enqueue_script( 'custom_js', plugins_url('/js/custom.js',__FILE__), array( 'jquery' ), null, true ); ?>
<script type = "text/javascript">
   <!--
      // Form validation code will come here.
      function validate($id) {
        
        var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
        var pat1=/^([0-9](6,6))+$/;
        var iChars = ";\\#%&";
        var alpha = /^[a-zA-Z-,]+(\s{0,1}[a-zA-Z-, ])*$/;
        var zip=/^[a-z0-9][a-z0-9\- ]{0,10}[a-z0-9]*$/g;
        var dzip=/^[a-z0-9][a-z0-9\- ]{0,10}[a-z0-9]*$/g;
        var client_name= document.myForm.client_name.value;
        
        
        var dpin= document.myForm.d_pincode.value;
        if($id=='dpin' || $id=='0')
        {
          if( document.myForm.d_pincode.value == "" ) {
               document.getElementById("d_pincode_err").innerHTML = "Enter destination pincode";
               document.myForm.d_pincode.focus() ;
               return false;
          }
          
          dpin= document.myForm.d_pincode.value;
          if (dzip.test(dpin) == false) 
          {
              document.getElementById("d_pincode_err").innerHTML = "Enter valid destination pincode";
                  document.myForm.d_pincode.focus() ;
              return false;
          }
          if ((document.myForm.d_pincode.value).length >10)  
          {
              document.getElementById("d_pincode_err").innerHTML = "Destination pincode should not be greater than 10 characters";
              document.myForm.d_pincode.focus() ;
              return false;
          }
          
        }
        if($id=='opin' || $id=='0')
        {
          if( document.myForm.org_pincode.value == "" ) {
               document.getElementById("org_pincode_err").innerHTML = "Select origin pincode";
               document.myForm.org_pincode.focus() ;
               return false;
          }
          
          var opin= document.myForm.org_pincode.value;
          if (zip.test(opin) == false) 
          {
              document.getElementById("org_pincode_err").innerHTML = "Enter valid origin pincode";
                  document.myForm.org_pincode.focus() ;
              return false;
          }
          if ((document.myForm.org_pincode.value).length >10)  
          {
              document.getElementById("org_pincode_err").innerHTML = "Origin pincode should not be greater than 10 characters";
              document.myForm.org_pincode.focus() ;
              return false;
          }
        }
        var wgt_in_gram = document.myForm.wgt_in_gram.value
        if($id=='wgtgm' || $id=='0')
        {
          if( document.myForm.wgt_in_gram.value != "" ) 
          {
             if(isNaN( document.myForm.wgt_in_gram.value ) ) 
             {
              document.getElementById("wgt_in_gram_err").innerHTML = "Enter numeric value for weight in gram";
              document.myForm.wgt_in_gram.focus() ;
              return false;
             }
             if( document.myForm.wgt_in_gram.value <= 0 ) {
              document.getElementById("wgt_in_gram_err").innerHTML = "Enter more than 0 for weight in gram";
              document.myForm.wgt_in_gram.focus() ;
              return false;
            }
          }
          
        }
        var shipment_mode = document.myForm.shipment_mode.value;
        if($id=='smode' || $id=='0')
        {
          if( document.myForm.shipment_mode.value == "" ) {
               document.getElementById("shipment_mode_err").innerHTML = "Select shipment mode";
               document.myForm.shipment_mode.focus() ;
               return false;
          }
        }
        var shipment_status = document.myForm.shipment_status.value;

        if($id=='sts' || $id=='0')
        {
          //alert(shipment_status);
          if( document.myForm.shipment_status.value == "" ) {
             document.getElementById("shipment_status_err").innerHTML = "Select shipment status";
             document.myForm.shipment_status.focus() ;
             return false;
          }
        }
        var payment_mode = document.myForm.payment_mode.value;
        if($id=='pmd' || $id=='0')
        {
          if( document.myForm.payment_mode.value == "" ) {
               document.getElementById("payment_mode_err").innerHTML = "Select payment mode";
               document.myForm.payment_mode.focus() ;
               return false;
          }
        }
        
        var cash_collected = document.myForm.cash_collected.value;
        //alert(cash_collected);

        if(($id=='csh_col' || $id=='0') && payment_mode=='COD' )
        {
            if( document.myForm.cash_collected.value == "" ) {
               document.getElementById("cash_collected_err").innerHTML = "Please enter value for cash to be collected";
               document.myForm.cash_collected.focus() ;
               return false;
            }
            if(isNaN( document.myForm.cash_collected.value ) ) 
            {
              document.getElementById("cash_collected_err").innerHTML = "Enter numeric value for cash to be collected";
              document.myForm.cash_collected.focus() ;
              return false;
            }
            if( document.myForm.cash_collected.value <= 0 ) {
              document.getElementById("cash_collected_err").innerHTML = "Enter more than 0 for cash to be collected";
              document.myForm.cash_collected.focus() ;
              return false;
            }
          
          
        }
        
        if(cash_collected!='')
        {
          
          var data = {
            'action': 'get_invoice',
            'client_name' : client_name,
            'org_pincode' : opin,
            'd_pincode' : dpin,
            'wgt_in_gram' : wgt_in_gram,
            'shipment_status' : shipment_status,
            'payment_mode' : payment_mode,
            'shipment_mode' : shipment_mode,
            'cod' : cash_collected
          };
        }
        else
        {
          
          var data = {
            'action': 'get_invoice',
            'client_name' : client_name,
            'org_pincode' : opin,
            'd_pincode' : dpin,
            'wgt_in_gram' : wgt_in_gram,
            'shipment_status' : shipment_status,
            'payment_mode' : payment_mode,
            'shipment_mode' : shipment_mode
          };
        }
        
        if($id=='0')
        {
          document.getElementById("loader_rate").style.display = "block";
          jQuery.ajax({
            type: "GET",
            url:ajaxurl,
            data: data,
            dataType: "json",
            success: function( data ) {
              
                if(data['status']==1)
                {
                 document.getElementById("loader_rate").style.display = "none";
                 document.getElementById("rate_output").innerHTML=data['data'];
                 jQuery('#exampleModal').modal('show');
                 //var omodal = document.getElementById('exampleModal');
                 //omodal.style.display = "block";
                }
                else
                {
                 var errmsg = data['err_msg'];
                 alert(errmsg);

                }

            }
        });
        
        }
        
        
        //return( true );
      }
      function remove_err_msg(id)
      {
        document.getElementById(id).innerHTML = "";
        var payment_mode = document.myForm.payment_mode.value;
        //alert(payment_mode);
        if(payment_mode=='COD')
        {
          document.getElementById("cash_col").style.display = "block";
        }
        else
        {
          document.getElementById("cash_col").style.display = "none";
        }
      }
      function reset()
      {
        document.getElementById("myForm").reset();
      }

</script>