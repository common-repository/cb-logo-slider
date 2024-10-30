<?php
/*
Plugin Name: CB Logo Slider
Plugin URI: https://profiles.wordpress.org/cynob
Description: This plugin will add a responsive logo slider in your wordpress site.
Author: cynob
Contributors: cynob
Version: 1.0.0
Author URI: http://cynob.com
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define('CBLS_PAGE_DIR', plugin_dir_path(__FILE__).'pages/');
define('CBLS_INCLUDE_URL', plugin_dir_url(__FILE__).'includes/');

//Include menu
function cbls_logo_plugin_menu() {
	add_menu_page("", "", "administrator", "logo-page-setting", "cbls_logo_plugin_pages", '');
	add_submenu_page("edit.php?post_type=cbls_logo_slider", "Logo Slider Setting", "Logo Slider Setting", "administrator", "logo-page-setting", "cbls_logo_plugin_pages");
	add_submenu_page("edit.php?post_type=cbls_logo_slider", "About Us", "About Us", "administrator", "about-us", "cbls_logo_plugin_pages");
}

add_action("admin_menu", "cbls_logo_plugin_menu");
function cbls_logo_plugin_pages() {
   $itm = CBLS_PAGE_DIR.$_GET["page"].'.php';
   include($itm);
}

//Include css
function cbls_css_add_init() {
    wp_enqueue_style("cbls_css_and_js", CBLS_INCLUDE_URL."front-style.css", false, "1.0", "all"); 
	wp_enqueue_script('cbls_css_and_js');
}
add_action( 'wp_enqueue_scripts', 'cbls_css_add_init' );

//add admin css
function cbls_admin_css() {
  wp_register_style('admin_css', plugins_url('includes/admin-style.css',__FILE__ ));
  wp_enqueue_style('admin_css');
}
add_action( 'admin_init','cbls_admin_css');

function cbls_slider_trigger() {
	
	//getting all settings
	$items = get_option('cbls_munber_of_images');
	$controls = get_option('cbls_controls');
	$pagination = get_option('cbls_pagination');
	$slide_speed = get_option('cbls_slide_speed');
	$navigation_text_next = get_option('cbls_navigation_text_next');
	$navigation_text_prev = get_option('cbls_navigation_text_prev');

	//if setting is naull then initial setting
	if($items == ''){ $items= 5;}
	if($controls == ''){ $controls= 'true';}
	if($pagination == ''){ $pagination= 'true';}
	if($slide_speed == ''){ $slide_speed= 1000;}
	if($navigation_text_next == ''){ $navigation_text_next= '>';}
	if($navigation_text_prev == ''){ $navigation_text_prev= '<';}

	//include carousel css and js
	wp_enqueue_style("cbls_caro_css_and_js", CBLS_INCLUDE_URL."owl.carousel.css", false, "1.0", "all"); 
	wp_register_script( 'cbls_caro_css_and_js', CBLS_INCLUDE_URL."owl.carousel.min.js" );
	wp_enqueue_script('cbls_caro_css_and_js');
?>

<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery('.cbls_logo_slider').owlCarousel({
		autoPlay: false, 
		items : <?php echo $items; ?>,
		itemsDesktopSmall : [979,4],
		itemsTablet : [768,3],
		itemsMobile : [479,1],
		paginationSpeed : 800,
		stopOnHover : true,
		navigation : <?php echo $controls; ?>,
		pagination : <?php echo $pagination; ?>,
		slideSpeed : <?php echo $slide_speed;?>,
		navigationText : ["<?php echo $navigation_text_prev;?>","<?php echo $navigation_text_next;?>"],
	});
});
</script>

<?php
}
add_action('wp_footer','cbls_slider_trigger');

// Add Shortcode
function cbls_shortcode( $atts ) {
	// Attributes
	extract( shortcode_atts(
		array(
			'posts' => "-1",
			'order' => '',
			'orderby' => '',
			'title' => 'yes',
		), $atts )
	);
	
	$return_string = '<div id="cbls_logo_slider" class="cbls_logo_slider">';
	query_posts(array('orderby' => 'date', 'order' => 'DESC' , 'showposts' => $posts, 'post_type' => 'cbls_logo_slider'));
		if (have_posts()) :
			while (have_posts()) : the_post();
				$post_id = get_the_ID();
				$logo_id = get_post_thumbnail_id();
				$logo_url = wp_get_attachment_image_src($logo_id,'full',true);
				$logo_mata = get_post_meta($logo_id,'_wp_attachment_image_alt',true);
				// Client Link
				$logo_link = get_post_meta( $post_id, 'cbls_logo_url', true );
				
				$return_string .= '<div class="logo_item">';
				if($logo_link) : 
				$return_string .= '<a href="'.$logo_link.'">'; // client url
				endif;
				$return_string .= '<img  src="'. $logo_url[0] .'" alt="'. $logo_mata .'" />';
				if($logo_link) :
				$return_string .= '</a>'; // client url end
				endif;
				
				$return_string .= '</div>';
			endwhile;
		endif;
	$return_string .= '</div>';

	wp_reset_query();
	
	return $return_string;
}
add_shortcode( 'cbls-logo-slider', 'cbls_shortcode' );

// Register logo Custom Post Type
function cbls_post_type() {

	$labels = array(
		'name'                => _x( 'Logo Slider', 'Post Type General Name', '' ),
		'singular_name'       => _x( 'Logo Slider', 'Post Type Singular Name', '' ),
		'menu_name'           => __( 'Logo Slider', '' ),
		'parent_item_colon'   => __( 'Parent Logo :', '' ),
		'all_items'           => __( 'All Logos', '' ),
		'view_item'           => __( 'View Logo ', '' ),
		'add_new_item'        => __( 'Add New Logo ', '' ),
		'add_new'             => __( 'Add New Logo', '' ),
		'edit_item'           => __( 'Edit Logo ', '' ),
		'update_item'         => __( 'Update Logo ', '' ),
		'search_items'        => __( 'Search Logo ', '' ),
		'not_found'           => __( 'Not found', '' ),
		'not_found_in_trash'  => __( 'Not found in Trash', '' ),
	);
	$args = array(
		'label'               => __( 'cbls_logo_slider', '' ),
		'description'         => __( 'Logo Slider post type.', '' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'thumbnail', ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 80,
		'menu_icon'           => 'dashicons-images-alt',
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
	);
	register_post_type( 'cbls_logo_slider', $args );

}

// Hook into the 'init' action
add_action( 'init', 'cbls_post_type', 0 );


//set featured image in normal  position   
add_action('do_meta_boxes', 'featured_image_move_meta_box');
function featured_image_move_meta_box(){
    remove_meta_box( 'postimagediv', 'cbls_logo_slider', 'side' );
    add_meta_box('postimagediv', __('Logo Image'), 'post_thumbnail_meta_box', 'cbls_logo_slider', 'normal', 'high');
}

// Fire our meta box setup function on the post editor screen. 
add_action( 'load-post.php', 'cbls_meta_boxes_setup' );
add_action( 'load-post-new.php', 'cbls_meta_boxes_setup' );

// Meta box setup function. /
function cbls_meta_boxes_setup() {
  // Add meta boxes on the 'add_meta_boxes' hook. /
  add_action( 'add_meta_boxes', 'cbls_add_logo_meta_boxes' );

  // Save post meta on the 'save_post' hook. 
  add_action( 'save_post', 'cbls_save_logo_class_meta', 10, 2 );
}

// Create one or more meta boxes to be displayed on the post editor screen. 
function cbls_add_logo_meta_boxes() {
	add_meta_box(
		'cbls-logo-url',      // Unique ID
		esc_html__( 'Client URL', '' ),    // Title
		'cbls_logo_url_meta_box',   // Callback function
		'cbls_logo_slider',         // Admin page (or post type)
		'normal',         // Context
		'default'         // Priority
	);
}

// Display the logo meta box. 
function cbls_logo_url_meta_box( $object, $box ) { ?>

  <?php wp_nonce_field( basename( __FILE__ ), 'cbls_logo_url_nonce' ); ?>

  <p>
    <label for="cbls-logo-url"><?php _e( "Client Website Url ", '' ); ?></label>
    <br />
    <input style="max-width:470px;" class="widefat" type="text" name="cbls-logo-url" id="cbls-logo-url" value="<?php echo esc_attr( get_post_meta( $object->ID, 'cbls_logo_url', true ) ); ?>"/>
  </p>
<?php }

// Save the meta box's post metadata. 
function cbls_save_logo_class_meta( $post_id, $post ) {

  // Verify the nonce before proceeding. 
  if ( !isset( $_POST['cbls_logo_url_nonce'] ) || !wp_verify_nonce( $_POST['cbls_logo_url_nonce'], basename( __FILE__ ) ) )
    return $post_id;

  // Get the post type object. 
  $post_type = get_post_type_object( $post->post_type );

  // Check if the current user has permission to edit the post. 
  if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
    return $post_id;

  // Get the posted data and sanitize it for use as an HTML class. 
  $new_meta_value = sanitize_text_field($_POST['cbls-logo-url']);

  // Get the meta key. /
  $meta_key = 'cbls_logo_url';

  // Get the meta value of the custom field key. 
  $meta_value = get_post_meta( $post_id, $meta_key, true );

  // If a new meta value was added and there was no previous value, add it. 
  if ( $new_meta_value && '' == $meta_value )
    add_post_meta( $post_id, $meta_key, $new_meta_value, true );

  // If the new meta value does not match the old value, update it.
  elseif ( $new_meta_value && $new_meta_value != $meta_value )
    update_post_meta( $post_id, $meta_key, $new_meta_value );

  // If there is no new meta value but an old value exists, delete it. 
  elseif ( '' == $new_meta_value && $meta_value )
    delete_post_meta( $post_id, $meta_key, $meta_value );
}

// Add the posts and pages columns filter. They can both use the same function.
add_filter('manage_posts_columns', 'cbls_add_post_thumbnail_column', 5);

// Add the column
function cbls_add_post_thumbnail_column($cols){
  $cols['cbls_post_thumb'] = __('Logo Image');
  return $cols;
}

// Hook into the posts an pages column managing. 
add_action('manage_posts_custom_column', 'cbls_display_post_thumbnail_column', 5, 2);
   
// Grab featured-thumbnail size post thumbnail and display it.
function cbls_display_post_thumbnail_column($col, $id){
  switch($col){
	case 'cbls_post_thumb':
	  if( function_exists('cbls_post_thumbnail') )
	  echo the_post_thumbnail( array(100, 100) );

	  else
		echo 'No Image';
	  break;
  }
}
?>