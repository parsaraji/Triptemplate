<?php
/**
 * Theme Functions and Bootstrapping
 * Now with localized high-accuracy Jalali (Shamsi) Date Conversion Helpers.
 *
 * @package Premium_Persian_Tourism
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Define Theme Constants.
define( 'PPT_THEME_VERSION', '1.0.0' );
define( 'PPT_THEME_DIR', get_template_directory() );
define( 'PPT_THEME_URI', get_template_directory_uri() );

/**
 * Autoload theme classes.
 */
spl_autoload_register( function ( $class_name ) {
	if ( false === strpos( $class_name, 'PPT_' ) ) {
		return;
	}

	$class_file = str_replace( 'PPT_', 'class-', $class_name );
	$class_file = str_replace( '_', '-', strtolower( $class_file ) ) . '.php';
	$filepath   = PPT_THEME_DIR . '/inc/' . $class_file;

	if ( file_exists( $filepath ) ) {
		require_once $filepath;
	}
} );

/**
 * Initialize theme modules.
 */
function ppt_initialize_theme() {
	if ( class_exists( 'PPT_Db_Migration' ) ) {
		PPT_Db_Migration::get_instance();
	}

	if ( class_exists( 'PPT_Theme_Setup' ) ) {
		PPT_Theme_Setup::get_instance();
	}

	if ( class_exists( 'PPT_Post_Types' ) ) {
		PPT_Post_Types::get_instance();
	}

	if ( class_exists( 'PPT_Meta_Boxes' ) ) {
		PPT_Meta_Boxes::get_instance();
	}

	if ( class_exists( 'PPT_Admin_Panel' ) ) {
		PPT_Admin_Panel::get_instance();
	}

	if ( class_exists( 'PPT_Ad_Manager' ) ) {
		PPT_Ad_Manager::get_instance();
	}

	if ( class_exists( 'PPT_Map_System' ) ) {
		PPT_Map_System::get_instance();
	}

	if ( class_exists( 'PPT_Seo_Schema' ) ) {
		PPT_Seo_Schema::get_instance();
	}

	if ( class_exists( 'PPT_Theme_Updater' ) ) {
		PPT_Theme_Updater::get_instance();
	}
}
add_action( 'after_setup_theme', 'ppt_initialize_theme', 5 );

/**
 * Register and enqueue styles and scripts.
 */
function ppt_enqueue_scripts() {
	wp_enqueue_style( 'ppt-shabnam-font', PPT_THEME_URI . '/assets/css/main.css', array(), PPT_THEME_VERSION );
	wp_enqueue_script( 'ppt-main-script', PPT_THEME_URI . '/assets/js/main.js', array(), PPT_THEME_VERSION, true );

	$map_settings = get_option( 'ppt_map_settings', array() );
	$is_map_enabled = ! empty( $map_settings['enable_maps'] ) && '1' === $map_settings['enable_maps'];

	wp_localize_script( 'ppt-main-script', 'ppt_vars', array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'is_map_enabled' => $is_map_enabled,
		'map_provider' => ! empty( $map_settings['map_provider'] ) ? $map_settings['map_provider'] : 'osm',
		'leaflet_assets_url' => PPT_THEME_URI . '/assets',
	) );
}
add_action( 'wp_enqueue_scripts', 'ppt_enqueue_scripts' );

/**
 * Enqueue Admin Assets.
 */
function ppt_enqueue_admin_scripts( $hook ) {
	if ( 'toplevel_page_ppt-settings' !== $hook && false === strpos( $hook, 'post.php' ) && false === strpos( $hook, 'post-new.php' ) ) {
		return;
	}

	wp_enqueue_style( 'ppt-admin-style', PPT_THEME_URI . '/assets/css/admin.css', array(), PPT_THEME_VERSION );
	wp_enqueue_script( 'ppt-admin-script', PPT_THEME_URI . '/assets/js/admin.js', array( 'jquery', 'jquery-ui-sortable' ), PPT_THEME_VERSION, true );
}
add_action( 'admin_enqueue_scripts', 'ppt_enqueue_admin_scripts' );

/**
 * Hook into pre_get_posts to optimize and filter taxonomy queries cleanly.
 */
function ppt_filter_destination_archives( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( is_post_type_archive( 'destination' ) ) {
		if ( isset( $_GET['province'] ) && ! empty( $_GET['province'] ) ) {
			$province_slug = sanitize_text_field( $_GET['province'] );

			$tax_query = array(
				array(
					'taxonomy' => 'province',
					'field'    => 'slug',
					'terms'    => $province_slug,
				),
			);

			$query->set( 'tax_query', $tax_query );
		}
	}
}
add_action( 'pre_get_posts', 'ppt_filter_destination_archives' );

/**
 * Helper to get settings.
 */
function ppt_get_setting( $option, $key, $default = '' ) {
	$settings = get_option( $option, array() );
	return isset( $settings[ $key ] ) ? $settings[ $key ] : $default;
}

/**
 * Jalali (Shamsi) High-Precision Date Conversion Algorithm in PHP.
 */
function ppt_to_jalali( $g_y, $g_m, $g_d ) {
	$g_days_in_month = array( 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31 );
	$j_days_in_month = array( 31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29 );

	$gy = $g_y - 1600;
	$gm = $g_m - 1;
	$gd = $g_d - 1;

	$g_day_no = 365 * $gy + floor( ( $gy + 3 ) / 4 ) - floor( ( $gy + 99 ) / 100 ) + floor( ( $gy + 399 ) / 400 );

	for ( $i = 0; $i < $gm; $i++ ) {
		$g_day_no += $g_days_in_month[ $i ];
	}
	if ( $gm > 1 && ( ( $gy % 4 === 0 && $gy % 100 !== 0 ) || ( $gy % 400 === 0 ) ) ) {
		$g_day_no++;
	}
	$g_day_no += $gd;

	$j_day_no = $g_day_no - 79;

	$j_np = floor( $j_day_no / 12053 );
	$j_day_no %= 12053;

	$jy = 979 + 33 * $j_np + 4 * floor( $j_day_no / 1461 );
	$j_day_no %= 1461;

	if ( $j_day_no >= 366 ) {
		$jy += floor( ( $j_day_no - 1 ) / 365 );
		$j_day_no = ( $j_day_no - 1 ) % 365;
	}

	for ( $i = 0; $i < 11 && $j_day_no >= $j_days_in_month[ $i ]; $i++ ) {
		$j_day_no -= $j_days_in_month[ $i ];
	}
	$jm = $i + 1;
	$jd = $j_day_no + 1;

	$persian_months = array(
		1  => 'فروردین',
		2  => 'اردیبهشت',
		3  => 'خرداد',
		4  => 'تیر',
		5  => 'مرداد',
		6  => 'شهریور',
		7  => 'مهر',
		8  => 'آبان',
		9  => 'آذر',
		10 => 'دی',
		11 => 'بهمن',
		12 => 'اسفند'
	);

	return array(
		'year'  => $jy,
		'month' => $jm,
		'day'   => $jd,
		'text'  => $jd . ' ' . $persian_months[ $jm ] . ' ' . $jy
	);
}

/**
 * Filter WP get_the_date and return beautiful Jalali shamsi date.
 */
function ppt_get_jalali_date( $the_date, $format, $post ) {
	if ( ! $post ) {
		return $the_date;
	}

	$g_date = get_post_time( 'Y-m-d', false, $post );
	if ( ! $g_date ) {
		return $the_date;
	}

	$parts = explode( '-', $g_date );
	if ( count( $parts ) === 3 ) {
		$jalali = ppt_to_jalali( intval( $parts[0] ), intval( $parts[1] ), intval( $parts[2] ) );
		return $jalali['text'];
	}

	return $the_date;
}
add_filter( 'get_the_date', 'ppt_get_jalali_date', 10, 3 );

/**
 * Filter WP get_the_modified_date and return beautiful Jalali shamsi date.
 */
function ppt_get_jalali_modified_date( $the_date, $format, $post ) {
	if ( ! $post ) {
		return $the_date;
	}

	$g_date = get_post_modified_time( 'Y-m-d', false, $post );
	if ( ! $g_date ) {
		return $the_date;
	}

	$parts = explode( '-', $g_date );
	if ( count( $parts ) === 3 ) {
		$jalali = ppt_to_jalali( intval( $parts[0] ), intval( $parts[1] ), intval( $parts[2] ) );
		return $jalali['text'];
	}

	return $the_date;
}
add_filter( 'get_the_modified_date', 'ppt_get_jalali_modified_date', 10, 3 );
