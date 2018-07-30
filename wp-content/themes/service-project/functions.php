<?php
/*This file is part of service-project, twentyseventeen child theme.

All functions of this file will be loaded before of parent theme functions.
Learn more at https://codex.wordpress.org/Child_Themes.

Note: this function loads the parent stylesheet before, then child theme stylesheet
(leave it in place unless you know what you are doing.)
*/

function service_project_enqueue_child_styles() {
$parent_style = 'parent-style'; 
	wp_enqueue_style($parent_style, get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 
		'child-style', 
		get_stylesheet_directory_uri() . '/style.css',
		array( $parent_style ),
		wp_get_theme()->get('Version') );
	}
add_action( 'wp_enqueue_scripts', 'service_project_enqueue_child_styles' );

/*Write here your own functions */

/************ create user role ***************/

$result = add_role(
            'service-provider',
    __('Service Provider'),
            array(
                'read' => true, // true allows this capability
                'edit_posts' => true, // Allows user to edit their own posts
                'edit_pages' => false, // Allows user to edit pages
                'edit_others_posts' => false, // Allows user to edit others posts not just their own
                'create_posts' => true, // Allows user to create new posts
                'manage_categories' => false, // Allows user to manage post categories
                'publish_posts' => false, // Allows the user to publish, otherwise posts stays in draft mode
            )
);




/*
* Creating a function to create our CPT
*/

 
function custom_post_type() {
 
// Set UI labels for Custom Post Type
    $labels = array(
        'name'                => _x( 'Services', 'Post Type General Name', 'twentythirteen' ),
        'singular_name'       => _x( 'Service', 'Post Type Singular Name', 'twentythirteen' ),
        'menu_name'           => __( 'Services', 'twentythirteen' ),
        'parent_item_colon'   => __( 'Parent Service', 'twentythirteen' ),
        'all_items'           => __( 'All Services', 'twentythirteen' ),
        'view_item'           => __( 'View Service', 'twentythirteen' ),
        'add_new_item'        => __( 'Add New Service', 'twentythirteen' ),
        'add_new'             => __( 'Add New', 'twentythirteen' ),
        'edit_item'           => __( 'Edit Service', 'twentythirteen' ),
        'update_item'         => __( 'Update Service', 'twentythirteen' ),
        'search_items'        => __( 'Search Service', 'twentythirteen' ),
        'not_found'           => __( 'Not Found', 'twentythirteen' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'twentythirteen' ),
    );
     
// Set other options for Custom Post Type
     
    $args = array(
        'label'               => __( 'services', 'twentythirteen' ),
        'description'         => __( 'Service news and reviews', 'twentythirteen' ),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
        /* A hierarchical CPT is like Pages and can have
        * Parent and child items. A non-hierarchical CPT
        * is like Posts.
        */ 
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
		// This is where we add taxonomies to our CPT
        'taxonomies'          => array( 'category','post_tag' ),
    );
     
    // Registering your Custom Post Type
    register_post_type( 'services', $args );
	
	
	
	/* Add custom Question Answer Post */
	
	// Set UI labels for Custom Post Type
    $labels = array(
        'name'                => _x( 'Questions & Answers', 'Post Type General Name', 'twentythirteen' ),
        'singular_name'       => _x( 'Question & Answer', 'Post Type Singular Name', 'twentythirteen' ),
        'menu_name'           => __( 'Questions & Answers', 'twentythirteen' ),
        'parent_item_colon'   => __( 'Parent Question & Answer', 'twentythirteen' ),
        'all_items'           => __( 'All Questions & Answers', 'twentythirteen' ),
        'view_item'           => __( 'View Question & Answer', 'twentythirteen' ),
        'add_new_item'        => __( 'Add New Question & Answer', 'twentythirteen' ),
        'add_new'             => __( 'Add New', 'twentythirteen' ),
        'edit_item'           => __( 'Edit Question & Answer', 'twentythirteen' ),
        'update_item'         => __( 'Update Question & Answer', 'twentythirteen' ),
        'search_items'        => __( 'Search Question & Answer', 'twentythirteen' ),
        'not_found'           => __( 'Not Found', 'twentythirteen' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'twentythirteen' ),
    );
     
	// Set other options for Custom Post Type
     
    $args = array(
        'label'               => __( 'Questions & Answers', 'twentythirteen' ),
        'description'         => __( 'Question & Answer news and reviews', 'twentythirteen' ),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', 'editor', 'author', 'thumbnail', 'comments', ),
        /* A hierarchical CPT is like Pages and can have
        * Parent and child items. A non-hierarchical CPT
        * is like Posts.
        */ 
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
    );
     
    // Registering your Custom Post Type
    register_post_type( 'question_answer', $args );
 
}
 
 
 
 
//hook into the init action and call create_book_taxonomies when it fires
add_action( 'init', 'create_topics_hierarchical_taxonomy', 0 );
 
//create a custom taxonomy name it topics for your posts
 
function create_topics_hierarchical_taxonomy() {
 
// Add new taxonomy, make it hierarchical like categories
//first do the translations part for GUI
 
  $labels = array(
    'name' => _x( 'Appartments', 'taxonomy general name' ),
    'singular_name' => _x( 'Appartment', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Appartment' ),
    'all_items' => __( 'All Appartments' ),
    'parent_item' => __( 'Parent Appartment' ),
    'parent_item_colon' => __( 'Parent Appartment:' ),
    'edit_item' => __( 'Edit Appartment' ), 
    'update_item' => __( 'Update Appartment' ),
    'add_new_item' => __( 'Add New Appartment' ),
    'new_item_name' => __( 'New Appartment Name' ),
    'menu_name' => __( 'Appartment' ),
  );    
 
// Now register the taxonomy
 
  register_taxonomy('appartments',array('services'), array(
    'hierarchical' => false,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'apprtment' ),
  ));
 
}
 
 
 
 
 
/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/
 
add_action( 'init', 'custom_post_type', 0 );

/* Function for move array element position. */
function moveElement(&$array, $a, $b) {
    $p1 = array_splice($array, $a, 1);
    $p2 = array_splice($array, 0, $b);
    $array = array_merge($p2,$p1,$array);
}


/* Code for display custom column in category list table*/
function my_custom_taxonomy_image_columns( $columns )
{
	
	$columns['image'] = __('Image');
	
	moveElement($columns, 5, 2);

	return $columns;
}
add_filter('manage_edit-category_columns' , 'my_custom_taxonomy_image_columns');

function my_custom_taxonomy_image_columns_content( $content, $column_name, $term_id )
{
    if ( 'image' == $column_name ) {
		
		$post_id = "category_".$term_id; // category term ID = 3
		
		$image = get_field('image',$post_id);
		
		$size = array('50','50'); // (thumbnail, medium, large, full or custom size)

		if( $image ) {
			$content = wp_get_attachment_image($image['id'],$size,true);
		}
		
    }
	return $content;
}
add_filter( 'manage_category_custom_column', 'my_custom_taxonomy_image_columns_content', 10, 3 );


/* Code for display custom column in category list table*/

function new_modify_user_table( $column ) {
    $column['status'] = 'Status';
    return $column;
}
add_filter( 'manage_users_columns', 'new_modify_user_table' );

function new_modify_user_table_row( $val, $column_name, $user_id ) {
     if ( 'status' == $column_name ) {
		
		
		$status = get_field('status','user_'.$user_id);

		if( !empty($status) ) {
			if($status == "approved"){
				$content = "<span style='padding: 7px; display: block; width: 80px; background: green; text-align:  center;'><b style='color:white;'>".ucfirst($status)."</b></span>";
			}else if($status == "blocked"){
				$content = "<span style='padding: 7px; display: block; width: 80px; background: red; text-align:  center;'><b style='color:white;'>".ucfirst($status)."</b></span>";
			}else{
				$content = "<span style='padding: 7px; display: block; width: 80px; background: gray; text-align:  center;'><b style='color:white;'>".ucfirst($status)."</b></span>";
			}
		}else{
			$content = "<span style='padding: 7px; display: block; width: 80px; background: gray; text-align:  center;'><b style='color:white;'>Pending</b></span>";
		}
		
    }
    return $content;
}
add_filter( 'manage_users_custom_column', 'new_modify_user_table_row', 10, 3 );


/* Remove unwanted pages from admin panel */
function remove_menus(){
  // remove_menu_page( 'edit.php' );                   //Posts
  remove_menu_page( 'edit.php?post_type=page' );    //Pages
}
add_action( 'admin_menu', 'remove_menus' );

// fix sized images generate for products & categories
add_action('after_setup_theme', 'wpdocs_theme_setup');
function wpdocs_theme_setup()
{
    add_image_size('category-thumb', 100); // 300 pixels wide (and unlimited height)
    add_image_size('category-midum', 200); // 300 pixels wide (and unlimited height)
    add_image_size('product-thumb', 200); // (cropped)
    add_image_size('product-midum', 500); // (cropped)
}


// // check mobile number exists or not
// add_filter('acf/validate_value/name=mobile_no', 'my_acf_validate_user_mobile', 10, 4);

// function my_acf_validate_user_mobile( $valid, $value, $field, $input ){
	
	// // bail early if value is already invalid
	// if( !$valid ) {
		// return $valid;
	// }
	
	// // check mobile number exists or not
	
	
	// $valid = 'Mobile no already exists';
	// // return
	// return $valid;	
// }




function mbile_no_exists($input_mobile_no){
	$users = get_users( array( 'fields' => array( 'ID' ) ) );
	foreach($users as $user){
		$user_id = $user->ID;
		if($input_mobile_no == get_field('mobile_no','user_'.$user_id)){
			return true;
		}
	}
	return false;
}


// Register user field for acf plugin addon
if(function_exists('register_field')) {
	register_field('Users_field', dirname(File) . '/acf_addons/users_field.php');
}


//Google map apikey registeration
// function my_acf_google_map_api( $api ){
	// $api['key'] = 'AIzaSyAs-Pz7SI6DKMS0xfK3QBc6z-9RLPwmS0U';
	// return $api;
// }
// add_filter('acf/fields/google_map/api', 'my_acf_google_map_api');
function my_acf_init() {
	
	acf_update_setting('google_api_key', 'AIzaSyAs-Pz7SI6DKMS0xfK3QBc6z-9RLPwmS0U');
}

add_action('acf/init', 'my_acf_init');






