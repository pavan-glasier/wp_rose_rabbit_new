<?php
/**
 * Template Name: FAQ Page
 * 
 */
get_header(); ?>


<main>
    <div class="space-top space-extra-bottom1 bg-gradient-1">
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

    <?php if( have_rows('faqs') ): ?>
    <section class="vs-blog-wrapper blog-details space-extra-bottom">
        <div class="container">
            <div class="row gx-50">
                <div class="col-lg-12">
                    <div id="right-side">
                        <?php while( have_rows('faqs') ): the_row(); ?>
                        <?php if( !empty( get_sub_field('title') ) ):?>
                        <div class="question-container">
                            <div class="question">
                                <p class="font-s"><?php echo get_sub_field('title');?></p>
                                <img src="<?php echo site_url('/wp-content/uploads/2023/06/down-arrow.png');?>"
                                    alt="arrow down" class="arrow">
                            </div>
                            <div class="hidden">
                                <p class="font-s"><?php echo get_sub_field('contents');?></p>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    
<?php echo do_shortcode('[job_listing]');?>

</main>
<?php get_footer(); ?>