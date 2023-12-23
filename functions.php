<?php

require get_theme_file_path('/inc/search-route.php');

function university_custom_rest() {
    register_rest_field('post', 'authorName', array(
        'get_callback' => function() {return get_the_author();}
    ));

    register_rest_field('note', 'userNoteCount', array(
        'get_callback' => function() {return count_user_posts(get_current_user_id(), 'note');}
    ));
}

add_action('rest_api_init', 'university_custom_rest');

function pageBanner($args = NULL) {
    if (!isset($args['title'])) {
        $args['title'] = get_the_title();
    }
    if (!isset($args['subtitle']))  {
        $args['subtitle'] = get_field('page_banner_subtitle');
    }
    if (!isset($args['photo'])) {
        if (get_field('page_banner_background_image') AND !is_archive() AND !is_home()) {
            $args['photo'] = get_field('page_banner_background_image')['sizes']['pageBanner'];
        } else {
            $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
        }
    }
    ?>
    <div class="page-banner">
            <div class="page-banner__bg-image" style="background-image: url(<?php echo $args['photo'] ?>);"></div>
                <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title"><?php echo $args['title'] ?></h1>
            <div class="page-banner__intro">
            <p><?php echo $args['subtitle'] ?></p>
                </div>
            </div>
        </div>
<?php
}

function university_files() {
    wp_enqueue_script('googleMap', '//maps.googleapis.com/maps/api/js?key=AIzaSyCeSa4OEpOmz_YhGZln5ZXO4TpFk225IwA',NULL, '1.0', true);
    wp_enqueue_script('main-university-js', get_theme_file_uri('/build/index.js'), array('jquery'), '1.0', true);
    wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css'));
    wp_enqueue_style('university_extra_styles', get_theme_file_uri('/build/index.css'));
    wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');

    wp_localize_script( 'main-university-js', 'universityData' , array(
        'root_url' => get_site_url(),
        'nonce' => wp_create_nonce('wp_rest')
    ));
}

add_action('wp_enqueue_scripts', 'university_files');

function university_features() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails'); // this is to add the featured image option to the post
    add_image_size('professorLandscape', 400, 260, true); // this is to add a new image size for the professor image
    add_image_size('professorPortrait', 480, 650, true); // this is to add a new image size for the professor image
    add_image_size('pageBanner', 1500, 350, true); // this is to add a new image size for the page banner image


    register_nav_menu('headerMenuLocation', 'Header Menu Location');
    register_nav_menu('footerLocationOne', 'Footer Location One');
    register_nav_menu('footerLocationTwo', 'Footer Location Two');
    
}

add_action('after_setup_theme', 'university_features');

function university_adjust_queries($query) {
    if (!is_admin() AND is_post_type_archive('campus') AND is_main_query()) {
        $query->set('posts_per_page', -1); // this is to show all posts on the campus map in campuses page
    }

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

function universityMapKey($api) {
    $api['key'] = 'AIzaSyCeSa4OEpOmz_YhGZln5ZXO4TpFk225IwA';
    return $api;
}


add_filter('acf/fields/google_map/api', 'universityMapKey');

// Redirect subscriber accounts out of admin and onto homepage
add_action('admin_init', 'redirectSubsToFrontend');

function redirectSubsToFrontend() {
    $ourCurrentUser = wp_get_current_user();
    if (count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles[0] == 'subscriber') {
        wp_redirect(site_url('/'));
        exit;
    }
}

add_action('wp_loaded', 'noSubsAdminBar');

function noSubsAdminBar() {
    $ourCurrentUser = wp_get_current_user();
    if (count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles[0] == 'subscriber') {
        show_admin_bar(false);
    }
}

// Customize login screen
add_filter('login_headerurl', 'ourHeaderUrl');

function ourHeaderUrl() {
    return esc_url(site_url('/'));
}

// this is to change the name of the login screen from "Powered by WordPress" to "Return to Fictional University" and loads in your own CSS file
add_action('login_enqueue_scripts', 'ourLoginCSS');

function ourLoginCSS() {
    wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css'));
    wp_enqueue_style('university_extra_styles', get_theme_file_uri('/build/index.css'));
    wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
}

// this is to change the name of the login screen from "Powered by WordPress" to "Return to Fictional University"
add_filter('login_headertitle', 'ourLoginTitle');

function ourLoginTitle() {
    return get_bloginfo('name');
}


// Force note posts to be private
add_filter('wp_insert_post_data', 'makeNotePrivate', 10, 2);

function makeNotePrivate($data, $postarr) {
    if ($data['post_type'] == 'note') {
        if (count_user_posts(get_current_user_id(), 'note') > 4 AND !$postarr['ID']) {
            die("You have reached your note limit.");
        }

        $data['post_content'] = sanitize_textarea_field($data['post_content']);
        $data['post_title'] = sanitize_text_field($data['post_title']);
    }

    if ($data['post_type'] == 'note' AND $data['post_status'] != 'trash') {
        $data['post_status'] = "private";
    }

    return $data;
}

/* CREATED NEW FILE CALLED 'university-post-types.php' 'mu-plugins' FOLDER IN 'wp-content' folder with the below function which adds a new custom post type called events, below is the code which is in this new file

<?php 

**** Registering a new custome post type in WordPress named "Campus"
**campus post type



<?php 
/* Registering a new custome post type in WordPress named "Event" */
// event post type
/*
function university_post_types()
{
    register_post_type('campus', array(
        'public' => true, //makes it visible to the public and other editors
        'show_in_rest' => true, //makes it visible in the REST API
        'labels' => array( //changes the name of the post type in the admin panel and other admin areas/items
            'name' => 'Campuses', //changes the name of the post type in the admin panel to Campuss
            'add_new_item' => 'Add New Campus', //changes the name of the post type in the admin panel to add new Campus
            'edit_item' => 'Edit Campus', //changes the name of the post type in the admin panel to edit Campus
            'all_items' => 'All Campuses', //changes the name of the post type in the admin panel to all Campuss
            'singular_name' => 'Campus' //changes the name of the post type in the admin panel to Campus
        ),
        'menu_icon' => 'dashicons-location-alt', // This will change the icon of the post type to a calendar icon
        'has_archive' => true, // This will enable the archive page for this post type
        'rewrite' => array(
            'slug' => 'campuses' // This will change the slug of the archive page to "events" instead of "event"
        ),
        'supports' => array('title', 'editor', 'excerpt'), // This will enable the title, editor and excerpt fields in custom post types
    'capability_type' => 'campus', // This will change the capability type to "event" instead of "post"
    'map_meta_cap' => true // This will enable the capability to add events to only the users who have the "edit_events" capability // This will enable the title, editor and excerpt fields in custom post types

    ));

    register_post_type('event', array(
        'public' => true, //makes it visible to the public and other editors
        'show_in_rest' => true, //makes it visible in the REST API
        'labels' => array( //changes the name of the post type in the admin panel and other admin areas/items
            'name' => 'Events', //changes the name of the post type in the admin panel to Events
            'add_new_item' => 'Add New Event', //changes the name of the post type in the admin panel to add new Event
            'edit_item' => 'Edit Event', //changes the name of the post type in the admin panel to edit Event
            'all_items' => 'All Events', //changes the name of the post type in the admin panel to all Events
            'singular_name' => 'Event' //changes the name of the post type in the admin panel to Event
        ),
        'menu_icon' => 'dashicons-calendar-alt', // This will change the icon of the post type to a calendar icon
        'has_archive' => true, // This will enable the archive page for this post type
        'rewrite' => array(
            'slug' => 'events' // This will change the slug of the archive page to "events" instead of "event"
        ),
        'supports' => array('title', 'editor', 'excerpt'), // This will enable the title, editor and excerpt fields in custom post types
    'capability_type' => 'event', // This will change the capability type to "event" instead of "post"
    
    'map_meta_cap' => true // This will enable the capability to add events to only the users who have the "edit_events" capability // This will enable the title, editor and excerpt fields in custom post types

    ));

    //program post type

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
        'supports' => array('title') // This will enable the title, editor and excerpt fields in custom post types

    ));

    //professors post type

    register_post_type('professor', array(
        'show_in_rest' => true, // This will enable the Gutenberg editor for this post type
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

add_action('init', 'university_post_types');

*/

