<?php
   /*
   Plugin Name: Sermon Management
   Plugin URI: https://dgtl.church/
   Description: Enable the Sermon Content Library.
   Version: 2.0
   Author: Digital Church
   Author URI: https://dgtl.church/
   License: GPL2
   */
   
// Register Custom Post Type for Sermons

function custom_post_type_sermon() {

	$labels = array(
		'name'                  => _x( 'Sermons', 'Post Type General Name', 'text_domain' ),
		'singular_name'         => _x( 'Sermon', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'             => __( 'Sermons', 'text_domain' ),
		'name_admin_bar'        => __( 'Sermon', 'text_domain' ),
		'archives'              => __( 'Sermon Archives', 'text_domain' ),
		'attributes'            => __( 'Sermon Attributes', 'text_domain' ),
		'parent_item_colon'     => __( 'Parent Sermon:', 'text_domain' ),
		'all_items'             => __( 'All Sermons', 'text_domain' ),
		'add_new_item'          => __( 'Add New Sermon', 'text_domain' ),
		'add_new'               => __( 'New Sermon', 'text_domain' ),
		'new_item'              => __( 'New Sermon', 'text_domain' ),
		'edit_item'             => __( 'Edit Sermon', 'text_domain' ),
		'update_item'           => __( 'Update Sermon', 'text_domain' ),
		'view_item'             => __( 'View Sermon', 'text_domain' ),
		'view_items'            => __( 'View Sermons', 'text_domain' ),
		'search_items'          => __( 'Search Sermons', 'text_domain' ),
		'not_found'             => __( 'Not found', 'text_domain' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
		'featured_image'        => __( 'Featured Image', 'text_domain' ),
		'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
		'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
		'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
		'insert_into_item'      => __( 'Insert into sermon', 'text_domain' ),
		'uploaded_to_this_item' => __( 'Uploaded to this sermon', 'text_domain' ),
		'items_list'            => __( 'Sermons list', 'text_domain' ),
		'items_list_navigation' => __( 'Sermons list navigation', 'text_domain' ),
		'filter_items_list'     => __( 'Filter sermons list', 'text_domain' ),
	);
	$args = array(
		'label'                 => __( 'Sermon', 'text_domain' ),
		'description'           => __( 'Sermon Description', 'text_domain' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'comments', 'editor' ),
		'taxonomies'            => array( 'category', 'post_tag' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-slides',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'post',
	);
	register_post_type( 'sermon', $args );

}
add_action( 'init', 'custom_post_type_sermon', 0 );
add_post_type_support( 'sermon', 'thumbnail' ); post_type_supports( 'sermon', 'thumbnail' );

// Add Custom Field to Sermons

add_filter( 'rwmb_meta_boxes', 'dgtl_sermons_register_meta_boxes' );
function dgtl_sermons_register_meta_boxes( $meta_boxes ) {
	$meta_boxes[] = array (
		'title' => 'Sermons',
		'id' => 'sermons',
		'post_types' => array(
			0 => 'sermon',
		),
		'context' => 'normal',
		'priority' => 'high',
		'autosave' => true,
		'fields' => array(
			array (
				'id' => 'speaker',
				'type' => 'post',
				'name' => 'Speaker',
				'post_type' => array(
					0 => 'team-members',
				),
				'field_type' => 'select_advanced',
				'columns' => 6,
				'after' => '<a class="rwmb-button button-primary" href="/wp-admin/post-new.php?post_type=team-members" target="_blank">+New Team Member</a>',
				'admin_columns' => 'after title',
				'tooltip' => 'Select a speaker from your available team members. If the speaker doesn\'t have a team member entry, please add that first.',
			),
			array (
				'id' => 'date',
				'type' => 'date',
				'name' => 'Date Preached',
				'columns' => 6,
				'inline' => false,
				'admin_columns' => 'replace date',
				'tooltip' => 'Use the date picker to select the date this sermon was preached.',
			),
			array (
				'id' => 'series_taxonomy',
				'type' => 'taxonomy',
				'name' => 'Sermon Series',
				'taxonomy' => 'sermon_series',
				'field_type' => 'select_advanced',
				'columns' => 6,
				'multiple' => true,
				'tooltip' => 'Enter the series link here. This is a taxonomy that must be selected on both the sermon and the sermon series to connect the two together.',
			),
			array (
				'id' => 'background_image',
				'type' => 'single_image',
				'name' => 'Background Artwork',
				'columns' => 6,
				'tooltip' => 'The background image is usually similar to the sermon artwork, but without any text. This will be displayed in the background and will be automatically darkened a bit so that white text is readable over it.',
			),
			array (
				'id' => 'video',
				'type' => 'oembed',
				'name' => 'Video',
				'columns' => 6,
				'tooltip' => 'Paste in your video URL from a service like YouTube, Vimeo, or Facebook.',
			),
			array(
			    'id'       => 'sermon_buttons',
			    'name'     => 'Buttons',
			    'type'     => 'button_group',
			    'options'  => array(
			        'notes'      => 'Download Notes',
			    //    'video'    => 'Download Video',
			    //    'audio' => 'Download Audio',
			        'misc' => 'Miscellaneous',
			    ),
			    'inline'   => true,
			    'multiple' => true,
			),
			array (
				'id' => 'sermon_notes_file',
				'visible' => array( 'sermon_buttons', 'contains', 'notes' ),
				'type' => 'file_advanced',
				'name' => 'Sermon Notes',
				'tooltip' => 'Upload a PDF document here. For best results, use a compressed pdf.',
			),
			array (
				'id' => 'sermon_button_text',
				'visible' => array( 'sermon_buttons', 'contains', 'misc' ),
				'type' => 'text',
				'name' => 'Misc Button Text',
				'columns' => 6,
				'tooltip' => 'Add text for your button here.',
			),
			array (
				'id' => 'sermon_button_url',
				'visible' => array( 'sermon_buttons', 'contains', 'misc' ),
				'type' => 'url',
				'name' => 'Misc Button URL',
				'columns' => 6,
				'tooltip' => 'Where do you want your button to go?',
			),
		),
	);
	return $meta_boxes;
}


// Series CPT

function custom_post_type_sermon_series() {

	$labels = array(
		'name'                  => _x( 'Sermon Series', 'Post Type General Name', 'text_domain' ),
		'singular_name'         => _x( 'Sermon Series', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'             => __( 'Sermon Series', 'text_domain' ),
		'name_admin_bar'        => __( 'Sermon Series', 'text_domain' ),
		'archives'              => __( 'Sermon Series Archives', 'text_domain' ),
		'attributes'            => __( 'Sermon Series Attributes', 'text_domain' ),
		'parent_item_colon'     => __( 'Parent Sermon Series:', 'text_domain' ),
		'all_items'             => __( 'All Sermon Series', 'text_domain' ),
		'add_new_item'          => __( 'Add New Sermon Series', 'text_domain' ),
		'add_new'               => __( 'New Series', 'text_domain' ),
		'new_item'              => __( 'New Sermon Series', 'text_domain' ),
		'edit_item'             => __( 'Edit Sermon Series', 'text_domain' ),
		'update_item'           => __( 'Update Sermon Series', 'text_domain' ),
		'view_item'             => __( 'View Sermon Series', 'text_domain' ),
		'view_items'            => __( 'View Sermon Series', 'text_domain' ),
		'search_items'          => __( 'Search Sermon Series', 'text_domain' ),
		'not_found'             => __( 'Not found', 'text_domain' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
		'featured_image'        => __( 'Featured Image', 'text_domain' ),
		'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
		'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
		'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
		'insert_into_item'      => __( 'Insert into sermon series', 'text_domain' ),
		'uploaded_to_this_item' => __( 'Uploaded to this sermon series', 'text_domain' ),
		'items_list'            => __( 'Sermon Series list', 'text_domain' ),
		'items_list_navigation' => __( 'Sermon Series list navigation', 'text_domain' ),
		'filter_items_list'     => __( 'Filter Sermon Series list', 'text_domain' ),
	);
	$args = array(
		'label'                 => __( 'Sermon Series', 'text_domain' ),
		'description'           => __( 'Sermon Series Description', 'text_domain' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor' ),
		'taxonomies'            => array( 'category', 'post_tag' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => 'edit.php?post_type=sermon',
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-exerpt-view',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'post',
	);
	register_post_type( 'sermon-series', $args );

}
add_action( 'init', 'custom_post_type_sermon_series', 0 );
add_post_type_support( 'sermon-series', 'thumbnail' ); post_type_supports( 'sermon-series', 'thumbnail' );

// Custom fields for Series CPT

add_filter( 'rwmb_meta_boxes', 'dgtl_sermon_series_register_meta_boxes' );
function dgtl_sermon_series_register_meta_boxes( $meta_boxes ) {
	$meta_boxes[] = array (
		'title' => 'Sermon Series',
		'id' => 'sermon-series',
		'post_types' => array(
			0 => 'sermon-series',
		),
		'context' => 'normal',
		'priority' => 'high',
		'fields' => array(
			array (
				'id' => 'series_bg',
				'type' => 'single_image',
				'name' => 'Background Image',
			),
		),
	);
	return $meta_boxes;
}

// Register Custom Series Taxonomy

function taxonomy_sermon_series() {

	$labels = array(
		'name'                       => _x( 'Sermon Series', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => _x( 'Series', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Series Taxonomy', 'text_domain' ),
		'all_items'                  => __( 'All Series Taxonomies', 'text_domain' ),
		'parent_item'                => __( 'Parent Series Taxonomy', 'text_domain' ),
		'parent_item_colon'          => __( 'Parent Series Taxonomy:', 'text_domain' ),
		'new_item_name'              => __( 'New Series Taxonomy Name', 'text_domain' ),
		'add_new_item'               => __( 'Add New Series Taxonomy', 'text_domain' ),
		'edit_item'                  => __( 'Edit Series Taxonomy', 'text_domain' ),
		'update_item'                => __( 'Update Series Taxonomy', 'text_domain' ),
		'view_item'                  => __( 'View Series Taxonomy', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate series with commas', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove series', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from the most used series', 'text_domain' ),
		'popular_items'              => __( 'Popular Series', 'text_domain' ),
		'search_items'               => __( 'Search Sermon Series', 'text_domain' ),
		'not_found'                  => __( 'Not Found', 'text_domain' ),
		'no_terms'                   => __( 'No series', 'text_domain' ),
		'items_list'                 => __( 'Series list', 'text_domain' ),
		'items_list_navigation'      => __( 'Series list navigation', 'text_domain' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true, // must remain true to Page Builder can use it.
		'show_in_menu'				 => false,
		'show_in_quick_edit'         => true,
		'meta_box_cb'                => false, // this removes the metabox
		'show_admin_column'          => true,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => true,
		'show_in_rest'               => true,
	);
	register_taxonomy( 'sermon_series', array( 'sermon', 'sermon-series' ), $args );

}
add_action( 'init', 'taxonomy_sermon_series', 0 );

// Register Custom Books Taxonomy

function taxonomy_sermon_book() {

	$labels = array(
		'name'                       => _x( 'Book', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => _x( 'Book', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Book', 'text_domain' ),
		'all_items'                  => __( 'All Books', 'text_domain' ),
		'parent_item'                => __( 'Parent Book', 'text_domain' ),
		'parent_item_colon'          => __( 'Parent Book:', 'text_domain' ),
		'new_item_name'              => __( 'New Book Name', 'text_domain' ),
		'add_new_item'               => __( 'Add New Book', 'text_domain' ),
		'edit_item'                  => __( 'Edit Book', 'text_domain' ),
		'update_item'                => __( 'Update Book', 'text_domain' ),
		'view_item'                  => __( 'View Book', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate books with commas', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove books', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from the most used books', 'text_domain' ),
		'popular_items'              => __( 'Popular Books', 'text_domain' ),
		'search_items'               => __( 'Search Books', 'text_domain' ),
		'not_found'                  => __( 'Not Found', 'text_domain' ),
		'no_terms'                   => __( 'No books', 'text_domain' ),
		'items_list'                 => __( 'Books list', 'text_domain' ),
		'items_list_navigation'      => __( 'Books list navigation', 'text_domain' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		'show_in_rest'               => true,
	);
	register_taxonomy( 'sermon_book', array( 'sermon', 'sermon-series' ), $args );

}
add_action( 'init', 'taxonomy_sermon_book', 0 );

// Register Custom Topics Taxonomy

function taxonomy_sermon_topic() {

	$labels = array(
		'name'                       => _x( 'Topic', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => _x( 'Topic', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Topic', 'text_domain' ),
		'all_items'                  => __( 'All Topics', 'text_domain' ),
		'parent_item'                => __( 'Parent Topic', 'text_domain' ),
		'parent_item_colon'          => __( 'Parent Topic:', 'text_domain' ),
		'new_item_name'              => __( 'New Topic Name', 'text_domain' ),
		'add_new_item'               => __( 'Add New Topic', 'text_domain' ),
		'edit_item'                  => __( 'Edit Topic', 'text_domain' ),
		'update_item'                => __( 'Update Topic', 'text_domain' ),
		'view_item'                  => __( 'View Topic', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate topics with commas', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove topics', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from the most used topics', 'text_domain' ),
		'popular_items'              => __( 'Popular Topics', 'text_domain' ),
		'search_items'               => __( 'Search Topics', 'text_domain' ),
		'not_found'                  => __( 'Not Found', 'text_domain' ),
		'no_terms'                   => __( 'No topics', 'text_domain' ),
		'items_list'                 => __( 'Topics list', 'text_domain' ),
		'items_list_navigation'      => __( 'Topics list navigation', 'text_domain' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		'show_in_rest'               => true,
	);
	register_taxonomy( 'sermon_topic', array( 'sermon', 'sermon-series' ), $args );

}
add_action( 'init', 'taxonomy_sermon_topic', 0 );



// Add New Series option to Sermons Submenu

add_action('admin_menu', 'add_new_series_admin_menu'); 
function add_new_series_admin_menu() { 
    add_submenu_page('edit.php?post_type=sermon', 'New Sermon Series', 'New Sermon Series', 'manage_options', 'post-new.php?post_type=sermon-series'); 
}  


// Automatically add each sermon-series post title as a new sermon_series taxonomy term

function add_series_taxonomy_automatically($post_ID) {
global $wpdb;
if(!has_term('','sermon_series',$post_ID)){
    $cat = get_the_title($post_ID);
    wp_set_object_terms($post_ID, $cat, 'sermon_series');
}
}
add_action('publish_sermon-series', 'add_series_taxonomy_automatically');

// Set up Metaboxes on Sermons Post Type

add_action('user_register', 'set_user_metaboxes_sermon');
add_action('admin_init', 'set_user_metaboxes_sermon');
function set_user_metaboxes_sermon($user_id=NULL) {

    // These are the metakeys we will need to update
    $meta_key['order'] = 'meta-box-order_sermon'; // set the post type here
    $meta_key['hidden'] = 'metaboxhidden_sermon'; // set the post type here

    // So this can be used without hooking into user_register
    if ( ! $user_id)
        $user_id = get_current_user_id(); 

    // Set the default order if it has not been set yet
    if ( ! get_user_meta( $user_id, $meta_key['order'], true) ) {
        $meta_value = array(
            'side' => 'postimagediv,submitdiv,formatdiv,categorydiv',
            'normal' => 'postexcerpt,tagsdiv-post_tag,postcustom,commentstatusdiv,commentsdiv,trackbacksdiv,slugdiv,authordiv,revisionsdiv,seopress_content_analysis,seopress_cpt',
            'advanced' => '',
        );
        update_user_meta( $user_id, $meta_key['order'], $meta_value );
    }

    // Set the default hiddens if it has not been set yet
    if ( ! get_user_meta( $user_id, $meta_key['hidden'], true) ) {
        $meta_value = array('postcustom','trackbacksdiv','commentstatusdiv','commentsdiv','slugdiv','authordiv','revisionsdiv','seopress_content_analysis','seopress_cpt','episode-embed-code');
        update_user_meta( $user_id, $meta_key['hidden'], $meta_value );
    }
}

// Set up Metaboxes on Sermon Series Post Type

add_action('user_register', 'set_user_metaboxes_sermon_series');
add_action('admin_init', 'set_user_metaboxes_sermon_series');
function set_user_metaboxes_sermon_series($user_id=NULL) {

    // These are the metakeys we will need to update
    $meta_key['order'] = 'meta-box-order_sermon-series'; // set the post type here
    $meta_key['hidden'] = 'metaboxhidden_sermon-series'; // set the post type here

    // So this can be used without hooking into user_register
    if ( ! $user_id)
        $user_id = get_current_user_id(); 

    // Set the default order if it has not been set yet
    if ( ! get_user_meta( $user_id, $meta_key['order'], true) ) {
        $meta_value = array(
            'side' => 'postimagediv,submitdiv,formatdiv,categorydiv','sermon_seriesdiv',
            'normal' => 'postexcerpt,tagsdiv-post_tag,postcustom,commentstatusdiv,commentsdiv,trackbacksdiv,slugdiv,authordiv,revisionsdiv,seopress_content_analysis,seopress_cpt','um-admin-restrict-content',
            'advanced' => '',
        );
        update_user_meta( $user_id, $meta_key['order'], $meta_value );
    }

    // Set the default hiddens if it has not been set yet
    if ( ! get_user_meta( $user_id, $meta_key['hidden'], true) ) {
        $meta_value = array('postcustom','trackbacksdiv','commentstatusdiv','commentsdiv','slugdiv','authordiv','revisionsdiv','seopress_content_analysis','sermon_seriesdiv','seopress_cpt');
        update_user_meta( $user_id, $meta_key['hidden'], $meta_value );
    }
}


// Add a Sermons Settings Page

add_filter( 'mb_settings_pages', 'dgtl_sermon_settings_pages' );
function dgtl_sermon_settings_pages( $settings_pages ) {
    $settings_pages[] = array(
        'id'            => 'sermon-settings',
        'menu_title'    => 'Sermons',
        'option_name'   => 'sermon_options',
        'icon_url'      => 'dashicons-images-alt',
        'submenu_title' => 'Sermons', // Note this
    );
        return $settings_pages;
}

// Register meta boxes and fields for settings page
add_filter( 'rwmb_meta_boxes', 'prefix_options_meta_boxes' );
function prefix_options_meta_boxes( $meta_boxes ) {
    $meta_boxes[] = array(
        'id'             => 'general',
        'title'          => 'General',
        'settings_pages' => 'sermon-settings',
        'tab'            => 'general',

        'fields' => array(
            array(
                'name' => 'Live Streaming Embed Code',
                'id'   => 'live-sermon-embed-code',
                'type' => 'oembed',
            ),
            array(
                'id'       => 'on_air_days',
                'name'     => 'Live Days',
                'type'     => 'button_group',
            
                'options'  => array(
                    '0'  	=> 'SUN',
                    '1'  	=> 'MON',
                    '2' 	=> 'TUE',
                    '3' 	=> 'WED',
                    '4' 	=> 'THU',
                    '5' 	=> 'FRI',
                    '6' 	=> 'SAT',
                ),
                'inline'   => true,
                'multiple' => true,
            ),
            array(
                'name'       => 'On Air',
                'id'         => 'on_air_time',
                'type'       => 'time',
            
                // Time options, see here http://trentrichardson.com/examples/timepicker/
                'js_options' => array(
                    'stepMinute'      => 5,
                    'controlType'     => 'select',
                    'showButtonPanel' => false,
                    'oneLine'         => true,
                    'timeFormat'	  => 'h:mm T',
                ),
            
                // Display inline?
                'inline'     => false,
            ),
            array(
                'name'       => 'Off Air',
                'id'         => 'off_air_time',
                'type'       => 'time',
            
                // Time options, see here http://trentrichardson.com/examples/timepicker/
                'js_options' => array(
                    'stepMinute'      => 5,
                    'controlType'     => 'select',
                    'showButtonPanel' => false,
                    'oneLine'         => true,
                    'timeFormat'	  => 'h:mm T',
                ),
            
                // Display inline?
                'inline'     => false,
            )
        ),
    );
    return $meta_boxes;
}

// Register a script for Recurring Timer in wp_head

function dgtl_live_countdown_register_scripts() {   
    wp_register_script( 'dgtl-live-timer', plugin_dir_url( __FILE__ ) . 'js/dgtl-live-timer.js', array('jquery'), '1.0' );
    wp_register_script( 'on-air-refresh', plugin_dir_url( __FILE__ ) . 'js/on-air-refresh.js', array('jquery'), '1.0' );
    wp_register_style( 'live-timer', plugin_dir_url( __FILE__ ) . 'css/live-timer.css', array(), '1.0' );
}
add_action('wp_enqueue_scripts', 'dgtl_live_countdown_register_scripts');

// Add shortcode for countdown to live timer [live-countdown]

function dgtl_live_countdown( $atts ) {
	
	$onairday = rwmb_meta( $on_air_days, array( 'object_type' => 'setting' ), $sermon_options );
	$onairtime = rwmb_meta( $on_air_time );
	$offairtime = rwmb_meta( $off_air_time );

	add_filter('widget_text', 'do_shortcode'); 
	wp_enqueue_script( 'dgtl-live-timer' ); // This makes the shortcode enqueue the script
	wp_enqueue_script( 'on-air-refresh' ); // This refreshes current visitors at on-air time
	wp_enqueue_style( 'live-timer' ); // This loads the styles for the timer

	return '<div id="countholder">
 	<div><span class="days" id="days"></span><div class="smalltext">Days</div></div>
 	<div><span class="hours" id="hours"></span><div class="smalltext">Hours</div></div>
 	<div><span class="minutes" id="minutes"></span><div class="smalltext">Minutes</div></div>
 	<div><span class="seconds" id="seconds"></span><div class="smalltext">Seconds</div></div>
	</div>
	<body onload = "getSeconds()">';
	}

add_shortcode( 'live-countdown', 'dgtl_live_countdown' );

/***** End ******/
