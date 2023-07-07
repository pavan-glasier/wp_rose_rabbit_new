<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Rose_and_Rabbit
 */

get_header(); ?>

<main id="primary" class="site-main<?php echo count(WC()->cart->get_cart()) > 0?'':' container';?> ">
	<?php while ( have_posts() ) : the_post();
			get_template_part( 'template-parts/content', 'page' );
		endwhile; ?>
</main><!-- #main -->

<?php get_footer();
