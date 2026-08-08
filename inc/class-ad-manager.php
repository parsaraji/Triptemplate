<?php
/**
 * Advanced Professional Advertising and Backlink Subsystem
 * Supports common Iranian advertising script providers (Yektanet, Sabavision, Sanjagh)
 * and incorporates structural backlink injections.
 *
 * @package Premium_Persian_Tourism
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class PPT_Ad_Manager {

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
		add_action( 'wp_head', array( $this, 'inject_yektanet_header_scripts' ), 10 );
		add_action( 'wp_footer', array( $this, 'inject_backlinks_footer' ), 100 );
	}

	/**
	 * Inject Yektanet or SabaVision tracking scripts inside WP Header.
	 */
	public function inject_yektanet_header_scripts() {
		$ad_data = get_option( 'ppt_ad_slots', array() );
		$yektanet_code = isset( $ad_data['yektanet_header_script'] ) ? $ad_data['yektanet_header_script'] : '';

		if ( ! empty( $yektanet_code ) ) {
			echo "\n<!-- Global Yektanet Advertising Integrations -->\n";
			echo $yektanet_code; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo "\n";
		}
	}

	/**
	 * Output designated active contextual backlinks in standard footer row.
	 */
	public function inject_backlinks_footer() {
		$ad_data   = get_option( 'ppt_ad_slots', array() );
		$backlinks = isset( $ad_data['backlinks'] ) ? $ad_data['backlinks'] : array();

		if ( empty( $backlinks ) || ! is_array( $backlinks ) ) {
			return;
		}

		echo '<div class="ppt-footer-backlinks container" style="margin:20px auto; padding-top:15px; border-top:1px solid #2D3748; font-size:12px; color:#718096; direction:rtl; text-align:center;">';
		echo '<span style="margin-left:10px;">شرکای تجاری ما:</span>';
		foreach ( $backlinks as $link ) {
			if ( empty( $link['url'] ) || empty( $link['anchor'] ) ) {
				continue;
			}
			$rel = ( isset( $link['nofollow'] ) && '1' === $link['nofollow'] ) ? 'nofollow' : 'dofollow';
			echo '<a href="' . esc_url( $link['url'] ) . '" rel="' . esc_attr( $rel ) . '" target="_blank" style="color:#A0AEC0; margin-left:15px; text-decoration:none;">' . esc_html( $link['anchor'] ) . '</a>';
		}
		echo '</div>';
	}

	/**
	 * Render specified Ad Slot with fixed layout-dimensions placeholder
	 * to ensure ZERO CLS (Cumulative Layout Shift).
	 *
	 * @param string $slot_key Slug of the slot (e.g. 'header_ad', 'sidebar_ad', 'content_ad_top', 'content_ad_bottom')
	 */
	public static function render_ad_slot( $slot_key ) {
		$ad_data = get_option( 'ppt_ad_slots', array() );
		$slots   = isset( $ad_data['slots'] ) ? $ad_data['slots'] : array();

		if ( ! isset( $slots[ $slot_key ] ) ) {
			return;
		}

		$slot = $slots[ $slot_key ];

		if ( ! isset( $slot['enabled'] ) || '1' !== $slot['enabled'] ) {
			return;
		}

		$desktop_code = isset( $slot['desktop_code'] ) ? $slot['desktop_code'] : '';
		$mobile_code  = isset( $slot['mobile_code'] ) ? $slot['mobile_code'] : '';

		if ( empty( $desktop_code ) && empty( $mobile_code ) ) {
			return;
		}

		// Define specific dimensions based on typical slots to prevent CLS.
		$cls_style = '';
		if ( 'header_ad' === $slot_key ) {
			$cls_style = 'min-height: 90px; max-width: 728px;';
		} elseif ( 'sidebar_ad' === $slot_key ) {
			$cls_style = 'min-height: 250px; max-width: 300px;';
		} elseif ( 'content_ad_top' === $slot_key || 'content_ad_bottom' === $slot_key ) {
			$cls_style = 'min-height: 60px; max-width: 468px;';
		}

		?>
		<div class="ppt-ad-placeholder-wrapper <?php echo esc_attr( $slot_key ); ?>-wrapper" style="margin: 20px auto; text-align: center;">
			<span class="ppt-ad-lbl" style="display:block; font-size:10px; color:#999; margin-bottom:4px; text-align:center;">تبلیغات مستقل (یکتانت / صباویژن)</span>
			<div class="ppt-ad-container" style="background: #fafafa; border: 1px dashed #ddd; margin: 0 auto; display: flex; align-items: center; justify-content: center; overflow: hidden; <?php echo esc_attr( $cls_style ); ?>">

				<?php if ( ! empty( $desktop_code ) ) : ?>
					<div class="ppt-ad-desktop" style="width: 100%; height: 100%;">
						<?php echo $desktop_code; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $mobile_code ) ) : ?>
					<div class="ppt-ad-mobile" style="width: 100%; height: 100%;">
						<?php echo $mobile_code; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				<?php endif; ?>

			</div>
		</div>
		<?php
	}
}
