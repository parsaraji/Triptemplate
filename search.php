<?php
/**
 * Generic Search Results Template
 *
 * @package Premium_Persian_Tourism
 */

get_header(); ?>

<div class="container" style="margin-top: 40px; margin-bottom: 50px;">
	<header class="page-header" style="margin-bottom:35px; border-bottom:1px solid #E2E8F0; padding-bottom:15px;">
		<h1 class="page-title">
			<?php
			/* translators: %s: search query. */
			printf( esc_html__( 'نتایج جستجو برای: %s', 'premium-persian-tourism' ), '<span style="color:#3182CE;">' . get_search_query() . '</span>' );
			?>
		</h1>
	</header>

	<?php if ( have_posts() ) : ?>
		<div class="editorial-grid">
			<?php
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/content', 'card' );
			endwhile;
			?>
		</div>

		<?php
		the_posts_navigation( array(
			'prev_text' => 'صفحه قبلی',
			'next_text' => 'صفحه بعدی',
		) );
		?>

	<?php else : ?>
		<div class="card-box text-center" style="padding: 60px 20px;">
			<h2>موردی یافت نشد!</h2>
			<p class="description">متأسفانه مقصدی متناسب با واژه جستجوی شما پیدا نشد. لطفاً از واژه‌های دیگری استفاده کنید.</p>
			<div style="max-width:400px; margin: 0 auto;">
				<?php get_search_form(); ?>
			</div>
		</div>
	<?php endif; ?>
</div>

<?php get_footer(); ?>
