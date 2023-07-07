<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
get_header(); ?>

<main>

    <?php
	/**
	 * woocommerce_before_main_content hook.
	 *
	 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
	 * @hooked woocommerce_breadcrumb - 20
	 */
	//do_action( 'woocommerce_before_main_content' ); ?>
    <?php while ( have_posts() ) : the_post(); global $product;?>
    <?php  //print_r($product);?>
    <section class="vs-product-wrapper product-details space-top bg-gradient-1">
        <div class="container-fluid">
            <div class="row gx-60">
                <div class="col-lg-6">
                    <?php $gallery_image_ids = $product->get_gallery_image_ids();?>
                    <div class="product-imgs product-view">
                        <div class="img-display">
                            <div class="img-showcase">
                                <img src="<?php echo wp_get_attachment_url( $product->get_image_id() ); ?>"
                                    alt="<?php the_title();?>" class="cart__product">
                                <?php foreach($gallery_image_ids as $gallery_image_id) :
							$image_url = wp_get_attachment_url($gallery_image_id); ?>
                                <img src="<?php echo $image_url; ?>" alt="<?php the_title();?>" class="cart__product">
                                <?php endforeach ?>

                                <?php if( have_rows('videos_gallery') ):?>
                                <?php while( have_rows('videos_gallery') ): the_row(); ?>
                                <?php if( !empty( get_sub_field('video')['url']) ): ?>
                                <video playsinline="" autoplay="" muted="" loop="" width="990px" class="cart__product">
                                    <source src="<?php echo get_sub_field('video')['url'];?>"
                                        type="<?php echo get_sub_field('video')['mime_type'];?>">
                                    Your browser does not support the video tag.
                                </video>
                                <?php endif; ?>
                                <?php endwhile; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="img-select">
                            <div class="img-item">
                                <a href="#" data-id="1">
                                    <img src="<?php echo wp_get_attachment_url( $product->get_image_id() ); ?>"
                                        alt="<?php the_title();?>" width="140px" height="140px">
                                </a>
                            </div>
                            <?php 
                           $index=2;
                           foreach($gallery_image_ids as $gallery_image_id) : ?>
                            <div class="img-item">
                                <a href="#" data-id="<?php echo $index;?>">
                                    <img src="<?php echo wp_get_attachment_url($gallery_image_id); ?>"
                                        alt="<?php the_title();?>" width="140px" height="140px">
                                </a>
                            </div>
                            <?php $index++; endforeach ?>
                            <?php if( have_rows('videos_gallery') ):?>
                            <?php while( have_rows('videos_gallery') ): the_row(); ?>
                            <?php if( !empty( get_sub_field('preview_image')['url'] ) ): ?>
                            <div class="img-item">
                                <a href="#" data-id="<?php echo $index;?>">
                                    <img src="<?php echo get_sub_field('preview_image')['url']; ?>"
                                        alt="<?php the_title();?>" width="140px" height="140px">
                                </a>
                            </div>
                            <?php endif; ?>
                            <?php endwhile; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>

                <div class="col-lg-6">
                    <div class="product-about product-view">
                        <?php do_action( 'woocommerce_single_product_summary' );?>
                        <div class="product-getway"></div>

                        <?php if( !empty( get_the_content() ) ):?>
                        <div class="product-description list-style2">
                            <h5>Description: </h5>
                            <p><?php echo get_the_content();?></p>
                        </div>
                        <?php endif; ?>
                        <!-- <div class="row">
                     <div class="col-lg-12">
                        <div id="right-side">
                           <div class="question-container">
                              <div class="question">
                                 <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. ?</p>
                                 <img src="https://raw.githubusercontent.com/ViktoriiaZaichuk/faq-accordion-card-main/48bb14e632a5bd5d9190da88b45d21622dd2ed14/img/icon-arrow-down.svg" alt="arrow down" class="arrow">
                              </div>
                              <div class="hidden">
                                 <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Officiis itaque
                                    fugit harum velit voluptatibus, consequatur modi obcaecati quia aperiam
                                    cumque?
                                 </p>
                              </div>
                           </div>
                           <div class="question-container">
                              <div class="question">
                                 <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. ?</p>
                                 <img src="https://raw.githubusercontent.com/ViktoriiaZaichuk/faq-accordion-card-main/48bb14e632a5bd5d9190da88b45d21622dd2ed14/img/icon-arrow-down.svg" alt="arrow down" class="arrow">
                              </div>
                              <div class="hidden">
                                 <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Officiis itaque
                                    fugit harum velit voluptatibus, consequatur modi obcaecati quia aperiam
                                    cumque?
                                 </p>
                              </div>
                           </div>
                           <div class="question-container">
                              <div class="question">
                                 <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. ?</p>
                                 <img src="https://raw.githubusercontent.com/ViktoriiaZaichuk/faq-accordion-card-main/48bb14e632a5bd5d9190da88b45d21622dd2ed14/img/icon-arrow-down.svg" alt="arrow down" class="arrow">
                              </div>
                              <div class="hidden">
                                 <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Officiis itaque
                                    fugit harum velit voluptatibus, consequatur modi obcaecati quia aperiam
                                    cumque?
                                 </p>
                              </div>
                           </div>
                           <div class="question-container">
                              <div class="question">
                                 <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. ?</p>
                                 <img src="https://raw.githubusercontent.com/ViktoriiaZaichuk/faq-accordion-card-main/48bb14e632a5bd5d9190da88b45d21622dd2ed14/img/icon-arrow-down.svg" alt="arrow down" class="arrow">
                              </div>
                              <div class="hidden">
                                 <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Officiis itaque
                                    fugit harum velit voluptatibus, consequatur modi obcaecati quia aperiam
                                    cumque?
                                 </p>
                              </div>
                           </div>
                           <div class="question-container">
                              <div class="question">
                                 <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. ?</p>
                                 <img src="https://raw.githubusercontent.com/ViktoriiaZaichuk/faq-accordion-card-main/48bb14e632a5bd5d9190da88b45d21622dd2ed14/img/icon-arrow-down.svg" alt="arrow down" class="arrow">
                              </div>
                              <div class="hidden">
                                 <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Officiis itaque
                                    fugit harum velit voluptatibus, consequatur modi obcaecati quia aperiam
                                    cumque?
                                 </p>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div> -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php do_action( 'woocommerce_after_single_product' ); ?>
    <?php endwhile; // end of the loop. ?>
    <?php
		/**
		 * woocommerce_after_main_content hook.
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action( 'woocommerce_after_main_content' ); ?>
<?php if( have_rows('sections') ): ?>
   
   <?php while( have_rows('sections') ): the_row(); ?>
   <?php if( get_row_layout() == 'icon_box_section'): ?>
   <?php if( have_rows('icon_box') ): ?>
   <section class="overflow-hidden martopps">
      <div class="container">
         <div class="row testimonial-slider">
            <?php while( have_rows('icon_box') ): the_row(); ?>
            <div class="col-xl-4">
               <div class="service-style2">
                  <?php if( !empty( get_sub_field('icon')['url'] ) ): ?>
                  <div class="vs-icon style3">
                     <img src="<?php echo get_sub_field('icon')['url']; ?>" alt="<?php echo get_sub_field('icon')['alt']; ?>" class="marpadiiimg">
                  </div>
                  <?php endif; ?>

                  <?php if( !empty( get_sub_field('title') ) ): ?>
                  <h3 class="service-title h5"><?php echo get_sub_field('title'); ?></h3>
                  <?php endif; ?>

                  <div class="arrow-shape">
                     <i class="arrow"></i>
                     <i class="arrow"></i>
                     <i class="arrow"></i>
                     <i class="arrow"></i>
                  </div>
                  <p class="service-text"><?php echo get_sub_field('content'); ?></p>
               </div>
            </div>
            <?php endwhile; ?>
         </div>
      </div>
   </section>
    <?php endif; ?>
    <?php endif; ?>

   <?php if( get_row_layout() == 'video_section'): ?>
   <?php if( !empty( get_sub_field('video_upload')['url']) ): ?>
   <section class="overflow-hidden martopps">
      <video playsinline="" autoplay="" muted="" loop="" width="100%">
         <source src="<?php echo get_sub_field('video_upload')['url']; ?>" type="<?php echo get_sub_field('video_upload')['mime_type']; ?>">
          Your browser does not support the video tag.
      </video>
   </section>
   <?php endif; ?>
   <?php endif; ?>

   <?php if( get_row_layout() == 'review_form'): ?>
      <?php if( get_sub_field('display_form') ): ?>
    <section class="tf_about_2 xs_mt_70 bg-gradient-1">
        <div class="container">
            <div class="row gx-xl-0 pt_120 pt_1201 ">
                <div class="tf__service_review_input mt_50 xs_mb_25 form-width">
                    <!-- <div class="hedingflex">
                        <h3>Review</h3>
                        <div class="comment-form-rating">
                            <label for="rating">Your Rating&nbsp;<span class="required">*</span>
                            </label>
                            <p class="stars">
                                <span>
                                    <a class="star-1" href="#">1</a>
                                    <a class="star-2" href="#">2</a>
                                    <a class="star-3" href="#">3</a>
                                    <a class="star-4" href="#">4</a>
                                    <a class="star-5" href="#">5</a>
                                </span>
                            </p>
                        </div>
                    </div> -->
                    
                    <!-- <form action="#" class="ajax-contact form-style6">
                        <div class="form-group">
                            <input type="text" class="formwidth" name="name" id="name" placeholder="Your Name*">
                        </div>
                        <div class="form-group">
                            <input type="email" class="formwidth" name="email" id="email" placeholder="Your Email*">
                        </div>
                        <div class="form-group">
                        </div>
                        <div class="form-group">
                            <textarea name="message" class="formwidth" id="message" placeholder="Message*"></textarea>
                        </div>
                        <button class="vs-btn" type="submit">Send Message</button>
                        <p class="form-messages"></p>
                    </form> -->
                    <?php wc_get_template_part('single', 'product-reviews'); ?>
               </div>
            </div>
        </div>
    </section>
    <?php endif; ?>
    <?php endif; ?>


   <?php if( get_row_layout() == 'image_content_section'): ?>
    <section class="tf_about_2 xs_mt_70 space-bottom">
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


    <?php if( get_row_layout() == 'featured_box'): ?>
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

            <?php if( have_rows('box') ): ?>
            <div class="row">
                <?php while( have_rows('box') ): the_row(); ?>
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

   <?php endwhile; ?>

<?php endif; ?>
</main>


<?php
		/**
		 * woocommerce_sidebar hook.
		 *
		 * @hooked woocommerce_get_sidebar - 10
		 */
		//do_action( 'woocommerce_sidebar' );

	?>

<?php get_footer();

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */