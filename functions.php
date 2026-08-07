<?php
/**
 * Theme Functions and Bootstrapping
 * Now with optimized taxonomy query handling using pre_get_posts filter to eliminate query_posts() overhead.
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
	wp_enqueue_script( 'ppt-admin-script', PPT_THEME_URI . '/assets/js/admin.js', array( 'jquery' ), PPT_THEME_VERSION, true );
}
add_action( 'admin_enqueue_scripts', 'ppt_enqueue_admin_scripts' );

/**
 * Hook into pre_get_posts to optimize and filter taxonomy queries cleanly
 * instead of query_posts() overhead on destination archive pages.
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
