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

		<!-- Middle: Desktop Navigation Menu with built-in Megamenu (Priority 4) -->
		<nav class="desktop-nav" aria-label="منوی اصلی دسکتاپ">
			<ul id="primary-menu-list">
				<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">خانه</a></li>

				<!-- Mega Menu Item (Hover to view Provinces Megamenu) -->
				<li class="ppt-megamenu-trigger">
					<a href="<?php echo esc_url( get_post_type_archive_link( 'destination' ) ); ?>" class="megamenu-title-link">مقاصد گردشگری 👇</a>

					<!-- Built-in dynamic megamenu drop -->
					<div class="ppt-megamenu-dropdown">
						<div class="megamenu-columns-grid">

							<!-- Megamenu Col 1: Province Quick Links -->
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

							<!-- Megamenu Col 2: Media links -->
							<div class="megamenu-col">
								<h4>🎧 رادیو صوتی و مستندها</h4>
								<ul>
									<li><a href="<?php echo esc_url( get_post_type_archive_link( 'podcast' ) ); ?>">شنیدن اپیزودهای رادیو سفر</a></li>
									<li><a href="<?php echo esc_url( get_post_type_archive_link( 'video' ) ); ?>">تماشای آنلاین مستندهای ویدیویی</a></li>
									<li><a href="<?php echo esc_url( get_post_type_archive_link( 'guide' ) ); ?>">مطالعه راهنماهای صفر تا صد سفر</a></li>
								</ul>
							</div>

							<!-- Megamenu Col 3: Featured visual promotion -->
							<div class="megamenu-col promo-col" style="background-color:#F7FAFC; padding:15px; border-radius:6px; text-align:center;">
								<span style="font-size:32px;">📻</span>
								<h5>اپلیکیشن رادیو سفر</h5>
								<p style="font-size:11px; color:#718096; margin-bottom:10px;">همسفر صوتی شما در جاده‌های ایران زمین</p>
								<a href="<?php echo esc_url( get_post_type_archive_link( 'podcast' ) ); ?>" class="button btn-small btn-secondary" style="font-size:11px;">شروع شنیدن</a>
							</div>

						</div>
					</div>
				</li>

				<li><a href="<?php echo esc_url( get_post_type_archive_link( 'podcast' ) ); ?>">رادیو سفر (پادکست)</a></li>
				<li><a href="<?php echo esc_url( get_post_type_archive_link( 'guide' ) ); ?>">راهنماها</a></li>
				<li><a href="<?php echo esc_url( home_url( '/advertising/' ) ); ?>">تبلیغات و رزرو جایگاه</a></li>
			</ul>
		</nav>

		<!-- Left Side: Refined Desktop Search form to avoid random pages overflow (Search Bug Fix) -->
		<div class="header-left">
			<!-- Expandable Header Search trigger -->
			<div class="ppt-header-search-container">
				<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="ppt-header-search-form">
					<input type="text" placeholder="جستجو در مقاصد..." name="s" required />
					<!-- Strict restriction to ensure only relevant content type is returned, fixing the search overflow bug -->
					<input type="hidden" name="post_type[]" value="destination" />
					<input type="hidden" name="post_type[]" value="attraction" />
					<input type="hidden" name="post_type[]" value="podcast" />
					<input type="hidden" name="post_type[]" value="video" />
					<input type="hidden" name="post_type[]" value="guide" />
					<button type="submit" class="header-search-submit">🔍</button>
				</form>
			</div>
			<a href="<?php echo esc_url( get_post_type_archive_link( 'podcast' ) ); ?>" class="header-cta-btn">🎙️ رادیو سفر</a>
		</div>

	</div>
</header>

<!-- Upgraded Interactive Sliding Sidebar Mobile Drawer -->
<div id="mobile-nav-drawer" class="mobile-drawer" aria-hidden="true" role="dialog" aria-modal="true" aria-label="منوی موبایل">
	<div class="drawer-header">
		<span class="drawer-logo">🎙️ رادیو سفر</span>
		<button class="drawer-close-btn" aria-label="بستن منو">&times;</button>
	</div>

	<!-- Quick Search inside Mobile Drawer targeting only main post types -->
	<div class="drawer-search-box">
		<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<input type="text" placeholder="جستجو در مقاصد و پادکست‌ها..." name="s" required />
			<input type="hidden" name="post_type[]" value="destination" />
			<input type="hidden" name="post_type[]" value="attraction" />
			<input type="hidden" name="post_type[]" value="podcast" />
			<button type="submit">🔍</button>
		</form>
	</div>

	<!-- Mobile Nav List -->
	<nav class="drawer-nav" aria-label="منوی اصلی موبایل">
		<ul class="mobile-nav-menu">
			<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">خانه</a></li>
			<li><a href="<?php echo esc_url( get_post_type_archive_link( 'destination' ) ); ?>">مقاصد گردشگری</a></li>
			<li><a href="<?php echo esc_url( get_post_type_archive_link( 'podcast' ) ); ?>">رادیو صوتی پادکست</a></li>
			<li><a href="<?php echo esc_url( get_post_type_archive_link( 'video' ) ); ?>">مستندهای تصویری</a></li>
			<li><a href="<?php echo esc_url( get_post_type_archive_link( 'guide' ) ); ?>">راهنماهای مکتوب</a></li>
			<li><a href="<?php echo esc_url( home_url( '/advertising/' ) ); ?>">تبلیغات و رزرو جایگاه</a></li>
		</ul>
	</nav>

	<!-- Quick Contacts and Social Networks in Mobile Drawer Footer -->
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

<!-- Display Premium Responsive Header Ad placement if configured -->
<?php
if ( class_exists( 'PPT_Ad_Manager' ) ) {
	PPT_Ad_Manager::render_ad_slot( 'header_ad' );
}
?>
