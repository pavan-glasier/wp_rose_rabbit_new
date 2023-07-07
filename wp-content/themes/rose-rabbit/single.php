<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Rose_and_Rabbit
 */

get_header(); ?>
<?php if( get_post_type() == 'product' ): ?>
	<?php //wc_get_template_part( 'single-product' );?>
<?php else: ?>
<?php while ( have_posts() ) : the_post();
	get_template_part( 'template-parts/content', get_post_type() );
	endwhile; ?>
<?php endif; ?>
<?php get_footer();
