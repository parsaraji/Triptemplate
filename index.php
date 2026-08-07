<?php
/**
 * Standard Archive and Index fallback layout
 *
 * @package Premium_Persian_Tourism
 */

get_header(); ?>

<div class="container" style="margin-top: 40px; margin-bottom: 50px;">
	<header class="page-header" style="margin-bottom:30px; border-bottom:1px solid #E2E8F0; padding-bottom:15px;">
		<h1 class="page-title">
			<?php
			if ( is_archive() ) {
				the_archive_title();
			} else {
				echo 'آخرین مطالب و سفرنامه‌ها';
			}
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
		<div class="card-box text-center">
			<p>هیچ مطلبی یافت نشد.</p>
		</div>
	<?php endif; ?>
</div>

<?php get_footer(); ?>
