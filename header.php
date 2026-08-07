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

		<!-- Middle: Desktop Navigation Menu -->
		<nav class="desktop-nav" aria-label="منوی اصلی دسکتاپ">
			<?php
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'menu_id'        => 'primary-menu',
				'container'      => false,
				'fallback_cb'    => false,
			) );
			?>
		</nav>

		<!-- Left Side: Quick Contact & Active Search trigger -->
		<div class="header-left">
			<a href="<?php echo esc_url( home_url( '/?s=' ) ); ?>" class="header-search-trigger" aria-label="جستجوی سریع">🔍</a>
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

	<!-- Quick Search inside Mobile Drawer -->
	<div class="drawer-search-box">
		<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<input type="text" placeholder="جستجو در مقاصد و پادکست‌ها..." name="s" required />
			<button type="submit">🔍</button>
		</form>
	</div>

	<!-- Mobile Nav List -->
	<nav class="drawer-nav" aria-label="منوی اصلی موبایل">
		<?php
		wp_nav_menu( array(
			'theme_location' => 'primary',
			'menu_class'     => 'mobile-nav-menu',
			'container'      => false,
			'fallback_cb'    => false,
		) );
		?>
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
