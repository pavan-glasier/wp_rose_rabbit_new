<?php
  //session_destroy();
  //session_start();
  if(session_status() == PHP_SESSION_NONE) {
    session_start();
  }
  //print_r($_SESSION);
  global $wpdb;
  require_once('config.php');
  $red_url = site_url().'/wp-admin/admin.php?page=delhivery_setting';
  
  if($_SESSION['token']!='')
  {
    $red_url = site_url().'/wp-admin/admin.php?page=my_order';
    ob_start();
    header('Location: '.$red_url);
    ob_end_flush();
  }

  $warehouse_list = $base_url.'client/warehouses/list.json';
  
?>
<!DOCTYPE html>
<html lang="en">
  <body class="bg-color"> 
   <div class="login-page">
    <?php echo '<img src="' . esc_url( plugins_url( '../images/login-bg.svg', __FILE__ ) ) . '" > '; ?>
    
    </div>
      <div class="main-shopify-wrapper">
               
               <div class="container-fluid"> 
                  
                  <div class="row m-0">
                     <h1 class="login-header col-md-6 col-6">Welcome user!</h1>
                     <div class="logo col-md-6 col-6">
                        <div class="logo-img">
                          <?php echo '<img src="' . esc_url( plugins_url( '../images/delhivery_logo.png', __FILE__ ) ) . '" > '; ?>
                          
                        </div>
                     </div>
                  </div>
                <?php 
                if( isset($_REQUEST['save']))
                {
                  $username =  sanitize_user( $_REQUEST['username'] );
                  $password = sanitize_text_field($_REQUEST['password']);
                  
                  $data = array('username'=> $username,'password'=> $password); 
                  $data_json = json_encode($data);
                  $headers = array(
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json', 
                    );
                  $arg = array(
                    'headers' => $headers,
                    'timeout'     => 45,
                    'redirection' => 5,
                    'httpversion' => '1.0',
                    'blocking'    => true,
                    'body'    =>  $data_json
                  );
                  $response = wp_remote_post( $login_api, $arg);
                  $output = wp_remote_retrieve_body( $response );
                  $output = json_decode( $output, true );
                  if($output['error']!='')
                  {
                    $error= $output['error'];
                    echo '<div id="woocommerce_errors" class="error"><div class="shopify-error">
                           <img src="' . esc_url( plugins_url( '../images/alert.png', __FILE__ ) ) . '" ><p id="err_msg">'.esc_html($error).'</p></div></div>';
                  }
                  else
                  {
                    $_SESSION['token'] =  $output['jwt']; //die;
                    $_SESSION['timestamp'] = time();
                    $red_url = site_url().'/wp-admin/admin.php?page=order';
                    $_SESSION['username'] = $username;
                    $_SESSION['pass'] = $password;
                    $warehouse_lists = array();
                    $warehouse = $wpdb->get_results("SELECT name from ".$wpdb->prefix."dv_my_warehouse where created_by='".$username."'");
                    $count = $wpdb->num_rows;
                    if($count==0)
                    {
                      $truncate_warehouse =$wpdb->query("truncate table ".$wpdb->prefix."dv_my_warehouse");
                      $accesstoken = 'Bearer '.$output['jwt'];
                      $args = array(
                        'headers' => array(
                            'Authorization' => $accesstoken
                        ),
                        'timeout'     => 45,
                        'redirection' => 5,
                        'httpversion' => '1.0',
                        'blocking'    => true
                      );
                      
                      $res = wp_remote_get($warehouse_list,$args);
                      $outputs     = wp_remote_retrieve_body($res);
                      $outputs = json_decode( $outputs, true );
                      $ware_list_array = $outputs['data'];
                      foreach($ware_list_array as $ware_listdatas)
                      {
                        $ware_house_name = $ware_listdatas['name'];
                        $ware_house_phone = $ware_listdatas['phone'];
                        $ware_house_city = $ware_listdatas['city'];
                        $ware_house_state = $ware_listdatas['state'];
                        $ware_house_pin = $ware_listdatas['pin'];
                        $ware_house_address = $ware_listdatas['address'];
                        $ware_house_country = $ware_listdatas['country'];
                        $ware_house_contact_person = $ware_listdatas['contact_person'];
                        $ware_house_email = $ware_listdatas['email'];
                        
                        $wquery="insert into ".$wpdb->prefix."dv_my_warehouse(name,phone,city,state,pin,address,country,contact_person,email,status,created_by) values('$ware_house_name','$ware_house_phone','$ware_house_city','$ware_house_state','$ware_house_pin','$ware_house_address','$ware_house_country','$ware_house_contact_person','$ware_house_email',1,'$username')";
                        $wpdb->query($wquery); 
                      } 
                    }
                    else
                    {
                      $warehouse_lists = array();
                      $warehouse = $wpdb->get_results("SELECT name from ".$wpdb->prefix."dv_my_warehouse where created_by='".$username."'");
                      foreach($warehouse as $data)
                      {
                        array_push($warehouse_lists,$data->name);
                      }
                      $accesstoken = 'Bearer '.$output['jwt'];
                      $args = array(
                      'headers' => array(
                          'Authorization' => $accesstoken
                      )
                      );
                      
                      $res = wp_remote_get($warehouse_list,$args);
                      $outputs     = wp_remote_retrieve_body($res);
                      $outputs = json_decode( $outputs, true );
                      $ware_list_array = $outputs['data'];
                      //print_r($outputs);//print_r($ware_list_array); die;
                      foreach($ware_list_array as $ware_listdatas)
                      {
                        $ware_house_name = $ware_listdatas['name'];
                        $ware_house_phone = $ware_listdatas['phone'];
                        $ware_house_city = $ware_listdatas['city'];
                        $ware_house_state = $ware_listdatas['state'];
                        $ware_house_pin = $ware_listdatas['pin'];
                        $ware_house_address = $ware_listdatas['address'];
                        $ware_house_country = $ware_listdatas['country'];
                        $ware_house_contact_person = $ware_listdatas['contact_person'];
                        $ware_house_email = $ware_listdatas['email'];
                        if (!in_array($ware_house_name, $warehouse_lists)) 
                        {
                          
                         $wquery="insert into ".$wpdb->prefix."dv_my_warehouse(name,phone,city,state,pin,address,country,contact_person,email,status,created_by) values('$ware_house_name','$ware_house_phone','$ware_house_city','$ware_house_state','$ware_house_pin','$ware_house_address','$ware_house_country','$ware_house_contact_person','$ware_house_email',1,'$username')";
                             
                         $wpdb->query($wquery); 
                        }
                        
                      } 
                    }
                    
                  $red_url = site_url().'/wp-admin/admin.php?page=my_order';
                  ob_start();
                  header('Location: '.$red_url);
                  ob_end_flush();
                  }
                  
                }
                session_write_close();
                ?>
                  <div class="row m-0 align-items-center">
                     <div class="main-form-container">
                        <form class="form-wrapper login-box" onsubmit = "return(validate('0'));" method="post" enctype="multipart/form-data" id="form-module">
                           <div class="or-block"><p>or</p></div>
                           <div class="row">
                              <h3 class="login-title">Login</h3> 
                              <div class="login-subtitle">Existing delhivery users</div>
                            
                           </div>
                           <div class="row">
							<p>
								This plugin will soon be removed. Delhivery offers a built-in integration using the Unified Client Portal where Woocommerce  orders will be visible automatically once you connect the channel.
							</p>
                            <div class="fill-details">
							
							Upgrade to New Delhivery Client Portal
                            
								
                             <!-- <p style="color:red;">
                  
                                This Plugin works only for our old product <a href="https://cl.delhivery.com">https://cl.delhivery.com</a>. If you have registered on UCP, please use <a href="https://ucp.delhivery.com">https://ucp.delhivery.com</a> directly.
                              </p>-->
                               <p style="color:red;">
                    <a href="https://ucp.delhivery.com/">https://ucp.delhivery.com/</a> to ship orders.
                  <br> If you want to connect your webstore to UCP, please refer to <a href="https://help.delhivery.com/docs/woocommerce.">https://help.delhivery.com/docs/woocommerce</a>.The entire process takes less than 5 minutes
                               
                              </p>

                          </div>
                           </div>
                           
                           <div class="row">
                              <div class="col-md-12">
                                 <div class="form-group">
                                    <label for="username">Username<span
                                       class="span-color">*</span></label>
                                   <input type="name" class="form-control" id="input-username" 
                            name="username" onkeyup="remove_err_msg('uname_err')" 
                            onblur = "return(validate('unm'));" value="<?php echo esc_html($_SESSION['username']); ?>">
                            <div class="form-inp-err" id="uname_err"></div>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-12">
                                 <div class="form-group">
                                    <label for="Password">Password<span class="span-color">*</span></label>
                                    <input type="password" class="form-control" id="input-password" 
                              name="password" onkeyup="remove_err_msg('pass_err')" 
                              value="<?php echo esc_html($_SESSION['pass']); ?>" onblur = "return(validate('psw'));">
                              <div class="form-inp-err" id="pass_err"></div>
                                 </div>
                              </div>
                           </div>
                           <div class="row row-button">
                            <div class="col-md-6">
                              <div class="button-left float-left">
                              <button type="submit" class="btn btn-primary btn-submit" name="save">Login</button>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="forget-password-link">
                              <a href="https://cl.delhivery.com/#/forgot-password" target="_blank">Forgot Password ?</a>
                              </div>
                            </div>
                          </div>
                        </form>
                     </div>
                     <div class="new-user-block">
                        <p class="new-user-title">New to Delhivery?</p>
                        <p class="new-user-subtitle">A few clicks away to start your business with us</h4>
                        <p class="new-user-caption"><a href="https://cl.delhivery.com/?utm_source=shopifysignup&utm_medium=referral&utm_campaign=shopify_newusersignup#/signup" target="_blank">Create an account</a> and you can use the same login details here</p>
                     </div>
                  </div>
                  <div class="row">
                      <div class="col-md-2"></div>
                     <div class="col-md-8">
                     <div class="footer-link">
                        For any queries please reach us at <a href="mailto:integrations@delhivery.com" target="_blank">integrations@delhivery.com</a> 
                     </div>
                  </div>
                  <div class="col-md-2"></div>
                  </div>
               </div>
         </div>
           
            
</body>   
</html>
<?php
wp_enqueue_style( 'bootstrap.min', plugins_url('/css/bootstrap.min.css',__FILE__) );
wp_enqueue_style( 'stylees', plugins_url('/css/custom_styles.css',__FILE__,__FILE__) );
wp_enqueue_script( 'bootstrap.min_js', plugins_url('/js/bootstrap.min.js',__FILE__));
?>
<script>
  function validate($id) 
  {
    if($id=='unm' || $id=='0')
    {
      var username = document.getElementsByName("username")[0].value;
      if( username == "" ) {
        document.getElementById("uname_err").innerHTML = "Enter user name";
        document.getElementsByName("username")[0].focus() ;
        return false;
      }
    }
    if($id=='psw' || $id=='0')
    {
      var password = document.getElementsByName("password")[0].value;
      if( password == "" ) {
        document.getElementById("pass_err").innerHTML = "Enter password";
        document.getElementsByName("password")[0].focus() ;
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