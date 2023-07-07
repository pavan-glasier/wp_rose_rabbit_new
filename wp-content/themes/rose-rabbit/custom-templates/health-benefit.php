<?php
/**
 * Template Name: Health Benefits
 * 
 */
get_header(); ?>

<main>
    <div class="space-top space-extra-bottom1 bg-gradient-1">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-ms-12">
                    <div>
                        <h1 class="breadcumb-title story__tital"><?php echo wp_title('');?></h1>
                        <span class="breadcumb-titlespan">
                            <?php echo get_post_field( 'post_content', get_queried_object_id() ); ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if( have_rows('sections') ): ?>

    <?php while( have_rows('sections') ): the_row(); ?>
    <?php if( get_row_layout() == 'benefit_section'): ?>
    <section class="tf_about_2 mt_120 xs_mt_70">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-sm-8">
                    <?php $img_group = get_sub_field('image_group'); ?>
                    <?php if( $img_group ): ?>
                    <div class="tf_about_2_img">
                        <div class="img_1">
                            <img src="<?php echo $img_group['image_1']['url']; ?>"
                                alt="<?php echo $img_group['image_1']['url']; ?>" class="img-fluid w-100">
                        </div>
                        <div class="img_2">
                            <img src="<?php echo $img_group['image_2']['url']; ?>"
                                alt="<?php echo $img_group['image_2']['url']; ?>" class="img-fluid w-100">
                        </div>
                        <?php if( !empty( $img_group['text'] ) ): ?>
                        <p><?php echo $img_group['text'];?></p>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="col-lg-6">
                    <?php if( have_rows('contact_group') ): ?>
                    <?php while( have_rows('contact_group') ): the_row(); ?>
                    <?php if( !empty( get_sub_field('heading') ) ): ?>
                    <div class="tf__section_heading_2 tf__heading_left_2 mb_10">
                        <h3 class="benifith3"><?php echo get_sub_field('heading'); ?></h3>
                    </div>
                    <?php endif; ?>
                    <div class="tf_about_2_text">
                        <p class="about-text"><?php echo get_sub_field('description'); ?></p>
                        <?php if( have_rows('points') ): ?>
                        <ul class="mt_5">
                            <?php while( have_rows('points') ): the_row(); ?>
                            <li><?php echo get_sub_field('point'); ?></li>
                            <?php endwhile; ?>
                        </ul>
                        <?php endif; ?>
                    </div>
                    <?php endwhile; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>


    <?php if( get_row_layout() == 'featured_section'): ?>
    <section class="space space-helth">
        <?php if( empty( get_sub_field('heading') ) && empty( get_sub_field('tag_line') ) ): ?>
        <?php else: ?>
        <div class="title-area text-center">
            <?php if( !empty( get_sub_field('tag_line') ) ): ?>
            <span class="sec-subtitle"><?php echo get_sub_field('tag_line');?></span>
            <?php endif; ?>

            <?php if( !empty( get_sub_field('heading') ) ): ?>
            <h2 class="sec-title"><?php echo get_sub_field('heading');?></h2>
            <?php endif; ?>

            <div class="sec-shape mb-5 pb-1">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/sec-shape-1.png" alt="shape">
            </div>
        </div>
        <?php endif; ?>
        <div class="service-inner1 shape-mockup-wrap">
            <div class="shape-mockup jump d-none d-xxl-block serviceblof">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/banner-leaf-2.png" alt="shape">
            </div>
            <div class="container-xl">
                <div class="row justify-content-between align-items-center">

                    <?php if( have_rows('feature_left') ): ?>
                    <div class="col-md-6 col-lg-5 col-xxl-auto">
                        <?php while( have_rows('feature_left') ): the_row(); ?>
                        <div class="service-style1 reverse">
                            <?php if( !empty(get_sub_field('icon')['url']) ): ?>
                            <div class="vs-icon">
                                <img src="<?php echo get_sub_field('icon')['url']; ?>"
                                    alt="<?php echo get_sub_field('icon')['alt']; ?>">
                            </div>
                            <?php endif; ?>

                            <div class="service-content">
                                <?php if( !empty( get_sub_field('title') ) ): ?>
                                <h3 class="service-title">
                                    <a href="#" class="text-inherit"><?php echo get_sub_field('title'); ?></a>
                                </h3>
                                <?php endif; ?>
                                <?php if( !empty( get_sub_field('contents') ) ): ?>
                                <p class="service-text"><?php echo get_sub_field('contents'); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                    <?php endif; ?>

                    <?php if( !empty( get_sub_field('center_image')['url'] ) ): ?>
                    <div class="col col-xxl-auto text-center d-none d-lg-block">
                        <img src="<?php echo get_sub_field('center_image')['url']; ?>"
                            alt="<?php echo get_sub_field('center_image')['alt']; ?>" class="mt-n4">
                    </div>
                    <?php endif; ?>

                    <?php if( have_rows('feature_right') ): ?>
                    <div class="col-md-6 col-lg-5 col-xxl-auto">
                        <?php while( have_rows('feature_right') ): the_row(); ?>
                        <div class="service-style1">
                            <?php if( !empty(get_sub_field('icon')['url']) ): ?>
                            <div class="vs-icon">
                                <img src="<?php echo get_sub_field('icon')['url']; ?>"
                                    alt="<?php echo get_sub_field('icon')['alt']; ?>">
                            </div>
                            <?php endif; ?>
                            <div class="service-content">
                                <?php if( !empty( get_sub_field('title') ) ): ?>
                                <h3 class="service-title">
                                    <a href="#" class="text-inherit"><?php echo get_sub_field('title'); ?></a>
                                </h3>
                                <?php endif; ?>
                                <?php if( !empty( get_sub_field('contents') ) ): ?>
                                <p class="service-text"><?php echo get_sub_field('contents'); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>


    <?php if( get_row_layout() == 'icon_box_section'): ?>
    <section class="tf__category_2 mt_115 xs_mt_65 space-bottom">
        <div class="container">
            <?php if( empty( get_sub_field('heading') ) && 
            empty( get_sub_field('description') ) ): ?>
            <?php else: ?>
            <div class="row justify-content-center text-center">
                <div class="col-md-9 col-lg-7 col-xl-6">
                    <div class="title-area">
                        <?php if( !empty( get_sub_field('heading') ) ): ?>
                        <h2 class="sec-title4"><?php echo get_sub_field('heading');?></h2>
                        <?php endif; ?>

                        <?php if( !empty( get_sub_field('description') ) ): ?>
                        <p class="sec-text"><?php echo get_sub_field('description');?></p>
                        <?php endif; ?>
                        <div class="sec-shape">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/sec-shape-1.png"
                                alt="shape">
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if( have_rows('icon_box') ): ?>
            <div class="row">
                <?php while( have_rows('icon_box') ): the_row(); ?>
                <div class="col-xl-3 col-md-6 col-lg-4">
                    <div class="tf__category_2_single">
                        <?php if( !empty( get_sub_field('icon')['url'] ) ): ?>
                        <span>
                            <img src="<?php echo get_sub_field('icon')['url']; ?>"
                                alt="<?php echo get_sub_field('icon')['alt']; ?>" class="img-fluid w-100">
                        </span>
                        <?php endif; ?>
                        <div class="tf__category_2_single_text">
                            <h4><?php echo get_sub_field('title');?></h4>
                            <p><?php echo get_sub_field('content');?></p>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            <?php endif; ?>
        </div>
    </section>
    <?php endif; ?>

    <?php if( get_row_layout() == 'image_content_section'): ?>
    <section class="space-extra-top space-bottom shape-mockup-wrap">
        <div class="shape-mockup jump-reverse-img d-none d-xxl-block d-hd-none" style="top: 4%; left: -3%;">
            <div class="curb-shape1"></div>
        </div>
        <div class="container">
            <div class="row gx-xl-0">
                <div class="col-lg-6 col-xl-7 mb-40 mb-lg-0">
                    <?php if( !empty( get_sub_field('image')['url'] ) ): ?>
                    <div class="img-box1">
                        <img src="<?php echo get_sub_field('image')['url']; ?>"
                            alt="<?php echo get_sub_field('image')['alt']; ?>">
                        <div class="img-1 jump-reverse">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/banner-leaf-2.png" alt="">
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="col-lg-6 col-xl-5 align-self-center">
                    <?php if( !empty( get_sub_field('content_group')['heading'] ) ): ?>
                    <h2 class="sec-title">
                        <?php echo get_sub_field('content_group')['heading']; ?>
                    </h2>
                    <?php endif; ?>

                    <?php if( !empty( get_sub_field('content_group')['content'] ) ): ?>
                    <p class="about-text text-justify">
                        <?php echo get_sub_field('content_group')['content']; ?>
                    </p>
                    <?php endif; ?>

                    <?php $link_buton = get_sub_field('content_group')['link_buton']; ?>
                    <?php if( $link_buton ): ?>
                    <a href="<?php echo $link_buton['url'];?>" class="vs-btn style3"
                        target="<?php echo $link_buton['target']?$link_buton['target']:'_self';?>"><?php echo $link_buton['title'];?></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>


    <?php if( get_row_layout() == 'product_section'): ?>
    <?php if( get_sub_field('display_products') ): ?>
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
                        <a href="<?php echo $product->add_to_cart_url();?>" data-quantity="1"
                            data-product_id="<?php echo $product->get_id();?>" data-product_sku=""
                            class="vs-btn style3 product_type_simple add_to_cart_button ajax_add_to_cart"
                            aria-label="Add “<?php echo get_the_title(); ?>” to your cart" rel="nofollow">Add to
                            cart</a>
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

    <?php endwhile; ?>
    <?php endif; ?>

</main>
<?php get_footer(); ?>