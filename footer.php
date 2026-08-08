<?php
/**
 * Elegant Desktop Footer and App-like Sticky Bottom Bar for Mobile Devices
 * Upgraded with dynamic show/hide column toggles, custom footer links builder,
 * and backcountry partner backlinks (Priority 4).
 *
 * @package Premium_Persian_Tourism
 */
?>

<!-- Render Content Ad Bottom Slot if configured -->
<?php
if ( class_exists( 'PPT_Ad_Manager' ) ) {
	PPT_Ad_Manager::render_ad_slot( 'content_ad_bottom' );
}

// Retrieve Dynamic Footer Customizer settings
$footer_settings = get_option( 'ppt_footer_settings', array() );
if ( class_exists( 'PPT_Admin_Panel' ) ) {
	$defaults = PPT_Admin_Panel::get_default_footer_settings();
} else {
	$defaults = array();
}
$settings = wp_parse_args( $footer_settings, $defaults );

$show_brand_col      = isset( $settings['show_brand_col'] ) ? $settings['show_brand_col'] : '1';
$show_links_col      = isset( $settings['show_links_col'] ) ? $settings['show_links_col'] : '1';
$show_cpt_col        = isset( $settings['show_cpt_col'] ) ? $settings['show_cpt_col'] : '1';
$show_newsletter_col = isset( $settings['show_newsletter_col'] ) ? $settings['show_newsletter_col'] : '1';
$show_sticky_nav     = isset( $settings['show_sticky_mobile_nav'] ) ? $settings['show_sticky_mobile_nav'] : '1';
$custom_links        = isset( $settings['custom_links'] ) ? $settings['custom_links'] : array();

$brand_settings     = get_option( 'ppt_brand_settings', array() );
$phone              = isset( $brand_settings['contact_phone'] ) ? $brand_settings['contact_phone'] : '۰۲۱-۸۸۸۸۸۸۸۸';
$email              = isset( $brand_settings['contact_email'] ) ? $brand_settings['contact_email'] : 'info@safarnama.ir';
$instagram          = isset( $brand_settings['social_instagram'] ) ? $brand_settings['social_instagram'] : '';
$telegram           = isset( $brand_settings['social_telegram'] ) ? $brand_settings['social_telegram'] : '';
$aparat             = isset( $brand_settings['social_aparat'] ) ? $brand_settings['social_aparat'] : '';
$footer_text        = isset( $brand_settings['footer_text'] ) ? $brand_settings['footer_text'] : 'تمامی حقوق این وب‌سایت محفوظ و متعلق به رادیو سفر می‌باشد.';
$hide_footer_mobile = isset( $brand_settings['hide_footer_mobile'] ) && '1' === $brand_settings['hide_footer_mobile'];

// Construct conditional CSS class for mobile footer visibility
$footer_class = 'site-footer premium-footer';
if ( $hide_footer_mobile ) {
	$footer_class .= ' hide-footer-on-mobile';
}
?>

<footer class="<?php echo esc_attr( $footer_class ); ?>">
	<div class="container">
		<div class="footer-grid">

			<!-- Column 1: Editorial intro and Contact Details -->
			<?php if ( '1' === $show_brand_col ) : ?>
				<div class="footer-col brand-col">
					<h3>🎙️ رادیو سفر</h3>
					<p class="footer-desc text-justify">پلتفرم بومی و صمیمی معرفی جاذبه‌ها و مقاصد گردشگری ایران زمین. همسفر ما شوید و زیبایی‌های ایران زمین را به شیوه متفاوتی تجربه کنید.</p>
					<div class="footer-contact-details">
						<p>📍 <strong>دفتر مرکزی:</strong> تهران، میدان ونک، ساختمان صبا</p>
						<p>📞 <strong>تلفن پشتیبانی:</strong> <?php echo esc_html( $phone ); ?></p>
						<p>✉️ <strong>ایمیل تحریریه:</strong> <?php echo esc_html( $email ); ?></p>
					</div>
				</div>
			<?php endif; ?>

			<!-- Column 2: Dynamic Navigation Links Builder (Demo settings links) -->
			<?php if ( '1' === $show_links_col ) : ?>
				<div class="footer-col link-col">
					<h3>🗺️ دسترسی سریع</h3>
					<ul class="footer-links-list">
						<?php
						if ( ! empty( $custom_links ) && is_array( $custom_links ) ) {
							foreach ( $custom_links as $link ) {
								if ( empty( $link['label'] ) ) continue;
								$url = $link['url'];
								if ( 0 === strpos( $url, '/' ) ) {
									$url = home_url( $url );
								}
								echo '<li><a href="' . esc_url( $url ) . '">' . esc_html( $link['label'] ) . '</a></li>';
							}
						} else {
							// Fallback standard menu navigation if empty
							wp_nav_menu( array(
								'theme_location' => 'footer',
								'menu_class'     => 'footer-links-list',
								'container'      => false,
								'fallback_cb'    => false,
							) );
						}
						?>
					</ul>
				</div>
			<?php endif; ?>

			<!-- Column 3: Custom Post Type Quick Lists -->
			<?php if ( '1' === $show_cpt_col ) : ?>
				<div class="footer-col cpt-col">
					<h3>🎧 سفر شنیداری</h3>
					<ul class="footer-links-list">
						<li><a href="<?php echo esc_url( get_post_type_archive_link( 'destination' ) ); ?>">مقاصد گردشگری برتر</a></li>
						<li><a href="<?php echo esc_url( get_post_type_archive_link( 'attraction' ) ); ?>">جاذبه‌های باستانی و طبیعی</a></li>
						<li><a href="<?php echo esc_url( get_post_type_archive_link( 'guide' ) ); ?>">راهنماهای اختصاصی ارزان سفر</a></li>
						<li><a href="<?php echo esc_url( get_post_type_archive_link( 'podcast' ) ); ?>">اپیزودهای صوتی رادیو سفر</a></li>
					</ul>
				</div>
			<?php endif; ?>

			<!-- Column 4: Newsletter mockup & Social Badges -->
			<?php if ( '1' === $show_newsletter_col ) : ?>
				<div class="footer-col newsletter-col">
					<h3>✉️ عضویت در خبرنامه</h3>
					<p class="footer-desc">با عضویت در خبرنامه صوتی، از آخرین مقاصد سفر باخبر شوید.</p>
					<div class="newsletter-form-mockup">
						<input type="email" placeholder="ایمیل خود را وارد کنید..." style="direction:rtl;" />
						<button type="button" class="newsletter-btn">عضویت</button>
					</div>
					<div class="footer-social-networks">
						<?php if ( ! empty( $instagram ) ) : ?>
							<a href="<?php echo esc_url( $instagram ); ?>" target="_blank" rel="noopener" class="social-badge instagram" aria-label="اینستاگرام">اینستاگرام</a>
						<?php endif; ?>
						<?php if ( ! empty( $telegram ) ) : ?>
							<a href="<?php echo esc_url( $telegram ); ?>" target="_blank" rel="noopener" class="social-badge telegram" aria-label="تلگرام">تلگرام</a>
						<?php endif; ?>
						<?php if ( ! empty( $aparat ) ) : ?>
							<a href="<?php echo esc_url( $aparat ); ?>" target="_blank" rel="noopener" class="social-badge aparat" aria-label="آپارات">آپارات</a>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>

		</div>

		<div class="footer-bottom">
			<p><?php echo esc_html( $footer_text ); ?> کپی‌رایت <?php echo esc_html( date( 'Y' ) ); ?>.</p>
		</div>
	</div>
</footer>

<!-- App-like Premium Sticky Bottom Bar Navigation for Mobile Devices -->
<?php if ( '1' === $show_sticky_nav ) : ?>
	<div class="sticky-bottom-mobile-nav" role="navigation" aria-label="ناوبری سریع موبایل">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="mobile-nav-item active">
			<span class="mobile-nav-icon">🏠</span>
			<span class="mobile-nav-label">خانه</span>
		</a>
		<a href="<?php echo esc_url( get_post_type_archive_link( 'destination' ) ); ?>" class="mobile-nav-item">
			<span class="mobile-nav-icon">🗺️</span>
			<span class="mobile-nav-label">مقاصد</span>
		</a>
		<a href="<?php echo esc_url( get_post_type_archive_link( 'podcast' ) ); ?>" class="mobile-nav-item">
			<span class="mobile-nav-icon">🎙️</span>
			<span class="mobile-nav-label">رادیو سفر</span>
		</a>
		<a href="<?php echo esc_url( get_post_type_archive_link( 'video' ) ); ?>" class="mobile-nav-item">
			<span class="mobile-nav-icon">🎬</span>
			<span class="mobile-nav-label">مستندها</span>
		</a>
	</div>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
