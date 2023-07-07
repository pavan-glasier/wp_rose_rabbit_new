<?php
/**
 * Template Name: Home
 *
 */

 get_header(); ?>

<?php if( have_rows('sections') ): ?>
<main>
    <?php while( have_rows('sections') ): the_row(); ?>
    <?php if( get_row_layout() == 'banner_section'): ?>
    <!--Section 1 -->
    <section class="hero-layout2">
        <?php if( have_rows('animated_images_left') ): ?>
        <?php while( have_rows('animated_images_left') ): the_row(); ?>
        <?php if( get_sub_field('shape_1') ): ?>
        <div class="hero-shape-4 rotate-reverse-img" data-top="37%" data-left="46%">
            <img src="<?php echo get_sub_field('shape_1')['url']; ?>" alt="<?php echo get_sub_field('shape_1')['alt']; ?>">
        </div>
        <?php endif; ?>
        <?php if( get_sub_field('shape_2') ): ?>
        <div class="hero-shape-1 jump" data-bottom="12%" data-left="4%">
            <img src="<?php echo get_sub_field('shape_2')['url']; ?>" alt="<?php echo get_sub_field('shape_2')['alt']; ?>">
        </div>
        <?php endif; ?>

        <?php endwhile; ?>
        <?php endif; ?>

        <?php if( have_rows('animated_images_right') ): ?>
        <?php while( have_rows('animated_images_right') ): the_row(); ?>

        
        <?php if( get_sub_field('shape_1') ): ?>
        <div class="hero-shape-3 jump" data-right="2%" data-top="17%">
            <img src="<?php echo get_sub_field('shape_1')['url']; ?>" alt="<?php echo get_sub_field('shape_1')['alt']; ?>">
        </div>
        <?php endif; ?>

        <?php if( get_sub_field('shape_2') ): ?>
        <div class="hero-shape-2 jump-reverse" data-right="43%" data-top="9%">
            <img src="<?php echo get_sub_field('shape_2')['url']; ?>" alt="<?php echo get_sub_field('shape_2')['alt']; ?>">
        </div>
        <?php endif; ?>

        <?php endwhile; ?>
        <?php endif; ?>

        <div class="hero-shape-5"></div>

        <?php if( get_sub_field('main_image') ): ?>
        <div class="hero-inner">
            <div class="hero-img">
                <img src="<?php echo get_sub_field('main_image')['url']; ?>" alt="<?php echo get_sub_field('main_image')['alt']; ?>"
                    id="banner-img">
                <img src="<?php echo get_sub_field('main_image')['url']; ?>" alt="<?php echo get_sub_field('main_image')['alt']; ?>"
                    id="banner-img1">
                <div class="hero-ripple"><i class="ripple"></i><i class="ripple"></i></div>
            </div>
        </div>
        <?php endif; ?>

    </section>
    <?php endif; ?>
    

    <?php if( get_row_layout() == 'about_section'): ?>
    <!-- Section 2 -->
    <section class="overflow-hidden space-extra-bottom">
        <?php if( !empty( get_sub_field('marquee') ) ): ?>
        <marquee class="section-taglin">
            <?php echo get_sub_field('marquee'); ?>
        </marquee>
        <?php endif; ?>

        <div class="container space-top">
            <div class="row gx-55">
                <div class="col-lg-5 col-xxl-auto align-self-center">
                    <?php if( have_rows('image_group') ): ?>
                    <?php while( have_rows('image_group') ): the_row(); ?>
                    <div class="px-xxl-4 mx-xxl-3 pb-md-4 pb-lg-0">
                        <div class="img-box3">
                            <div class="shape-line">
                                <svg viewBox="0 0 442 357">
                                    <path class="shape-line"
                                        d="M220.6 3C339.98 3 437.1 100.12 437.1 219.5V351.99H440.1V219.5C440.1 160.87 417.27 105.75 375.81 64.29C334.35 22.83 279.23 0 220.6 0C161.97 0 106.85 22.83 65.39 64.29C28.67 101.01 6.57 148.46 2 199.56H5.02C15.12 89.5 107.94 3 220.6 3Z" />
                                    <path class="shape-dot"
                                        d="M7 198.5C7 200.433 5.433 202 3.5 202C1.567 202 0 200.433 0 198.5C0 196.567 1.567 195 3.5 195C5.433 195 7 196.567 7 198.5Z" />
                                    <path class="shape-dot"
                                        d="M442 353.5C442 355.433 440.433 357 438.5 357C436.567 357 435 355.433 435 353.5C435 351.567 436.567 350 438.5 350C440.433 350 442 351.567 442 353.5Z" />
                                </svg>
                            </div>
                            <div class="text-shape">
                                <svg viewBox="0 0 408 579">
                                    <path id="textboxpath"
                                        d="M0 204C0 91.3339 91.3339 0 204 0V0C316.666 0 408 91.3339 408 204V316.879V375C408 487.666 316.666 579 204 579V579C91.3339 579 0 487.666 0 375V204Z">
                                    </path>
                                    <text>
                                        <textPath href="#textboxpath" startOffset="888"><?php echo get_sub_field('text'); ?></textPath>
                                    </text>
                                </svg>
                            </div>
                            <?php if( get_sub_field('image') ): ?>
                            <div class="img-1">
                                <img src="<?php echo get_sub_field('image')['url']; ?>" alt="<?php echo get_sub_field('image')['alt']; ?>">
                            </div>
                            <?php endif; ?>
                            <div class="img-2 jump-img">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/banner-leaf-4.png"
                                    alt="about">
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                    <?php endif; ?>
                </div>
                <div class="col-lg-7 col-xxl align-self-center">
                    <?php if( have_rows('content_group') ): ?>
                    <?php while( have_rows('content_group') ): the_row(); ?>
                    <div class="section-1img">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/banner-leaf-2.png" class="section1-img-top">
                    </div>
                    <span class="sec-subtitle">
                        <?php echo get_sub_field('tag_line');?>
                        <?php if( !empty( get_sub_field('badge') ) ):?>
                        <span class="sec-subtext"><?php echo get_sub_field('badge');?></span>
                        <?php endif; ?>
                    </span>

                    <?php if( !empty( get_sub_field('heading') ) ):?>
                    <h2 class="sec-title2 home-heading">
                    <?php echo get_sub_field('heading');?>
                    </h2>
                    <?php endif; ?>

                    <?php if( !empty( get_sub_field('quote') ) ): ?>
                    <p class="quote-text"><?php echo get_sub_field('quote');?></p>
                    <?php endif; ?>

                    <?php if( !empty( get_sub_field('contents') ) ):?>
                    <div class="about-text">
                    <?php echo get_sub_field('contents');?>
                    </div>
                    <?php endif; ?>

                    <?php endwhile; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    
    <?php if( get_row_layout() == 'specification_section'): ?>
    <!-- Section 3 -->
    <section class="space-top space-extra-bottom1 bg-gradient-1 z-index-common">
        <div class="container">
            <?php if( !empty( get_sub_field('heading') ) ): ?>
            <div class="title-area text-center">
                <?php if( !empty( get_sub_field('tag_line') ) ): ?>
                <span class="sec-subtitle"><?php echo get_sub_field('tag_line'); ?></span>
                <?php endif; ?>

                <?php if( !empty( get_sub_field('heading') ) ): ?>
                <h2 class="sec-title sec__font"><?php echo get_sub_field('heading'); ?></h2>
                <?php endif; ?>

                <div class="sec-shape">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/sec-shape-1.png" alt="shape" class="section3-img">
                </div>
            </div>
            <?php endif; ?>

            <?php if( have_rows('specification') ): ?>
            <div class="row">
                <?php while( have_rows('specification') ): the_row(); ?>
                <div class="col-lg-4 col-xl-4">
                    <div class="feature-style2">
                        <div class="vs-icon style2">
                            <?php if( !empty( get_sub_field('img')['url'] ) ): ?>
                            <img src="<?php echo esc_url( get_sub_field('img')['url'] ); ?>" alt="<?php echo esc_attr( get_sub_field('img')['alt'] ); ?>">
                            <?php endif; ?>
                        </div>

                        <h3 class="feature-title h4"><?php echo get_sub_field('title'); ?></h3>
                        <div class="arrow-shape">
                            <i class="arrow"></i>
                            <i class="arrow"></i>
                            <i class="arrow"></i>
                            <i class="arrow"></i>
                        </div>
                        <p class="feature-text">
                            <?php echo get_sub_field('content'); ?>
                        </p>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            <?php endif; ?>

            <?php if( !empty( get_sub_field('bottom_text') ) ): ?>
            <div class="title-area text-center mt-5">
                <h2 class="sec-title sec__font">
                    <?php echo get_sub_field('bottom_text');?>
                </h2>
                <div class="sec-shape">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/sec-shape-1.png" alt="shape"
                        class="section3-img">
                </div>
            </div>
            <?php endif; ?>

        </div>
    </section>
    <?php endif; ?>

    <?php if( get_row_layout() == 'video_section'): ?>
    <section>
        <?php if( !empty( get_sub_field('url') ) ): ?>
        <video playsinline="" autoplay="" muted="" loop="" width="100%">
            <source src="<?php echo get_sub_field('url'); ?>" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <?php endif; ?>

        <?php if( !empty( get_sub_field('upload_video')['url'] ) ): ?>
        <video playsinline="" autoplay="" muted="" loop="" width="100%">
            <source src="<?php echo esc_url( get_sub_field('upload_video')['url'] ); ?>" type="<?php echo esc_attr( get_sub_field('upload_video')['mime_type'] ); ?>">
            Your browser does not support the video tag.
        </video>
        <?php endif; ?>

        <?php if( !empty( get_sub_field('marquee_text') ) ): ?>
        <marquee class="section-taglin">
            <?php echo get_sub_field('marquee_text'); ?>
        </marquee>
        <?php endif; ?>

    </section>
    <?php endif; ?>


    <?php if( get_row_layout() == 'product_section'): ?>
    <?php if( get_sub_field( 'display_products' ) ): ?>
    <!--Section 4-->
    <?php  
    $productArgs = array(
        'post_type'      => 'product',
        'posts_per_page' => 3,
    );
    $productLoop = new WP_Query( $productArgs );
    if($productLoop->have_posts()): ?>
    <section class="overflow-hidden space-extra-bottom mt-3">
        <div class="container">
            <div class="row">
                <?php while ( $productLoop->have_posts() ) : $productLoop->the_post();
                global $product;?>
                <div class="col-xl-4">
                    <div class="vs-blog blog-style1">
                        <div class="blog-img">
                            <a href="<?php echo get_permalink();?>">
                                <?php echo woocommerce_get_product_thumbnail('woocommerce_single', ['class' => 'w-100']); ?>
                            </a>
                        </div>
                        <div class="blog-content text-center">
                            <h3 class="blog-title h5">
                                <a href="<?php echo get_permalink();?>">
                                <?php echo get_the_title(); ?>
                                </a>
                            </h3>
                        </div>
                    </div>
                    <div class="package-btn">
                        <a href="<?php echo $product->add_to_cart_url();?>" data-quantity="1" data-product_id="<?php echo $product->get_id();?>" data-product_sku="" class="vs-btn style3 product_type_simple add_to_cart_button ajax_add_to_cart" aria-label="Add “<?php echo get_the_title(); ?>” to your cart" rel="nofollow">Add to cart</a>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>
    <?php wp_reset_query();?>
    <?php endif; ?>

    <?php endif; ?>
    <?php endif; ?>


    <?php if( get_row_layout() == 'call_to_action'): ?>
        <?php $default = get_template_directory_uri().'/assets/img/section4-banner.jpg';?>
        <?php $banner = get_sub_field('banner');?>
    <!-- Section 5 -->
    <section class="text-center space background-image" style="background-image: url('<?php echo $banner?$banner['url']:$default; ?>');background-repeat: no-repeat; background-size: cover;">
        <div class="container">
            <div class="row">
                <div class="col-xl-4"></div>
                <div class="col-xl-8">
                    <?php if( !empty(get_sub_field('content_group')['content']) ): ?>
                    <h2 class="sec-title1 text-uppercase mb-4 pb-2">
                        <?php echo get_sub_field('content_group')['content']; ?>
                    </h2>
                    <?php endif; ?>

                    <?php $link_button = get_sub_field('content_group')['link_button']; ?>
                    <?php if( $link_button ): ?>
                    <div class="d-inline-flex flex-wrap justify-content-center gap-3 align-items-center">
                        <a href="<?php echo esc_url( $link_button['url'] );?>" class="vs-btn style7" target="<?php echo $link_button['target']?$link_button['target']:'_self';?>" ><?php echo esc_html( $link_button['title'] );?></a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>


    <?php if( get_row_layout() == 'clients_review_section'): ?>
    <?php if( get_sub_field( 'display_reviews' ) ): ?>
    <!-- Section 6 -->
    <section class="space-top space-extra-bottom bg-gradient-1">
        <div class="container">
            <?php if( empty(get_sub_field('heading')) && empty(get_sub_field('description')) ): ?>
            <?php else: ?>
            <div class="row justify-content-center text-center">
                <div class="col-md-9 col-lg-7 col-xl-6">
                    <div class="title-area">
                        <?php if( !empty(get_sub_field('heading')) ): ?>
                        <h2 class="sec-title4"><?php echo get_sub_field('heading'); ?></h2>
                        <?php endif; ?>

                        <?php if( !empty(get_sub_field('description')) ): ?>
                        <p class="sec-text">
                            <?php echo get_sub_field('description'); ?>
                        </p>
                        <?php endif; ?>

                        <div class="sec-shape">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/sec-shape-1.png"
                                alt="shape">
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php  
            $clientArgs = array(
                'post_type'      => 'clients_review',
                'posts_per_page' => 3,
            );
            $clientLoop = new WP_Query( $clientArgs );
            if($clientLoop->have_posts()): ?>
            <div class="row testimonial-slider">
                <?php while ( $clientLoop->have_posts() ) : $clientLoop->the_post(); ?>
                <div class="col-lg-4">
                    <div class="testi-style3">
                        <span class="testi-icon">
                            <i class="fas fa-quote-right"></i>
                        </span>
                        <?php if( !empty( get_field('profile_image', get_the_ID())['url'] ) ): ?>
                        <div class="testi-avater">
                            <img src="<?php echo esc_url(get_field('profile_image', get_the_ID())['url']); ?>"
                                alt="<?php echo esc_attr(get_field('profile_image', get_the_ID())['alt']); ?>">
                        </div>
                        <?php endif; ?>
                        
                        <div class="testi-content">
                            <?php if( !empty( get_field('rating', get_the_ID()) ) ): ?>
                            <div class="testi-rating">
                                <?php echo get_field('rating', get_the_ID()); ?>
                            </div>
                            <?php endif; ?>

                            <?php if( !empty( get_field('reviews', get_the_ID()) ) ): ?>
                            <p class="testi-text">
                                <?php echo get_field('reviews', get_the_ID()); ?>
                            </p>
                            <?php endif; ?>

                            <?php if( !empty( get_field('name', get_the_ID()) ) ): ?>
                            <p class="testi-name"><?php echo get_field('name', get_the_ID()); ?></p>
                            <?php endif; ?>

                            <?php if( !empty( get_field('designation', get_the_ID()) ) ): ?>
                            <span class="testi-degi"><?php echo get_field('designation', get_the_ID()); ?></span>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            <?php wp_reset_query();?>
            <?php endif; ?>
        </div>
    </section>
    <?php endif; ?>
    <?php endif; ?>

    <?php if( get_row_layout() == 'blog_section'): ?>
    <?php if( get_sub_field( 'display_blogs' ) ): ?>
    <!--Section 7-->
    <section class="overflow-hidden space-extra-bottom">
        <div class="container">
            <?php if( empty(get_sub_field('heading')) && empty(get_sub_field('description')) ): ?>
            <?php else: ?>
            <div class="row justify-content-center text-center">
                <div class="col-md-9 col-lg-7 col-xl-6">
                    <div class="title-area">
                        <?php if( !empty(get_sub_field('heading')) ): ?>
                        <h2 class="sec-title4"><?php echo get_sub_field('heading'); ?></h2>
                        <?php endif; ?>

                        <?php if( !empty(get_sub_field('description')) ): ?>
                        <p class="sec-text">
                            <?php echo get_sub_field('description'); ?>
                        </p>
                        <?php endif; ?>
                        <div class="sec-shape">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/sec-shape-1.png"
                                alt="shape">
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php  
            $blogsArgs = array(
                'post_type'      => 'post',
                'posts_per_page' => 3,
            );
            $blogsLoop = new WP_Query( $blogsArgs );
            if($blogsLoop->have_posts()): ?>
            <div class="row">
            <?php while ( $blogsLoop->have_posts() ) : $blogsLoop->the_post(); ?>
                <div class="col-xl-4">
                    <div class="vs-blog blog-style1">
                        <div class="blog-img">
                            <a href="<?php the_permalink();?>">
                                <?php echo do_shortcode('[featured_image]');?>
                            </a>
                        </div>
                        <div class="blog-content">
                            <h3 class="blog-title h5">
                                <a href="<?php the_permalink();?>"><?php echo the_title();?></a>
                            </h3>
                            <div class="blog-meta">
                                <a href="#"><?php the_author();?></a>
                                <a href="#"><?php echo get_the_date( 'd M, Y', $post->ID ); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
            </div>
            <?php wp_reset_query();?>
            <?php endif; ?>
        </div>
    </section>
    <?php endif; ?>
    <?php endif; ?>


    <?php if( get_row_layout() == 'process_section'): ?>
    <?php if( have_rows('boxes') ): ?>
    <!-- Section 8 -->
    <section class="tf__work_2 xs_mt_65">
        <div class="tf__work_2_area">
            <div class="tf__work_2_area_overlay xs_pt_70 pb_120 xs_pb_30">
                <div class="container">
                    <div class="row">
                        <?php while( have_rows('boxes') ): the_row(); ?>
                        <div class="col-xl-4 col-sm-6 col-md-4">
                            <div class="tf__work_single_2">
                                <p class="bg-gradient-1">
                                    <!-- <i class="fa-solid fa-truck-fast"></i> -->
                                    <?php if( !empty( get_sub_field('icon')['url'] ) ): ?>
                                    <img src="<?php echo get_sub_field('icon')['url'];?>" alt="<?php echo get_sub_field('icon')['alt'];?>"> 
                                    <?php endif; ?>
                                    <span><?php echo get_row_index(); ?></span>
                                </p>
                                <h4><?php echo get_sub_field('title')?></h4>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>
    <?php endif; ?>

    <?php endwhile; ?>
</main>
<?php endif; ?>
<?php get_footer(); ?>