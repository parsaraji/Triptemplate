<?php
/**
 * Database Migration Class
 *
 * @package Premium_Persian_Tourism
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class PPT_Db_Migration {

	/**
	 * Instance option key.
	 */
	private static $instance = null;
	private $db_version_option = 'ppt_db_version';
	private $target_version = '1.0.0';

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
		add_action( 'init', array( $this, 'maybe_migrate_database' ), 10 );
	}

	/**
	 * Checks if migration is necessary and runs migrations sequentially.
	 */
	public function maybe_migrate_database() {
		$current_version = get_option( $this->db_version_option, '0.0.0' );

		if ( version_compare( $current_version, $this->target_version, '<' ) ) {
			$this->run_migrations( $current_version );
			update_option( $this->db_version_option, $this->target_version );
		}
	}

	/**
	 * Run migrations based on version.
	 */
	private function run_migrations( $current_version ) {
		global $wpdb;

		// v1.0.0 Migration: Create tables if custom logs or tracking is needed,
		// and initialize basic option defaults.
		if ( version_compare( $current_version, '1.0.0', '<' ) ) {
			// Ensure custom option defaults are established
			$default_homepage_sections = array(
				'sections' => array(
					array( 'id' => 'hero_search', 'title' => 'جستجوی مقاصد گردشگری', 'enabled' => '1', 'source' => 'all', 'count' => 0, 'layout' => 'hero' ),
					array( 'id' => 'destinations', 'title' => 'مقاصد برتر ایران', 'enabled' => '1', 'source' => 'destination', 'count' => 6, 'layout' => 'grid' ),
					array( 'id' => 'attractions', 'title' => 'جاذبه‌های گردشگری محبوب', 'enabled' => '1', 'source' => 'attraction', 'count' => 4, 'layout' => 'carousel' ),
					array( 'id' => 'podcasts', 'title' => 'رادیو سفر - پادکست‌ها', 'enabled' => '1', 'source' => 'podcast', 'count' => 3, 'layout' => 'list' ),
					array( 'id' => 'videos', 'title' => 'سفر تصویری - ویدیوها', 'enabled' => '1', 'source' => 'video', 'count' => 3, 'layout' => 'grid' ),
					array( 'id' => 'guides', 'title' => 'راهنماهای کاربردی سفر', 'enabled' => '1', 'source' => 'guide', 'count' => 4, 'layout' => 'grid' )
				)
			);
			if ( false === get_option( 'ppt_homepage_sections' ) ) {
				update_option( 'ppt_homepage_sections', $default_homepage_sections );
			}

			$default_map_settings = array(
				'enable_maps' => '1',
				'map_provider' => 'osm',
				'lazy_load' => '1'
			);
			if ( false === get_option( 'ppt_map_settings' ) ) {
				update_option( 'ppt_map_settings', $default_map_settings );
			}

			$default_ad_slots = array(
				'slots' => array(
					'header_ad' => array( 'desktop_code' => '', 'mobile_code' => '', 'enabled' => '0', 'label' => 'بنر هدر (۷۲۸ در ۹۰)' ),
					'sidebar_ad' => array( 'desktop_code' => '', 'mobile_code' => '', 'enabled' => '0', 'label' => 'بنر سایدبار (۳۰۰ در ۲۵۰)' ),
					'content_ad_top' => array( 'desktop_code' => '', 'mobile_code' => '', 'enabled' => '0', 'label' => 'بنر بالای محتوا (۴۶۸ در ۶۰)' ),
					'content_ad_bottom' => array( 'desktop_code' => '', 'mobile_code' => '', 'enabled' => '0', 'label' => 'بنر انتهای محتوا (۴۶۸ در ۶۰)' )
				)
			);
			if ( false === get_option( 'ppt_ad_slots' ) ) {
				update_option( 'ppt_ad_slots', $default_ad_slots );
			}

			$default_updater_settings = array(
				'update_endpoint' => 'https://api.example.com/theme-updates',
				'check_interval' => '86400', // 24 hours
				'last_check' => '0'
			);
			if ( false === get_option( 'ppt_updater_settings' ) ) {
				update_option( 'ppt_updater_settings', $default_updater_settings );
			}
		}
	}
}
