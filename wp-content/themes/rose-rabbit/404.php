<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Rose_and_Rabbit
 */

get_header(); ?>
<main>
	<div class="space-top space-extra-bottom1 bg-gradient-1">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-ms-12">
                    <div>
                        <h1 class="breadcumb-title story__tital text-first">Oops! That page can&rsquo;t be found</h1>
                        <span class="breadcumb-titlespan">
						<?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'rose_and_rabbit' ); ?>
                        </span>
                    </div>
					<div class="text-center mt-3">
						<a href="<?php echo esc_url( home_url('/') )?>" class="vs-btn has-spinner">Home</a>
					</div>
                </div>
            </div>
        </div>
    </div>
</main><!-- #main -->

<?php get_footer();