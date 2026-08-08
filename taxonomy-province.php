<?php
/**
 * Province Taxonomy Landing Archive Template
 * Generates an extremely polished, high-conversion editorial landing page for each Province,
 * listing its associated Destinations, Attractions, Itineraries, and Guides in distinct sections.
 *
 * @package Premium_Persian_Tourism
 */

get_header();

$term = get_queried_object();
$brand_settings = get_option( 'ppt_brand_settings', array() );
$prov_color     = isset( $brand_settings['prov_color'] ) ? $brand_settings['prov_color'] : '#2B6CB0';
?>

<div class="container" style="margin-top:40px; margin-bottom:60px; font-family: 'Shabnam', sans-serif; direction: rtl; text-align: right;">

	<!-- 1. Editorial Province Header Banner -->
	<header class="province-hero-banner" style="background: linear-gradient(135deg, <?php echo esc_attr( $prov_color ); ?> 0%, #1A202C 100%); padding: 60px 30px; border-radius: 16px; color: #FFF; text-align: center; margin-bottom: 45px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
		<span style="font-size: 42px; display: block; margin-bottom: 10px;">🗺️</span>
		<h1 style="color: #FFF; font-size: 30px; font-weight: 800; margin-bottom: 10px;">راهنمای سفر و جاذبه‌های گردشگری استان <?php echo esc_html( $term->name ); ?></h1>
		<p style="color: #E2E8F0; font-size: 15px; max-width: 700px; margin: 0 auto; line-height: 1.8;">
			<?php echo esc_html( $term->description ? $term->description : 'بررسی مقاصد توریستی، جاذبه‌های باستانی، بکرترین مناطق طبیعی و برنامه‌های روزانه سفر به استان ' . $term->name ); ?>
		</p>
	</header>

	<!-- Render Content Ad Top Slot if configured -->
	<?php
	if ( class_exists( 'PPT_Ad_Manager' ) ) {
		PPT_Ad_Manager::render_ad_slot( 'content_ad_top' );
	}
	?>

	<!-- 2. Distinct Content Type Grids inside the Province -->
	<div class="layout-with-sidebar" style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">

		<main class="site-main" style="display: flex; flex-direction: column; gap: 40px;">

			<!-- SECTION A: Destinations (مقاصد توریستی) -->
			<section class="province-section">
				<h2 style="color: #2C5282; font-size: 20px; font-weight: 800; border-bottom: 2px solid #EDF2F7; padding-bottom: 8px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
					<span>📍</span> مقاصد و شهرهای توریستی استان
				</h2>
				<?php
				$dest_query = new WP_Query( array(
					'post_type'      => 'destination',
					'posts_per_page' => 6,
					'tax_query'      => array(
						array(
							'taxonomy' => 'province',
							'field'    => 'term_id',
							'terms'    => $term->term_id,
						),
					),
				) );

				if ( $dest_query->have_posts() ) :
					?>
					<div class="editorial-grid" style="grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 20px;">
						<?php
						while ( $dest_query->have_posts() ) :
							$dest_query->the_post();
							get_template_part( 'template-parts/content', 'card' );
						endwhile;
						wp_reset_postdata();
						?>
					</div>
					<?php
				else :
					echo '<div style="background:#F7FAFC; padding:20px; border-radius:8px; border:1px solid #E2E8F0; color:#718096; font-size:14px;">مقصدی هنوز برای این استان ثبت نشده است.</div>';
				endif;
				?>
			</section>

			<!-- SECTION B: Attractions (جاذبه‌های گردشگری) -->
			<section class="province-section">
				<h2 style="color: #2C5282; font-size: 20px; font-weight: 800; border-bottom: 2px solid #EDF2F7; padding-bottom: 8px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
					<span>📸</span> جاذبه‌های دیدنی، تاریخی و طبیعی
				</h2>
				<?php
				$attr_query = new WP_Query( array(
					'post_type'      => 'attraction',
					'posts_per_page' => 6,
					'tax_query'      => array(
						array(
							'taxonomy' => 'province',
							'field'    => 'term_id',
							'terms'    => $term->term_id,
						),
					),
				) );

				if ( $attr_query->have_posts() ) :
					?>
					<div class="editorial-grid" style="grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 20px;">
						<?php
						while ( $attr_query->have_posts() ) :
							$attr_query->the_post();
							get_template_part( 'template-parts/content', 'card' );
						endwhile;
						wp_reset_postdata();
						?>
					</div>
					<?php
				else :
					echo '<div style="background:#F7FAFC; padding:20px; border-radius:8px; border:1px solid #E2E8F0; color:#718096; font-size:14px;">جاذبه توریستی فعالی برای این استان پیدا نشد.</div>';
				endif;
				?>
			</section>

			<!-- SECTION C: Itineraries & Travel Guides (برنامه‌های سفر و راهنماها) -->
			<section class="province-section">
				<h2 style="color: #2C5282; font-size: 20px; font-weight: 800; border-bottom: 2px solid #EDF2F7; padding-bottom: 8px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
					<span>📚</span> برنامه‌های سفر و راهنماهای کاربردی
				</h2>
				<?php
				$guide_query = new WP_Query( array(
					'post_type'      => array( 'itinerary', 'guide' ),
					'posts_per_page' => 4,
					'tax_query'      => array(
						array(
							'taxonomy' => 'province',
							'field'    => 'term_id',
							'terms'    => $term->term_id,
						),
					),
				) );

				if ( $guide_query->have_posts() ) :
					?>
					<div class="editorial-grid" style="grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 20px;">
						<?php
						while ( $guide_query->have_posts() ) :
							$guide_query->the_post();
							get_template_part( 'template-parts/content', 'card' );
						endwhile;
						wp_reset_postdata();
						?>
					</div>
					<?php
				else :
					echo '<div style="background:#F7FAFC; padding:20px; border-radius:8px; border:1px solid #E2E8F0; color:#718096; font-size:14px;">برنامه سفر یا راهنمای تخصصی برای این استان ثبت نشده است.</div>';
				endif;
				?>
			</section>

			<!-- Dynamic Taxonomy End Ad Injection (Priority 5) -->
			<?php
			if ( class_exists( 'PPT_Ad_Manager' ) ) {
				PPT_Ad_Manager::render_taxonomy_end_ad();
			}
			?>

		</main>

		<!-- Right Sidebar area -->
		<aside class="sidebar-right">

			<div class="widget card-box" style="background:#FFF; border:1px solid #E2E8F0; border-radius:12px; padding:20px; margin-bottom:25px;">
				<h3 class="widget-title" style="font-size:16px; font-weight:bold; color:#2D3748; margin-bottom:12px; border-bottom:2px solid #EDF2F7; padding-bottom:8px;">🗺️ درباره استان <?php echo esc_html( $term->name ); ?></h3>
				<p style="font-size:13.5px; line-height:1.8; color:#4A5568;" class="text-justify">
					استان <?php echo esc_html( $term->name ); ?> یکی از مقاصد استراتژیک گردشگری ایران است که سالانه میزبان صدها هزار مسافر داخلی و گردشگر بین‌المللی می‌باشد. جاذبه‌های ثبت شده، هتل‌های سنتی و غذاهای بومی منحصربه‌فرد این استان را در بخش‌های روبرو به تفکیک بررسی فرمایید.
				</p>
			</div>

			<?php if ( is_active_sidebar( 'main-sidebar' ) ) : ?>
				<?php dynamic_sidebar( 'main-sidebar' ); ?>
			<?php endif; ?>

			<!-- Cumulative Layout Shift Protected Ad Placeholder -->
			<?php
			if ( class_exists( 'PPT_Ad_Manager' ) ) {
				PPT_Ad_Manager::render_ad_slot( 'sidebar_ad' );
			}
			?>
		</aside>

	</div>

</div>

<?php get_footer(); ?>
