<?php
/**
 * SEO & JSON-LD Structured Schema Subsystem
 *
 * @package Premium_Persian_Tourism
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class PPT_Seo_Schema {

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
		add_action( 'wp_head', array( $this, 'generate_structured_data_schema' ), 15 );
	}

	/**
	 * Print JSON-LD Schema on WP Head.
	 */
	public function generate_structured_data_schema() {
		$schemas = array();

		// 1. Breadcrumb List Schema (All Pages)
		$schemas[] = $this->get_breadcrumb_schema();

		// 2. Custom Post Type Specific Schemas
		if ( is_singular( 'destination' ) ) {
			$schemas[] = $this->get_destination_schema();
		} elseif ( is_singular( 'attraction' ) ) {
			$schemas[] = $this->get_attraction_schema();
		} elseif ( is_singular( 'guide' ) ) {
			$schemas[] = $this->get_guide_schema();
		}

		// Filter out empty entries and output.
		$schemas = array_filter( $schemas );

		if ( ! empty( $schemas ) ) {
			foreach ( $schemas as $schema ) {
				echo "\n" . '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . '</script>' . "\n";
			}
		}
	}

	/**
	 * Breadcrumb Schema.
	 */
	private function get_breadcrumb_schema() {
		$items = array();

		// Home
		$items[] = array(
			'@type'    => 'ListItem',
			'position' => 1,
			'name'     => 'خانه',
			'item'     => home_url( '/' ),
		);

		if ( is_singular() ) {
			global $post;
			$post_type = get_post_type();
			$post_type_obj = get_post_type_object( $post_type );

			// Post Type Archive Link if exists
			$archive_link = get_post_type_archive_link( $post_type );
			$position = 2;

			if ( $archive_link && $post_type_obj ) {
				$items[] = array(
					'@type'    => 'ListItem',
					'position' => $position,
					'name'     => $post_type_obj->labels->name,
					'item'     => $archive_link,
				);
				$position++;
			}

			// Single post name
			$items[] = array(
				'@type'    => 'ListItem',
				'position' => $position,
				'name'     => get_the_title(),
				'item'     => get_permalink(),
			);
		} elseif ( is_archive() ) {
			$items[] = array(
				'@type'    => 'ListItem',
				'position' => 2,
				'name'     => get_the_archive_title(),
				'item'     => get_current_clean_url(),
			);
		}

		return array(
			'@context'        => 'https://schema.org',
			'@type'           => 'BreadcrumbList',
			'itemListElement' => $items,
		);
	}

	/**
	 * Destination Schema (TouristDestination).
	 */
	private function get_destination_schema() {
		global $post;
		$best_time = get_post_meta( $post->ID, '_ppt_best_time', true );
		$lat       = get_post_meta( $post->ID, '_ppt_lat', true );
		$lng       = get_post_meta( $post->ID, '_ppt_lng', true );

		$schema = array(
			'@context' => 'https://schema.org',
			'@type'    => 'TouristDestination',
			'name'     => get_the_title(),
			'description' => wp_strip_all_tags( get_the_excerpt() ),
		);

		if ( ! empty( $best_time ) ) {
			$schema['touristType'] = $best_time;
		}

		if ( has_post_thumbnail() ) {
			$schema['image'] = get_the_post_thumbnail_url( $post->ID, 'large' );
		}

		if ( ! empty( $lat ) && ! empty( $lng ) ) {
			$schema['geo'] = array(
				'@type'     => 'GeoCoordinates',
				'latitude'  => $lat,
				'longitude' => $lng,
			);
		}

		return $schema;
	}

	/**
	 * Attraction Schema (TouristAttraction).
	 */
	private function get_attraction_schema() {
		global $post;
		$address      = get_post_meta( $post->ID, '_ppt_address', true );
		$lat          = get_post_meta( $post->ID, '_ppt_lat', true );
		$lng          = get_post_meta( $post->ID, '_ppt_lng', true );
		$opening_hours = get_post_meta( $post->ID, '_ppt_opening_hours', true );

		$schema = array(
			'@context' => 'https://schema.org',
			'@type'    => 'TouristAttraction',
			'name'     => get_the_title(),
			'description' => wp_strip_all_tags( get_the_excerpt() ),
		);

		if ( ! empty( $address ) ) {
			$schema['address'] = array(
				'@type'          => 'PostalAddress',
				'streetAddress' => $address,
				'addressLocality' => 'Iran',
			);
		}

		if ( has_post_thumbnail() ) {
			$schema['image'] = get_the_post_thumbnail_url( $post->ID, 'large' );
		}

		if ( ! empty( $lat ) && ! empty( $lng ) ) {
			$schema['geo'] = array(
				'@type'     => 'GeoCoordinates',
				'latitude'  => $lat,
				'longitude' => $lng,
			);
		}

		if ( ! empty( $opening_hours ) ) {
			$schema['publicAccess'] = true;
		}

		return $schema;
	}

	/**
	 * Guide FAQ Schema (FAQPage).
	 */
	private function get_guide_schema() {
		global $post;
		$faqs = get_post_meta( $post->ID, '_ppt_faqs', true );

		if ( empty( $faqs ) || ! is_array( $faqs ) ) {
			return null;
		}

		$main_entity = array();
		foreach ( $faqs as $faq ) {
			$main_entity[] = array(
				'@type'          => 'Question',
				'name'           => $faq['q'],
				'acceptedAnswer' => array(
					'@type' => 'Answer',
					'text'  => $faq['a'],
				),
			);
		}

		return array(
			'@context'   => 'https://schema.org',
			'@type'      => 'FAQPage',
			'mainEntity' => $main_entity,
		);
	}
}

/**
 * Clean current URL utility helper.
 */
function get_current_clean_url() {
	global $wp;
	return home_url( $wp->request );
}
