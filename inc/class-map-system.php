<?php
/**
 * Map Subsystem incorporating Leaflet assets
 *
 * @package Premium_Persian_Tourism
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class PPT_Map_System {

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
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_map_assets' ) );
	}

	/**
	 * Enqueue Leaflet styles and scripts only on single pages requiring maps.
	 */
	public function enqueue_map_assets() {
		$map_settings = get_option( 'ppt_map_settings', array() );
		$is_enabled   = isset( $map_settings['enable_maps'] ) ? $map_settings['enable_maps'] : '1';

		if ( '1' !== $is_enabled ) {
			return;
		}

		// Only load map scripts/styles on single destination or attraction pages.
		if ( is_singular( array( 'destination', 'attraction' ) ) ) {
			// Check if we have valid coordinates.
			global $post;
			$lat = get_post_meta( $post->ID, '_ppt_lat', true );
			$lng = get_post_meta( $post->ID, '_ppt_lng', true );

			if ( ! empty( $lat ) && ! empty( $lng ) ) {
				// We load Leaflet CDN assets (or local fallbacks) securely.
				wp_enqueue_style( 'ppt-leaflet-css', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css', array(), '1.9.4' );
				wp_enqueue_script( 'ppt-leaflet-js', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', array(), '1.9.4', true );
			}
		}
	}

	/**
	 * Helper function to render a map block in front-end.
	 *
	 * @param float  $lat Latitude coordinate
	 * @param float  $lng Longitude coordinate
	 * @param string $label Pin hover label text
	 */
	public static function render_map_container( $lat, $lng, $label = '' ) {
		$map_settings = get_option( 'ppt_map_settings', array() );
		$is_enabled   = isset( $map_settings['enable_maps'] ) ? $map_settings['enable_maps'] : '1';
		$lazy_load    = isset( $map_settings['lazy_load'] ) ? $map_settings['lazy_load'] : '1';

		if ( '1' !== $is_enabled || empty( $lat ) || empty( $lng ) ) {
			return;
		}

		$div_classes = 'ppt-map-wrapper';
		if ( '1' === $lazy_load ) {
			$div_classes .= ' ppt-map-lazy';
		}

		?>
		<div class="ppt-map-block-container card-box" style="margin:25px 0; padding:15px;">
			<h3 class="section-title">موقعیت روی نقشه تعاملی</h3>
			<p class="description">مسیریابی دقیق و مشاهده موقعیت این جاذبه بر روی نقشه تعاملی آزاد:</p>
			<div class="<?php echo esc_attr( $div_classes ); ?>"
				 id="ppt-single-map"
				 data-lat="<?php echo esc_attr( $lat ); ?>"
				 data-lng="<?php echo esc_attr( $lng ); ?>"
				 data-label="<?php echo esc_attr( $label ); ?>"
				 style="height: 380px; width: 100%; border-radius: 8px; border: 1px solid #ddd; background: #eee; z-index: 10;">
				 <div class="ppt-map-placeholder" style="display:flex; justify-content:center; align-items:center; height:100%; direction:rtl; font-family:Shabnam, sans-serif; color:#777;">
					در حال بارگذاری نقشه تعاملی...
				 </div>
			</div>
			<div style="margin-top:10px; display:flex; gap:10px; font-size:12px;">
				<a href="https://www.google.com/maps/dir/?api=1&destination=<?php echo esc_attr( $lat ); ?>,<?php echo esc_attr( $lng ); ?>" target="_blank" rel="noopener" class="button btn-small btn-secondary">مسیریابی با گوگل مپ</a>
				<a href="https://nshn.ir/?lat=<?php echo esc_attr( $lat ); ?>&lng=<?php echo esc_attr( $lng ); ?>" target="_blank" rel="noopener" class="button btn-small btn-secondary">مسیریابی با نشان</a>
			</div>
		</div>
		<?php
	}
}
