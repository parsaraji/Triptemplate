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

<header id="masthead" class="site-header">
	<div class="container header-container">

		<!-- Burger Menu Button for Mobile -->
		<button class="burger-menu-btn" aria-label="منوی ناوبری">&#9776;</button>

		<!-- Brand Logo -->
		<div class="logo-wrapper">
			<?php
			if ( has_custom_logo() ) {
				the_custom_logo();
			} else {
				echo '<a href="' . esc_url( home_url( '/' ) ) . '" class="logo-text">' . esc_html( get_bloginfo( 'name' ) ) . '</a>';
			}
			?>
		</div>

		<!-- Desktop Navigation Menu -->
		<nav class="desktop-nav" aria-label="منوی اصلی دسکتاپ">
			<?php
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'menu_id'        => 'primary-menu',
				'fallback_cb'    => false,
			) );
			?>
		</nav>

	</div>
</header>

<!-- Mobile Navigation Sidebar Drawer -->
<div id="mobile-nav-drawer" class="mobile-drawer" aria-hidden="true">
	<button class="drawer-close-btn" aria-label="بستن منو">&times;</button>
	<nav aria-label="منوی اصلی موبایل">
		<?php
		wp_nav_menu( array(
			'theme_location' => 'primary',
			'menu_class'     => 'mobile-nav-menu',
			'fallback_cb'    => false,
		) );
		?>
	</nav>
</div>
<div id="drawer-bg-overlay" class="drawer-overlay"></div>

<!-- Display Premium Responsive Header Ad placement if configured -->
<?php
if ( class_exists( 'PPT_Ad_Manager' ) ) {
	PPT_Ad_Manager::render_ad_slot( 'header_ad' );
}
?>
