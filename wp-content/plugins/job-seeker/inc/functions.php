<?php
function filter_job_listing() {
    ob_start(); 

    $job_type = $_POST[ 'job_type' ];
    $industry = $_POST[ 'industry' ];
    $skills   = $_POST[ 'skills' ];

    $args = array(
        'post_type'      => 'job_listing',
        'posts_per_page' => -1,
        'tax_query'     => array(),
    );
    
    if ( ! empty( $job_type ) ) {
        $args['tax_query'][] = array(
            'taxonomy'   => 'job_type',
            'field'      => 'slug',
            'terms'      => array($job_type),
        );
    }
    
    if ( ! empty( $industry ) ) {
        $args['tax_query'][] = array(
            'taxonomy'   => 'industry',
            'field'      => 'slug',
            'terms'      => array($industry),
        );
    }
    
    if ( ! empty( $skills ) ) {
        $args['tax_query'][] = array(
            'taxonomy'   => 'skill',
            'field' => 'slug',
            'terms' => array($skills),
        );
    }

    $query = new WP_Query( $args ); ?>
    <?php if ( $query->have_posts() ) : ?>
    <?php while ( $query->have_posts() ) : $query->the_post(); ?>
    <li>
        <div class="job_card">
            <div class="card__img-wrapper">
                <?php 
                $attr = ['class' => 'card__img', 'alt' => get_the_title(), 'loading' => 'lazy'];
                if (has_post_thumbnail()):
                    echo the_post_thumbnail('full', $attr);
                else: ?>
                    <img src="<?php echo JS_PLUGIN_URL.'/assets/img/image_not_available.png'; ?>" loading="<?php echo $attr['loading']; ?>"
                        class="<?php echo $attr['class']; ?>" alt="<?php echo $attr['alt']; ?>">
                <?php endif; ?>
            </div>
            <div class="card__content">
                <h3><?php the_title(); ?></h3>
                <p><?php echo the_excerpt(); ?></p>
                <a href="<?php the_permalink();?>" class="button">Apply Now</a>
            </div>
        </div>
    </li>
    <?php endwhile; wp_reset_postdata();?>
    <?php else: ?>
        <li> No Jobs Found!</li>
    <?php endif; ?>
    <?php $html_contents = ob_get_contents();
    ob_end_clean();
    $arr = array(
        "html_contents" => $html_contents,
    );
    echo json_encode($arr);
    exit();
}
add_action( 'wp_ajax_filter_job_listing', 'filter_job_listing' );
add_action( 'wp_ajax_nopriv_filter_job_listing', 'filter_job_listing' );
