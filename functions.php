<?php

function university_files() {
    wp_enqueue_script('main-university-js', get_theme_file_uri('/build/index.js'), array('jquery'), '1.0', true);
    wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css'));
    wp_enqueue_style('university_extra_styles', get_theme_file_uri('/build/index.css'));
    wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
}

add_action('wp_enqueue_scripts', 'university_files');

function university_features() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails'); // this is to add the featured image option to the post
    add_image_size('professorLandscape', 400, 260, true); // this is to add a new image size for the professor image
    add_image_size('professorPortrait', 480, 650, true); // this is to add a new image size for the professor image
    add_image_size('pageBanner', 1500, 350, true); // this is to add a new image size for the page banner image


    /* register_nav_menu('headerMenuLocation', 'Header Menu Location');
    register_nav_menu('footerLocationOne', 'Footer Location One');
    register_nav_menu('footerLocationTwo', 'Footer Location Two'); */
    
}

add_action('after_setup_theme', 'university_features');

function university_adjust_queries($query) {
    if (!is_admin() AND is_post_type_archive('program') AND is_main_query()) {
        $query->set('orderby', 'title');
        $query->set('order', 'ASC');
        $query->set('posts_per_page', -1); // this is to show all posts on the page
    }


    if(!is_admin() AND is_post_type_archive('event') AND $query->is_main_query()) {
        $today = date('Ymd'); // this is the current date set as a variable
        $query->set('meta_key', 'event_date'); // meta_key is the name by which the meta_value is retrieved,
        $query->set('orderby', 'meta_value_num'); // this is to order by the meta_value as a number
        $query->set('order', 'ASC'); // this is to order the meta_value as ascending instead of descending
        $query->set('meta_query', array( // this is to filter out past events
            array( 
            'key' => 'event_date', 
            'compare' => '>=',
            'value' => $today, 
            'type' => 'numeric' // this is to make sure the date is a number
            )
        ));
    }
}

add_action('pre_get_posts', 'university_adjust_queries');



/* CREATED NEW FILE CALLED 'university-post-types.php' 'mu-plugins' FOLDER IN 'wp-content' folder with the below function which adds a new custom post type called events, below is the code which is in this new file

<?php 
**** Registering a new custome post type in WordPress named "Event"
**event post type
function university_post_types() {
    register_post_type('event', array(
    'public' => true, 
    'show_in_rest' => true, // This will enable the Gutenberg editor for this post type
    'labels' => array(
    'name' => 'Events', // This will change the name of the post type to "Events" instead of "Event"
    'add_new_item' => 'Add New Event',
    'edit_item' => 'Edit Event',
    'all_items' => 'All Events',
    'singular_name' => 'Event'
    ),
    'menu_icon' => 'dashicons-calendar-alt', // This will change the icon of the post type to a calendar icon
    'has_archive' => true, // This will enable the archive page for this post type
    'rewrite' => array(
    'slug' => 'events' // This will change the slug of the archive page to "events" instead of "event"
    ),
    'supports' => array('title', 'editor', 'excerpt') // This will enable the title, editor and excerpt fields in custom post types
    
    ));
    
    **program post type
    
    register_post_type('program', array(
        'public' => true, 
        'show_in_rest' => true, // This will enable the Gutenberg editor for this post type
        'labels' => array(
        'name' => 'Programs', // This will change the name of the post type to "Events" instead of "Event"
        'add_new_item' => 'Add New Program',
        'edit_item' => 'Edit Program',
        'all_items' => 'All Programs',
        'singular_name' => 'Program'
        ),
        'menu_icon' => 'dashicons-awards', // This will change the icon of the post type to a calendar icon
        'has_archive' => true, // This will enable the archive page for this post type
        'rewrite' => array(
        'slug' => 'programs' // This will change the slug of the archive page to "events" instead of "event"
        ),
        'supports' => array('title', 'editor') // This will enable the title, editor and excerpt fields in custom post types
        
        ));

        **professors post type

register_post_type('professor', array(
    'supports' => array('title', 'editor', 'thumbnail'), // This will enable the title, editor and excerpt fields in custom post types
    'public' => true, 
    'labels' => array(
        'name' => 'Professors', // This will change the name of the post type to "professors" instead of "professor"
        'add_new_item' => 'Add New Professor',
        'edit_item' => 'Edit Professor',
        'all_items' => 'All Professors',
        'singular_name' => 'professor'
        ),
    'show_in_rest' => true, // This will enable the Gutenberg editor for this post type
    'menu_icon' => 'dashicons-welcome-learn-more' // This will change the icon of the post type to a calendar icon
    ));
    }
    
    add_action( 'init', 'university_post_types' );
    
    
    
    

*/