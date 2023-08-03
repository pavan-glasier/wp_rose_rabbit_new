<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Rose_and_Rabbit
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <?php $custom_logo_id = get_theme_mod( 'custom_logo' );
	$image = wp_get_attachment_image_src( $custom_logo_id , 'full' ); ?>
    <!--Mobile Menu-->
    <div class="vs-menu-wrapper">
        <div class="vs-menu-area text-center">
            <button class="vs-menu-toggle"><i class="fal fa-times"></i></button>
            <div class="mobile-logo">
                <?php if( $image ):?>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <img src="<?php echo esc_url( $image[0] ); ?>" loading="lazy" alt="<?php bloginfo( 'name' ); ?>">
                </a>
                <?php endif; ?>
            </div>
            <div class="vs-mobile-menu">
                <ul>
                    <li class="menu-item-has-children">
                        <a href="#">Shop</a>
                        <ul class="sub-menu">
                            <li><a href="product.php">Lorem, ipsum.</a></li>
                            <li><a href="product.php">Lorem, ipsum.</a></li>
                            <li><a href="product.php">Lorem, ipsum.</a></li>
                            <li><a href="product.php">Lorem, ipsum.</a></li>
                            <li><a href="product.php">Lorem, ipsum.</a></li>
                            <li><a href="product.php">Lorem, ipsum.</a></li>
                        </ul>
                    </li>
                    <li class="menu-item-has-children">
                        <a href="#">About Us</a>
                        <ul class="sub-menu">
                            <li><a href="story.php">Our Story</a></li>
                            <li><a href="health-benefits.php">Health Benefits</a></li>
                            <li><a href="blog.php">Blog</a></li>
                            <li><a href="faq.php">FAQ</a></li>
                            <li><a href="#">Contact Us</a></li>
                        </ul>
                    </li>
                    <li><a href="#">Subscriptions</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- header -->

    <?php /* ?>
    <header class="vs-header header-layout2 d-none">
        <div class="sticky-wrap">
            <div class="sticky-active">
                <div class="menu-area">
                    <div class="menu-inner">
                        <div class="container">
                            <div class="row justify-content-between align-items-center gx-60">
                                <!--Start header Menu-->
                                <div class="col-auto">
                                    <nav class="main-menu menu-style1 d-none d-lg-block">
                                        <ul>
                                            <li class="menu-item-has-children mega-menu-wrap">
                                                <a href="#">Shop</a>
                                                <ul class="mega-menu">
                                                    <li>
                                                        <a href="#">Product 1</a>
                                                        <ul>
                                                            <li>
                                                                <a href="product.php">
                                                                    <img
                                                                        src="<?php echo get_template_directory_uri(); ?>/assets/img/menu-product.png">
                                                                    <p style="text-align: center; margin-top: 20px;">
                                                                        Lorem ipsum dolor sit amet.
                                                                    </p>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                    <li>
                                                        <a href="#">Product 2</a>
                                                        <ul>
                                                            <li>
                                                                <a href="product.php">
                                                                    <img
                                                                        src="<?php echo get_template_directory_uri(); ?>/assets/img/menu-product.png">
                                                                    <p style="text-align: center; margin-top: 20px;">
                                                                        Lorem ipsum dolor sit amet.
                                                                    </p>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                    <li>
                                                        <a href="#">Product 3</a>
                                                        <ul>
                                                            <li>
                                                                <a href="product.php">
                                                                    <img
                                                                        src="<?php echo get_template_directory_uri(); ?>/assets/img/menu-product.png">
                                                                    <p style="text-align: center; margin-top: 20px;">
                                                                        Lorem ipsum dolor sit amet.
                                                                    </p>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                    <li>
                                                        <a href="#">Product 4</a>
                                                        <ul>
                                                            <li>
                                                                <a href="product.php">
                                                                    <img
                                                                        src="<?php echo get_template_directory_uri(); ?>/assets/img/menu-product.png">
                                                                    <p style="text-align: center; margin-top: 20px;">
                                                                        Lorem ipsum dolor sit amet.
                                                                    </p>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li class="menu-item-has-children">
                                                <a href="#">About Us</a>
                                                <ul class="sub-menu">
                                                    <li><a href="story.php">Our Story</a></li>
                                                    <li><a href="health-benefits.php">Health Benefits</a></li>
                                                    <li><a href="blog.php">Blog</a></li>
                                                    <li><a href="faq.php">FAQ</a></li>
                                                    <li><a href="#">Contact Us</a></li>
                                                </ul>
                                            </li>
                                            <li><a href="#">Subscriptions</a></li>
                                        </ul>
                                    </nav>
                                </div>
                                <!--Start header Logo-->
                                <div class="col-auto">
                                    <div class="header-logo">
                                        <?php if( $image ):?>
                                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                                            <img src="<?php echo esc_url( $image[0] ); ?>" loading="lazy"
                                                alt="<?php bloginfo( 'name' ); ?>" class="company-logo">
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <!--Start header cart / profile-->
                                <div class="col-auto">
                                    <div class="header-icons">
                                        <button id="myBtn" class="bar-btn d-xl-inline-block">
                                            <i class="far fa-user" aria-hidden="true"></i>
                                            <!-- <img src="<?php echo site_url( '/wp-content/uploads/2023/05/User_alt_light.svg' )?>" alt="profile" width="30"> -->
                                        </button>
                                        <button class="bar-btn sideMenuToggler d-xl-inline-block cart-menu-icon"
                                            cart-item-count="0">
                                            <i class="fa fa-shopping-bag" aria-hidden="true"></i>
                                        </button>
                                        <button class="vs-menu-toggle d-inline-block d-lg-none" type="button">
                                            <i class="fal fa-bars"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <?php */ ?>


    <header class="vs-header header-layout2">
        <div class="sticky-wrap">
            <div class="sticky-active">
                <div class="menu-area">
                    <div class="menu-inner">
                        <div class="container">
                            <div class="row justify-content-between align-items-center gx-60">
                                <!--Start header Menu-->
                                <div class="col-auto">
                                    <nav class="main-menu menu-style1 d-none d-lg-block">
                                    <?php
                                        wp_nav_menu(
                                        array(
                                            'theme_location' => get_theme_mod( 'rose_and_rabbit_header', '0' ),
                                            'menu_id'        => 'primary-menu',
                                            'container'      => 'ul',
                                            'fallback_cb'    => 'WalkerNav::fallback',
                                            'walker'         => new WalkerNav()
                                        )
                                        );
                                    ?>
                                    </nav>
                                </div>
                                <!--Start header Logo-->
                                <div class="col-auto">
                                    <div class="header-logo">
                                        <?php if( $image ):?>
                                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                                            <img src="<?php echo esc_url( $image[0] ); ?>" loading="lazy"
                                                alt="<?php bloginfo( 'name' ); ?>" class="company-logo">
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <!--Start header cart / profile-->
                                <div class="col-auto">
                                    <div class="header-icons">
                                        <?php 
                                        if ( is_user_logged_in() ) { ?>
                                            <a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" class="bar-btn d-xl-inline-block">
                                                <?php
                                                // $theAuthorId = get_the_author_meta('ID');

                                                // echo get_avatar_url($theAuthorId);

                                                // Get the current user object
                                                $current_user = wp_get_current_user();
                                                // Get the user's email
                                                $user_email = $current_user->user_email;
                                                
                                                // Specify the size of the avatar (you can change this to fit your requirements)
                                                $avatar_size = 48;

                                                $args = array(
                                                    'class' => 'border-2 border-bottom border-end border-start border-top p-1 rounded-circle',
                                                    'title' => $current_user->display_name,
                                                );
                                                $alt = $current_user->user_login;
                                                // Get the HTML code for the avatar image
                                                $avatar_image = get_avatar($user_email, $avatar_size, "", $alt, $args);
                                                if( $avatar_image ): 
                                                // Display the avatar image 
                                                echo $avatar_image; ?>
                                                <!-- <img title="user_8269456145" alt="user_8269456145" src="http://2.gravatar.com/avatar/216651f0794c4ca171d118894a1b010f?s=48&amp;d=mm&amp;r=g" srcset="http://2.gravatar.com/avatar/216651f0794c4ca171d118894a1b010f?s=96&amp;d=mm&amp;r=g 2x" class="border-2 border-bottom border-end border-start border-top p-1 rounded-circle" height="48" width="48" loading="lazy" decoding="async"> -->
                                                <?php else: ?>
                                                <?php if( get_option( 'user_icon_upload' ) ): ?>
                                                <img src="<?php echo esc_url( get_option( 'user_icon_upload' ) );?>" alt="profile" width="27">
                                                <?php else: ?>
                                                <img src="<?php echo esc_url(site_url('/wp-content/uploads/2023/05/User_alt_light.svg'));?>" alt="profile" width="35">
                                                <?php endif; ?>
                                                <?php endif; ?>
                                            </a>
                                        <?php } else { ?>
                                            <?php if( is_cart() ){ ?>
                                            <button class="bar-btn d-xl-inline-block" onClick="(function(){jQuery('#phone-number-input').focus();return false;})();return false;">
                                                <img src="<?php echo get_option( 'user_icon_upload', site_url('/wp-content/uploads/2023/05/User_alt_light.svg') );?>" alt="profile" width="30">
                                            </button>
                                            <?php } else{ ?>
                                            <button id="myBtn" class="bar-btn d-xl-inline-block">
                                                <img src="<?php echo get_option( 'user_icon_upload', site_url('/wp-content/uploads/2023/05/User_alt_light.svg') );?>" alt="profile" width="30">
                                            </button>
                                            <?php } ?>
                                        <?php } ?>
                                        <?php echo do_shortcode('[woo_cart_count]'); ?>
                                        <button class="vs-menu-toggle d-inline-block d-lg-none" type="button">
                                            <i class="fal fa-bars"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    