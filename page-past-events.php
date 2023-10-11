<?php

get_header();
pageBanner(array(
    'title' => 'Past Events!',
    'subtitle' => 'A recap of our past events.'
));
?>


<div class="container container--narrow page-section">
    <?php 

    $today = date('Ymd'); // this is the current date set as a variable
    $pastEvents = new WP_Query(array(
        'paged' => get_query_var('paged', 1), // this is to make sure pagination works kepping posts in order throughout the pages.
        'posts_per_page' => 1, // this decides how many posts per page
        'post_type' => 'event',
        'meta_key' => 'event_date', // meta_key is the name by which the meta_value is retrieved,
        'orderby' => 'meta_value_num', // this is to order by the meta_value as a number
        'order' => 'ASC', // this is to order the meta_value as ascending instead of descending
        'meta_query' => array( // this is to filter out past events
            array( 
            'key' => 'event_date', 
            'compare' => '<',
            'value' => $today, 
            'type' => 'numeric' // this is to make sure the date is a number
            )
        )
    ));

    while($pastEvents->have_posts()) {
        $pastEvents->the_post(); ?>

<div class="event-summary">
<a class="event-summary__date t-center" href="#">
        <span class="event-summary__month"><?php 
        $eventDate = new DateTime(get_field('event_date'));
        echo $eventDate->format('M');
        ?></span>
        <span class="event-summary__day"><?php
        echo $eventDate->format('d');
        ?></span>
    </a>
        <div class="event-summary__content">
        <h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
        <p><?php echo wp_trim_words(get_the_content(), 18); ?><a href="<?php the_permalink(); ?>" class="nu gray">Learn more</a></p>
    </div>
</div>

    <?php }
        echo paginate_links(array(
            'total' => $pastEvents->max_num_pages // this is to make sure pagination works to the max number of pages for the past events page
        ));
    ?>
</div>

<?php get_footer();

?>

