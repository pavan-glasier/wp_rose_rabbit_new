<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Rose_and_Rabbit
 */

get_header(); ?>

<main id="primary" class="site-main">
    <div class="space-top space-extra-bottom1 bg-gradient-1">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-ms-12">
                    <div>
                        <?php echo the_archive_title('<h1 class="breadcumb-title story__tital">', '</h1>');?>
                        <span class="breadcumb-titlespan"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<section class="overflow-hidden space-extra-bottom">
        <div class="hero-shape-3 jump overflojumphid">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/banner-leaf-2.png" alt="shape">
        </div>
        <div class="hero-shape-3 jump overflojumphid1">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/banner-leaf-3.png" alt="shape">
        </div>
        <div class="container">
            <div class="row">
                <?php if ( have_posts() ) :
				/* Start the Loop */
				while ( have_posts() ) : the_post(); ?>
                <div class="col-xl-4">
                    <div class="vs-blog blog-style1 mb-5">
                        <div class="blog-img">
                            <a href="<?php the_permalink();?>">
                                <?php echo do_shortcode('[featured_image]');?>
                            </a>
                        </div>
                        <div class="blog-content text-center">
                            <h3 class="blog-title h5">
                                <a href="<?php the_permalink();?>"><?php echo the_title();?></a>
                            </h3>
                            <div class="blog-meta">
                                <a href="#"><?php the_author();?></a>
                                <a href="#"><?php echo get_the_date( 'd M, Y', $post->ID ); ?></a>
                            </div>
                        </div>
                        <div class="package-btn">
                            <a href="<?php the_permalink();?>" class="vs-btn" target="_blank">See More</a>
                        </div>
                    </div>
                </div>
                <?php endwhile;
				do_shortcode('[rose_and_rabbit_pagination]');
			else :
				get_template_part( 'template-parts/content', 'none' );
			endif; ?>
                <?php wp_reset_postdata(); ?>
            </div>
        </div>
    </section>
</main><!-- #main -->
<?php get_footer();