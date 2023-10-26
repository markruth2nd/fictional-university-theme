<?php

get_header();


while (have_posts()) {
    the_post();
    pageBanner();
?>

    <div class="container container--narrow page-section">
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p>
                <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('campus') ?>"><i class="fa fa-home" aria-hidden="true"></i> All Campuses</a> <span class="metabox__main"><?php the_title(); ?></span>
            </p>
        </div>
        <div class="generic-content"><?php the_content(); ?></div>

        <?php
        $mapLocation = get_field('map_location');
        ?>

        <div class="acf-map">

                <div class="marker" data-lat="<?php echo $mapLocation['lat']; ?>" data-lng="<?php echo $mapLocation['lng']; ?>">
                    <h3><?php the_title(); ?></h3>
                    <?php echo $mapLocation['address']; ?>
                </div>

        </div>

        <?php

        $relatedPrograms = new WP_Query(array(
            'posts_per_page' => -1,
            'post_type' => 'program',
            'orderby' => 'title',
            'order' => 'ASC',
            'meta_query' => array(
                array( // this nested array is to filter out events that are not related to the program for upcoming events which are related to the program
                    'key' => 'related_campus',
                    'compare' => 'LIKE',
                    'value' => '"' . get_the_ID() . '"' // this is to make sure the ID is not a part of another ID
                )
            )
        ));

        if ($relatedPrograms->have_posts()) { // this if statement is to check if there are any upcoming events related to the program and then will add the below code
            echo '<hr class="section-break">';
            echo '<h2 class="headline headline--medium">' . get_the_title() . ' Programs available at this campus</h2>';


            echo '<ul class="min-list link-list">';
            while ($relatedPrograms->have_posts()) { // this while loop is to display the upcoming events related to the program
                $relatedPrograms->the_post(); ?>
                <li >
                    <a  href="<?php the_permalink(); ?>"> <?php the_title(); ?>

                    </a>
                </li>
        <?php }
            echo '</ul>';
        }


        wp_reset_postdata(); // this is to reset the global variable $post so that it doesn't interfere with other queries
        $relatedCampuses = get_field('related_campus');

        if ($relatedCampuses) {
            echo '<hr class="section-break">';
            echo '<h2 class="headline headline--medium">Campuses Available</h2>';
            echo '<ul class="min-list link-list">';
            foreach ($relatedCampuses as $campus) { ?>
                <li><a href="<?php echo get_the_permalink($campus); ?>"><?php echo get_the_title($campus); ?></a></li>
        <?php }
            echo '</ul>';
        }
        ?>

    </div>

    <hr>
<?php }

get_footer();
?>