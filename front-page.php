<?php
/**
 * Dynamic Tourism Discovery Hub Home Page Template
 *
 * @package Premium_Persian_Tourism
 */

get_header();

// Fetch homepage settings
$homepage_data = get_option( 'ppt_homepage_sections', array() );
$sections      = isset( $homepage_data['sections'] ) ? $homepage_data['sections'] : array();

if ( empty( $sections ) ) {
	// Fallback/Default config if empty.
	$sections = array(
		array( 'id' => 'hero_search', 'title' => 'جستجوی مقاصد گردشگری', 'enabled' => '1', 'source' => 'all', 'count' => 0, 'layout' => 'hero' ),
		array( 'id' => 'destinations', 'title' => 'مقاصد برتر ایران', 'enabled' => '1', 'source' => 'destination', 'count' => 6, 'layout' => 'grid' ),
	);
}

// Render home modules in ordered priority
foreach ( $sections as $section ) {
	if ( ! isset( $section['enabled'] ) || '1' !== $section['enabled'] ) {
		continue;
	}

	$section_id = $section['id'];
	$title      = $section['title'];
	$source     = $section['source'];
	$count      = isset( $section['count'] ) ? intval( $section['count'] ) : 4;
	$layout     = isset( $section['layout'] ) ? $section['layout'] : 'grid';

	// 1. Hero Search module
	if ( 'hero_search' === $section_id ) {
		?>
		<section class="hero-search-section">
			<div class="container">
				<h1><?php echo esc_html( $title ); ?></h1>
				<p>بزرگ‌ترین مرجع معرفی مقاصد گردشگری، دیدنی‌ها و پادکست‌های صوتی اختصاصی رادیو سفر</p>
				<div class="search-form-wrapper">
					<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
						<input type="text" placeholder="مثلاً: شیراز، جنگل ابر، تخت جمشید..." name="s" required />
						<input type="hidden" name="post_type" value="destination" />
						<button type="submit">جستجو</button>
					</form>
				</div>
			</div>
		</section>
		<?php
		continue;
	}

	// 2. Query other custom content types.
	$args = array(
		'post_type'      => $source,
		'posts_per_page' => $count,
		'post_status'    => 'publish',
	);

	$query = new WP_Query( $args );

	if ( $query->have_posts() ) :
		?>
		<section class="ppt-home-module-section container" style="margin-top: 50px;">
			<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 25px; border-bottom:2px solid #EDF2F7; padding-bottom:12px;">
				<h2 style="margin:0; font-size: 24px; position:relative; padding-bottom:10px;">
					<?php echo esc_html( $title ); ?>
					<span style="position:absolute; bottom:-12px; right:0; width:60px; height:3px; background-color:#3182CE;"></span>
				</h2>
				<a href="<?php echo esc_url( get_post_type_archive_link( $source ) ); ?>" class="button button-link" style="font-weight:bold; font-size:14px;">مشاهده همه آرشیو &larr;</a>
			</div>

			<div class="editorial-grid">
				<?php
				while ( $query->have_posts() ) :
					$query->the_post();
					get_template_part( 'template-parts/content', 'card' );
				endwhile;
				wp_reset_postdata();
				?>
			</div>
		</section>
		<?php
	endif;
}

get_footer();
