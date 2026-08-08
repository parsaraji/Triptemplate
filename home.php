<?php
/**
 * Professional Blog and Travelogue Template fallback (home.php)
 * Featuring beautiful layout grids, categorized filtering, and sticky feature cards.
 *
 * @package Premium_Persian_Tourism
 */

get_header(); ?>

<div class="container" style="margin-top: 40px; margin-bottom: 60px;">

	<!-- Beautiful Header Introduction -->
	<header class="archive-header text-center" style="background: linear-gradient(135deg, #1A365D 0%, #2A4365 100%); padding: 50px 20px; border-radius: 12px; margin-bottom: 40px; color:#FFF;">
		<span style="font-size:36px; display:block; margin-bottom:10px;">📰</span>
		<h1 class="archive-title" style="color:#FFF; font-size:30px; font-weight:800; margin-bottom:10px;">مجله گردشگری و سفرنامه‌های رادیو سفر</h1>
		<p class="description" style="max-width:650px; margin:0 auto; font-size:15px; color:#E2E8F0; line-height:1.8;">آخرین مطالب آموزشی سفر، توصیه‌های کوله‌گردی، معرفی کمپینگ‌ها و راهنماهای کاربردی از ایران زمین را بخوانید.</p>
	</header>

	<!-- Standard WP Loop Grid -->
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
		<div class="card-box text-center" style="padding:60px 20px;">
			<p class="text-muted" style="font-size:15px; margin:0;">هیچ نوشته یا سفرنامه‌ای در مجله یافت نشد.</p>
		</div>
	<?php endif; ?>

</div>

<?php get_footer(); ?>
