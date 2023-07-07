<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Rose_and_Rabbit
 */
?>
<div class="space-top space-extra-bottom1 bg-gradient-1  mb-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-ms-12">
                <div>
                    <?php the_title( '<h1 class="breadcumb-title story__tital text-first">', '</h1>' ); ?>
                    <!-- <span class="breadcumb-titlespan">Lorem ipsum dolor sit amet, consectetur adipisicing elit. In
                        voluptas nesciunt dolores eligendi vel dicta quidem error pariatur, voluptates provident aperiam
                        tempora fuga et dolore ullam. Sapiente possimus consectetur ad amet quis dolor illo
                        aspernatur!</span> -->
                </div>
            </div>
        </div>
    </div>
</div>
<?php if(is_cart()): ?>
    <?php the_content(); ?>
<?php else: ?>
<section class="space-extra-bottom shape-mockup-wrap">
    <div class="shape-mockup jump-reverse d-none d-xxxl-block">
		<img src="<?php echo get_template_directory_uri(); ?>/assets/img/sec-i-1-1.png" alt="shape">
	</div>
    <div class="container">
        <div class="row gx-80 align-items-center">
            <div class="col-lg-12">
                <div class="about-box1">
					<?php the_content(); ?>
					<?php if ( get_edit_post_link() ) : ?>
					<a href="<?php echo get_edit_post_link(get_the_ID()); ?>" class="vs-btn style7 float-lg-end">Edit</a>
					<?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>