<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.4.0
 * @global WC_Checkout $checkout
 */

defined( 'ABSPATH' ) || exit;
do_action( 'woocommerce_before_cart' ); 
?>

<!--Start Cart-->
<section class="vs-product-wrapper product-details space-top bg-gradient-1">
    <div class="container-fluid">
        <ul class="woocommerce-error d-none" role="alert"></ul>
        <div class="row gx-60">
            <div class="col-lg-6 backbgcart">
                <div class="container">
                    <div class="row">
                        <?php $checkout = WC()->checkout(); ?>
                        <div class="col-md-2"></div>
                        <div class="col-md-10">
                            <form name="checkout" method="post" class="checkout woocommerce-checkout"
                                enctype="multipart/form-data" novalidate="novalidate">
                                <div class="card1 px-0 pt-4 pb-0 mt-3 mb-3">
                                    <h2 id="heading">Contact Information</h2>
                                    <div id="msform">
                                        <!-- progressbar -->
                                        <ul id="progressbar">
                                            <li class="active" id="account"><strong>1</strong></li>
                                            <li id="personal"><strong>2</strong></li>
                                            <li id="payments"><strong>3</strong></li>
                                        </ul>
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-striped" role="progressbar"
                                                aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <br> <!-- fieldsets -->
                                        <fieldset>
                                            <div class="form-card">
                                                <div class="row">
                                                    <div class="col-7">
                                                        <h2 class="fs-title">Phone Verification</h2>
                                                    </div>
                                                    <div class="col-5">
                                                        <h2 class="steps">Step 1 - 3</h2>
                                                    </div>
                                                </div>
                                                <div class="alert alert-danger" id="error" style="display: none;"></div>
                                                <div class="phone-field">
                                                    <ul class="woocommerce-message" id="sentSuccess" role="alert" style="display: none;"></ul>
                                                    <label class="fieldlabels">Mobile Number:</label>
                                                    <input type="text" name="number" id="phone-number-input" placeholder="Enter Mobile Number" /><span class="position-relative"></span>
                                                    <div id="recaptcha-container"></div>
                                                </div>
                                                <div class="otp-field d-none">
                                                    <ul class="woocommerce-message" id="successRegsiter" role="alert" style="display: none;"></ul>
                                                    <label class="fieldlabels">OTP:</label>
                                                    <div class="otp-flex digit-group inputfield" data-group-name="digits"
                                                        data-autosubmit="false" autocomplete="off">
                                                            <input type="number" pattern="[0-9]*"  class="input" value="" inputtype="numeric" autocomplete="one-time-code" id="otp-1" required>
                                                            <!-- Autocomplete not to put on other input -->
                                                            <input type="number" pattern="[0-9]*" min="0" max="9" maxlength="1"  class="input" value="" inputtype="numeric" id="otc-2" required>
                                                            <input type="number" pattern="[0-9]*" min="0" max="9" maxlength="1"  class="input" value="" inputtype="numeric" id="otc-3" required>
                                                            <input type="number" pattern="[0-9]*" min="0" max="9" maxlength="1"  class="input" value="" inputtype="numeric" id="otc-4" required>
                                                            <input type="number" pattern="[0-9]*" min="0" max="9" maxlength="1"  class="input" value="" inputtype="numeric" id="otc-5" required>
                                                            <input type="number" pattern="[0-9]*" min="0" max="9" maxlength="1"  class="input" value="" inputtype="numeric" id="otc-6" required>
                                                        <!-- </div> -->
                                                        <!-- <button class="hide" id="submit" onclick="validateOTP()">Submit</button> -->
                                                    </div>
                                                    <input type="hidden" name="verificationCode" id="verificationCode">
                                                </div>
                                            </div>
                                            <button type="button" class="vs-btn style7 d-none" id="send-otp" onclick="phoneSendAuth();">Next</button>
                                            
                                            <button type="button" class="vs-btn style7 d-none" id="otp-verify" name="next" onclick="codeverify();" value="Next">Next</button>
                                            <button type="button" class="next vs-btn style7 d-none" name="next" value="Next">Next</button>
                                            <!-- <input type="button" name="next" class="next action-button" value="Next" /> -->

                                        </fieldset>
                                        <fieldset>
                                            <div class="form-card">
                                                <div class="row">
                                                    <div class="col-7">
                                                        <h2 class="fs-title">Personal Information:</h2>
                                                    </div>
                                                    <div class="col-5">
                                                        <h2 class="steps">Step 2 - 3</h2>
                                                    </div>
                                                </div>

                                                <div id="checkout-steps-container">
                                                    <?php do_action('woocommerce_checkout_before_customer_details'); ?>
                                                    <?php do_action('woocommerce_checkout_billing'); ?>
                                                    <?php do_action('woocommerce_checkout_after_customer_details'); ?>
                                                </div>
                                                <?php do_action('woocommerce_checkout_payment'); ?>
                                                <?php do_action('woocommerce_after_checkout_billing_form'); ?>

                                                <div class="woocommerce-privacy-policy-text">
                                                    <?php do_action('woocommerce_checkout_after_customer_details'); ?>
                                                </div>
                                                <input type="hidden" name="register_user_on_order" value="1">
                                            </div>
                                            <!-- <button type="button" class="previous vs-btn style7 mt-5" name="previous"
                                                value="Previous">Previous</button> -->
                                            <button type="button" id="billing_details_submit" class="vs-btn style7"
                                                name="next" value="Next">Next</button>
                                            <button type="button" class="next vs-btn style7 d-none" name="next"
                                                value="Next">Next</button>
                                            <!-- <input type="button" name="next" class="next action-button" value="Next" />
                                    <input type="button" name="previous" class="previous action-button-previous" value="Previous" /> -->
                                        </fieldset>
                                        <fieldset>
                                            <div class="form-card">
                                                <div class="row">
                                                    <div class="col-7">
                                                        <h2 class="fs-title">Order Review:</h2>
                                                    </div>
                                                    <div class="col-5">
                                                        <h2 class="steps">Step 3 - 3</h2>
                                                    </div>
                                                </div>

                                                <?php 
                                                /*
                                                do_action( 'woocommerce_before_cart_collaterals' ); ?>

                                                <div class="cart-collaterals mb-5">
                                                    <?php do_action( 'woocommerce_cart_collaterals' ); ?>
                                                </div>
                                                
                                                <?php 
                                                */
                                                remove_action('woocommerce_checkout_order_review', 'woocommerce_order_review', 10);
                                                echo do_action('woocommerce_checkout_order_review');
                                                // add_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );
                                                ?>


                                            </div>
                                            <button type="button" class="previous vs-btn style7 mt-3" name="previous"
                                                value="previous">previous</button>
                                            <!-- <button type="button" class="next vs-btn style7 mb-5" name="next"
                                                value="Submit">Submit</button> -->
                                            <?php do_action( 'woocommerce_review_order_before_submit' ); ?>

                                            <?php echo apply_filters( 'woocommerce_order_button_html', '<button type="submit" class="vs-btn style7 mt-3 alt' . esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ) . '" name="woocommerce_checkout_place_order" id="place_order" value="Place order" data-value="Place order">Place order</button>' ); // @codingStandardsIgnoreLine ?>

                                            <?php do_action( 'woocommerce_review_order_after_submit' ); ?>

                                            <!-- <input type="button" name="next" class="next action-button" value="Submit" />
                                    <input type="button" name="previous" class="previous action-button-previous" value="Previous" /> -->
                                        </fieldset>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 backbgcart1">
                <form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
                    <?php do_action( 'woocommerce_before_cart_table' ); ?>
                    <div class="row shop_table shop_table_responsive cart woocommerce-cart-form__contents">
                        <div class="col-md-10">
                            <h3 class="widget_title1 mt-5">Cart Item</h3>
                        </div>
                        <?php do_action( 'woocommerce_before_cart_contents' ); ?>
                        <div class="col-md-10 mb-5">
                            <div class="cart-item">
                                <?php woocommerce_cart_items(); ?>
                            </div>
                            <?php do_action( 'woocommerce_cart_contents' ); ?>
                            <div colspan="6" class="actions">

                                <?php if ( wc_coupons_enabled() ) { ?>
                                <div class="coupon">
                                    <label for="coupon_code"
                                        class="screen-reader-text"><?php esc_html_e( 'Coupon:', 'woocommerce' ); ?></label>
                                    <input type="text" name="coupon_code" class="input-text" id="coupon_code" value=""
                                        placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" />
                                    <button type="submit"
                                        class="button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>"
                                        name="apply_coupon"
                                        value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"><?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?></button>
                                    <?php do_action( 'woocommerce_cart_coupon' ); ?>
                                </div>
                                <?php } ?>

                                <?php /*
                                
                                <button type="submit"
                                    class="button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>"
                                name="update_cart"
                                value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>">
                                <?php esc_html_e( 'Update cart', 'woocommerce' ); ?>
                                </button>
                                */ ?>

                                <?php do_action( 'woocommerce_cart_actions' ); ?>

                                <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
                            </div>

                            <?php do_action( 'woocommerce_after_cart_contents' ); ?>
                        </div>
                    </div>
                    <?php do_action( 'woocommerce_after_cart_table' ); ?>
                </form>
              
                <?php do_action( 'woocommerce_before_cart_collaterals' ); ?>

                <div class="cart-collaterals mb-5">
                    <?php
                        do_action( 'woocommerce_cart_collaterals' );
                    ?>
                </div>

            </div>

        </div>
    </div>
</section>
<!--End Cart-->


<?php do_action( 'woocommerce_after_cart' ); ?>