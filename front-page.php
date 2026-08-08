<?php
/**
 * Dynamic Tourism Discovery Hub Home Page Template
 * Completely upgraded to fully support dynamic layouts (Carousel, List, Grid)
 * for all configured modules (Priority 7).
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
		array( 'id' => 'videos', 'title' => 'سفر تصویری - ویدیوها و مستندها', 'enabled' => '1', 'source' => 'video', 'count' => 3, 'layout' => 'carousel' ),
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
						<input type="hidden" name="post_type[]" value="destination" />
						<input type="hidden" name="post_type[]" value="attraction" />
						<input type="hidden" name="post_type[]" value="podcast" />
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

			<!-- Dynamic layout rendering based on admin configuration -->
			<?php if ( 'carousel' === $layout ) : ?>
				<!-- Carousel snap slider wrapper -->
				<div class="ppt-carousel-wrapper" style="overflow-x: auto; scroll-snap-type: x mandatory; display: flex; gap: 20px; padding-bottom: 15px; -webkit-overflow-scrolling: touch; scrollbar-width: thin;">
					<?php
					while ( $query->have_posts() ) :
						$query->the_post();
						?>
						<div style="flex: 0 0 280px; scroll-snap-align: start;">
							<?php get_template_part( 'template-parts/content', 'card' ); ?>
						</div>
						<?php
					endwhile;
					wp_reset_postdata();
					?>
				</div>
			<?php elseif ( 'list' === $layout ) : ?>
				<!-- List Layout (Vertical lined list) -->
				<div class="ppt-horizontal-list-wrapper" style="display: flex; flex-direction: column; gap: 15px;">
					<?php
					while ( $query->have_posts() ) :
						$query->the_post();
						?>
						<div style="background: #FFF; border: 1px solid #E2E8F0; border-radius: 12px; padding: 15px; display: flex; gap: 20px; align-items: center; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
							<?php if ( has_post_thumbnail() ) : ?>
								<div style="width: 120px; aspect-ratio: 16/9; border-radius: 8px; overflow: hidden; flex-shrink: 0;">
									<?php the_post_thumbnail( 'ppt-card-thumb', array( 'style' => 'width:100%; height:100%; object-fit:cover;' ) ); ?>
								</div>
							<?php endif; ?>
							<div>
								<h3 style="font-size: 16px; font-weight: bold; margin: 0 0 8px 0;"><a href="<?php the_permalink(); ?>" style="color: #2D3748; text-decoration: none;"><?php the_title(); ?></a></h3>
								<p style="margin: 0; font-size: 13px; color: #718096; line-height: 1.6;"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 18, '...' ) ); ?></p>
							</div>
						</div>
						<?php
					endwhile;
					wp_reset_postdata();
					?>
				</div>
			<?php else : ?>
				<!-- Standard Editorial Grid Layout (Grid) -->
				<div class="editorial-grid">
					<?php
					while ( $query->have_posts() ) :
						$query->the_post();
						get_template_part( 'template-parts/content', 'card' );
					endwhile;
					wp_reset_postdata();
					?>
				</div>
			<?php endif; ?>
		</section>
		<?php
	endif;
}

// 3. Static Call-to-Action Promo Section for Brand Engagement
?>
<section class="cta-banner-section container" style="background: linear-gradient(135deg, #2B6CB0 0%, #1A365D 100%); color:#FFF; padding:60px 20px; text-align:center; margin-top:60px; border-radius:16px;">
	<div style="max-width:800px; margin: 0 auto;">
		<span style="font-size:32px; display:block; margin-bottom:15px;">🎙️</span>
		<h2 style="color:#FFF; font-size:28px; margin-bottom:15px; font-weight:800;">به جمع همسفران صوتی رادیو سفر بپیوندید</h2>
		<p style="font-size:16px; line-height:1.8; color:#E2E8F0; margin-bottom:30px;">اپیزودهای صوتی شنیدنی از شگفتی‌های ناشناخته، کویرهای لوت، جنگل‌های هیرکانی و داستان‌های شیرین مردمان بومی ایران با صدای بهترین گویندگان.</p>
		<a href="<?php echo esc_url( get_post_type_archive_link( 'podcast' ) ); ?>" class="button" style="background-color:#E53E3E; color:#FFF; padding:12px 35px; border-radius:30px; font-weight:bold; font-size:16px; box-shadow: 0 4px 15px rgba(229,62,62,0.4); display:inline-block;">شروع شنیدن رایگان اپیزودها</a>
	</div>
</section>

<?php
get_footer();
