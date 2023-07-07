<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Rose_and_Rabbit
 */

?>
<?php
$wplogoutURL = urlencode(get_the_permalink());
$wplogoutTitle = urlencode(get_the_title());
$wplogoutImage= urlencode(get_the_post_thumbnail_url(get_the_ID(), 'full'));
?>


<main>
    <div class="space-top space-extra-bottom1 bg-gradient-1">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-ms-12">
                    <div>
                        <?php echo the_title('<h1 class="breadcumb-title story__tital">', '</h1>');?>
                        <span class="breadcumb-titlespan"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="vs-blog-wrapper blog-details  space-extra-bottom">
        <div class="container">
            <div class="row gx-50">
                <div class="col-lg-8 col-xxl-9">
                    <div class="vs-blog blog-single has-post-thumbnail">
                        <div class="blog-img">
                            <?php echo do_shortcode('[single_image]');?>
                        </div>
                        <div class="blog-content">
                            <?php echo the_title('<h2 class="blog-title mt-4">', '</h2>');?>
                            <div class="blog-meta">
                                <a href="#">
                                    <i class="fas fa-user"></i>by <?php the_author();?>
                                </a>
                                <a href="#">
                                    <i
                                        class="fas fa-calendar-alt"></i><?php echo get_the_date( 'd M, Y', $post->ID ); ?>
                                </a>
                                <a href="#">
                                    <i class="far fa-comments"></i><?php echo get_comments_number();?> comments
                                </a>
                            </div>
                            <?php the_content(); ?>
                        </div>
                    </div>
                    <!--Coment Section-->
                    <div class="tf_service_det_tag d-flex flex-wrap align-items-center">
                        <ul class="share d-flex flex-wrap">
                            <li><span><i class="fal fa-share-alt" aria-hidden="true"></i> share :</span></li>
                            <li><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $wplogoutURL; ?>"
                                    target="_blank" rel="nofollow"><i class="fa fa-facebook"></i></a></li>
                            <li><a href="https://twitter.com/intent/tweet?text=<?php echo $wplogoutTitle;?>&amp;url=<?php echo $wplogoutURL;?>&amp;via=wplogout"
                                    target="_blank" rel="nofollow"><i class="fa fa-twitter"></i></a></li>
                            <li><a href="https://www.linkedin.com/shareArticle?url=<?php echo $wplogoutURL; ?>&amp;title=<?php echo $wplogoutTitle; ?>&amp;mini=true"
                                    target="_blank" rel="nofollow"><i class="fa fa-linkedin"></i></a></li>
                            <li><a href="https://pinterest.com/pin/create/button/?url=<?php echo $wplogoutURL; ?>&amp;media=<?php echo $wplogoutImage;   ?>&amp;description=<?php echo $wplogoutTitle; ?>"
                                    target="_blank" rel="nofollow"><i class="fa fa-pinterest"></i></a></li>
                        </ul>
                    </div>
                    <div class="tf__service_review_list mt_50 d-none">
                        <h3><?php echo get_comments_number();?> Comments</h3>
                        <?php $comments_number = get_comments_number();
                        if($comments_number != 0){  ?>
                        <?php $args1 = array(
                                'add_below' 		=> true,
                                'depth'     		=> 4,
                                'max_depth' 		=> 20,
                                'callback'          => 'better_comments',
                                'reverse_top_level' => false,
                                'reverse_children'  => true,
                            ); ?>
                        <?php
                            $comments = get_comments(array(
                                'post_id' => $post->ID,
                                'status' => 'approve'
                                ));
                                wp_list_comments($args1, $comments);
                            }
                            ?>

                        <div class="tf__single_review">
                            <div class="review_img">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/blog-detail-coment.jpg"
                                    alt="Client" class="img-fluid w-100">
                            </div>
                            <div class="review_text">
                                <h4>Lorem, ipsum. <span>17 dec 2022</span></h4>
                                <p class="description">Lorem ipsum dolor sit amet consectetur adipisicing elit. Totam
                                    soluta quam, beatae repellendus fugit temporibus cupiditate architecto excepturi?
                                    Vero, ipsa.</p>
                            </div>
                        </div>
                        <div class="tf__single_review">
                            <div class="review_img">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/blog-detail-coment.jpg"
                                    alt="Client" class="img-fluid w-100">
                            </div>
                            <div class="review_text">
                                <h4>Lorem, ipsum. <span>17 dec 2022</span></h4>
                                <p class="description">Lorem ipsum dolor sit amet consectetur adipisicing elit. Totam
                                    soluta quam, beatae repellendus fugit temporibus cupiditate architecto excepturi?
                                    Vero, ipsa.</p>
                            </div>
                        </div>
                    </div>
                    <div class="tf__service_review_input mt_50 xs_mb_25 d-none">
                        <div class="hedingflex">
                            <h3>Leave a comment</h3>
                        </div>
                        <form action="#" class="ajax-contact form-style6">
                            <div class="form-group">
                                <input type="text" class="formwidth" name="name" id="name" placeholder="Your Name*">
                            </div>
                            <div class="form-group">
                                <input type="email" class="formwidth" name="email" id="email" placeholder="Your Email*">
                            </div>
                            <div class="form-group">
                            </div>
                            <div class="form-group">
                                <textarea name="message" class="formwidth" id="message" placeholder="Message*"
                                    style="height: 150px;"></textarea>
                            </div>
                            <button class="vs-btn" type="submit">Send Message</button>
                            <p class="form-messages"></p>
                        </form>
                    </div>
                    <!--End Comment Section-->
                </div>
                <!--End col-8-->

                <!-- SIDE BAR BLOG -->
                <div class="col-lg-4 col-xxl-3">
                    <aside class="sidebar-area">
                        <?php $categories = get_categories();?>
                        <?php if($categories): ?>
                        <div class="widget widget_categories">
                            <h3 class="widget_title">Categories</h3>
                            <ul>
                                <?php foreach ($categories as $category): ?>
                                <li><a
                                        href="<?php echo get_category_link($category->term_id); ?>"><?php echo $category->name; ?></a>
                                    <span><?php echo $category->count; ?></span>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php endif; ?>

                        <?php $recent = new WP_Query( array( 
                        'post_type' => 'post',
                        'order' => 'DESC', 
                        'posts_per_page' => 3,
                        'post__not_in' => array( get_the_ID() )
                        ) 
                    );
                    if( $recent->have_posts() ): ?>

                        <div class="widget">
                            <h3 class="widget_title">Latest post</h3>
                            <div class="recent-post-wrap">
                                <?php while( $recent->have_posts() ) : $recent->the_post(); ?>
                                <div class="recent-post">
                                    <div class="media-img">
                                        <a href="<?php the_permalink();?>">
                                            <?php echo do_shortcode('[thumb_image]');?>
                                        </a>
                                    </div>
                                    <div class="media-body">
                                        <h4 class="post-title">
                                            <a class="text-inherit" href="<?php the_permalink();?>">
                                                <?php the_title(); ?>
                                            </a>
                                        </h4>
                                        <div class="recent-post-meta">
                                            <a href="#">
                                                <i
                                                    class="fas fa-calendar-alt"></i><?php echo get_the_date( 'd M, Y', get_the_ID() ); ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <?php endwhile; ?>
                            </div>
                        </div>
                        <?php wp_reset_query();?>
                        <?php endif; ?>

                        <?php $tags = get_tags();?>
                        <?php if($tags): ?>
                        <div class="widget widget_tag_cloud">
                            <h3 class="widget_title">Popular Tags</h3>
                            <div class="tagcloud">
                                <?php foreach ($tags as $tag): ?>
                                <a
                                    href="<?php echo esc_attr( get_tag_link( $tag->term_id ) );?>"><?php echo $tag->name; ?></a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                    </aside>
                </div>
            </div>
        </div>
    </section>
</main>