<?php
/**
 * Theme Self-Updater Subsystem with Version checking
 *
 * @package Premium_Persian_Tourism
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class PPT_Theme_Updater {

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
		add_filter( 'pre_set_site_transient_update_themes', array( $this, 'check_theme_update' ) );
		add_filter( 'themes_api', array( $this, 'theme_update_popup_details' ), 10, 3 );
	}

	/**
	 * Inject remote theme update data into WordPress updates transient.
	 */
	public function check_theme_update( $transient ) {
		if ( empty( $transient->checked ) ) {
			return $transient;
		}

		$theme_slug = get_template();
		$current_version = wp_get_theme()->get( 'Version' );

		// Query settings for endpoint.
		$updater_settings = get_option( 'ppt_updater_settings', array() );
		$endpoint = isset( $updater_settings['update_endpoint'] ) ? $updater_settings['update_endpoint'] : '';

		if ( empty( $endpoint ) ) {
			return $transient;
		}

		// Perform simulated or real remote HTTP request.
		// Since we cannot run an actual server API request safely in this scope, we handle a structured response.
		$response = wp_remote_get( add_query_arg( array(
			'slug'    => $theme_slug,
			'version' => $current_version,
		), $endpoint ), array( 'timeout' => 15 ) );

		if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) !== 200 ) {
			// Mocking/Fallback: Prevents crashing on offline / sandbox environments.
			return $transient;
		}

		$data = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( ! empty( $data ) && version_compare( $current_version, $data['new_version'], '<' ) ) {
			$transient->response[ $theme_slug ] = array(
				'theme'       => $theme_slug,
				'new_version' => $data['new_version'],
				'url'         => $data['url'],
				'package'     => $data['package'],
			);
		}

		return $transient;
	}

	/**
	 * Inject popup theme details modal data.
	 */
	public function theme_update_popup_details( $result, $action, $args ) {
		if ( 'theme_information' !== $action ) {
			return $result;
		}

		$theme_slug = get_template();
		if ( ! isset( $args->slug ) || $args->slug !== $theme_slug ) {
			return $result;
		}

		$updater_settings = get_option( 'ppt_updater_settings', array() );
		$endpoint = isset( $updater_settings['update_endpoint'] ) ? $updater_settings['update_endpoint'] : '';

		if ( empty( $endpoint ) ) {
			return $result;
		}

		$response = wp_remote_get( add_query_arg( array(
			'slug'   => $theme_slug,
			'action' => 'info'
		), $endpoint ), array( 'timeout' => 15 ) );

		if ( ! is_wp_error( $response ) && wp_remote_retrieve_response_code( $response ) === 200 ) {
			$data = json_decode( wp_remote_retrieve_body( $response ), true );
			if ( ! empty( $data ) ) {
				return (object) $data;
			}
		}

		return $result;
	}
}
