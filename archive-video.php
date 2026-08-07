<?php
/**
 * Video Archive template - Safar Tasviri hub
 *
 * @package Premium_Persian_Tourism
 */

get_header(); ?>

<div class="container" style="margin-top: 40px; margin-bottom: 50px;">
	<header class="archive-header" style="background-color:#F0FFF4; border:1px solid #C6F6D5; padding:40px; border-radius:12px; margin-bottom:40px; text-align:center;">
		<h1 class="archive-title" style="margin-bottom:10px; color:#22543D;">سفر تصویری - ویدیوها و فیلم‌های دیدنی</h1>
		<p class="description" style="max-width:650px; margin:0 auto; color:#2F855A;">تایم‌لپس‌های رویایی، مستندهای بومی و فیلم‌های باکیفیت آپارات از دل شگفتی‌های ناشناخته ایران زمین.</p>
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
			<p>هیچ ویدیویی یافت نشد.</p>
		</div>
	<?php endif; ?>
</div>

<?php get_footer(); ?>
