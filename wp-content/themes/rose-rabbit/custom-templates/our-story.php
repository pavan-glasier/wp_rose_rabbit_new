<?php
/**
 * Template Name: Our Story
 * 
 */
get_header(); ?>

<main>
    <div class="space-top space-extra-bottom1 bg-gradient-1 mb-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-ms-12">
                    <div>
                        <h1 class="breadcumb-title story__tital text-first"><?php echo wp_title('');?></h1>
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
    <?php if( get_row_layout() == 'about_section'): ?>
    <section class="space-extra-bottom shape-mockup-wrap">
        <div class="shape-mockup jump-reverse d-none d-xxxl-block">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/sec-i-1-1.png" alt="shape">
        </div>
        <div class="container">
            <div class="row gx-80 align-items-center">
                <div class="col-lg-6">
                    <div class="img-box2">
                        <?php $img_group = get_sub_field('image_group')['image'];?>
                        <?php if( $img_group ): ?>
                        <div class="img-1">
                            <img src="<?php echo $img_group['url']; ?>" alt="<?php echo $img_group['alt']; ?>">
                        </div>
                        <?php endif; ?>
                        <div class="img-2 jump">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/banner-leaf-3.png"
                                alt="shape">
                        </div>
                        <div class="img-shape d-none d-md-block">
                            <?php if( !empty( get_sub_field('image_group')['vertical_text'] ) ): ?>
                            <span
                                class="img-text jump-reverse"><?php echo get_sub_field('image_group')['vertical_text']; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <?php $content_group = get_sub_field('content_group'); ?>
                    <?php if( $content_group ): ?>
                    <div class="about-box1">
                        <?php if( !empty( $content_group['heading'] ) ): ?>
                        <h2 class="sec-title2">
                            <?php echo $content_group['heading']; ?>
                        </h2>
                        <?php endif; ?>

                        <?php if( !empty( $content_group['content'] ) ): ?>
                        <div class="about-text text-justify">
                            <?php echo $content_group['content']; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>


    <?php if( get_row_layout() == 'process_section'): ?>
    <section class="tf__work mt_115 xs_mt_70">
        <div class="container">
            <?php if( empty( get_sub_field('heading') ) && empty( get_sub_field('description') ) ): ?>
            <?php else: ?>
            <div class="row justify-content-center text-center mb-5">
                <div class="col-md-9 col-lg-7 col-xl-6">
                    <div class="title-area">
                        <?php if( !empty( get_sub_field('heading') ) ): ?>
                        <h2 class="sec-title4"><?php echo get_sub_field('heading'); ?></h2>
                        <?php endif; ?>

                        <?php if( !empty( get_sub_field('description') ) ): ?>
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

            <?php if( have_rows('boxes') ): ?>
            <div class="tf__work_text_area">
                <div class="d-grid justify-content-center">
                    <?php while( have_rows('boxes') ): the_row(); ?>
                    <div class="mb-lg-0 mb-5">
                        <div class="tf__work_single<?php echo ( get_row_index() == 2 )?' second':' first';?>">
                            <h4><?php echo get_sub_field('title');?></h4>
                            <p><?php echo get_sub_field('content');?></p>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </section>
    <?php endif; ?>



    <?php if( get_row_layout() == 'made_with_care_section'): ?>
    <section class="space-extra-bottom shape-mockup-wrap">
        <div class="hero-shape-3 jump storyjump-left">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/banner-leaf-2.png" alt="shape">
        </div>
        <div class="hero-shape-3 jump storyjump-right">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/banner-leaf-3.png" alt="shape">
        </div>
        <div class="container">
            <div class="row justify-content-between gx-0">
                <div class="col-md-12">
                    <span class="sec-subtitle"><?php echo get_sub_field('tag_line'); ?></span>
                    <h2 class="h3 pe-xxl-5 me-xxl-5 pb-xl-3 mobile-h3">
                        <?php echo get_sub_field('heading'); ?>
                    </h2>
                </div>
            </div>
            <?php if( !empty(get_sub_field('image')['url']) ): ?>
            <div class="mb-3">
                <img src="<?php echo get_sub_field('image')['url']; ?>"
                    alt="<?php echo get_sub_field('image')['alt']; ?>" class="w-100">
            </div>
            <?php endif; ?>
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