<?php
/**
 * Created hooks:
 * "_footer_inline_js" -> hook for inserting inline JS into footer; content is wrapped in jQuery document ready wrap, just make global variable $script and add to it
 */ 
 
if ( ! isset( $content_width ) ) $content_width = 960;


#define textdomain for translations
if(!defined('_TEXTDOMAIN_'))
	define('_TEXTDOMAIN_', 'template');

#define absolute path to template
if(!defined('_PATH_'))
	define('_PATH_', get_stylesheet_directory().'/');

define('_ABSPATH_', get_template_directory().'/');

if(!defined('_LOAD_GALLERY_'))
	define('_LOAD_GALLERY_', false);

function template_setup()
{
	#Load custom CSS into TINYmce editor
	add_editor_style();
	
	#Load languages to use
	load_theme_textdomain(_TEXTDOMAIN_, _PATH_.'languages');	
	
	#Remove unused links
	remove_theme_support('automatic-feed-links');
	
	#Register navigation
	register_nav_menu( 'primary', __( 'Hlavní navigace', _TEXTDOMAIN_ ) );
}
add_action ('after_setup_theme', 'template_setup', 11);
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rsd_link');
remove_action( 'wp_head', 'feed_links_extra', 3 ); 
remove_action( 'wp_head', 'feed_links', 2 );

/**
 * Function loads minifying libraries
 * Minify CSS - CssMin::minify(string)
 * Minify JS - JSMin::minify(string)
 * To minify HTML you need to only include this library
 */
function _minify()
{
	require_once(dirname(__FILE__).'/php/minify.php');
	require_once(dirname(__FILE__).'/php/cssmin.php');
	require_once(dirname(__FILE__).'/php/jsmin.php');
}

/**
 * Function disables update alert for specified plugins
 * To make this work, create another function "_plugins_to_deactivate_updates" returning array of plugins to be processed
 * Array value construction array("plugin_folder/plugin.ext")
 */
function _filter_plugin_updates( $value ) {
	foreach(_plugins_to_deactivate_updates() as $plugin)	
		if(isset($value->response[$plugin]))
			unset( $value->response[$plugin] );
		
    return $value;
}
if(function_exists('_plugins_to_deactivate_updates'))
	add_filter( 'site_transient_update_plugins', '_filter_plugin_updates' );	
	
/**
 * Function removes pages from admin menu
 * To make this work, create another function "_admin_pages_to_remove" returning array of pages to be removed
 * Array value construction array("slug.ext")
 */
function _remove_admin_pages() {	
	foreach(_admin_pages_to_remove() as $remove)
		remove_menu_page($remove);	
}	
if(function_exists('_admin_pages_to_remove'))
	add_action( 'admin_menu', '_remove_admin_pages' );
	

/**
 * Function removes subpages from admin menu
 * To make this work, create another function "_admin_subpages_to_remove" returning array of pages to be removed
 * Array value construction array("menu" => "submenu")
 */
function _remove_subadmin_pages() {	
	foreach(_admin_subpages_to_remove() as $key => $value)
		remove_submenu_page($key, $value);	
}	
if(function_exists('_admin_subpages_to_remove'))
	add_action( 'admin_menu', '_remove_subadmin_pages' );
	

/**
 * Function removes items from admin toolbar
 * To make this work, create another function "_admin_toolbar_items_remove" returning array of items to be removed
 * Array value construction array("array_key")
 */
function _remove_admin_bar_items(){
	  global $wp_admin_bar;
	  foreach(_admin_toolbar_items_remove() as $item)
	  	$wp_admin_bar->remove_menu($item);
}
if(function_exists('_admin_toolbar_items_remove'))
	add_action( 'wp_before_admin_bar_render', '_remove_admin_bar_items' );

/**
 * Function add links like favicon, additional meta to header
 * Currently added:
 ** meta tag for devices
 ** favicon
 */
function _header_links() {
	echo "<link rel=\"shortcut icon\" type=\"image/x-icon\" href=\"".get_stylesheet_directory_uri()."/img/favicon.ico\">\n";	
}
add_action('wp_head', '_header_links', 1);
	

#Create site title
function _site_title() {
	global $page, $paged;
	if ( $paged >= 2 || $page >= 2 )
		echo sprintf( __( 'Stránka %s', _TEXTDOMAIN_ ), max( $paged, $page ) ).' | ';
	echo esc_attr( wp_title( '|', true, 'right' ) );
	echo esc_attr(get_bloginfo( 'name' ));
}


#Load default css
function _add_default_css() {
	wp_enqueue_style('default', get_stylesheet_uri(), FALSE, NULL, 'all');	
}
add_action('wp_print_styles', '_add_default_css');

/**
 * Function loads the prettyPhoto gallery automatically with dependance on post content
 * If the post has shortcode gallery or has image wrapped in anchor
 * @return void
 */
function post_gallery_init()
{
	if(_LOAD_GALLERY_)
	{
		global $post;
		
		if(is_loaded_post_object())
			if (strpos($post->post_content,'[gallery') !== false || strpos($post->post_content,'<img') !== false)
				prettyPhoto_init();
	}
}
add_action('wp', 'post_gallery_init');

/**
 * Initialize scripts and style for prettyPhoto gallery
 * @return void
 */
function prettyPhoto_init(){
	add_action('wp_enqueue_scripts', 'prettyPhoto_js_init');
	add_action('wp_print_styles', 'prettyPhoto_css_init');
	add_action('wp_footer', '_add_inline_js', 20);	
}

#add prettyphoto scripts to queue
function prettyPhoto_js_init(){
	wp_deregister_script('jquery');
	wp_enqueue_script('jquery', get_template_directory_uri().'/js/jquery-1.8.3.min.js', FALSE, NULL, true);
	wp_enqueue_script('prettyPhoto', get_template_directory_uri().'/js/jquery.prettyPhoto.js', array('jquery'), NULL, true);	
}

#add prettyphoto css to queue
function prettyPhoto_css_init(){
	wp_enqueue_style('prettyphoto', get_template_directory_uri().'/css/prettyphoto.css', FALSE, NULL, 'screen');	
}

/**
 * Function inserts inline JS to footer after executing hook wp_enqueue_scripts
 * In this case
 */
function _add_inline_js()
{
	global $script;
	$script = '
	<script>
		jQuery(document).ready(function(){
			$("a[href$=\'.jpg\'], a[href$=\'.png\']").prop("rel","prettyPhoto[pp_gal]");
			$("a[rel^=\'prettyPhoto\']").prettyPhoto({social_tools:\'\'});
		';
	do_action('_footer_inline_js');	
	$script .= "\n});</script>\n";	
	
	echo $script;
}

#Functions checks, if is loaded the variable $post and if it is a object
function is_loaded_post_object()
{
	global $post;
	if(is_object($post))
		return true;
	return false;
}

/**
 * Functions serves to get the ID of the very first parent of the page if page has deeper structure
 * @var $page_id integer ID of the page we want the very first parent
 * @return integer
 */
function get_root_parent_id( $page_id ) {
	global $wpdb;
	$parent = $wpdb->get_var( "SELECT post_parent FROM $wpdb->posts WHERE post_type='page' AND post_status='publish' AND ID = '$page_id'" );
	if( $parent == 0 ) return $page_id;
	else return get_root_parent_id( $parent );
}

/**
 * 
 */
function _get_image_url($id, $size){
	$image = wp_get_attachment_image_src($id, $size);	
	return $image[0];
}

/**
 * 
 */
if(isset($_GET['noadminbar']) && $_GET['noadminbar'] == true)
	add_action('show_admin_bar', '__return_false');	
	
/**
 *
 */
function add_page_slug_to_body_class($classes) {
	if(is_loaded_post_object())	{
		global $post;
		$classes[] = 'page-'.$post->post_name;
	}
	return $classes;
}
add_filter('body_class', 'add_page_slug_to_body_class');

/**
 *
 */
function _get_postOBJ_value($post_id, $key){
	$post = get_post($post_id);
	
	return $post->$key;
}

/**
 * Functions returns pages
 */
function get_Page_Siblings($id_post = 0, $id_parent = 0){
	$final = ($id_parent !== 0 ? $id_parent : $id_post);
	
	return get_pages(
		array(
			'child_of' => $final,
			'parent' => $final,
			'numberposts' => -1,
			'sort_column' => 'menu_order',
			'sort_order' => 'ASC',
			'post_type' => get_post_type()
		)
	);
}