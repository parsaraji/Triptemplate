<?php
/**
 * Dynamic Tourism Discovery Hub Home Page Template
 * Completely upgraded with refined layout grids, customizable section headers, and call-to-actions.
 *
 * @package Premium_Persian_Tourism
 */

get_header();

// Fetch homepage sections configuration
$homepage_data = get_option( 'ppt_homepage_sections', array() );
$sections      = isset( $homepage_data['sections'] ) ? $homepage_data['sections'] : array();

if ( empty( $sections ) ) {
	$sections = array(
		array( 'id' => 'hero_search', 'title' => 'جستجوی مقاصد گردشگری', 'enabled' => '1', 'source' => 'all', 'count' => 0, 'layout' => 'hero' ),
		array( 'id' => 'destinations', 'title' => 'مقاصد برتر ایران زمین', 'enabled' => '1', 'source' => 'destination', 'count' => 6, 'layout' => 'grid' ),
		array( 'id' => 'attractions', 'title' => 'جاذبه‌های گردشگری و باستانی محبوب', 'enabled' => '1', 'source' => 'attraction', 'count' => 4, 'layout' => 'grid' ),
		array( 'id' => 'podcasts', 'title' => 'رادیو سفر - پادکست‌های صوتی صمیمانه', 'enabled' => '1', 'source' => 'podcast', 'count' => 3, 'layout' => 'list' ),
		array( 'id' => 'videos', 'title' => 'سفر تصویری - ویدیوها و مستندها', 'enabled' => '1', 'source' => 'video', 'count' => 3, 'layout' => 'grid' ),
		array( 'id' => 'guides', 'title' => 'راهنماهای کاربردی و تجربیات سفر', 'enabled' => '1', 'source' => 'guide', 'count' => 4, 'layout' => 'grid' )
	);
}

// Render homepage sections sequentially
foreach ( $sections as $section ) {
	if ( ! isset( $section['enabled'] ) || '1' !== $section['enabled'] ) {
		continue;
	}

	$section_id = $section['id'];
	$title      = $section['title'];
	$source     = $section['source'];
	$count      = isset( $section['count'] ) ? intval( $section['count'] ) : 4;
	$layout     = isset( $section['layout'] ) ? $section['layout'] : 'grid';

	// 1. Hero Search Section
	if ( 'hero_search' === $section_id ) {
		?>
		<section class="hero-search-section">
			<div class="container">
				<h1 style="font-weight: 900; letter-spacing: -0.5px;"><?php echo esc_html( $title ); ?></h1>
				<p>بزرگ‌ترین و کامل‌ترین دانشنامه معرفی مقاصد گردشگری، دیدنی‌های باستانی، مستندهای تصویری و پادکست‌های صوتی صمیمانه در ایران زمین</p>
				<div class="search-form-wrapper">
					<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
						<input type="text" placeholder="مثلاً: شیراز، اصفهان، جزیره قشم، تخت جمشید..." name="s" required />
						<input type="hidden" name="post_type" value="destination" />
						<button type="submit">جستجوی صمیمانه</button>
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
		<section class="ppt-home-module-section container" style="margin-top: 60px; margin-bottom: 30px;">
			<!-- Styled Section Header -->
			<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 30px; border-bottom:2px solid #E2E8F0; padding-bottom:12px;">
				<h2 style="margin:0; font-size: 24px; position:relative; padding-bottom:12px; font-weight:800; color:#1A202C;">
					<?php echo esc_html( $title ); ?>
					<span style="position:absolute; bottom:-14px; right:0; width:80px; height:4px; background-color:#3182CE; border-radius:2px;"></span>
				</h2>
				<a href="<?php echo esc_url( get_post_type_archive_link( $source ) ); ?>" class="button button-link" style="font-weight:bold; font-size:14px; color:#3182CE;">مشاهده همه موارد &larr;</a>
			</div>

			<!-- Dynamic Grid Styles -->
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

// 3. Static Call-to-Action Promo Section for Brand Engagement
?>
<section class="cta-banner-section" style="background: linear-gradient(135deg, #2B6CB0 0%, #1A365D 100%); color:#FFF; padding:60px 20px; text-align:center; margin-top:60px; border-radius:16px;" class="container">
	<div class="container" style="max-width:800px; margin: 0 auto;">
		<span style="font-size:32px; display:block; margin-bottom:15px;">🎙️</span>
		<h2 style="color:#FFF; font-size:28px; margin-bottom:15px; font-weight:800;">به جمع همسفران صوتی رادیو سفر بپیوندید</h2>
		<p style="font-size:16px; line-height:1.8; color:#E2E8F0; margin-bottom:30px;">اپیزودهای صوتی شنیدنی از شگفتی‌های ناشناخته، کویرهای لوت، جنگل‌های هیرکانی و داستان‌های شیرین مردمان بومی ایران با صدای بهترین گویندگان.</p>
		<a href="<?php echo esc_url( get_post_type_archive_link( 'podcast' ) ); ?>" class="button" style="background-color:#E53E3E; color:#FFF; padding:12px 35px; border-radius:30px; font-weight:bold; font-size:16px; box-shadow: 0 4px 15px rgba(229,62,62,0.4); display:inline-block;">شروع شنیدن رایگان اپیزودها</a>
	</div>
</section>

<?php
get_footer();
