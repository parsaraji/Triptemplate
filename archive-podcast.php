<?php
/**
 * Podcast Archive page - Radio Safar Hub
 *
 * @package Premium_Persian_Tourism
 */

get_header(); ?>

<div class="container" style="margin-top: 40px; margin-bottom: 50px;">
	<header class="archive-header" style="background-color:#FFF5F5; border:1px solid #FED7D7; padding:40px; border-radius:12px; margin-bottom:40px; text-align:center;">
		<h1 class="archive-title" style="margin-bottom:10px; color:#9B2C2C;">رادیو سفر - پادکست‌های اختصاصی</h1>
		<p class="description" style="max-width:650px; margin:0 auto; color:#742A2A;">شنیدن تجربه‌های واقعی، روایت صوتی شگفتی‌ها، تاریخچه‌ها و افسانه‌های کهن مناطق مختلف ایران در سفرهایی جذاب.</p>
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
			<p>هیچ پادکست صوتی یافت نشد.</p>
		</div>
	<?php endif; ?>
</div>

<?php get_footer(); ?>
