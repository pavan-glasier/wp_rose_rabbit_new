<?php
/**
 * Template Name: Create My Warehouse
 */
  //session_start();
  global $wpdb;
  require_once('refresh_token.php');
  $red_url = site_url().'/wp-admin/admin.php?page=my_warehouse';
  $table_name = $wpdb->prefix . 'dv_my_warehouse';
  if (isset($_SESSION['username']))
  {
    $username = sanitize_text_field($_SESSION['username']);
  }
  $read_only='';
  if(isset($_REQUEST['name']) && sanitize_text_field($_REQUEST['action'])=='edit')
  {
    $read_only = 'readonly';
    if(count($myrows)>0)
    {
      foreach($myrows as $data)
      {
        $gtphone = $data->phone;
        $gtcity = $data->city;
        $gtname = $data->name;
        $gtpin = $data->pin;
        $gtaddress = $data->address;
        $gtcountry = $data->country;
        $gtstate = $data->state;
        $gtcontact_person = $data->contact_person;
        $gtemail = $data->email;
        $gtregistered_name = $data->registered_name;
        $gtreturn_address = $data->return_address;
        $gtreturn_pin = $data->return_pin ;
        $gtreturn_city = $data->return_city;
        $gtreturn_state = $data->return_state;
        $gtreturn_country = $data->return_country;
      }
    }
  }

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
                    <h1 class="comman-heading">Enter New Warehouse Details</h1>
                  </div>
                    
    <?php
    if(isset($_REQUEST['save']))
    {
     
      $phone = sanitize_text_field($_REQUEST['phone']);
      $city = sanitize_text_field($_REQUEST['city']);
      $name = sanitize_text_field($_REQUEST['name']);
      $pincode = sanitize_text_field($_REQUEST['pin']);
      $address = sanitize_text_field($_REQUEST['address']);
      $country = sanitize_text_field($_REQUEST['country']);
      $state = sanitize_text_field($_REQUEST['state']);
      $contact_person = sanitize_text_field($_REQUEST['contact_person']);
      $email = sanitize_email($_REQUEST['email']);
      $registered_name = sanitize_text_field($_REQUEST['registered_name']);
      $return_address = sanitize_text_field($_REQUEST['return_address']);
      $return_pin = sanitize_text_field($_REQUEST['return_pin']);
      $return_city = sanitize_text_field($_REQUEST['return_city']);
      $return_state = sanitize_text_field($_REQUEST['return_state']);
      $return_country = sanitize_text_field($_REQUEST['return_country']);
      $status = 1;
      if($setting_count>0) 
          {
            $accesstoken = 'Bearer '.$auth_token; 
            $create_url = $base_url.'api/backend/clientwarehouse/create/';
            $edit_url = $base_url.'api/backend/clientwarehouse/edit/';
            if(isset($_REQUEST['action'])) 
            {
              $action = sanitize_text_field($_REQUEST['action']);
            }
            else
            {
              $action = '';
            }
            if(isset($_REQUEST['name'])) 
            {
              $name = sanitize_text_field($_REQUEST['name']);
            }
            else
            {
              $name = '';
            }
            if($action=='create')
            {
              $data = array('phone'=> $phone,
                          'city'=> $city,
                          'name'=> $name,
                          'pin'=> $pincode,
                          'address'=> $address,
                          'country'=>$country,
                          'contact_person'=> $contact_person,
                          'email'=>$email,
                          'registered_name'=> $registered_name,
                          'return_address'=> $return_address,
                          'return_pin'=> $return_pin,
                          'return_city'=> $return_city,
                          'return_state'=> $return_state,
                          'return_country'=> $return_country);
            
                    $data_json = json_encode($data);
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
                    $output = wp_remote_retrieve_body($res);
                    $output = json_decode( $output, true );
                  //Insert into log table
                  $create_res_vlaue = json_encode($output);
                  $create_data_header = json_encode($headers);
                  //$logqry = "insert into ".$wpdb->prefix."dv_logs set api_name='create_warehouse',header_value='$create_data_header ' ,request_value='$data_json',url='$create_url',response_value='',order_id=0";
                  //$wpdb->query($logqry);
                  //$last_log_id = $wpdb->insert_id; 
                  //Update log table
                 //$lqry = "update ".$wpdb->prefix."dv_logs set response_value='$create_res_vlaue' where id=$last_log_id";
                 //$wpdb->query($lqry);
                 $finaldata = $output['success']; 
              
                  if($output['error'][0]!='')
                  {
                    $error = $output['error'][0];
                     echo '<div id="woocommerce_errors" class="error"><div class="shopify-error">
                               <img src="images/alert.png" alt=""><pid="err_msg" id="err_msg">'.esc_html($error).'</p></div></div>';
                   

                  }
                  else if($output['detail']!='')
                  {
                    $error = $output['detail'];
                   echo '<div id="woocommerce_errors" class="error"><div class="shopify-error">
                               <img src="images/alert.png" alt=""><pid="err_msg" id="err_msg">'.esc_html($error).'</p></div></div>';

                  }
                  else
                  {
                    $sql = "insert into $table_name set phone='$phone',city='$city',pin='$pincode',name='$name',address='$address',country='$country',state='$state',contact_person='$contact_person',email='$email',registered_name='$registered_name',return_address='$return_address',return_pin='$return_pin',return_city='$return_city',return_state='$return_state',return_country='$return_country',status='$status',created_by='$username'";
                    $wpdb->query($sql);
                    $_SESSION["succmsg"]='Warehouse successfully added!';
                    ob_start();
                    header('Location: '.$red_url);
                    ob_end_flush();
                  }
                }
                else
                {
                  $data = array('phone'=> $phone,
                              'name'=> $name,
                              'pin'=> $pincode,
                              'address'=> $address,
                              'registered_name'=> $registered_name,
                              'return_address'=>$return_address,
                              'return_pin'=>$return_pin,
                              'email'=>$email,
                              'city'=>$city,
                              'state'=>$state,
                              'contact_person'=> $contact_person,
                              'return_city'=> $return_city,
                              'return_state'=> $return_state,
                              'return_country'=> $return_country); 
                  
                  $data_json = json_encode($data);
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
                    $res = wp_remote_post($edit_url,$arg);
                    $output = wp_remote_retrieve_body($res);
                    $output = json_decode( $output, true );
                  //Insert into log table
                  $update_res_value = json_encode($output);
                  $update_data_header = json_encode($headers);
                  //$logqry = "insert into ".$wpdb->prefix."dv_logs set api_name='edit_warehouse',header_value='$update_data_header ' ,request_value='$data_json',url='$edit_url',response_value='',order_id=0";
                  //$wpdb->query($logqry);
                  //$last_log_id = $wpdb->insert_id; 
                  $finaldata = $output['success']; 
                  //echo '<pre>'; print_r($output); 
                  if($output['success']!=1)
                  {
                    if(count($output['error'])>0)
                   {
                    $error = $output['error'];
                    echo '<div id="woocommerce_errors" class="error"><div class="shopify-error">
                               <img src="images/alert.png" alt=""><pid="err_msg" id="err_msg">'.esc_html($error).'</p></div></div>';

                   }
                    else if(count($output['detail'])>0)
                   {
                    $error = $output['detail'];
                    echo '<div id="woocommerce_errors" class="error"><div class="shopify-error">
                               <img src="images/alert.png" alt=""><pid="err_msg" id="err_msg">'.esc_html($error).'</p></div></div>';

                   }
                   else if($output [message]!='')
                   {
                    $error = $output [message];
                    echo '<div id="woocommerce_errors" class="error"><div class="shopify-error">
                               <img src="images/alert.png" alt=""><pid="err_msg" id="err_msg">'.esc_html($error).'</p></div></div>';
                   }
                  }
              
              
                    else
                    {
                     $sql = "update $table_name set phone='$phone',pin='$pincode',name='$name',address='$address',registered_name='$registered_name',country='$country',email='$email',return_address='$return_address',return_pin='$return_pin',return_city='$return_city',return_state='$return_state',return_country='$return_country',contact_person='$contact_person',city='$city',state='$state' where name='$name'"; 
                      $wpdb->query($sql);
                      $_SESSION["succmsg"]='Warehouse successfully updated!';
                      ob_start();
                      header('Location: '.$red_url);
                      ob_end_flush();
                    }
                    //Update log table
                      //$lqry = "update ".$wpdb->prefix."dv_logs set response_value='$update_res_value' where id=$last_log_id";
                      //$wpdb->query($lqry);
                  }
                }
                else
                {
                    $error = 'Please enable settings';
                    echo '<div id="woocommerce_errors" class="error"><div class="shopify-error">
                                 <img src="images/alert.png" alt=""><pid="err_msg" id="err_msg">'.esc_html($error).'</p></div></div>';
                }
                  
          }
    ?>
                <form name = "myForm" onsubmit = "return(validate('0'));" method="post" enctype="multipart/form-data" id="form-module" class="form-wrapper">
                  <div class="row">
                    <div class="col-md-4">
                            <div class="form-group">
                               <label for="Username">User name<span class="span-color">*</span></label>
                               <input type="name" class="form-control" name="name" id="input-name" class="form-control" onkeyup="remove_err_msg('name_err')" value="<?php echo esc_html(@$gtname); ?>"  <?php echo $read_only; ?> onblur = "return(validate('nm'));">
                              <div class="form-inp-err" id="name_err"></div>
                            </div>
                      </div>
                     <div class="col-md-4">
                        <div class="form-group">
                           <label for="Email">Email<span class="span-color">*</span></label>
                           <input type="name" class="form-control" name="email" id="input-email" class="form-control" onkeyup="remove_err_msg('email_err')" value="<?php echo esc_html(@$gtemail); ?>"  onblur = "return(validate('eml'));">
                           <div class="form-inp-err" id="email_err"></div>
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-4">
                        <div class="form-group">
                           <label for="PhoneNumber">Phone Number<span class="span-color">*</span></label>
                           <input type="number" class="form-control" name="phone" id="input-phone" class="form-control" onkeyup="remove_err_msg('phone_err')" value="<?php echo esc_html(@$gtphone); ?>" onblur = "return(validate('phn'));" maxlength="15">
                           <div class="form-inp-err" id="phone_err"></div>
                        </div>
                     </div>
                     <div class="col-md-4">
                        <div class="form-group">
                           <label for="ContactPerson">Contact Person<span class="span-color">*</span></label>
                           <input type="name" class="form-control" name="contact_person" id="input-contact_person" class="form-control" onkeyup="remove_err_msg('contact_person_err')" value="<?php echo esc_html(@$gtcontact_person); ?>"  onblur = "return(validate('c_prsn'));">
                           <div class="form-inp-err" id="contact_person_err"></div>
                        </div>
                     </div>
                  </div>
                  <div class="row">
                        <div class="col-md-4">
                                <div class="form-group">
                                   <label for="EnabledProductionMode">Country<span
                                      class="span-color">*</span></label>
                                    <div class="custom-select">
                                      <select name="country" id="input-country" class="form-control">
                                        <option value="">Select Country</option>
                                        <option value="India" selected>India</option>
                                      </select>
                                    </div>
                                   
                                </div>
                             </div>
                     <div class="col-md-4">
                        <div class="form-group">
                           <label for="State">State<span class="span-color">*</span></label>
                           <input type="name" class="form-control" name="state" id="input-state" class="form-control" onkeyup="remove_err_msg('state_err')" value="<?php echo esc_html(@$gtstate); ?>" onblur = "return(validate('st'));">
                           <div class="form-inp-err" id="state_err"></div>
                        </div>
                     </div>
                  </div>

                  <div class="row">
                        <div class="col-md-4">
                           <div class="form-group">
                              <label for="City">City<span class="span-color">*</span></label>
                              <input type="name" class="form-control" name="city" id="input-city" class="form-control" onkeyup="remove_err_msg('city_err')" value="<?php echo esc_html(@$gtcity); ?>"  onblur = "return(validate('cty'));">
                              <div class="form-inp-err" id="city_err"></div>
                           </div>
                        </div>
                        <div class="col-md-4">
                           <div class="form-group">
                              <label for="Pincode">Pincode<span class="span-color">*</span></label>
                              <input type="number" class="form-control" name="pin" id="input-pin" class="form-control" onkeyup="remove_err_msg('pin_err')" value="<?php echo esc_html(@$gtpin); ?>" onblur = "return(validate('pn'));" maxlength="10">
                              <div class="form-inp-err" id="pin_err"></div>
                           </div>
                        </div>
                  </div>
                     <div class="row">
                            <div class="col-md-8">
                               <div class="form-group">
                                  <label for="Address">Address<span class="span-color">*</span></label>
                                  <input type="name" class="form-control" name="address" id="input-address" class="form-control" onkeyup="remove_err_msg('address_err')" onblur = "return(validate('add'));" value=" <?php echo esc_html(@$gtaddress); ?>">
                                  <div class="form-inp-err" id="address_err"></div>
                               </div>
                            </div>
                           
                         </div>


                         <div class="table-top-section">
                        <h1 class="comman-heading">Enter Return Details</h1>
                        </div>
                         <div class="row">
                                <div class="col-md-4">
                                   <div class="form-group">
                                      <label for="RegisteredName">Registered Name<span class="span-color">*</span></label>
                                      <input type="name" class="form-control" name="registered_name" id="input-registered_name" class="form-control" onkeyup="remove_err_msg('registered_name_err')" value="<?php echo esc_html(@$gtregistered_name); ?>" onblur = "return(validate('r_nm'));">
                                      <div class="form-inp-err" id="registered_name_err"></div>
                                   </div>
                                </div>
                                <div class="col-md-4">
                                        <div class="form-group">
                                           <label for="EnabledProductionMode">Country</label>
                                            <div class="custom-select">
                                            
                                              <select name="return_country" id="return_country" class="form-control">
                                                <option value="">Select Country</option>
                                        
                                                <option value="India" selected>India</option>
                                                 
                                              </select>
                                            </div>
                                            
                                           
                                        </div>
                                     </div>
                             </div>
                             <div class="row">
                                    <div class="col-md-4">
                                       <div class="form-group">
                                          <label for="City">State</label>
                                          <input type="State" class="form-control"  name="return_state" id="return_state" class="form-control" onkeyup="remove_err_msg('return_state_err')" value="<?php echo esc_html(@$gtreturn_city); ?>" onblur = "return(validate('rt_state'));">
                                          <div class="form-inp-err" id="return_state_err"></div>
                                       </div>
                                    </div>
                                    <div class="col-md-4">
                                            <div class="form-group">
                                               <label for="City">City</label>
                                               <input type="name" class="form-control" name="return_city" id="return_city" class="form-control" onkeyup="remove_err_msg('return_city_err')" value="<?php echo esc_html(@$gtreturn_city); ?>" onblur = "return(validate('rt_city'));">
                                              <div class="form-inp-err" id="return_city_err"></div>
                                            </div>
                                         </div>
                              </div>
                                 <div class="row">
                                        <div class="col-md-2">
                                                <div class="form-group">
                                                   <label for="Pincode">Pincode<span class="span-color">*</span></label>
                                                   <input type="number" class="form-control" name="return_pin" id="return_pin" class="form-control" onkeyup="remove_err_msg('return_pin_err')" value="<?php echo esc_html(@$gtreturn_pin); ?>" onblur = "return(validate('rt_pin'));" maxlength="10">
                                                  <div class="form-inp-err" id="return_pin_err"></div>
                                                </div>
                                             </div>
                                        <div class="col-md-6">
                                                <div class="form-group">
                                                        <label for="Address">Address<span class="span-color">*</span></label>
                                                        <input type="name" class="form-control" name="return_address" id="return_address" class="form-control" onkeyup="remove_err_msg('return_address_err')" onblur = "return(validate('rt_add'));" value="<?php echo esc_html(@$gtreturn_address); ?>">
                                                      <div class="form-inp-err" id="return_address_err"></div>
                                                     </div>
                                        </div>
                                     </div>


                  <div class="row">
                     <div class="col-md-8">
                        <div class="button-right float-right">
                          <a href="<?php echo esc_url($red_url); ?>" class="btn-reset" target="_top">back</a>
                          
                           <button type="submit" class="btn btn-primary btn-submit" name="save">Save</button>
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
wp_enqueue_script( 'custom_js', plugins_url('/js/custom.js',__FILE__), array( 'jquery' ), null, true ); ?>
<script type = "text/javascript">
   <!--
      // Form validation code will come here.
      function validate($id) {

        var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
        var pat1=/^([0-9](6,6))+$/;
        var iChars = ";\\#%&";
        var alpha = /^[a-zA-Z-,]+(\s{0,1}[a-zA-Z-, ])*$/
        var mob=/^[+]*[(]{0,1}[0-9]{1,3}[)]{0,1}[-\s\./0-9]*$/g;
        var zip=/^[a-z0-9][a-z0-9\- ]{0,10}[a-z0-9]*$/g;
        var rzip=/^[a-z0-9][a-z0-9\- ]{0,10}[a-z0-9]*$/g;
        if($id=='nm' || $id=='0')
        {
            var name = document.myForm.name.value;
            if( document.myForm.name.value == "" ) {
              document.getElementById("name_err").innerHTML = "Enter name";
              document.myForm.name.focus() ;
              return false;
            }
            for (var i = 0; i < name.length; i++)

            { 
              if (iChars.indexOf(name.charAt(i)) != -1)
              { 
                document.getElementById("name_err").innerHTML = "Name has special characters.\nSpecial characters(\\,;,&,#,%) are not allowed.\n Remove them and try again.";
                document.myForm.name.focus() ;
                return false;
              }
            }
        }
        if($id=='eml' || $id=='0')
        {
            var email = document.myForm.email.value;
            if( document.myForm.email.value == "" ) {
               document.getElementById("email_err").innerHTML = "Enter email";
               document.myForm.email.focus() ;
               return false;
            }
            for (var i = 0; i < email.length; i++)

            { 
              if (iChars.indexOf(email.charAt(i)) != -1)
              { 
                document.getElementById("email_err").innerHTML = "Email has special characters. \nSpecial characters(\\,;,&,#,%) are not allowed.\n Remove them and try again. ";
                document.myForm.email.focus() ;
                return false;
              }
            }
            
            if (reg.test(document.myForm.email.value) == false) 
            {
               document.getElementById("email_err").innerHTML = "Enter valid email";
               document.myForm.email.focus() ;
               return false;
            }
        }
        if($id=='phn' || $id=='0')
        {
          if( document.myForm.phone.value == "" ) {
            document.getElementById("phone_err").innerHTML = "Enter phone no.";
            document.myForm.phone.focus() ;
            return false;
          }
          
          if(isNaN( document.myForm.phone.value )) {
            document.getElementById("phone_err").innerHTML = "Enter digits for phone no.";
            document.myForm.phone.focus() ;
            return false;
          } 
          num= document.myForm.phone.value;
          if (mob.test(num) == false) 
          {
            document.getElementById("phone_err").innerHTML = "Enter valid phone no.";
                document.myForm.phone.focus() ;
            return false;
          }
          if (num.length > 15) 
          {
            //alert("Only 15 characters allowed for Phone Number field");
            document.getElementById("phone_err").innerHTML = "Only 15 characters allowed for phone no field";
                document.myForm.phone.focus() ;
            return false;
          }
          
        }
        if($id=='c_prsn' || $id=='0')
        {
          var contact_person = document.myForm.contact_person.value
          if( document.myForm.contact_person.value == "" ) {
             document.getElementById("contact_person_err").innerHTML = "Enter contact person name";
             document.myForm.contact_person.focus() ;
             return false;
          }

          for (var i = 0; i < contact_person.length; i++)

          { 
            if (iChars.indexOf(contact_person.charAt(i)) != -1)
            { 
              document.getElementById("contact_person_err").innerHTML = "Contact person name has special characters. \n Special characters(\\,;,&,#,%) are not allowed.\n Remove them and try again.";
              document.myForm.contact_person.focus() ;
              return false;
            }
          }
        }
        if($id=='cnt' || $id=='0')
        {  
          if( document.myForm.country.value == "" ) {
             document.getElementById("country_err").innerHTML = "Enter country";
             document.myForm.country.focus() ;
             return false;
          }
          /*if (!alpha.test(document.myForm.country.value)) {
             document.getElementById("country_err").innerHTML = "Please enter only characters for country!";
            document.myForm.country.focus() ;
            return false;
          }*/
          var country = document.myForm.country.value
          for (var i = 0; i < country.length; i++)

          { 
            if (iChars.indexOf(country.charAt(i)) != -1)
            { 
              document.getElementById("country_err").innerHTML = "Country has special characters. \n Special characters(\\,;,&,#,%) are not allowed.\n Remove them and try again.";
              document.myForm.country.focus() ;
              return false;
            }
          }
        }
        if($id=='st' || $id=='0')
        {
          if( document.myForm.state.value == "" ) {
             document.getElementById("state_err").innerHTML = "Enter state";
             document.myForm.state.focus() ;
             return false;
          }
          /*if (!alpha.test(document.myForm.state.value)) {
             document.getElementById("state_err").innerHTML = "Please enter only characters for state!";
            document.myForm.state.focus() ;
            return false;
          }*/
          var state = document.myForm.state.value
          for (var i = 0; i < state.length; i++)

          { 
            if (iChars.indexOf(state.charAt(i)) != -1)
            { 
              document.getElementById("state_err").innerHTML = "State has special characters. \n Special characters(\\,;,&,#,%) are not allowed.\n Remove them and try again.";
              document.myForm.state.focus() ;
              return false;
            }
          }
        }
        if($id=='cty' || $id=='0')
        {  

            var city = document.myForm.city.value;

            if( document.myForm.city.value == "" ) {
              document.getElementById("city_err").innerHTML = "Enter city";
              document.myForm.city.focus() ;
              return false;
            }
            /*if (!alpha.test(document.myForm.city.value)) {
               document.getElementById("city_err").innerHTML = "Please enter only characters for city!";
              document.myForm.city.focus() ;
              return false;
            }*/
            for (var i = 0; i < city.length; i++)

            { 
              if (iChars.indexOf(city.charAt(i)) != -1)
              { 
                document.getElementById("city_err").innerHTML = "City has special characters. \nSpecial characters(\\,;,&,#,%) are not allowed.\n Remove them and try again.";
                document.myForm.city.focus() ;
                return false; 
              }
            }
        }
          
        if($id=='pn' || $id=='0')
        {
          pin= document.myForm.pin.value;
          if( document.myForm.pin.value == "" ) 
          {
            document.getElementById("pin_err").innerHTML = "Enter pincode";
            document.myForm.pin.focus() ;
            return false;
          }
         
          if (zip.test(pin) == false) 
          {
              document.getElementById("pin_err").innerHTML = "Enter valid pincode";
              document.myForm.pin.focus() ;
              return false;
          }
            if ((document.myForm.pin.value).length >10)  {
              document.getElementById("pin_err").innerHTML = "Pincode should not be greater than 10 characters";
              document.myForm.pin.focus() ;
              return false;
            }
        }
        if($id=='add' || $id=='0')
        { 
          var address = document.getElementById('input-address').value;
          if(document.getElementById('input-address').value == '') 
          {      
            document.getElementById("address_err").innerHTML = "Enter address";
            document.getElementById('input-address').focus();
            return false;      
          }
          for (var i = 0; i < address.length; i++)

          { 
            if (iChars.indexOf(address.charAt(i)) != -1)
            { 
              document.getElementById("address_err").innerHTML = "Address has special characters. \n Special characters(\\,;,&,#,%) are not allowed.\n Remove them and try again.";
              document.getElementById('input-address').focus() ;
              return false;
            }
          }
        }
       
        if($id=='r_nm' || $id=='0')
        {
            if( document.myForm.registered_name.value == "" ) {
             document.getElementById("registered_name_err").innerHTML = "Enter registered name";
             document.myForm.registered_name.focus() ;
             return false;
            }
            var registered_name = document.myForm.registered_name.value 
            for (var i = 0; i < registered_name.length; i++)

            { 
              if (iChars.indexOf(registered_name.charAt(i)) != -1)
              { 
                document.getElementById("registered_name_err").innerHTML = "Registered name has special characters. \nSpecial characters(\\,;,&,#,%) are not allowed.\n  Remove them and try again.";
                document.myForm.registered_name.focus() ;
                return false;
              }
            } 
        }
        if($id=='rt_state' || $id=='0')
        {
          if( document.myForm.return_state.value != "" ) 
          {
            var ret_state = document.myForm.return_state.value
            for (var i = 0; i < ret_state.length; i++)

            { 
              if (iChars.indexOf(ret_state.charAt(i)) != -1)
              { 
                document.getElementById("return_state_err").innerHTML = "Return State has special characters. \n Special characters(\\,;,&,#,%) are not allowed.\n Remove them and try again.";
                document.myForm.return_state.focus() ;
                return false;
              }
          }
          }
        }
        if($id=='rt_city' || $id=='0')
        {
          if( document.myForm.return_city.value != "" ) 
          {
            var ret_city = document.myForm.return_city.value
            for (var i = 0; i < ret_city.length; i++)

            { 
              if (iChars.indexOf(ret_city.charAt(i)) != -1)
              { 
                document.getElementById("return_city_err").innerHTML = "Return City has special characters. \n Special characters(\\,;,&,#,%) are not allowed.\n Remove them and try again.";
                document.myForm.return_city.focus() ;
                return false;
              }
          }
          }
        }
        if($id=='rt_pin' || $id=='0')
        {
          if( document.myForm.return_pin.value == "" ) {
             document.getElementById("return_pin_err").innerHTML = "Enter return pin";
             document.myForm.return_pin.focus() ;
             return false;
          }

          rtpin= document.myForm.return_pin.value;
          //alert(zip.test(rtpin));
          if (rzip.test(rtpin) == false) 
          {
            document.getElementById("return_pin_err").innerHTML = "Enter valid return pincode";
            document.myForm.return_pin.focus() ;
            return false;
          }
          if ((document.myForm.return_pin.value).length >10)  
          {
                  document.getElementById("return_pin_err").innerHTML = "Return pincode should not be greater than 10 characters!";
                  document.myForm.return_pin.focus() ;
                  return false;
          }
        }
        
        if($id=='rt_add' || $id=='0')
        {
          if( document.myForm.return_address.value == "" ) {
             document.getElementById("return_address_err").innerHTML = "Enter return address";
             document.myForm.return_address.focus() ;
             return false;
          }
          var ret_add = document.myForm.return_address.value
          for (var i = 0; i < ret_add.length; i++)

          { 
            if (iChars.indexOf(ret_add.charAt(i)) != -1)
            { 
              document.getElementById("return_address_err").innerHTML = "Return address has special characters. \n Special characters(\\,;,&,#,%) are not allowed.\n Remove them and try again.";
              document.myForm.return_address.focus() ;
              return false;
            }
          }
        }
        
        return( true );
      }
      function remove_err_msg(id)
      {
        document.getElementById(id).innerHTML = "";
      }
</script>