<?php
/**
 * Twenty Nineteen functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Omari
 */
	require_once get_template_directory() . '/inc/class-tgm-plugin-activation.php';
	require_once get_template_directory() . '/inc/required-plugins.php';
	//Register Custom Navigation Walker
	//require_once get_template_directory() . '/inc/class-wp-bootstrap-navwalker.php';
	require_once get_template_directory() . '/inc/customizer.php';

 function omari_scripts(){
     wp_enqueue_script( 'bootstrap-js', get_template_directory_uri() . '/inc/bootstrap.min.js', array( 'jquery' ), '5.0.0', true );
     wp_enqueue_style( 'bootstrap-css', get_template_directory_uri() . '/inc/bootstrap.min.css', array(), '5.0.0', 'all' );
     wp_enqueue_style( 'omari-style', get_stylesheet_uri(), array(), '1.0' , 'all' );

	 // Google Fonts
 	 wp_enqueue_style( 'google-fonts', 'https://fonts.googleapis.com/css2?family=Roboto&display=swap' );

	// Flexslider Javascript and CSS files
	wp_enqueue_script( 'flexslider-min-js', get_template_directory_uri() . '/inc/flexslider/jquery.flexslider-min.js', array( 'jquery' ), '', true );
	wp_enqueue_style( 'flexslider-css', get_template_directory_uri() . '/inc/flexslider/flexslider.css', array(), '', 'all' );
	wp_enqueue_script( 'flexslider-js', get_template_directory_uri() . '/inc/flexslider/flexslider.js', array( 'jquery' ), '', true );
 }
 add_action( 'wp_enqueue_scripts', 'omari_scripts' );

 function omari_config(){

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus(
		array(
			'omari_main_menu' 	=> esc_html__( 'Omari Main Menu', 'omari' ),
			'omari_footer_menu' => esc_html__( 'Omari Footer Menu', 'omari' ),
		)
	);

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Omari, use a find and replace
	 * to change 'omari' to the name of your theme in all the template files.
	 */
	$textdomain = 'omari';
	load_theme_textdomain( $textdomain, get_stylesheet_directory() . '/languages/' );
	load_theme_textdomain( $textdomain, get_template_directory() . '/languages/' );

	// This theme is WooCommerce compatible, so we're adding support to WooCommerce
	add_theme_support( 'woocommerce', array(
		'thumbnail_image_width' => 255,
		'single_image_width'	=> 255,
		'product_grid' 			=> array(
			'default_rows'    => 10,
			'min_rows'        => 5,
			'max_rows'        => 10,
			'default_columns' => 1,
			'min_columns'     => 1,
			'max_columns'     => 1,				
		)
	) );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	/**
	* Add support for core custom logo.
	*
	* @link https://codex.wordpress.org/Theme_Logo
	*/
	add_theme_support( 'custom-logo', array(
		'height' 		=> 85,
		'width'			=> 160,
		'flex_height'	=> true,
		'flex_width'	=> true,
	) );

	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_image_size( 'omari-slider', 1920, 800, array( 'center', 'center' ) );
	add_image_size( 'omari-blog', 960, 640, array( 'center', 'center' ) );

	if ( ! isset( $content_width ) ) {
		$content_width = 600;
	}	

	add_theme_support( 'title-tag' );			
}
add_action( 'after_setup_theme', 'omari_config', 0 );

/**
* If WooCommerce is active, we want to enqueue a file
* with a couple of template overrides
*/
if( class_exists( 'WooCommerce' )){
require get_template_directory() . '/inc/wc-modifications.php';
}

/**
* Show cart contents / total Ajax
*/
add_filter( 'woocommerce_add_to_cart_fragments', 'omari_woocommerce_header_add_to_cart_fragment' );

function omari_woocommerce_header_add_to_cart_fragment( $fragments ) {
global $woocommerce;

ob_start();

?>
<span class="items"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
<?php
$fragments['span.items'] = ob_get_clean();
return $fragments;
}

// bootstrap 5 wp_nav_menu walker
class bootstrap_5_wp_nav_menu_walker extends Walker_Nav_menu
{
  private $current_item;
  private $dropdown_menu_alignment_values = [
    'dropdown-menu-start',
    'dropdown-menu-end',
    'dropdown-menu-sm-start',
    'dropdown-menu-sm-end',
    'dropdown-menu-md-start',
    'dropdown-menu-md-end',
    'dropdown-menu-lg-start',
    'dropdown-menu-lg-end',
    'dropdown-menu-xl-start',
    'dropdown-menu-xl-end',
    'dropdown-menu-xxl-start',
    'dropdown-menu-xxl-end'
  ];

  function start_lvl(&$output, $depth = 0, $args = null)
  {
    $dropdown_menu_class[] = '';
    foreach($this->current_item->classes as $class) {
      if(in_array($class, $this->dropdown_menu_alignment_values)) {
        $dropdown_menu_class[] = $class;
      }
    }
    $indent = str_repeat("\t", $depth);
    $submenu = ($depth > 0) ? ' sub-menu' : '';
    $output .= "\n$indent<ul class=\"dropdown-menu$submenu " . esc_attr(implode(" ",$dropdown_menu_class)) . " depth_$depth\">\n";
  }

  function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
  {
    $this->current_item = $item;

    $indent = ($depth) ? str_repeat("\t", $depth) : '';

    $li_attributes = '';
    $class_names = $value = '';

    $classes = empty($item->classes) ? array() : (array) $item->classes;

    $classes[] = ($args->walker->has_children) ? 'dropdown' : '';
    $classes[] = 'nav-item';
    $classes[] = 'nav-item-' . $item->ID;
    if ($depth && $args->walker->has_children) {
      $classes[] = 'dropdown-menu dropdown-menu-end';
    }

    $class_names =  join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
    $class_names = ' class="' . esc_attr($class_names) . '"';

    $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
    $id = strlen($id) ? ' id="' . esc_attr($id) . '"' : '';

    $output .= $indent . '<li ' . $id . $value . $class_names . $li_attributes . '>';

    $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
    $attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
    $attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
    $attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';

    $active_class = ($item->current || $item->current_item_ancestor || in_array("current_page_parent", $item->classes, true) || in_array("current-post-ancestor", $item->classes, true)) ? 'active' : '';
    $nav_link_class = ( $depth > 0 ) ? 'dropdown-item ' : 'nav-link ';
    $attributes .= ( $args->walker->has_children ) ? ' class="'. $nav_link_class . $active_class . ' dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"' : ' class="'. $nav_link_class . $active_class . '"';

    $item_output = $args->before;
    $item_output .= '<a' . $attributes . '>';
    $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
    $item_output .= '</a>';
    $item_output .= $args->after;

    $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
  }
}
// register a new menu
register_nav_menu('main-menu', 'Main menu');

/**
* Registers a widget area.
*
* @link https://developer.wordpress.org/reference/functions/register_sidebar/
*
*/
add_action( 'widgets_init', 'omari_sidebars' );
function omari_sidebars(){
register_sidebar( array(
	'name'			=> esc_html__( 'Omari Main Sidebar', 'omari' ),
	'id'			=> 'omari-sidebar-1',
	'description'	=> esc_html__( 'Drag and drop your widgets here', 'omari' ),
	'before_widget'	=> '<div id="%1$s" class="widget %2$s widget-wrapper">', 
	'after_widget'	=> '</div>',
	'before_title'	=> '<h4 class="widget-title">',
	'after_title'	=> '</h4>',
) );
register_sidebar( array(
	'name'			=> esc_html__( 'Sidebar Shop', 'omari' ),
	'id'			=> 'omari-sidebar-shop',
	'description'	=> esc_html__( 'Drag and drop your WooCommerce widgets here', 'omari' ),
	'before_widget'	=> '<div id="%1$s" class="widget %2$s widget-wrapper">', 
	'after_widget'	=> '</div>',
	'before_title'	=> '<h4 class="widget-title">',
	'after_title'	=> '</h4>',
) );	
register_sidebar( array(
	'name'			=> esc_html__( 'Footer Sidebar 1', 'omari' ),
	'id'			=> 'omari-sidebar-footer1',
	'description'	=> esc_html__( 'Drag and drop your widgets here', 'omari' ),
	'before_widget'	=> '<div id="%1$s" class="widget %2$s widget-wrapper">', 
	'after_widget'	=> '</div>',
	'before_title'	=> '<h4 class="widget-title">',
	'after_title'	=> '</h4>',
) );
register_sidebar( array(
	'name'			=> esc_html__( 'Footer Sidebar 2', 'omari' ),
	'id'			=> 'omari-sidebar-footer2',
	'description'	=> esc_html__( 'Drag and drop your widgets here', 'omari' ),
	'before_widget'	=> '<div id="%1$s" class="widget %2$s widget-wrapper">', 
	'after_widget'	=> '</div>',
	'before_title'	=> '<h4 class="widget-title">',
	'after_title'	=> '</h4>',
) );
register_sidebar( array(
	'name'			=> esc_html__( 'Footer Sidebar 3', 'omari' ),
	'id'			=> 'omari-sidebar-footer3',
	'description'	=> esc_html__( 'Drag and drop your widgets here', 'omari' ),
	'before_widget'	=> '<div id="%1$s" class="widget %2$s widget-wrapper">', 
	'after_widget'	=> '</div>',
	'before_title'	=> '<h4 class="widget-title">',
	'after_title'	=> '</h4>',
) );			
}