<?php
/**
 * Single Itinerary Detail Page (برنامه‌ریزی دقیق روز به روز سفر)
 * Renders structured day-by-day itineraries, difficulty gauges, duration highlights,
 * and timelines with custom progress indicators.
 *
 * @package Premium_Persian_Tourism
 */

get_header();

while ( have_posts() ) :
	the_post();

	// Retrieve Custom Metadata
	$duration   = get_post_meta( get_the_ID(), '_ppt_itinerary_duration', true );
	$difficulty = get_post_meta( get_the_ID(), '_ppt_itinerary_difficulty', true );
	$start_pt   = get_post_meta( get_the_ID(), '_ppt_itinerary_start_point', true );
	$season     = get_post_meta( get_the_ID(), '_ppt_itinerary_season', true );
	$days_plan  = get_post_meta( get_the_ID(), '_ppt_itinerary_days', true );

	// Human Readable Difficulty Labels
	$difficulty_label = 'متوسط';
	$difficulty_color = '#D69E2E'; // Yellow
	if ( 'easy' === $difficulty ) {
		$difficulty_label = 'آسان (مناسب برای خانواده)';
		$difficulty_color = '#38A169'; // Green
	} elseif ( 'hard' === $difficulty ) {
		$difficulty_label = 'سخت (مناسب برای کوهنوردان و حرفه‌ای‌ها)';
		$difficulty_color = '#E53E3E'; // Red
	}
	?>

	<div class="container" style="margin-top: 40px; margin-bottom: 50px; font-family: 'Shabnam', sans-serif; direction: rtl; text-align: right;">

		<!-- 1. Header Hero Title Block -->
		<header class="itinerary-header" style="margin-bottom: 35px; background: linear-gradient(135deg, #2D3748 0%, #1A202C 100%); padding: 45px 30px; border-radius: 16px; color: #FFF; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
			<div style="display: flex; align-items: center; gap: 8px; margin-bottom: 15px;">
				<span style="background: #3182CE; padding: 4px 12px; border-radius: 20px; font-size: 13px; font-weight: bold; color: #FFF;">📅 برنامه سفر تفصیلی</span>
				<?php
				$provinces = get_the_terms( get_the_ID(), 'province' );
				if ( ! empty( $provinces ) && ! is_wp_error( $provinces ) ) {
					echo '<span style="background: #E2E8F0; padding: 4px 12px; border-radius: 20px; font-size: 13px; font-weight: bold; color: #2D3748;">' . esc_html( $provinces[0]->name ) . '</span>';
				}
				?>
			</div>
			<h1 style="color: #FFF; font-size: 28px; font-weight: 800; margin: 0 0 10px 0;"><?php the_title(); ?></h1>
			<p style="color: #CBD5E0; font-size: 14px; margin: 0;">با بررسی و نقشه گام‌به‌گام ارائه شده توسط هیئت تحریریه رادیو سفر</p>
		</header>

		<!-- 2. Itinerary Spec Highlights Card -->
		<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin-bottom: 35px;">
			<div style="background: #FFF; border: 1px solid #E2E8F0; border-radius: 12px; padding: 15px; text-align: center;">
				<span style="font-size: 24px; display: block; margin-bottom: 5px;">⏱️</span>
				<strong style="font-size: 12px; color: #718096; display: block; margin-bottom: 5px;">مدت زمان برنامه:</strong>
				<span style="font-size: 15px; font-weight: bold; color: #2D3748;"><?php echo esc_html( $duration ? $duration : 'ثبت نشده' ); ?></span>
			</div>
			<div style="background: #FFF; border: 1px solid #E2E8F0; border-radius: 12px; padding: 15px; text-align: center;">
				<span style="font-size: 24px; display: block; margin-bottom: 5px;">⛰️</span>
				<strong style="font-size: 12px; color: #718096; display: block; margin-bottom: 5px;">درجه سختی مسیر:</strong>
				<span style="font-size: 15px; font-weight: bold; color: <?php echo esc_attr( $difficulty_color ); ?>;"><?php echo esc_html( $difficulty_label ); ?></span>
			</div>
			<div style="background: #FFF; border: 1px solid #E2E8F0; border-radius: 12px; padding: 15px; text-align: center;">
				<span style="font-size: 24px; display: block; margin-bottom: 5px;">📍</span>
				<strong style="font-size: 12px; color: #718096; display: block; margin-bottom: 5px;">مبداء حرکت:</strong>
				<span style="font-size: 15px; font-weight: bold; color: #2D3748;"><?php echo esc_html( $start_pt ? $start_pt : 'ثبت نشده' ); ?></span>
			</div>
			<div style="background: #FFF; border: 1px solid #E2E8F0; border-radius: 12px; padding: 15px; text-align: center;">
				<span style="font-size: 24px; display: block; margin-bottom: 5px;">🍂</span>
				<strong style="font-size: 12px; color: #718096; display: block; margin-bottom: 5px;">فصل پیشنهادی:</strong>
				<span style="font-size: 15px; font-weight: bold; color: #2D3748;"><?php echo esc_html( $season ? $season : 'همه فصول' ); ?></span>
			</div>
		</div>

		<!-- 3. Layout with Main Timeline Content and Ads Sidebar -->
		<div class="layout-with-sidebar" style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">

			<main class="site-main card-box" style="line-height: 1.9; background: #FFF; border: 1px solid #E2E8F0; border-radius: 12px; padding: 25px;">

				<!-- Description overview -->
				<div style="margin-bottom: 40px;">
					<h2 style="font-size: 20px; color: #2B6CB0; border-bottom: 2px solid #EDF2F7; padding-bottom: 10px; margin-bottom: 15px;">🔍 معرفی و ملزومات برنامه سفر</h2>
					<div class="entry-content text-justify">
						<?php the_content(); ?>
					</div>
				</div>

				<!-- Day-by-Day Timeline -->
				<?php if ( ! empty( $days_plan ) && is_array( $days_plan ) ) : ?>
					<div style="margin-top: 40px; margin-bottom: 40px;">
						<h2 style="font-size: 20px; color: #2B6CB0; border-bottom: 2px solid #EDF2F7; padding-bottom: 10px; margin-bottom: 30px;">📅 نقشه زمانی روز شمار سفر (Day-by-Day Journey Timeline)</h2>

						<!-- Vertical Progress Line Roadmap -->
						<div style="position: relative; border-right: 3px solid #E2E8F0; margin-right: 20px; padding-right: 25px; display: flex; flex-direction: column; gap: 30px;">
							<?php foreach ( $days_plan as $index => $day ) :
								if ( empty( $day['title'] ) ) continue;
								?>
								<div style="position: relative;">

									<!-- Pulse Timeline Circle Dot -->
									<div style="position: absolute; right: -33px; top: 2px; width: 14px; height: 14px; border-radius: 50%; background: #3182CE; border: 3px solid #FFF; box-shadow: 0 0 0 3px #90CDF4;"></div>

									<h3 style="font-size: 17px; font-weight: bold; color: #2D3748; margin: 0 0 10px 0; display: flex; align-items: center; gap: 10px;">
										<span style="background: #EBF8FF; color: #2B6CB0; width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: bold;">
											<?php echo esc_html( $index + 1 ); ?>
										</span>
										<?php echo esc_html( $day['title'] ); ?>
									</h3>

									<p style="font-size: 14.5px; color: #4A5568; margin: 0; line-height: 1.8; text-align: justify; white-space: pre-line;">
										<?php echo esc_html( $day['desc'] ); ?>
									</p>
								</div>
							<?php endforeach; ?>
						</div>

					</div>
				<?php endif; ?>

				<!-- Comments Section -->
				<div class="comments-section-wrapper" style="margin-top: 50px; border-top: 2px solid #EDF2F7; padding-top: 30px;">
					<?php
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;
					?>
				</div>

			</main>

			<!-- Right Sidebar area -->
			<aside class="sidebar-right">

				<!-- Sidebar Widget info -->
				<div class="widget card-box" style="background: #F7FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 20px; margin-bottom: 25px;">
					<h3 class="widget-title" style="color: #2D3748; font-size: 16px; font-weight: bold; margin-bottom: 12px; border-bottom: 2px solid #E2E8F0; padding-bottom: 8px;">🎒 وسایل و تجهیزات ضروری</h3>
					<ul style="list-style-type: none; padding: 0; margin: 0; font-size: 13px; line-height: 2.2; color: #4A5568;">
						<li>✓ کفش مناسب پیاده‌روی و کوهپیمایی</li>
						<li>✓ کوله‌پشتی سبک یک‌روزه</li>
						<li>✓ بطری آب شخصی و تنقلات انرژی‌زا</li>
						<li>✓ کرم ضدآفتاب و عینک آفتابی</li>
						<li>✓ نقشه آفلاین یا GPS فعال مسیر</li>
					</ul>
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

	<?php
endwhile;

get_footer();
