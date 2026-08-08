<?php
/**
 * Custom Post Types and Taxonomies Registration
 *
 * @package Premium_Persian_Tourism
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class PPT_Post_Types {

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
		add_action( 'init', array( $this, 'register_custom_post_types' ), 10 );
		add_action( 'init', array( $this, 'register_custom_taxonomies' ), 10 );
	}

	/**
	 * Register Custom Post Types.
	 */
	public function register_custom_post_types() {
		// 1. Destination (مقاصد)
		register_post_type( 'destination', array(
			'labels'             => array(
				'name'               => 'مقاصد',
				'singular_name'      => 'مقصد',
				'menu_name'          => 'مقاصد گردشگری',
				'add_new'            => 'افزودن مقصد جدید',
				'add_new_item'       => 'افزودن مقصد جدید',
				'edit_item'          => 'ویرایش مقصد',
				'new_item'           => 'مقصد جدید',
				'view_item'          => 'نمایش مقصد',
				'search_items'       => 'جستجوی مقصد',
				'not_found'          => 'مقصدی پیدا نشد',
				'not_found_in_trash' => 'مقصدی در زباله‌دان پیدا نشد',
			),
			'public'             => true,
			'has_archive'        => true,
			'rewrite'            => array( 'slug' => 'destinations' ),
			'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ),
			'menu_icon'          => 'dashicons-location-alt',
			'show_in_rest'       => true,
		) );

		// 2. Attraction (جاذبه‌ها)
		register_post_type( 'attraction', array(
			'labels'             => array(
				'name'               => 'جاذبه‌ها',
				'singular_name'      => 'جاذبه',
				'menu_name'          => 'جاذبه‌های گردشگری',
				'add_new'            => 'افزودن جاذبه جدید',
				'add_new_item'       => 'افزودن جاذبه جدید',
				'edit_item'          => 'ویرایش جاذبه',
				'new_item'           => 'جاذبه جدید',
				'view_item'          => 'نمایش جاذبه',
				'search_items'       => 'جستجوی جاذبه',
				'not_found'          => 'جاذبه‌ای پیدا نشد',
				'not_found_in_trash' => 'جاذبه‌ای در زباله‌دان پیدا نشد',
			),
			'public'             => true,
			'has_archive'        => true,
			'rewrite'            => array( 'slug' => 'attractions' ),
			'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ),
			'menu_icon'          => 'dashicons-camera',
			'show_in_rest'       => true,
		) );

		// 3. Itinerary (برنامه سفر)
		register_post_type( 'itinerary', array(
			'labels'             => array(
				'name'               => 'برنامه‌های سفر',
				'singular_name'      => 'برنامه سفر',
				'menu_name'          => 'برنامه‌های سفر',
				'add_new'            => 'افزودن برنامه جدید',
				'add_new_item'       => 'افزودن برنامه جدید سفر',
				'edit_item'          => 'ویرایش برنامه',
				'new_item'           => 'برنامه جدید',
				'view_item'          => 'نمایش برنامه',
				'search_items'       => 'جستجوی برنامه سفر',
				'not_found'          => 'برنامه‌ای پیدا نشد',
				'not_found_in_trash' => 'برنامه‌ای در زباله‌دان پیدا نشد',
			),
			'public'             => true,
			'has_archive'        => true,
			'rewrite'            => array( 'slug' => 'itineraries' ),
			'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
			'menu_icon'          => 'dashicons-calendar-alt',
			'show_in_rest'       => true,
		) );

		// 4. Guide (راهنماها)
		register_post_type( 'guide', array(
			'labels'             => array(
				'name'               => 'راهنماهای سفر',
				'singular_name'      => 'راهنما',
				'menu_name'          => 'راهنماهای سفر',
				'add_new'            => 'افزودن راهنمای جدید',
				'add_new_item'       => 'افزودن راهنمای جدید سفر',
				'edit_item'          => 'ویرایش راهنما',
				'new_item'           => 'راهنمای جدید',
				'view_item'          => 'نمایش راهنما',
				'search_items'       => 'جستجوی راهنما',
				'not_found'          => 'راهنمایی پیدا نشد',
				'not_found_in_trash' => 'راهنمایی در زباله‌دان پیدا نشد',
			),
			'public'             => true,
			'has_archive'        => true,
			'rewrite'            => array( 'slug' => 'guides' ),
			'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ),
			'menu_icon'          => 'dashicons-book-alt',
			'show_in_rest'       => true,
		) );

		// 5. Podcast (پادکست‌ها)
		register_post_type( 'podcast', array(
			'labels'             => array(
				'name'               => 'پادکست‌ها',
				'singular_name'      => 'پادکست',
				'menu_name'          => 'پادکست‌ها (رادیو سفر)',
				'add_new'            => 'افزودن پادکست جدید',
				'add_new_item'       => 'افزودن پادکست جدید',
				'edit_item'          => 'ویرایش پادکست',
				'new_item'           => 'پادکست جدید',
				'view_item'          => 'نمایش پادکست',
				'search_items'       => 'جستجوی پادکست',
				'not_found'          => 'پادکستی پیدا نشد',
				'not_found_in_trash' => 'پادکستی در زباله‌دان پیدا نشد',
			),
			'public'             => true,
			'has_archive'        => true,
			'rewrite'            => array( 'slug' => 'podcasts' ),
			'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
			'menu_icon'          => 'dashicons-microphone',
			'show_in_rest'       => true,
		) );

		// 6. Video (ویدیوها)
		register_post_type( 'video', array(
			'labels'             => array(
				'name'               => 'ویدیوها',
				'singular_name'      => 'ویدیو',
				'menu_name'          => 'ویدیوها (سفر تصویری)',
				'add_new'            => 'افزودن ویدیو جدید',
				'add_new_item'       => 'افزودن ویدیو جدید',
				'edit_item'          => 'ویرایش ویدیو',
				'new_item'           => 'ویدیو جدید',
				'view_item'          => 'نمایش ویدیو',
				'search_items'       => 'جستجوی ویدیو',
				'not_found'          => 'ویدیویی پیدا نشد',
				'not_found_in_trash' => 'ویدیویی در زباله‌دان پیدا نشد',
			),
			'public'             => true,
			'has_archive'        => true,
			'rewrite'            => array( 'slug' => 'videos' ),
			'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
			'menu_icon'          => 'dashicons-video-alt3',
			'show_in_rest'       => true,
		) );

		// 7. Hidden CPT to securely register Ad Bookings without option bloats (Security Overhaul)
		register_post_type( 'ppt_booking', array(
			'labels'             => array(
				'name'               => 'رزروهای تبلیغات',
				'singular_name'      => 'رزرو تبلیغ',
				'menu_name'          => 'رزروهای تبلیغات',
				'add_new'            => 'افزودن رزرو جدید',
				'add_new_item'       => 'افزودن رزرو تبلیغ',
				'edit_item'          => 'ویرایش رزرو',
				'new_item'           => 'رزرو جدید',
				'view_item'          => 'نمایش رزرو',
				'search_items'       => 'جستجوی رزرو',
				'not_found'          => 'رزروی پیدا نشد',
			),
			'public'             => false,
			'show_ui'            => true,
			'has_archive'        => false,
			'supports'           => array( 'title', 'custom-fields' ),
			'menu_icon'          => 'dashicons-chart-line',
		) );
	}

	/**
	 * Register Custom Taxonomies.
	 */
	public function register_custom_taxonomies() {
		// Province Taxonomy (استان‌ها) for destination, attraction, itinerary, guide CPTs.
		register_taxonomy( 'province', array( 'destination', 'attraction', 'itinerary', 'guide' ), array(
			'labels'            => array(
				'name'              => 'استان‌ها',
				'singular_name'     => 'استان',
				'search_items'      => 'جستجوی استان',
				'all_items'         => 'همه استان‌ها',
				'parent_item'       => 'استان والد',
				'parent_item_colon' => 'استان والد:',
				'edit_item'         => 'ویرایش استان',
				'update_item'       => 'بروزرسانی استان',
				'add_new_item'      => 'افزودن استان جدید',
				'new_item_name'     => 'نام استان جدید',
				'menu_name'         => 'استان‌ها',
			),
			'hierarchical'      => true,
			'public'            => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'provinces' ),
		) );

		// Topic/Category for guides and podcasts
		register_taxonomy( 'travel_topic', array( 'guide', 'podcast', 'video' ), array(
			'labels'            => array(
				'name'              => 'موضوعات سفر',
				'singular_name'     => 'موضوع سفر',
				'search_items'      => 'جستجوی موضوع',
				'all_items'         => 'همه موضوعات',
				'edit_item'         => 'ویرایش موضوع',
				'update_item'       => 'بروزرسانی موضوع',
				'add_new_item'      => 'افزودن موضوع جدید',
				'new_item_name'     => 'نام موضوع جدید',
				'menu_name'         => 'موضوعات سفر',
			),
			'hierarchical'      => true,
			'public'            => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'travel-topics' ),
		) );
	}
}
