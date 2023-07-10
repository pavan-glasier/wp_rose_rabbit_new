<?php
//session_start();
$red_login_url = site_url().'/wp-admin/admin.php?page=home';
require_once('refresh_token.php');
wp_enqueue_style( 'bootstrap.min', plugins_url('/css/bootstrap.min.css',__FILE__) );
wp_enqueue_style( 'stylees', plugins_url('/css/custom_styles.css',__FILE__) );
wp_enqueue_script( 'bootstrap.min_js', plugins_url('/js/bootstrap.min.js',__FILE__));
?>
<!DOCTYPE html>
<html lang="en">

   <body class="bg-color">
         <div class="main-shopify-wrapper">
            <?php //require_once('menus.php'); ?>
            <div class="container-fluid">
                  
                  <div class="faq-wrapper">
                     <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                           <?php require_once('wallet.php'); ?>
                           <div class="table-top-section">
                              <h1 class="comman-heading">Frequently Asked Question</h1>
                           </div>
                          
                           <div class="row">
                              <div class="col-md-12">
                                 <div class="accordion" id="accordionExample">
                                    <div class="card">
                                       <div class="card-header" id="headingOne">
                                          <a class="faq-accordion collapsed" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                          <i class="fas fa-caret-down glyphicon glyphicon-down"></i><i class="fas fa-caret-up glyphicon glyphicon-up"></i>How do I manage my orders from the Delhivery Plugin?
                                          </a>
                                       </div>
                                       <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                                          <div class="card-body">
                                             Go to My orders tab, you can simply edit, track or cancel orders.Print packaging slips option is also available on the same page.
                                          </div>
                                       </div>
                                    </div>
                                    <div class="card">
                                       <div class="card-header" id="headingTwo">
                                          <a class="faq-accordion collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                          <i class="fas fa-caret-down glyphicon glyphicon-down"></i><i class="fas fa-caret-up glyphicon glyphicon-up"></i>How do I search for my package status?
                                          </a>
                                       </div>
                                       <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                                          <div class="card-body">
                                             You can go to My Orders and click 'Track Order' from Actions tab.
                                             The actions available on the 'Action' button will change. There would be the following "Four" icon visible (Edit, Cancel, Track and Shipping Label). On clicking on 'Cancel', the details of that order will no longer be visible.
                                          </div>
                                       </div>
                                    </div>
                                    <div class="card">
                                       <div class="card-header" id="headingThree">
                                          <a class="faq-accordion collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                          <i class="fas fa-caret-down glyphicon glyphicon-down"></i><i class="fas fa-caret-up glyphicon glyphicon-up"></i>How will I know the estimated cost of my Package?
                                          </a>
                                       </div>
                                       <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                                          <div class="card-body">
                                             Here seller can enter the origin, destination pincode, shipping mode, COD amount, weight (higher of actual or volumetric) and check the Delhivery shipping charges.
                                          </div>
                                       </div>
                                    </div>
                                    <div class="card">
                                       <div class="card-header" id="headingFour">
                                          <a class="faq-accordion collapsed" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                          <i class="fas fa-caret-down glyphicon glyphicon-down"></i><i class="fas fa-caret-up glyphicon glyphicon-up"></i>How do I Create Warehouses?
                                          </a>
                                       </div>
                                       <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordionExample">
                                          <div class="card-body">
                                             Warehouses are the pickup points from where Delhivery would be picking up your shipments. Each seller can have one or more warehouses or pickup points.
                                             From this menu, you can create new warehouses or view your existing warehouses.
                                             Click on "Add New" button to create a new warehouse. Fields which marked with "*" are mandatory and trying to save without the mandatory fields is going to show up the following error.
                                          </div>
                                       </div>
                                    </div>
                                    <div class="card">
                                       <div class="card-header" id="headingFive">
                                          <a class="faq-accordion collapsed" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                          <i class="fas fa-caret-down glyphicon glyphicon-down"></i><i class="fas fa-caret-up glyphicon glyphicon-up"></i>I have created an order. Will it be picked up automatically?
                                          </a>
                                       </div>
                                       <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordionExample">
                                          <div class="card-body">
                                             No,You will have to create a pickup request for Delhivery to assign an executive to pickup your shipments.You can do that through the app.
                                          </div>
                                       </div>
                                    </div>
                                    <div class="card">
                                       <div class="card-header" id="headingSix">
                                          <a class="faq-accordion collapsed" data-toggle="collapse" data-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                          <i class="fas fa-caret-down glyphicon glyphicon-down"></i><i class="fas fa-caret-up glyphicon glyphicon-up"></i>Can I charge different shipping costs to different consignees?
                                          </a>
                                       </div>
                                       <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordionExample">
                                          <div class="card-body">
                                             Yes, you can enter shipping cost for your orders which is to be passed on to the consignee based on your business model.
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
               </div>
            </div>
         </div>
      
     
   </body>
</html>