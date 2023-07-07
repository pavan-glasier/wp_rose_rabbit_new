<?php
function job_listing_filter_shortcode() { ?>
<section class="job-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Jobs</h2>
            <form id="filter-form">
                <div class="filters">
                    <?php
                    $job_type_taxonomies = get_terms( array(
                        'taxonomy'   => 'job_type',
                        'hide_empty' => false,
                    ) );
                    if ( $job_type_taxonomies ) : ?>
                    <select id="job-type" class="filter-select">
                        <option value="">Select Job Type</option>
                        <?php foreach( $job_type_taxonomies as $taxonomy ): ?>
                        <option value="<?php echo $taxonomy->slug; ?>"><?php echo $taxonomy->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php endif; ?>

                    <?php $industry_taxonomies = get_terms( array(
                        'taxonomy'   => 'industry',
                        'hide_empty' => false,
                    ) );
                    if ( $industry_taxonomies ) : ?>
                    <select id="industry" class="filter-select">
                        <option value="">Select Industry</option>
                        <?php foreach( $industry_taxonomies as $taxonomy ): ?>
                            <option value="<?php echo $taxonomy->slug; ?>"><?php echo $taxonomy->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php endif; ?>
                    
                    <?php $skill_taxonomies = get_terms( array(
                        'taxonomy'   => 'skill',
                        'hide_empty' => false,
                    ) );
                    if ( $skill_taxonomies ) : ?>
                    <select id="skills" class="filter-select">
                        <option value="">Select Skills</option>
                        <?php foreach( $skill_taxonomies as $taxonomy ): ?>
                        <option value="<?php echo $taxonomy->slug; ?>"><?php echo $taxonomy->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php endif; ?>

                    <?php if( $job_type_taxonomies || $industry_taxonomies || $skill_taxonomies):?>
                    <input type="submit" class="button filter-submit" value="Filter">
                    <?php endif; ?>
                </div>
                <!-- Add filter options for Job Type, Industry, and Skills -->
            </form>
        </div>
        <?php 
        $args = array(
            'post_type'      => 'job_listing',
            'posts_per_page' => -1,
        );
        $query = new WP_Query( $args ); ?>
        <ul role="list" class="cards" id="jobs-container">
            <?php if ( $query->have_posts() ) : ?>
            <?php while ( $query->have_posts() ) : $query->the_post(); ?>
            <li>
                <div class="job_card">
                    <div class="card__img-wrapper">
                        <?php $attr = ['class' => 'card__img', 'alt' => get_the_title(), 'loading' => 'lazy'];
                        if (has_post_thumbnail()):
                            echo the_post_thumbnail('full', $attr);
                        else: ?>
                            <img src="<?php echo JS_PLUGIN_URL . '/assets/img/image_not_available.png'; ?>" loading="<?php echo $attr['loading']; ?>"
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
        </ul>
    </div>
</section>

<?php }
if ( ! is_admin() ) {
    add_shortcode( 'job_listing', 'job_listing_filter_shortcode' );
}