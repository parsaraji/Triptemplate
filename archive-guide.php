<?php
/**
 * Travel Guides listing page archive
 *
 * @package Premium_Persian_Tourism
 */

get_header(); ?>

<div class="container" style="margin-top: 40px; margin-bottom: 50px;">
	<header class="archive-header" style="background-color:#E2E8F0; padding:40px; border-radius:12px; margin-bottom:40px; text-align:center;">
		<h1 class="archive-title" style="margin-bottom:10px;">راهنماهای کامل و کاربردی سفر</h1>
		<p class="description" style="max-width:650px; margin:0 auto;">سفرنامه‌ها، بودجه‌بندی‌ها، راهنمای ترابری، اقامت و غذاهای محلی در شهرهای مختلف به زبان ساده.</p>
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
		<div class="card-box text-center">
			<p>هیچ راهنمای سفری یافت نشد.</p>
		</div>
	<?php endif; ?>
</div>

<?php get_footer(); ?>
