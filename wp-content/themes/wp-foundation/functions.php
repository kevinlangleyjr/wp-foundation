<?php
/**
 * WP Foundation functions and definitions
 *
 * @package WP Foundation
 */

if( !defined( 'SCRIPT_VERSION' ) )
	define( 'SCRIPT_VERSION', date('mjY') );

if ( !defined( 'THEME_VERSION' ) )
	define( 'THEME_VERSION', 1.0 );

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}

if ( ! function_exists( 'wp_foundation_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function wp_foundation_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on WP Foundation, use a find and replace
	 * to change 'wp_foundation' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'wp_foundation', __DIR__ . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	//add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'wp_foundation' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'wp_foundation_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif; // wp_foundation_setup
add_action( 'after_setup_theme', 'wp_foundation_setup' );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function wp_foundation_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'wp_foundation' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'wp_foundation_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function wp_foundation_scripts() {
	wp_enqueue_style( 'wp-foundation-style', get_stylesheet_uri() );

		if( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ){
			wp_enqueue_script( 'foundation', get_template_directory_uri() . '/js/libs/foundation.js', array( 'jquery' ), SCRIPT_VERSION, true );
			wp_enqueue_script( 'wp-foundation-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), SCRIPT_VERSION, true );
			wp_enqueue_script( 'wp-foundation-main', get_template_directory_uri() . '/js/main.js', array( 'jquery', 'foundation' ), SCRIPT_VERSION, true );
		} else {
			wp_enqueue_script( 'wp-foundation-libs', get_template_directory_uri() . '/js/libs.min.js', array( 'jquery' ), SCRIPT_VERSION, true );
			wp_enqueue_script( 'wp-foundation-main', get_template_directory_uri() . '/js/main.min.js', array( 'jquery', 'wp-foundation-libs' ), SCRIPT_VERSION, true );
		}

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );
}
add_action( 'wp_enqueue_scripts', 'wp_foundation_scripts' );

/**
 * Implement the Custom Header feature.
 */
//require __DIR__ . '/includes/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require __DIR__ . '/includes/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require __DIR__ . '/includes/extras.php';

/**
 * Customizer additions.
 */
require __DIR__ . '/includes/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require __DIR__ . '/includes/jetpack.php';

/**
 * Load Jetpack compatibility file.
 */
require __DIR__ . '/includes/walkers/walkers.php';