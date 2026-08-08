<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php
// Retrieve Custom Header Configs
$header_settings = get_option( 'ppt_header_settings', array() );
if ( class_exists( 'PPT_Admin_Panel' ) ) {
	$defaults = PPT_Admin_Panel::get_default_header_settings();
} else {
	$defaults = array();
}
$settings = wp_parse_args( $header_settings, $defaults );

$show_search = isset( $settings['show_search'] ) ? $settings['show_search'] : '1';
$show_cta    = isset( $settings['show_cta'] ) ? $settings['show_cta'] : '1';
$cta_text    = isset( $settings['cta_text'] ) ? $settings['cta_text'] : '🎙️ رادیو سفر';
$cta_link    = isset( $settings['cta_link'] ) ? $settings['cta_link'] : '/podcasts/';
$links       = isset( $settings['custom_links'] ) ? $settings['custom_links'] : array();
?>

<!-- Premium Persian RTL Header -->
<header id="masthead" class="site-header premium-header">
	<div class="container header-container">

		<!-- Right Side: Brand Logo and Burger Button -->
		<div class="header-right">
			<!-- Burger Menu Button for Mobile -->
			<button class="burger-menu-btn" aria-label="منوی ناوبری" aria-expanded="false" aria-controls="mobile-nav-drawer">
				<span class="burger-icon-bar"></span>
				<span class="burger-icon-bar"></span>
				<span class="burger-icon-bar"></span>
			</button>

			<div class="logo-wrapper">
				<?php
				if ( has_custom_logo() ) {
					the_custom_logo();
				} else {
					echo '<a href="' . esc_url( home_url( '/' ) ) . '" class="logo-link">';
					echo '<span class="logo-icon">🎙️</span>';
					echo '<div class="logo-brand-meta">';
					echo '<span class="logo-text">' . esc_html( get_bloginfo( 'name' ) ) . '</span>';
					echo '<span class="logo-tagline">' . esc_html( get_bloginfo( 'description' ) ) . '</span>';
					echo '</div>';
					echo '</a>';
				}
				?>
			</div>
		</div>

		<!-- Middle: Desktop Navigation Menu with built-in Megamenu -->
		<nav class="desktop-nav" aria-label="منوی اصلی دسکتاپ">
			<ul id="primary-menu-list">
				<?php
				if ( ! empty( $links ) && is_array( $links ) ) {
					foreach ( $links as $link ) {
						if ( empty( $link['label'] ) ) continue;

						$url = $link['url'];
						// Normalize relative URLs cleanly
						if ( 0 === strpos( $url, '/' ) ) {
							$url = home_url( $url );
						}

						// Activate Megamenu if it represents Travel Destinations or Destinations keyword
						if ( false !== strpos( $link['url'], 'destination' ) ) {
							?>
							<li class="ppt-megamenu-trigger">
								<a href="<?php echo esc_url( $url ); ?>" class="megamenu-title-link"><?php echo esc_html( $link['label'] ); ?> 👇</a>

								<!-- Dynamic Built-in Megamenu Dropdown -->
								<div class="ppt-megamenu-dropdown">
									<div class="megamenu-columns-grid">

										<!-- Col 1: Provinces List -->
										<div class="megamenu-col">
											<h4>📍 استان‌های دیدنی ایران</h4>
											<ul>
												<?php
												$provinces = get_terms( array( 'taxonomy' => 'province', 'number' => 5, 'hide_empty' => false ) );
												if ( ! empty( $provinces ) && ! is_wp_error( $provinces ) ) {
													foreach ( $provinces as $prov ) {
														echo '<li><a href="' . esc_url( get_term_link( $prov ) ) . '">سفر به استان ' . esc_html( $prov->name ) . '</a></li>';
													}
												}
												?>
											</ul>
										</div>

										<!-- Col 2: Custom Post Type Lists -->
										<div class="megamenu-col">
											<h4>🎧 رادیو صوتی و مستندها</h4>
											<ul>
												<li><a href="<?php echo esc_url( get_post_type_archive_link( 'podcast' ) ); ?>">شنیدن اپیزودهای رادیو سفر</a></li>
												<li><a href="<?php echo esc_url( get_post_type_archive_link( 'video' ) ); ?>">تماشای آنلاین مستندهای ویدیویی</a></li>
												<li><a href="<?php echo esc_url( get_post_type_archive_link( 'guide' ) ); ?>">مطالعه راهنماهای صفر تا صد سفر</a></li>
											</ul>
										</div>

										<!-- Col 3: Promotional Offer -->
										<div class="megamenu-col promo-col" style="background-color:#F7FAFC; padding:15px; border-radius:6px; text-align:center;">
											<span style="font-size:32px;">📻</span>
											<h5>اپلیکیشن رادیو سفر</h5>
											<p style="font-size:11px; color:#718096; margin-bottom:10px;">همسفر صوتی شما در جاده‌های ایران زمین</p>
											<a href="<?php echo esc_url( get_post_type_archive_link( 'podcast' ) ); ?>" class="button btn-small btn-secondary" style="font-size:11px;">شروع شنیدن</a>
										</div>

									</div>
								</div>
							</li>
							<?php
						} else {
							echo '<li><a href="' . esc_url( $url ) . '">' . esc_html( $link['label'] ) . '</a></li>';
						}
					}
				} else {
					// Fallback standard structure if links are empty
					?>
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">خانه</a></li>
					<li><a href="<?php echo esc_url( get_post_type_archive_link( 'destination' ) ); ?>">مقاصد گردشگری</a></li>
					<li><a href="<?php echo esc_url( get_post_type_archive_link( 'podcast' ) ); ?>">رادیو سفر</a></li>
					<li><a href="<?php echo esc_url( get_post_type_archive_link( 'guide' ) ); ?>">راهنماها</a></li>
					<li><a href="<?php echo esc_url( home_url( '/advertising/' ) ); ?>">تبلیغات</a></li>
					<?php
				}
				?>
			</ul>
		</nav>

		<!-- Left Side: Expandable Search & CTA Button Toggle -->
		<div class="header-left">
			<?php if ( '1' === $show_search ) : ?>
				<div class="ppt-header-search-container">
					<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="ppt-header-search-form">
						<input type="text" placeholder="جستجو در مقاصد..." name="s" required />
						<input type="hidden" name="post_type[]" value="destination" />
						<input type="hidden" name="post_type[]" value="attraction" />
						<input type="hidden" name="post_type[]" value="podcast" />
						<input type="hidden" name="post_type[]" value="video" />
						<input type="hidden" name="post_type[]" value="guide" />
						<button type="submit" class="header-search-submit">🔍</button>
					</form>
				</div>
			<?php endif; ?>

			<?php if ( '1' === $show_cta ) :
				$final_cta_link = $cta_link;
				if ( 0 === strpos( $final_cta_link, '/' ) ) {
					$final_cta_link = home_url( $final_cta_link );
				}
				?>
				<a href="<?php echo esc_url( $final_cta_link ); ?>" class="header-cta-btn">🎙️ <?php echo esc_html( $cta_text ); ?></a>
			<?php endif; ?>
		</div>

	</div>
</header>

<!-- Mobile Navigation Drawer -->
<div id="mobile-nav-drawer" class="mobile-drawer" aria-hidden="true" role="dialog" aria-modal="true" aria-label="منوی موبایل">
	<div class="drawer-header">
		<span class="drawer-logo">🎙️ رادیو سفر</span>
		<button class="drawer-close-btn" aria-label="بستن منو">&times;</button>
	</div>

	<!-- Quick Search inside Mobile Drawer -->
	<?php if ( '1' === $show_search ) : ?>
		<div class="drawer-search-box">
			<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<input type="text" placeholder="جستجو در مقاصد و پادکست‌ها..." name="s" required />
				<input type="hidden" name="post_type[]" value="destination" />
				<input type="hidden" name="post_type[]" value="attraction" />
				<input type="hidden" name="post_type[]" value="podcast" />
				<button type="submit">🔍</button>
			</form>
		</div>
	<?php endif; ?>

	<!-- Mobile Nav List -->
	<nav class="drawer-nav" aria-label="منوی اصلی موبایل">
		<ul class="mobile-nav-menu">
			<?php
			if ( ! empty( $links ) && is_array( $links ) ) {
				foreach ( $links as $link ) {
					if ( empty( $link['label'] ) ) continue;
					$url = $link['url'];
					if ( 0 === strpos( $url, '/' ) ) {
						$url = home_url( $url );
					}
					echo '<li><a href="' . esc_url( $url ) . '">' . esc_html( $link['label'] ) . '</a></li>';
				}
			} else {
				?>
				<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">خانه</a></li>
				<li><a href="<?php echo esc_url( get_post_type_archive_link( 'destination' ) ); ?>">مقاصد گردشگری</a></li>
				<li><a href="<?php echo esc_url( get_post_type_archive_link( 'podcast' ) ); ?>">رادیو صوتی پادکست</a></li>
				<li><a href="<?php echo esc_url( get_post_type_archive_link( 'video' ) ); ?>">مستندهای تصویری</a></li>
				<li><a href="<?php echo esc_url( get_post_type_archive_link( 'guide' ) ); ?>">راهنماهای مکتوب</a></li>
				<?php
			}
			?>
		</ul>
	</nav>

	<!-- Quick Contacts and Social Networks inside Drawer Footer -->
	<div class="drawer-footer">
		<?php
		$brand = get_option( 'ppt_brand_settings', array() );
		$phone = isset( $brand['contact_phone'] ) ? $brand['contact_phone'] : '۰۲۱-۸۸۸۸۸۸۸۸';
		$instagram = isset( $brand['social_instagram'] ) ? $brand['social_instagram'] : '';
		$telegram = isset( $brand['social_telegram'] ) ? $brand['social_telegram'] : '';
		$aparat = isset( $brand['social_aparat'] ) ? $brand['social_aparat'] : '';
		?>
		<div class="drawer-contact-info">
			<span>📞 تلفن: <?php echo esc_html( $phone ); ?></span>
		</div>
		<div class="drawer-socials">
			<?php if ( ! empty( $instagram ) ) : ?>
				<a href="<?php echo esc_url( $instagram ); ?>" target="_blank" rel="noopener">اینستاگرام</a>
			<?php endif; ?>
			<?php if ( ! empty( $telegram ) ) : ?>
				<a href="<?php echo esc_url( $telegram ); ?>" target="_blank" rel="noopener">تلگرام</a>
			<?php endif; ?>
			<?php if ( ! empty( $aparat ) ) : ?>
				<a href="<?php echo esc_url( $aparat ); ?>" target="_blank" rel="noopener">آپارات</a>
			<?php endif; ?>
		</div>
	</div>
</div>
<div id="drawer-bg-overlay" class="drawer-overlay"></div>

<!-- Display Premium Responsive Header Ad placement -->
<?php
if ( class_exists( 'PPT_Ad_Manager' ) ) {
	PPT_Ad_Manager::render_ad_slot( 'header_ad' );
}
?>
