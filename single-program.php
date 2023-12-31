<?php

get_header(); 


    while(have_posts()) {
        the_post(); 
        pageBanner();
        ?>

        <div class="container container--narrow page-section">
            <div class="metabox metabox--position-up metabox--with-home-link">
                <p>
                    <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('program') ?>"><i class="fa fa-home" aria-hidden="true"></i> All programs</a> <span class="metabox__main"><?php the_title(); ?></span>
                </p>
            </div>
            <div class="generic-content"><?php the_field('main_body_content'); ?></div>

            <?php

            $relatedProfessors = new WP_Query(array(
                'posts_per_page' => -1,
                'post_type' => 'professor',
                'orderby' => 'title',
                'order' => 'ASC',
                'meta_query' => array(
                    array( // this nested array is to filter out events that are not related to the program for upcoming events which are related to the program
                        'key' => 'related_programs', 
                        'compare' => 'LIKE',
                        'value' => '"' . get_the_ID() . '"' // this is to make sure the ID is not a part of another ID
                    )
                )
            ));

            if ($relatedProfessors->have_posts()) { // this if statement is to check if there are any upcoming events related to the program and then will add the below code
                echo '<hr class="section-break">';
                echo '<h2 class="headline headline--medium">' . get_the_title() . ' Professors</h2>';


                echo '<ul class="professor-cards">';
            while($relatedProfessors->have_posts()) { // this while loop is to display the upcoming events related to the program
                $relatedProfessors->the_post(); ?>
                <li class="professor-card__list-item">
                    <a class="professor-card" href="<?php the_permalink(); ?>">
                        <img class="professor-card__image" src="<?php the_post_thumbnail_url('professorLandscape') ?>">
                        <span class="professor-card__name"><?php the_title(); ?></span>
                    
                    </a>
                </li>
            <?php }
            echo '</ul>';
            }

            wp_reset_postdata(); // this is to reset the global variable $post so that it doesn't interfere with other queries

            $today = date('Ymd');
            $homepageEvents = new WP_Query(array(
                'posts_per_page' => 2,
                'post_type' => 'event',
                'meta_key' => 'event_date',
                'orderby' => 'meta_value_num',
                'order' => 'ASC',
                'meta_query' => array(
                    array(
                    'key' => 'event_date',
                    'compare' => '>=',
                    'value' => $today,
                    'type' => 'numeric'
                    ),
                    array( // this nested array is to filter out events that are not related to the program for upcoming events which are related to the program
                        'key' => 'related_programs', 
                        'compare' => 'LIKE',
                        'value' => '"' . get_the_ID() . '"' // this is to make sure the ID is not a part of another ID
                    )
                )
            ));

            if ($homepageEvents->have_posts()) { // this if statement is to check if there are any upcoming events related to the program and then will add the below code
                echo '<hr class="section-break">';
                echo '<h2 class="headline headline--medium">Upcoming ' . get_the_title() . ' Events</h2>';


            while($homepageEvents->have_posts()) { // this while loop is to display the upcoming events related to the program
                $homepageEvents->the_post(); 
                get_template_part('template-parts/content-event'); // this is to call the event.php file
            }
            }
            ?>

        </div>
        
        <hr>
    <?php }

get_footer();
?>