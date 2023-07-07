<?php
/**
 * Template Name: Custom Cart Template
 */
get_header(); // Include your custom header if needed

do_action( 'woocommerce_before_cart' ); ?>
<!--Start Cart-->
<section class="vs-product-wrapper product-details space-top bg-gradient-1">
    <div class="container-fluid">
        <div class="row gx-60">
            <div class="col-lg-6 backbgcart">
                <div class="container">
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-10">
                            <div class="card1 px-0 pt-4 pb-0 mt-3 mb-3">
                                <h2 id="heading">Contact Information</h2>
                                <form id="msform">
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
                                            <div class="phone-field">
                                                <label class="fieldlabels">Mobile Number:</label>
                                                <input type="number" name="number" placeholder="Enter Mobile Number" />
                                            </div>

                                            <div class="otp-field d-none">
                                                <label class="fieldlabels">OTP:</label>
                                                <div class="otp-flex digit-group" data-group-name="digits" data-autosubmit="false" autocomplete="off">
                                                    <input type="text" id="digit-1" name="digit-1" data-next="digit-2" />
                                                    <input type="text" id="digit-2" name="digit-2" data-next="digit-3" data-previous="digit-1" />
                                                    <input type="text" id="digit-3" name="digit-3" data-next="digit-4" data-previous="digit-2" />
                                                    <span class="splitter">&ndash;</span>
                                                    <input type="text" id="digit-4" name="digit-4" data-next="digit-5" data-previous="digit-3" />
                                                    <input type="text" id="digit-5" name="digit-5" data-next="digit-6" data-previous="digit-4" />
                                                    <input type="text" id="digit-6" name="digit-6" data-previous="digit-5" />
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="vs-btn style7" id="send-otp">Next</button>

                                        <button type="button" class="next vs-btn style7 d-none" name="next"
                                            value="Next">Next</button>
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
                                            <!-- <label class="fieldlabels">Address: </label>
                                            <textarea></textarea>
                                            <label class="fieldlabels">Location: </label>
                                            <input type="text" name="lname" placeholder="" />
                                            <iframe
                                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3672.383112256553!2d72.50348831188215!3d23.009701416719665!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x395e9b19de060de3%3A0x392e95e370777bb3!2sGlasier%20Inc!5e0!3m2!1sen!2sin!4v1683113858049!5m2!1sen!2sin"
                                                width="100%" height="200" style="border:0;" allowfullscreen=""
                                                loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe> -->
                                                <?php global $checkout; 
                                                //print_r( new WC_Checkout() );
                                                ?>                                       
                                            <!-- Step 1: Billing Details -->
<div class="checkout-step" id="step1">
    <h2>Billing Details</h2>
    <?php do_action('woocommerce_checkout_billing'); ?>
    <?php do_action('woocommerce_checkout_shipping'); ?>
    <button class="next-step" data-step="step2">Next</button>
</div>

<!-- Step 2: Shipping Method -->
<div class="checkout-step" id="step2">
    <h2>Shipping Method</h2>
    <?php do_action('woocommerce_checkout_shipping'); ?>
    <button class="prev-step" data-step="step1">Previous</button>
    <button class="next-step" data-step="step3">Next</button>
</div>

<!-- Step 3: Order Review -->
<div class="checkout-step" id="step3">
    <h2>Order Review</h2>
    <?php do_action('woocommerce_checkout_order_review'); ?>
    <button class="prev-step" data-step="step2">Previous</button>
    <?php do_action('woocommerce_checkout_payment'); ?>
    <button type="submit" class="place-order button alt">Place Order</button>
</div>

                                        </div>
                                        <button type="button" class="previous vs-btn style7 mt-5" name="previous"
                                            value="Previous">Previous</button>
                                        <button type="button" class="next vs-btn style7" name="next"
                                            value="Next">Next</button>
                                        <!-- <input type="button" name="next" class="next action-button" value="Next" />
                                 <input type="button" name="previous" class="previous action-button-previous" value="Previous" /> -->
                                    </fieldset>
                                    <fieldset>
                                        <div class="form-card">
                                            <div class="row">
                                                <div class="col-7">
                                                    <h2 class="fs-title">Image Upload:</h2>
                                                </div>
                                                <div class="col-5">
                                                    <h2 class="steps">Step 3 - 3</h2>
                                                </div>
                                            </div>
                                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/cards-2.png"
                                                class="mb-3 mt-3">

                                            <div class="redio-button">
                                                <input type="radio" class="inputwid" name="number"
                                                    placeholder="Enter Card Holder Name" />
                                                <span class="redio-button-span">Online Payment</span>
                                            </div>

                                            <div class="redio-button">
                                                <input type="radio" class="inputwid" name="number"
                                                    placeholder="Enter Card Holder Name" />
                                                <span class="redio-button-span">Cash on Delevriy</span>
                                            </div>
                                        </div>
                                        <button type="button" class="previous vs-btn style7 mt-5" name="previous"
                                            value="previous">previous</button>
                                        <button type="button" class="next vs-btn style7 mb-5" name="next"
                                            value="Submit">Submit</button>

                                        <!-- <input type="button" name="next" class="next action-button" value="Submit" />
                                 <input type="button" name="previous" class="previous action-button-previous" value="Previous" /> -->
                                    </fieldset>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 backbgcart1">
                <form class="woocommerce-cart-form" action="<?php echo esc_url( site_url('cart-page/') ); ?>" method="post">
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

                                <button type="submit"
                                    class="button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>"
                                    name="update_cart"
                                    value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>"><?php esc_html_e( 'Update cart', 'woocommerce' ); ?></button>

                                <?php do_action( 'woocommerce_cart_actions' ); ?>

                                <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
                            </div>

                            <?php do_action( 'woocommerce_after_cart_contents' ); ?>
                        </div>
                    </div>
                    <?php do_action( 'woocommerce_after_cart_table' ); ?>
                </form>
                
            </div>

        </div>
    </div>
</section>
<!--End Cart-->

<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>

<div class="cart-collaterals">
    <?php
		/**
		 * Cart collaterals hook.
		 *
		 * @hooked woocommerce_cross_sell_display
		 * @hooked woocommerce_cart_totals - 10
		 */
		do_action( 'woocommerce_cart_collaterals' );
	?>
</div>

<?php do_action( 'woocommerce_after_cart' ); ?>

<?php get_footer(); // Include your custom footer if needed
