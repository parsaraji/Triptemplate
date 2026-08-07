<?php
/**
 * Destinations Archive landing page
 *
 * @package Premium_Persian_Tourism
 */

get_header(); ?>

<div class="container" style="margin-top: 40px; margin-bottom: 50px;">
	<header class="archive-header" style="background-color:#E2E8F0; padding:40px; border-radius:12px; margin-bottom:40px; text-align:center;">
		<h1 class="archive-title" style="margin-bottom:10px;">مقاصد رویایی و تفریحی ایران</h1>
		<p class="description" style="max-width:650px; margin:0 auto;">سفر به شگفت‌انگیزترین شهرها، روستاها و جزایر دیدنی ایران به همراه تصاویر، پادکست‌های صوتی اختصاصی و راهنمای دقیق مسیرها.</p>
	</header>

	<!-- Advanced Province Filter Bar -->
	<div class="card-box" style="margin-bottom: 40px; padding: 20px;">
		<form method="get" action="<?php echo esc_url( get_post_type_archive_link( 'destination' ) ); ?>" style="display:flex; flex-wrap:wrap; gap:15px; align-items:center; direction:rtl;">
			<label style="font-weight:bold;">📍 فیلتر بر اساس استان:</label>
			<?php
			wp_dropdown_categories( array(
				'show_option_all' => 'همه استان‌ها',
				'taxonomy'        => 'province',
				'name'            => 'province',
				'value_field'     => 'slug',
				'selected'        => isset( $_GET['province'] ) ? sanitize_text_field( $_GET['province'] ) : '',
				'hierarchical'    => true,
				'class'           => 'filter-select',
			) );
			?>
			<button type="submit" class="button button-primary">اعمال فیلتر</button>
		</form>
	</div>

	<?php
	// Custom Tax Filter Check
	if ( isset( $_GET['province'] ) && ! empty( $_GET['province'] ) ) {
		global $wp_query;
		$tax_query = array(
			array(
				'taxonomy' => 'province',
				'field'    => 'slug',
				'terms'    => sanitize_text_field( $_GET['province'] ),
			),
		);
		query_posts( array_merge( $wp_query->query, array( 'tax_query' => $tax_query ) ) );
	}

	if ( have_posts() ) : ?>
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
			<p>هیچ مقصد گردشگری یافت نشد.</p>
		</div>
	<?php endif; ?>
</div>

<?php get_footer(); ?>
