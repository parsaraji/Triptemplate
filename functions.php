<?php
/**
 * Theme Functions and Bootstrapping
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
	// Only load our theme classes.
	if ( false === strpos( $class_name, 'PPT_' ) ) {
		return;
	}

	// Class map/naming convention: PPT_Theme_Setup -> inc/class-theme-setup.php
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
	// Database Migration.
	if ( class_exists( 'PPT_Db_Migration' ) ) {
		PPT_Db_Migration::get_instance();
	}

	// Theme Setup and activation procedures.
	if ( class_exists( 'PPT_Theme_Setup' ) ) {
		PPT_Theme_Setup::get_instance();
	}

	// Custom Post Types and Taxonomies.
	if ( class_exists( 'PPT_Post_Types' ) ) {
		PPT_Post_Types::get_instance();
	}

	// Meta Boxes for non-Gutenberg fields.
	if ( class_exists( 'PPT_Meta_Boxes' ) ) {
		PPT_Meta_Boxes::get_instance();
	}

	// Custom Admin Settings Panel.
	if ( class_exists( 'PPT_Admin_Panel' ) ) {
		PPT_Admin_Panel::get_instance();
	}

	// Ad Manager Subsystem.
	if ( class_exists( 'PPT_Ad_Manager' ) ) {
		PPT_Ad_Manager::get_instance();
	}

	// Map System Subsystem.
	if ( class_exists( 'PPT_Map_System' ) ) {
		PPT_Map_System::get_instance();
	}

	// SEO & Schema Subsystem.
	if ( class_exists( 'PPT_Seo_Schema' ) ) {
		PPT_Seo_Schema::get_instance();
	}

	// Theme Updater Subsystem.
	if ( class_exists( 'PPT_Theme_Updater' ) ) {
		PPT_Theme_Updater::get_instance();
	}
}
add_action( 'after_setup_theme', 'ppt_initialize_theme', 5 );

/**
 * Register and enqueue styles and scripts.
 */
function ppt_enqueue_scripts() {
	// Font styling.
	wp_enqueue_style( 'ppt-shabnam-font', PPT_THEME_URI . '/assets/css/main.css', array(), PPT_THEME_VERSION );

	// Enqueue main script.
	wp_enqueue_script( 'ppt-main-script', PPT_THEME_URI . '/assets/js/main.js', array(), PPT_THEME_VERSION, true );

	// Localize main script for Leaflet / Aparat.
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
 * Helper to get settings.
 */
function ppt_get_setting( $option, $key, $default = '' ) {
	$settings = get_option( $option, array() );
	return isset( $settings[ $key ] ) ? $settings[ $key ] : $default;
}
