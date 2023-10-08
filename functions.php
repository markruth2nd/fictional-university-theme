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
    register_nav_menu('headerMenuLocation', 'Header Menu Location');
    register_nav_menu('footerLocationOne', 'Footer Location One');
    register_nav_menu('footerLocationTwo', 'Footer Location Two');
    add_theme_support('title-tag');
}
add_action('after_setup_theme', 'university_features');

function university_adjust_queries($query) {
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
**** Registering a new custome post type in WordPress named "Event" ****

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
    }
    
    add_action( 'init', 'university_post_types' );
    
    

*/