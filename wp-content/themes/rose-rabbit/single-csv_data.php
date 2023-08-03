<?php
get_header();

if ( have_posts() ) :
    while ( have_posts() ) :
        the_post();
        $csv_file_url = get_post_meta( get_the_ID(), 'csv_file_url', true );
        ?>
        <div class="entry-content">
            <?php the_content(); ?>
        </div>

        <?php if ( $csv_file_url ) : ?>
            <div class="csv-file-content">
                <?php
                if ( false !== ( $handle = fopen( $csv_file_url, 'r' ) ) ) {
                    echo '<table>';
                    while ( false !== ( $data = fgetcsv( $handle, 1000, ',' ) ) ) {
                        echo '<tr>';
                        foreach ( $data as $cell ) {
                            echo '<td>' . esc_html( $cell ) . '</td>';
                        }
                        echo '</tr>';
                    }
                    echo '</table>';
                    fclose( $handle );
                }
                ?>
            </div>
        <?php endif;
    endwhile;
endif;

get_footer();
