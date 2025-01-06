<?php 
function university_post_types() {
    //Event Post Type
    register_post_type('event',array(
        'has_archive' => true,
        'public' => true,
        'show_in_rest' => true,
        'supports' => array('title', 'editor', 'excerpt'),
        'menu_icon' => 'dashicons-calendar',
        'labels' => array(
            'name' => 'Events',
            'edit_item' => 'Edit Event',
            'all_items' => 'All Events',
            'add_new_item' => 'Add new event',
            'singular_name' => 'Event'
        )
        ));
        //Program Post Type
        register_post_type('program',array(
            'has_archive' => true,
            'public' => true,
            'show_in_rest' => true,
            'supports' => array('title', 'editor'),
            'menu_icon' => 'dashicons-awards',
            'labels' => array(
                'name' => 'Programs',
                'edit_item' => 'Edit Program',
                'all_items' => 'All Programs',
                'add_new_item' => 'Add new program',
                'singular_name' => 'Program'
            )
            ));
        //Professors Post Type
        register_post_type('professor', array(
            'public' => true,
            'show_in_rest' => true,
            'supports' => array('title', 'editor', 'thumbnail'),
            'menu_icon' => 'dashicons-welcome-learn-more',
            'labels' => array(
                'name' => 'Professors',
                'edit_item' => 'Edit Professor',
                'all_items' => 'All Professors',
                'add_new_item' => 'Add new professor',
                'singular_name' => 'Professor'
            )
            ));
        //Event Post Type
        register_post_type('campus',array(
            'has_archive' => true,
            'public' => true,
            'show_in_rest' => true,
            'supports' => array('title', 'editor', 'excerpt'),
            'menu_icon' => 'dashicons-location-alt',
            'labels' => array(
                'name' => 'Campuses',
                'edit_item' => 'Edit Campus',
                'all_items' => 'All Campuses',
                'add_new_item' => 'Add new Campus',
                'singular_name' => 'Campus'
            )
            ));
}
add_action('init' , 'university_post_types');


function pageBanner($args = []) { 
    if (empty($args['title'])) { // check if the key is exist in array and have value
        $args['title'] = get_the_title();
    }

    if (!isset($args['subtitle'])){ // check if the key is exist in array
        $args['subtitle'] = get_field('page_banner');
    }
    
    if (!isset($args['photo'])) {
        if (get_field('page_banner_background_image')) {
            $args['photo'] = get_field('page_banner_background_image')['sizes']['pageBanner'];
        } else{
            $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
        }
    }
    ?>
<div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url(<?php echo $args['photo']; ?>);"></div>
    <div class="page-banner__content container container--narrow">
        <h1 class="page-banner__title"><?php echo $args['title'];?></h1>
        <div class="page-banner__intro">
            <p><?php  echo $args['subtitle']; ?></p>
        </div>
    </div>
</div>
<?php }; 

function university_files() { 
    wp_enqueue_script('googleMap', '//maps.googleapis.com/maps/api/js?key=', NULL, '1.0',true);
    wp_enqueue_script('main_university_js', get_theme_file_uri('/build/index.js'), array('jquery'), '1.0',true);
    wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css'));
    wp_enqueue_style('university_extra_styles', get_theme_file_uri('/build/index.css'));
    wp_enqueue_style('font-google', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');

}
add_action('wp_enqueue_scripts', 'university_files');

function university_boda(){
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_image_size('professorLandscape', 400, 260, true);
    add_image_size('professorPortrait', 480, 650, true);
    add_image_size('pageBanner', 1500, 350, true);
}
add_action('after_setup_theme', 'university_boda');

function university_adjust_queries($query){
    $today = date('Ymd');
    if(!is_admin() AND is_post_type_archive('event') AND $query->is_main_query()) {
        $query->set('meta_key', 'event_date');
        $query->set('orderby', 'meta_value_num');
        $query->set('order', 'ASC');
        $query->set('meta_query', array(
            array(
                'key' => 'event_date',
                'compare' => '>=',
                'value' => $today,
                'type' => 'numeric'
            )
        ));
    }
    if(!is_admin() AND is_post_type_archive('program') AND $query->is_main_query()) {
        $query->set('posts_per_page', -1);
        $query->set('orderby', 'title');
        $query->set('order', 'ASC');
    }
    if(!is_admin() AND is_post_type_archive('campus') AND $query->is_main_query()) {
        $query->set('posts_per_page', -1);
    }
}
add_action('pre_get_posts' , 'university_adjust_queries');


?>

