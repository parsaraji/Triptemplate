<?php
/**
 * Single Destination Editorial Travel Guide Template
 * Enriched with Climate Guides, Localized travel tips, FAQ Page accordion, Leaflet Interactive maps,
 * and comprehensive travel parameters (Souvenirs, Foods, Transport, Accommodation).
 *
 * @package Premium_Persian_Tourism
 */

get_header();

while ( have_posts() ) :
	the_post();

	// Retrieve Custom Metadata
	$best_time     = get_post_meta( get_the_ID(), '_ppt_best_time', true );
	$weather       = get_post_meta( get_the_ID(), '_ppt_weather', true );
	$cost_lvl      = get_post_meta( get_the_ID(), '_ppt_cost_level', true );
	$lat           = get_post_meta( get_the_ID(), '_ppt_lat', true );
	$lng           = get_post_meta( get_the_ID(), '_ppt_lng', true );

	// Advanced Enriched fields
	$souvenirs     = get_post_meta( get_the_ID(), '_ppt_souvenirs', true );
	$local_foods   = get_post_meta( get_the_ID(), '_ppt_local_foods', true );
	$transport     = get_post_meta( get_the_ID(), '_ppt_transport', true );
	$accommodation = get_post_meta( get_the_ID(), '_ppt_accommodation', true );
	$travel_tips   = get_post_meta( get_the_ID(), '_ppt_travel_tips', true );

	$cost_lvl_lbl = 'متوسط';
	if ( 'low' === $cost_lvl ) {
		$cost_lvl_lbl = 'اقتصادی و ارزان';
	} elseif ( 'high' === $cost_lvl ) {
		$cost_lvl_lbl = 'لوکس و گران';
	}
	?>

	<div class="container" style="margin-top:40px; margin-bottom:50px;">

		<!-- 1. Editorial Destination Banner -->
		<div class="destination-hero">
			<div class="destination-hero-bg" style="background-image: url('<?php echo has_post_thumbnail() ? esc_url( get_the_post_thumbnail_url( get_the_ID(), 'full' ) ) : ''; ?>');"></div>
			<div class="destination-hero-overlay"></div>
			<div class="destination-hero-content">
				<?php
				$provinces = get_the_terms( get_the_ID(), 'province' );
				if ( ! empty( $provinces ) && ! is_wp_error( $provinces ) ) {
					echo '<span class="card-badge" style="position:static; display:inline-block; margin-bottom:10px;">' . esc_html( $provinces[0]->name ) . '</span>';
				}
				?>
				<h1>راهنمای جامع و سفرنامه <?php the_title(); ?></h1>
			</div>
		</div>

		<!-- 2. Destination Highlights Row (Climate, Best Season, Pricing level) -->
		<div class="destination-highlights-grid">
			<div class="highlight-item">
				<span style="font-size:24px; display:block; margin-bottom:5px;">⏱️</span>
				<strong>بهترین زمان سفر:</strong>
				<p style="margin:5px 0 0; font-size:14px; color:#4A5568;"><?php echo esc_html( $best_time ? $best_time : 'بهار و تابستان' ); ?></p>
			</div>
			<div class="highlight-item">
				<span style="font-size:24px; display:block; margin-bottom:5px;">🌤️</span>
				<strong>وضعیت آب و هوا:</strong>
				<p style="margin:5px 0 0; font-size:14px; color:#4A5568;"><?php echo esc_html( $weather ? $weather : 'معتدل کوهستانی' ); ?></p>
			</div>
			<div class="highlight-item">
				<span style="font-size:24px; display:block; margin-bottom:5px;">💳</span>
				<strong>حدود سطح هزینه:</strong>
				<p style="margin:5px 0 0; font-size:14px; color:#4A5568;"><?php echo esc_html( $cost_lvl_lbl ); ?></p>
			</div>
		</div>

		<!-- Render Content Ad Top Slot if configured -->
		<?php
		if ( class_exists( 'PPT_Ad_Manager' ) ) {
			PPT_Ad_Manager::render_ad_slot( 'content_ad_top' );
		}
		?>

		<!-- 3. Layout with Content and Sidebar -->
		<div class="layout-with-sidebar">
			<!-- Main editorial content -->
			<main class="site-main card-box" style="line-height:1.9;">

				<!-- Table of Contents -->
				<div class="toc-box">
					<h4>فهرست عناوین راهنما</h4>
					<ul>
						<li><a href="#overview" class="active">۱. معرفی و توصیف اجمالی</a></li>
						<?php if ( ! empty( $souvenirs ) ) : ?>
							<li><a href="#souvenirs">۲. صنایع دستی و سوغات محلی</a></li>
						<?php endif; ?>
						<?php if ( ! empty( $local_foods ) ) : ?>
							<li><a href="#foods">۳. غذاهای بومی و طعم‌های ماندگار</a></li>
						<?php endif; ?>
						<?php if ( ! empty( $transport ) ) : ?>
							<li><a href="#transport">۴. راه‌های دسترسی و ترابری</a></li>
						<?php endif; ?>
						<?php if ( ! empty( $accommodation ) ) : ?>
							<li><a href="#accommodation">۵. بوم‌گردی‌ها و گزینه‌های اقامت</a></li>
						<?php endif; ?>
						<li><a href="#tips">۶. توصیه‌ها و ملزومات کلیدی سفر</a></li>
						<li><a href="#attractions">۷. جاذبه‌های تفریحی نزدیک</a></li>
						<li><a href="#map-section">۸. موقعیت روی نقشه تعاملی</a></li>
						<li><a href="#faq">۹. سوالات متداول مسافران</a></li>
					</ul>
				</div>

				<!-- Section 1: Overview -->
				<div id="overview" class="section-content-box" style="margin-bottom:35px;">
					<h2 class="section-title" style="color:#2C5282; border-bottom:2px solid #EDF2F7; padding-bottom:8px; margin-bottom:15px;">معرفی و توصیف اجمالی</h2>
					<div class="entry-content text-justify">
						<?php the_content(); ?>
					</div>
				</div>

				<!-- Section 2: Souvenirs & Crafts -->
				<?php if ( ! empty( $souvenirs ) ) : ?>
					<div id="souvenirs" class="section-content-box" style="margin-bottom:35px; border-top:1px solid #EDF2F7; padding-top:25px;">
						<h2 class="section-title" style="color:#2C5282; border-bottom:2px solid #EDF2F7; padding-bottom:8px; margin-bottom:15px; display:flex; align-items:center; gap:8px;">
							<span>🎁</span> صنایع دستی و سوغات محلی
						</h2>
						<p style="font-size:15px; color:#4A5568; white-space: pre-line;" class="text-justify"><?php echo esc_html( $souvenirs ); ?></p>
					</div>
				<?php endif; ?>

				<!-- Section 3: Local Foods -->
				<?php if ( ! empty( $local_foods ) ) : ?>
					<div id="foods" class="section-content-box" style="margin-bottom:35px; border-top:1px solid #EDF2F7; padding-top:25px;">
						<h2 class="section-title" style="color:#2C5282; border-bottom:2px solid #EDF2F7; padding-bottom:8px; margin-bottom:15px; display:flex; align-items:center; gap:8px;">
							<span>🍲</span> غذاهای بومی و طعم‌های ماندگار
						</h2>
						<p style="font-size:15px; color:#4A5568; white-space: pre-line;" class="text-justify"><?php echo esc_html( $local_foods ); ?></p>
					</div>
				<?php endif; ?>

				<!-- Section 4: Transport -->
				<?php if ( ! empty( $transport ) ) : ?>
					<div id="transport" class="section-content-box" style="margin-bottom:35px; border-top:1px solid #EDF2F7; padding-top:25px;">
						<h2 class="section-title" style="color:#2C5282; border-bottom:2px solid #EDF2F7; padding-bottom:8px; margin-bottom:15px; display:flex; align-items:center; gap:8px;">
							<span>🚗</span> راه‌های دسترسی و ترابری
						</h2>
						<p style="font-size:15px; color:#4A5568; white-space: pre-line;" class="text-justify"><?php echo esc_html( $transport ); ?></p>
					</div>
				<?php endif; ?>

				<!-- Section 5: Accommodation -->
				<?php if ( ! empty( $accommodation ) ) : ?>
					<div id="accommodation" class="section-content-box" style="margin-bottom:35px; border-top:1px solid #EDF2F7; padding-top:25px;">
						<h2 class="section-title" style="color:#2C5282; border-bottom:2px solid #EDF2F7; padding-bottom:8px; margin-bottom:15px; display:flex; align-items:center; gap:8px;">
							<span>🏡</span> بوم‌گردی‌ها و گزینه‌های اقامت
						</h2>
						<p style="font-size:15px; color:#4A5568; white-space: pre-line;" class="text-justify"><?php echo esc_html( $accommodation ); ?></p>
					</div>
				<?php endif; ?>

				<hr style="margin:30px 0; border:0; border-top:1px solid #EDF2F7;">

				<!-- Section 6: Localized Travel Tips -->
				<div id="tips" class="section-content-box" style="margin-bottom:35px; background-color: #FFFDF5; border:1px solid #FEFCBF; padding:20px; border-radius:8px;">
					<h3 style="color:#B7791F; margin-top:0; display:flex; align-items:center; gap:8px;">
						<span>💡</span> نکات کلیدی و ملزومات سفر رادیو سفر
					</h3>
					<?php if ( ! empty( $travel_tips ) ) : ?>
						<p style="font-size:14px; line-height:2; color:#744210; margin:0; white-space: pre-line;" class="text-justify"><?php echo esc_html( $travel_tips ); ?></p>
					<?php else : ?>
						<ul style="padding-right:20px; margin-bottom:0; font-size:14px; line-height:2; color:#744210;">
							<li>پیش از حرکت، حتما از رزرو بودن اقامتگاه بوم‌گردی خود اطمینان حاصل فرمایید.</li>
							<li>در مناطق کوهستانی، حتماً تجهیزات کامل و زنجیر چرخ به همراه داشته باشید.</li>
							<li>پاسداشت فرهنگ بومی، استفاده از لیدرهای محلی و خرید صنایع دستی بومی به اقتصاد پایدار منطقه کمک بسزایی می‌کند.</li>
						</ul>
					<?php endif; ?>
				</div>

				<hr style="margin:30px 0; border:0; border-top:1px solid #EDF2F7;">

				<!-- Section 7: Associated Attractions -->
				<div id="attractions" class="section-content-box" style="margin-bottom:35px;">
					<h2 class="section-title" style="color:#2C5282; border-bottom:2px solid #EDF2F7; padding-bottom:8px; margin-bottom:15px;">جاذبه‌های تفریحی و دیدنی نزدیک</h2>
					<p class="description">مهم‌ترین مکان‌های دیدنی، تاریخی و طبیعی نزدیک که در سفر به این مقصد باید تجربه کنید:</p>

					<?php
					if ( ! empty( $provinces ) && ! is_wp_error( $provinces ) ) {
						$attr_query = new WP_Query( array(
							'post_type'      => 'attraction',
							'posts_per_page' => 3,
							'tax_query'      => array(
								array(
									'taxonomy' => 'province',
									'field'    => 'term_id',
									'terms'    => $provinces[0]->term_id,
								),
							),
						) );

						if ( $attr_query->have_posts() ) :
							?>
							<div class="editorial-grid" style="grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 20px;">
								<?php
								while ( $attr_query->have_posts() ) :
									$attr_query->the_post();
									?>
									<div class="ppt-card">
										<div class="card-img-wrapper" style="aspect-ratio:16/9;">
											<a href="<?php the_permalink(); ?>">
												<?php the_post_thumbnail( 'ppt-card-thumb' ); ?>
											</a>
										</div>
										<div class="card-content" style="padding:12px;">
											<h4 style="font-size:14px; margin:0;"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
										</div>
									</div>
									<?php
								endwhile;
								wp_reset_postdata();
								?>
							</div>
							<?php
						else :
							echo '<p class="text-muted">جاذبه مرتبطی برای این منطقه ثبت نشده است.</p>';
						endif;
					}
					?>
				</div>

				<hr style="margin:30px 0; border:0; border-top:1px solid #EDF2F7;">

				<!-- Section 8: Interactive OSM Map (Toggled by CPT Settings) -->
				<?php
				$brand_settings = get_option( 'ppt_brand_settings', array() );
				$dest_show_map  = isset( $brand_settings['cpt_dest_show_map'] ) ? $brand_settings['cpt_dest_show_map'] : '1';
				if ( '1' === $dest_show_map ) :
				?>
					<div id="map-section" class="section-content-box" style="margin-bottom:35px;">
						<?php
						if ( ! empty( $lat ) && ! empty( $lng ) ) {
							PPT_Map_System::render_map_container( $lat, $lng, get_the_title() );
						}
						?>
					</div>
				<?php endif; ?>

				<!-- Section 9: Shared FAQ accordion -->
				<div id="faq" class="section-content-box" style="margin-top:30px;">
					<h3 class="section-title" style="color:#2C5282; border-bottom:2px solid #EDF2F7; padding-bottom:8px; margin-bottom:15px;">سوالات متداول کاربران درباره سفر به <?php the_title(); ?></h3>
					<?php
					$faqs = get_post_meta( get_the_ID(), '_ppt_faqs', true );
					if ( ! empty( $faqs ) && is_array( $faqs ) ) :
						foreach ( $faqs as $faq ) :
							?>
							<div class="faq-item" style="border: 1px solid #E2E8F0; border-radius: 8px; margin-bottom: 12px; overflow: hidden;">
								<details style="padding: 15px; cursor: pointer;">
									<summary style="font-weight: bold; font-size:15px; color:#2D3748; outline:none;"><?php echo esc_html( $faq['q'] ); ?></summary>
									<p style="margin-top: 10px; margin-bottom: 0; color: #4A5568; line-height: 1.8; font-size:14px;"><?php echo esc_html( $faq['a'] ); ?></p>
								</details>
							</div>
							<?php
						endforeach;
					else :
						echo '<p class="text-muted">سوالی برای این مقصد افزوده نشده است.</p>';
					endif;
					?>
				</div>

				<!-- Section 10: Comments Area -->
				<div class="comments-section-wrapper" style="margin-top: 50px; border-top: 2px solid #EDF2F7; padding-top: 30px;">
					<?php
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;
					?>
				</div>

			</main>

			<!-- Right Sidebar Area -->
			<aside class="sidebar-right">
				<?php if ( is_active_sidebar( 'main-sidebar' ) ) : ?>
					<?php dynamic_sidebar( 'main-sidebar' ); ?>
				<?php else : ?>
					<!-- Default Fallback Widget -->
					<div class="widget card-box">
						<h3 class="widget-title">پادکست سفر صوتی</h3>
						<p style="font-size:14px;">همسفر رادیو صوتی شوید و شگفتی‌های ایران را متفاوت بشنوید.</p>
						<a href="<?php echo esc_url( get_post_type_archive_link( 'podcast' ) ); ?>" class="button button-primary" style="display:block; text-align:center;">شنیدن رادیو سفر</a>
					</div>
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
