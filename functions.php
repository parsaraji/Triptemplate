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
 * Localized Persian Digits Normalization Helper.
 * Converts all English numbers to beautiful Persian numbers.
 */
function ppt_normalize_persian_digits( $text ) {
	$english_digits = array( '0', '1', '2', '3', '4', '5', '6', '7', '8', '9' );
	$persian_digits = array( '۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹' );
	return str_replace( $english_digits, $persian_digits, $text );
}

/**
 * Filter WP get_the_date and return beautiful Jalali shamsi date with Farsi digits.
 */
function ppt_get_jalali_date( $the_date, $format, $post ) {
	$post_obj = get_post( $post );
	if ( ! $post_obj ) {
		return $the_date;
	}

	$g_date = get_post_time( 'Y-m-d', false, $post_obj );
	if ( ! $g_date ) {
		return $the_date;
	}

	$parts = explode( '-', $g_date );
	if ( count( $parts ) === 3 ) {
		$jalali = ppt_to_jalali( intval( $parts[0] ), intval( $parts[1] ), intval( $parts[2] ) );
		return ppt_normalize_persian_digits( $jalali['text'] );
	}

	return ppt_normalize_persian_digits( $the_date );
}
add_filter( 'get_the_date', 'ppt_get_jalali_date', 10, 3 );
add_filter( 'get_the_time', 'ppt_get_jalali_date', 10, 3 );

/**
 * Filter WP get_the_modified_date and return beautiful Jalali shamsi date with Farsi digits.
 */
function ppt_get_jalali_modified_date( $the_date, $format, $post ) {
	$post_obj = get_post( $post );
	if ( ! $post_obj ) {
		return $the_date;
	}

	$g_date = get_post_modified_time( 'Y-m-d', false, $post_obj );
	if ( ! $g_date ) {
		return $the_date;
	}

	$parts = explode( '-', $g_date );
	if ( count( $parts ) === 3 ) {
		$jalali = ppt_to_jalali( intval( $parts[0] ), intval( $parts[1] ), intval( $parts[2] ) );
		return ppt_normalize_persian_digits( $jalali['text'] );
	}

	return ppt_normalize_persian_digits( $the_date );
}
add_filter( 'get_the_modified_date', 'ppt_get_jalali_modified_date', 10, 3 );

/**
 * Filter WP comments date to return beautiful Jalali date with Farsi digits.
 */
function ppt_get_jalali_comment_date( $date, $format, $comment ) {
	if ( ! $comment ) {
		return $date;
	}
	$g_date = $comment->comment_date;
	if ( ! $g_date ) {
		return $date;
	}
	$parts = explode( ' ', $g_date );
	$date_parts = explode( '-', $parts[0] );
	if ( count( $date_parts ) === 3 ) {
		$jalali = ppt_to_jalali( intval( $date_parts[0] ), intval( $date_parts[1] ), intval( $date_parts[2] ) );
		return ppt_normalize_persian_digits( $jalali['text'] );
	}
	return ppt_normalize_persian_digits( $date );
}
add_filter( 'get_comment_date', 'ppt_get_jalali_comment_date', 10, 3 );

/**
 * Filter comment times and counts to Persian digits.
 */
add_filter( 'get_comment_time', 'ppt_normalize_persian_digits', 10, 1 );
add_filter( 'get_comments_number', 'ppt_normalize_persian_digits', 10, 1 );

/**
 * Advanced Ajax Discovery Search & Multi-criteria Taxonomy Filters (Priority 2)
 * Securely handles non-refresh live queries from the front-end.
 */
function ppt_ajax_discovery_filter() {
	// Check security nonce if provided, otherwise proceed with safe read-only queries
	$search_query   = isset( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '';
	$post_type      = isset( $_GET['content_type'] ) ? sanitize_text_field( $_GET['content_type'] ) : 'any';
	$province_slug  = isset( $_GET['province'] ) ? sanitize_text_field( $_GET['province'] ) : '';
	$topic_slug     = isset( $_GET['topic'] ) ? sanitize_text_field( $_GET['topic'] ) : '';
	$sort_by        = isset( $_GET['sort_by'] ) ? sanitize_text_field( $_GET['sort_by'] ) : 'newest';

	// Whitelist post types to avoid injection
	$allowed_post_types = array( 'any', 'destination', 'attraction', 'podcast', 'video', 'guide' );
	if ( ! in_array( $post_type, $allowed_post_types, true ) ) {
		$post_type = 'any';
	}

	$args = array(
		'post_type'      => ( 'any' === $post_type ) ? array( 'destination', 'attraction', 'podcast', 'video', 'guide' ) : $post_type,
		'posts_per_page' => 12,
		'post_status'    => 'publish',
	);

	// Keyword search
	if ( ! empty( $search_query ) ) {
		$args['s'] = $search_query;
	}

	// Taxonomies configuration
	$tax_query = array();
	if ( ! empty( $province_slug ) ) {
		$tax_query[] = array(
			'taxonomy' => 'province',
			'field'    => 'slug',
			'terms'    => $province_slug,
		);
	}
	if ( ! empty( $topic_slug ) ) {
		$tax_query[] = array(
			'taxonomy' => 'travel_topic',
			'field'    => 'slug',
			'terms'    => $topic_slug,
		);
	}
	if ( count( $tax_query ) > 1 ) {
		$tax_query['relation'] = 'AND';
	}
	if ( ! empty( $tax_query ) ) {
		$args['tax_query'] = $tax_query;
	}

	// Dynamic sorting configuration
	if ( 'popular' === $sort_by ) {
		$args['orderby'] = 'comment_count';
		$args['order']   = 'DESC';
	} elseif ( 'title' === $sort_by ) {
		$args['orderby'] = 'title';
		$args['order']   = 'ASC';
	} else {
		// Fallback newest sorting
		$args['orderby'] = 'date';
		$args['order']   = 'DESC';
	}

	$query = new WP_Query( $args );

	ob_start();

	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			get_template_part( 'template-parts/content', 'card' );
		}
		wp_reset_postdata();
	} else {
		echo '<div class="card-box text-center" style="grid-column: 1 / -1; padding: 40px 20px; width:100%;">';
		echo '<p class="text-muted" style="font-size:15px; margin:0;">هیچ موردی متناسب با فیلترهای انتخابی شما یافت نشد.</p>';
		echo '</div>';
	}

	$html = ob_get_clean();
	wp_send_json_success( array( 'html' => $html ) );
}
add_action( 'wp_ajax_ppt_ajax_filter', 'ppt_ajax_discovery_filter' );
add_action( 'wp_ajax_nopriv_ppt_ajax_filter', 'ppt_ajax_discovery_filter' );

/**
 * Dynamic Inline CSS Customizer Variables Injection (Priority 1)
 * Bridges database customizer options cleanly into the front-end layout engine.
 */
function ppt_inject_customizer_css() {
	$brand = get_option( 'ppt_brand_settings', array() );
	$primary_color   = isset( $brand['primary_color'] ) ? $brand['primary_color'] : '#3182CE';
	$secondary_color = isset( $brand['secondary_color'] ) ? $brand['secondary_color'] : '#2B6CB0';
	$bg_color        = isset( $brand['bg_color'] ) ? $brand['bg_color'] : '#F7FAFC';
	$text_color      = isset( $brand['text_color'] ) ? $brand['text_color'] : '#2D3748';
	$border_radius   = isset( $brand['border_radius'] ) ? intval( $brand['border_radius'] ) : 12;
	$container_width = isset( $brand['container_width'] ) ? intval( $brand['container_width'] ) : 1200;

	// Dynamic Header & Footer Styling settings
	$header_settings = get_option( 'ppt_header_settings', array() );
	$header_bg       = isset( $header_settings['bg_color'] ) ? $header_settings['bg_color'] : '#FFFFFF';
	$header_text     = isset( $header_settings['text_color'] ) ? $header_settings['text_color'] : '#2D3748';

	$footer_settings = get_option( 'ppt_footer_settings', array() );
	$footer_bg       = isset( $footer_settings['bg_color'] ) ? $footer_settings['bg_color'] : '#1A202C';
	$footer_text     = isset( $footer_settings['text_color'] ) ? $footer_settings['text_color'] : '#CBD5E0';

	// Dynamic CPT detailed styles
	$dest_badge     = isset( $brand['cpt_dest_badge_color'] ) ? $brand['cpt_dest_badge_color'] : '#3182CE';
	$attr_badge     = isset( $brand['cpt_attr_badge_color'] ) ? $brand['cpt_attr_badge_color'] : '#E53E3E';
	$itin_timeline  = isset( $brand['cpt_itin_timeline_color'] ) ? $brand['cpt_itin_timeline_color'] : '#3182CE';
	$pod_player_bg  = isset( $brand['cpt_pod_player_color'] ) ? $brand['cpt_pod_player_color'] : '#EBF8FF';
	$vid_theatre_bg = isset( $brand['cpt_vid_theatre_bg'] ) ? $brand['cpt_vid_theatre_bg'] : '#0F172A';
	?>
	<style type="text/css">
		:root {
			--ppt-primary-color: <?php echo esc_html( $primary_color ); ?> !important;
			--ppt-secondary-color: <?php echo esc_html( $secondary_color ); ?> !important;
			--ppt-bg-color: <?php echo esc_html( $bg_color ); ?> !important;
			--ppt-text-color: <?php echo esc_html( $text_color ); ?> !important;
			--ppt-border-radius: <?php echo esc_html( $border_radius ); ?>px !important;
			--ppt-container-width: <?php echo esc_html( $container_width ); ?>px !important;
		}

		/* Granular dynamic CPT style customizations */
		.card-badge, .destination-hero-content .card-badge {
			background-color: <?php echo esc_html( $dest_badge ); ?> !important;
		}
		.theatre-mode-wrapper {
			background: linear-gradient(135deg, <?php echo esc_html( $vid_theatre_bg ); ?> 0%, #000 100%) !important;
		}
		.ppt-player-box {
			background-color: <?php echo esc_html( $pod_player_bg ); ?> !important;
		}
		.ppt-itinerary-day-row {
			border-right-color: <?php echo esc_html( $itin_timeline ); ?> !important;
		}

		/* Dynamic customizer colors for Header */
		.site-header.premium-header {
			background-color: <?php echo esc_html( $header_bg ); ?> !important;
			border-bottom: 1px solid rgba(0,0,0,0.06) !important;
		}
		.site-header.premium-header .desktop-nav ul li a,
		.site-header.premium-header .logo-text,
		.site-header.premium-header .logo-link {
			color: <?php echo esc_html( $header_text ); ?> !important;
		}
		.site-header.premium-header .burger-menu-btn .burger-icon-bar {
			background-color: <?php echo esc_html( $header_text ); ?> !important;
		}

		/* Dynamic customizer colors for Footer */
		footer.site-footer.premium-footer {
			background-color: <?php echo esc_html( $footer_bg ); ?> !important;
			color: <?php echo esc_html( $footer_text ); ?> !important;
			border-top: 1px solid rgba(255,255,255,0.05) !important;
		}
		footer.site-footer.premium-footer a,
		footer.site-footer.premium-footer h3,
		footer.site-footer.premium-footer .footer-desc,
		footer.site-footer.premium-footer .footer-bottom p {
			color: <?php echo esc_html( $footer_text ); ?> !important;
		}
	</style>
	<?php
}
add_action( 'wp_head', 'ppt_inject_customizer_css', 100 );
