<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Rose_and_Rabbit
 */
?>
<!--Footer-->
<footer class="footer-wrapper footer-layout1">
    <?php if( have_rows('footer_top', 'option') ): ?>
    <?php while( have_rows('footer_top', 'option') ): the_row(); ?>
    <div class="footer-top">
        <div class="container">
            <div class="row align-items-stretch">

                <div class="col-md-4 d-lg-flex">
                    <?php if( have_rows('social_media', 'option') ): ?>
                    <div class="social-style2">
                        <?php while( have_rows('social_media', 'option') ): the_row(); ?>
                        <a href="<?php echo get_sub_field('url'); ?>">
                            <?php echo get_sub_field('icon'); ?>
                        </a>
                        <?php endwhile; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="col-md-5 col-lg-4">
                    <?php if( get_sub_field('footer_logo', 'option') ): ?>
                    <div class="vs-logo">
                        <a href="<?php echo home_url('/'); ?>">
                            <img src="<?php echo get_sub_field('footer_logo', 'option')['url']; ?>"
                                alt="<?php echo get_sub_field('footer_logo', 'option')['alt']; ?>">
                        </a>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="col-md-7 col-lg-4">
                    <?php if( have_rows('newsletter', 'option') ): ?>
                    <?php while( have_rows('newsletter', 'option') ): the_row(); ?>
                    <?php if( !empty( get_sub_field('select_form') ) ): ?>
                    <div class="form-style1">
                        <h3 class="form-title"><?php echo get_sub_field('heading');?></h3>
                        <?php echo do_shortcode('[contact-form-7 id="'.get_sub_field('select_form').'"]'); ?>
                    </div>
                    <?php endif; ?>
                    <?php endwhile; ?>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
    <?php endwhile; ?>
    <?php endif; ?>


    <?php if( have_rows('footer_bottom', 'option') ): ?>
    <div class="widget-area">
        <div class="container">
            <div class="row justify-content-between">
                <?php while( have_rows('footer_bottom', 'option') ): the_row(); ?>
                <div class="<?php echo get_sub_field('column_size');?> col-xl-auto">
                    <div
                        class="widget<?php echo ( !empty( get_sub_field('links') ) )?' widget_nav_menu':''; ?> footer-widget">
                        <?php if( !empty( get_sub_field('heading') ) ): ?>
                        <h3 class="widget_title"><?php echo get_sub_field('heading');?></h3>
                        <?php endif; ?>

                        <?php if( have_rows('contact_info') ): ?>
                        <?php while( have_rows('contact_info') ): the_row(); ?>
                        <?php if( empty(get_sub_field('address')) && empty(get_sub_field('phone')) && empty(get_sub_field('email')) ): ?>
                        <?php else: ?>
                        <p class="footer-info">

                            <?php if( !empty(get_sub_field('address')) ): ?>
                            <i class="fal fa-map-marker-alt text-theme me-2"></i>
                            <?php echo get_sub_field('address');?><br>
                            <?php endif; ?>

                            <?php if( !empty(get_sub_field('phone')) ): ?>
                            <a href="tel:<?php echo get_sub_field('phone');?>" class="text-inherit">
                                <i class="fa fa-phone-alt text-theme me-2"></i>
                                <?php echo get_sub_field('phone');?>
                            </a>
                            <br>
                            <?php endif; ?>

                            <?php if( !empty(get_sub_field('email')) ): ?>
                            <a class="text-inherit" href="mailto:<?php echo get_sub_field('email');?>">
                                <i class="fal fa-envelope text-theme me-2"></i>
                                <?php echo get_sub_field('email');?>
                            </a>
                            <?php endif; ?>

                        </p>

                        <?php endif; ?>
                        <?php endwhile; ?>
                        <?php endif; ?>

                        <?php if ( has_nav_menu( get_sub_field('links') ) ) : ?>
                        <div class="menu-all-pages-container footer-menu">
                            <?php wp_nav_menu(
                              array(
                                 'theme_location' => get_sub_field('links'),
                                 'menu_id'        => 'primary-menu',
                                 'menu_class'     => 'menu',
                                 'container'      => 'ul',
                              )
                           ); ?>
                        </div>
                        <?php endif; ?>

                    </div>
                </div>
                <?php endwhile; ?>

            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="copyright-wrap">
        <div class="container">
            <div class="row justify-content-between align-items-center">
                <div class="col-md-4 text-center">
                    <p class="copyright-text">
                        <?php echo get_theme_mod( 'set_copyright', 'Copyright' );?>
                    </p>
                </div>
                <div class="col-md-4 text-center">
                    <?php if( get_option( 'image_icon_upload' ) ): ?>
                    <img src="<?php echo get_option( 'image_icon_upload', '' );?>">
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</footer>

<?php if( !is_cart() ):?>
<!--Login Model-->
<div id="myModal" class="modal">
    <div class="modal-content">
        <div class="modal-header1">
            <span class="close">&times;</span>
            <h2>Login</h2>
        </div>
        <div class="modal-body woocommerce">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 mt-3 mb-5">
                        <div class="form-card">
                            <div class="alert alert-danger" id="error" style="display: none;"></div>
                            <div class="phone-field">
                                <ul class="woocommerce-message" id="sentSuccess" role="alert" style="display: none;">
                                </ul>
                                <label class="fieldlabels">Mobile Number:</label>
                                <input type="text" name="number" id="phone-number-input"
                                    placeholder="Enter Mobile Number" /><span class="position-relative"></span>
                                <div id="recaptcha-container"></div>
                            </div>
                            <div class="otp-field d-none">
                                <ul class="woocommerce-message" id="successRegsiter" role="alert"
                                    style="display: none;"></ul>
                                <label class="fieldlabels">OTP:</label>
                                <div class="otp-flex digit-group inputfield" data-group-name="digits"
                                    data-autosubmit="false" autocomplete="off">
                                    <input type="number" pattern="[0-9]*" class="input" value="" inputtype="numeric"
                                        autocomplete="one-time-code" id="otp-1" required>
                                    <!-- Autocomplete not to put on other input -->
                                    <input type="number" pattern="[0-9]*" min="0" max="9" maxlength="1" class="input"
                                        value="" inputtype="numeric" id="otc-2" required>
                                    <input type="number" pattern="[0-9]*" min="0" max="9" maxlength="1" class="input"
                                        value="" inputtype="numeric" id="otc-3" required>
                                    <input type="number" pattern="[0-9]*" min="0" max="9" maxlength="1" class="input"
                                        value="" inputtype="numeric" id="otc-4" required>
                                    <input type="number" pattern="[0-9]*" min="0" max="9" maxlength="1" class="input"
                                        value="" inputtype="numeric" id="otc-5" required>
                                    <input type="number" pattern="[0-9]*" min="0" max="9" maxlength="1" class="input"
                                        value="" inputtype="numeric" id="otc-6" required>
                                </div>
                                <input type="hidden" name="verificationCode" id="verificationCode">
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="button" class="vs-btn style7 d-none" id="send-otp"
                                onclick="phoneSendAuth();">Submit</button>
    
                            <button type="button" class="vs-btn style7 d-none" id="otp-verify" name="next"
                                onclick="loginCodeverify();" value="Next">Submit</button>
                        </div>

                        <?php //echo do_shortcode('[wc_login_form_bbloomer]');?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<?php wp_footer(); ?>
</body>

</html>