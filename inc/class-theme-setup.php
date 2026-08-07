<?php
/**
 * Theme Setup Class
 *
 * @package Premium_Persian_Tourism
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class PPT_Theme_Setup {

	/**
	 * Instance option key.
	 */
	private static $instance = null;

	/**
	 * Singleton pattern.
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	private function __construct() {
		add_action( 'after_setup_theme', array( $this, 'theme_supports' ) );
		add_action( 'after_setup_theme', array( $this, 'register_menus' ) );
		add_action( 'after_switch_theme', array( $this, 'create_default_content' ) );
		add_action( 'widgets_init', array( $this, 'register_sidebars' ) );
	}

	/**
	 * Add theme supports.
	 */
	public function theme_supports() {
		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		// Title tag.
		add_theme_support( 'title-tag' );

		// Enable support for Post Thumbnails on posts and pages.
		add_theme_support( 'post-thumbnails' );

		// Set custom sizes if desired.
		add_image_size( 'ppt-card-thumb', 400, 250, true );
		add_image_size( 'ppt-hero-large', 1200, 500, true );

		// Switch default core markup to output valid HTML5.
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		) );

		// Support custom backgrounds and custom logos.
		add_theme_support( 'custom-logo', array(
			'height'      => 80,
			'width'       => 240,
			'flex-height' => true,
			'flex-width'  => true,
		) );
	}

	/**
	 * Register Menus.
	 */
	public function register_menus() {
		register_nav_menus( array(
			'primary' => esc_html__( 'منوی اصلی (Primary RTL Menu)', 'premium-persian-tourism' ),
			'footer'  => esc_html__( 'منوی فوتر (Footer RTL Links)', 'premium-persian-tourism' ),
		) );
	}

	/**
	 * Register sidebars.
	 */
	public function register_sidebars() {
		register_sidebar( array(
			'name'          => esc_html__( 'سایدبار عمومی (Sidebar)', 'premium-persian-tourism' ),
			'id'            => 'main-sidebar',
			'description'   => esc_html__( 'ابزارک‌های خود را اینجا قرار دهید.', 'premium-persian-tourism' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s card-box">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );
	}

	/**
	 * Create Default Pages and Post types if not exist on theme activation.
	 */
	public function create_default_content() {
		// Prevent running multiple times unless theme is reactivated.
		if ( '1' === get_option( 'ppt_default_content_created' ) ) {
			return;
		}

		// Create default "درباره ما" (About Us) Page
		$about_page = array(
			'post_title'   => 'درباره ما',
			'post_content' => 'این یک برگه نمونه درباره ما است. پلتفرم جامع گردشگری ایران با معرفی برترین جاذبه‌ها، راهنماها و پادکست‌های صوتی سفر.',
			'post_status'  => 'publish',
			'post_type'    => 'page',
		);
		if ( ! get_page_by_path( 'about-us' ) && ! get_page_by_title( 'درباره ما' ) ) {
			wp_insert_post( $about_page );
		}

		// Create default "تماس با ما" (Contact Us) Page
		$contact_page = array(
			'post_title'   => 'تماس با ما',
			'post_content' => 'راه‌های ارتباط با ما. نظرات، انتقادات و پیشنهادات خود را برای ما ارسال کنید.',
			'post_status'  => 'publish',
			'post_type'    => 'page',
		);
		if ( ! get_page_by_path( 'contact-us' ) && ! get_page_by_title( 'تماس با ما' ) ) {
			wp_insert_post( $contact_page );
		}

		// Save flag to prevent repeated execution.
		update_option( 'ppt_default_content_created', '1' );
	}
}
