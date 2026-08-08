<?php
/**
 * Advanced Professional Advertising and Backlink Subsystem
 * Supports common Iranian advertising script providers (Yektanet, Sabavision, Sanjagh)
 * and incorporates structural backlink injections.
 * Upgraded with Paragraph and Taxonomy End injection configurations (Priority 5).
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
		add_filter( 'the_content', array( $this, 'inject_ad_after_paragraphs' ), 20 );
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
	 * Inject ad banner after the Nth paragraph of selected post types automatically.
	 */
	public function inject_ad_after_paragraphs( $content ) {
		if ( ! is_singular() ) {
			return $content;
		}

		$ad_data = get_option( 'ppt_ad_slots', array() );
		$enabled_pts = isset( $ad_data['paragraph_injection_post_types'] ) ? $ad_data['paragraph_injection_post_types'] : array();

		if ( empty( $enabled_pts ) || ! is_array( $enabled_pts ) || ! in_array( get_post_type(), $enabled_pts, true ) ) {
			return $content;
		}

		$paragraph_count = isset( $ad_data['paragraph_injection_count'] ) ? intval( $ad_data['paragraph_injection_count'] ) : 2;
		$injection_code  = isset( $ad_data['paragraph_injection_code'] ) ? $ad_data['paragraph_injection_code'] : '';

		if ( empty( $injection_code ) ) {
			return $content;
		}

		// Parse content by </p> tags
		$paragraphs = explode( '</p>', $content );

		if ( count( $paragraphs ) > $paragraph_count ) {
			$ad_html = '<div class="ppt-paragraph-injected-ad" style="margin: 25px auto; text-align: center; max-width: 468px; min-height: 60px; border: 1px dashed #E2E8F0; padding: 10px; background: #FFFDF5; border-radius: 8px;">';
			$ad_html .= '<span style="display:block; font-size:10px; color:#A0AEC0; margin-bottom:5px;">تبلیغ میان‌محتوای متنی (هوشمند)</span>';
			$ad_html .= $injection_code;
			$ad_html .= '</div>';

			$paragraphs[ $paragraph_count - 1 ] .= '</p>' . $ad_html;
			$content = implode( '</p>', $paragraphs );
		}

		return $content;
	}

	/**
	 * Render ad block at the bottom of taxonomy landing archives.
	 */
	public static function render_taxonomy_end_ad() {
		$ad_data = get_option( 'ppt_ad_slots', array() );
		$enabled = isset( $ad_data['taxonomy_end_enabled'] ) ? $ad_data['taxonomy_end_enabled'] : '1';
		$code    = isset( $ad_data['taxonomy_end_code'] ) ? $ad_data['taxonomy_end_code'] : '';

		if ( '1' !== $enabled || empty( $code ) ) {
			return;
		}

		?>
		<div class="ppt-taxonomy-end-ad container" style="margin: 40px auto; text-align: center; max-width: 728px; min-height: 90px; border: 1.5px dashed #CBD5E0; padding: 15px; background: #FFF; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
			<span style="display:block; font-size:11px; color:#A0AEC0; margin-bottom:8px; text-align:center;">📢 حامی مالی این استان / موضوع سفر (تبلیغ اختصاصی)</span>
			<div style="display:flex; justify-content:center; align-items:center;">
				<?php echo $code; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		</div>
		<?php
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
