<?php

if ( ! class_exists( 'Timber' ) ) {
	add_action( 'admin_notices', function() {
			echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php' ) ) . '</a></p></div>';
		} );
	return;
}

Timber::$dirname = array('templates', 'views');

require get_template_directory() . '/inc/version.php';
global $package_version;

class LaunchframeSite extends TimberSite {

	function __construct() {
		add_theme_support( 'post-formats' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'menus' );
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
		add_action( 'init', array( $this, 'register_menus' ) );
		add_filter( 'timber_context', array( $this, 'add_to_context' ) );
    	add_action('wp_enqueue_scripts', array( $this, 'lf_cleanup'));
    	add_action( 'wp_enqueue_scripts', array( $this, 'register_stylesheets' ) );
    	add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ) );
		parent::__construct();
	}

	function lf_cleanup() {
		wp_deregister_script('jquery');
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
	}

	function register_stylesheets() {
		global $package_version;
		wp_enqueue_style( 'slick-style', '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css', true, $package_version );
		wp_enqueue_style( 'application-style', get_template_directory_uri() . '/assets/dist/css/application.min.css', true, $package_version );
		wp_enqueue_style( 'nunito-style', 'https://fonts.googleapis.com/css?family=Nunito:400,400i,600,600i,700,700i', true, $package_version );
		wp_enqueue_style( 'work-sans-style', 'https://fonts.googleapis.com/css?family=Work+Sans:400,800', true, $package_version );
	}

	function register_scripts() {
		global $package_version;
		wp_enqueue_script( 'fa-js', 'https://kit.fontawesome.com/6818f8c5c7.js', array(), $package_version, true );
		wp_enqueue_script( 'jquery-js', 'https://code.jquery.com/jquery-3.3.1.min.js', array(), $package_version, true );
		wp_enqueue_script( 'slick-js', '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', array('jquery-js'), $package_version, true );
		wp_enqueue_script( 'lightbox-js', get_template_directory_uri() . '/inc/lightbox.js', array('jquery-js'), $package_version, true );
		wp_enqueue_script( 'application-js', get_template_directory_uri() . '/assets/dist/js/script.min.js', array('jquery-js', 'lightbox-js', 'slick-js'), $package_version, true );
	}

	function register_post_types() {
		//Sermons
		$labels = array(
			'name'               => 'Sermons',
			'singular_name'      => 'Sermon',
			'menu_name'          => 'Sermons',
			'name_admin_bar'     => 'Sermon',
			'add_new'            => 'Add New',
			'add_new_item'       => 'Add New Sermon',
			'new_item'           => 'New Sermon',
			'edit_item'          => 'Edit Sermon',
			'view_item'          => 'View Sermon',
			'all_items'          => 'All Sermons',
			'search_items'       => 'Search Sermons',
			'parent_item_colon'  => 'Parent Sermons:',
			'not_found'          => 'No sermons found.',
			'not_found_in_trash' => 'No sermons found in Trash.'
		);
		$args = array(
			'labels'             => $labels,
	        'description'        => 'Sermons',
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'sermon' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'editor', 'excerpt' )
		);
		register_post_type( 'sermon', $args );

		//Series
		$labels = array(
			'name'               => 'Series',
			'singular_name'      => 'Series',
			'menu_name'          => 'Series',
			'name_admin_bar'     => 'Series',
			'add_new'            => 'Add New',
			'add_new_item'       => 'Add New Series',
			'new_item'           => 'New Series',
			'edit_item'          => 'Edit Series',
			'view_item'          => 'View Series',
			'all_items'          => 'All Series',
			'search_items'       => 'Search Series',
			'parent_item_colon'  => 'Parent Series:',
			'not_found'          => 'No series found.',
			'not_found_in_trash' => 'No series found in Trash.'
		);
		$args = array(
			'labels'             => $labels,
	        'description'        => 'Series',
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'series' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'editor', 'excerpt' )
		);
		register_post_type( 'series', $args );
	}

	function register_taxonomies() {
		//this is where you can register custom taxonomies
	}

	function register_menus() {
	  	register_nav_menus(
	    	array(
	      		'primary-nav' => __( 'Primary Navigation' ),
	      		'footer-nav-1' => __( 'Footer Navigation 1' ),
	      		'footer-nav-2' => __( 'Footer Navigation 2' )
	    	)
	  	);
	}

	function add_to_context( $context ) {
		$context['primaryNav'] = new TimberMenu('primary-nav');
		$context['footerNav1'] = new TimberMenu('footer-nav-1');
		$context['footerNav2'] = new TimberMenu('footer-nav-2');
		$context['site'] = $this;
		return $context;
	}
}

if( function_exists('acf_add_options_page') ) {
	acf_add_options_page();	
}

function mytheme_timber_context( $context ) {
    $context['options'] = get_fields('option');
    return $context;
}
add_filter('timber_context', 'mytheme_timber_context');

// -------------------------------------------------------------------------
// IMGIX Function
// -------------------------------------------------------------------------

function imgix($image_url, $width = '') {
	$imgix_domain = 'https://kingscc.imgix.net';
	// $imgix_domain = '//kingscommunity.ch';
	$the_url = parse_url($image_url, 5);
	$the_params = '?auto=compress,format&w=' . $width;
    return $imgix_domain . $the_url . $the_params;
}

// -------------------------------------------------------------------------
// Hide Content Editor in Pages / Posts
// -------------------------------------------------------------------------

add_action('init', function() {
	remove_post_type_support('post', 'editor');
	remove_post_type_support('page', 'editor');
}, 99);

// -------------------------------------------------------------------------
// Return Date Range of Messages in a Series
// -------------------------------------------------------------------------

function get_series_date_range($series_id) {
    $date_string = "";

    $messages = get_field('sermons', $series_id);

    if ($messages) {
        $first_message_id = reset($messages)->ID;
        $last_message_id = end($messages)->ID;

        if (get_the_date("M", $first_message_id) === get_the_date("M", $last_message_id)) {
            $date_string = "" . get_the_date("M Y", $last_message_id) . "";
        } else {
            $date_string = "" . get_the_date("M", $first_message_id) . "-" . get_the_date("M Y", $last_message_id) . "";
        }
    }

    return $date_string;

}

new LaunchframeSite();
