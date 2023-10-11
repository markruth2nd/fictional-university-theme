<?php

get_header();
pageBanner(array(
    'title' => 'All Events',
    'subtitle' => 'See what is going on in our world!'
));
?>

<div class="container container--narrow page-section">
    <?php 
    while(have_posts()) {
        the_post(); 
        
        get_template_part('template-parts/content-event'); // this is to call the event.php file
    }
        echo paginate_links();
    ?>

        <hr class="section-break">
    <p>See all previous events <a href="<?php echo site_url('/past-events') ?>">HERE!</a></p>

</div>

<?php get_footer();

?>