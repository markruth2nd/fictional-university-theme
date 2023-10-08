<?php

get_header();
?>

<div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('images/ocean.jpg') ?>)"></div>
        <div class="page-banner__content container container--narrow">
    <h1 class="page-banner__title">All Programs</h1>
        
    <!-- The below is a breakdown of the_archive_title(); function if need to do seperately -->
<!-- <?php if (is_category()) { single_cat_title(); } if (is_author()) { echo 'Posts by '; the_author();} ?>-->
    <div class="page-banner__intro">
        <p>There is somethign for everyone, have a look around.</p>
        </div>
    </div>
</div>

<div class="container container--narrow page-section">

    <ul class="link-list min-list">
    <?php 
    while(have_posts()) {
        the_post(); ?>

    <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>

        <?php }
        echo paginate_links();
    ?>
    </ul>

</div>

<?php get_footer();

?>