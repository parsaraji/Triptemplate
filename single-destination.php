<?php
/**
 * Single Destination Editorial Travel Guide Template
 *
 * @package Premium_Persian_Tourism
 */

get_header();

while ( have_posts() ) :
	the_post();

	// Retrieve Custom Metadata
	$best_time = get_post_meta( get_the_ID(), '_ppt_best_time', true );
	$weather   = get_post_meta( get_the_ID(), '_ppt_weather', true );
	$cost_lvl  = get_post_meta( get_the_ID(), '_ppt_cost_level', true );
	$lat       = get_post_meta( get_the_ID(), '_ppt_lat', true );
	$lng       = get_post_meta( get_the_ID(), '_ppt_lng', true );

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
				<h1>راهنمای سفر به <?php the_title(); ?></h1>
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
				<div class="toc-box">
					<h4>فهرست عناوین راهنما</h4>
					<ul>
						<li><a href="#overview" class="active">۱. معرفی و توصیف اجمالی</a></li>
						<li><a href="#attractions">۲. جاذبه‌های دیدنی و گردشگری</a></li>
						<li><a href="#map-section">۳. موقعیت جغرافیابی و مسیریابی</a></li>
						<li><a href="#faq">۴. سوالات متداول مسافران</a></li>
					</ul>
				</div>

				<div id="overview" class="section-content-box" style="margin-bottom:35px;">
					<h2 class="section-title">معرفی و توصیف اجمالی</h2>
					<div class="entry-content text-justify">
						<?php the_content(); ?>
					</div>
				</div>

				<hr style="margin:30px 0; border:0; border-top:1px solid #EDF2F7;">

				<!-- Associated Attractions Section -->
				<div id="attractions" class="section-content-box" style="margin-bottom:35px;">
					<h2 class="section-title">جاذبه‌های تفریحی و دیدنی نزدیک</h2>
					<p class="description">مهم‌ترین مکان‌های دیدنی، تاریخی و طبیعی که در طول سفر به این مقصد باید تجربه کنید:</p>

					<?php
					// Query attractions associated with this destination's province.
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

				<!-- Interactive OSM Map -->
				<div id="map-section" class="section-content-box" style="margin-bottom:35px;">
					<?php
					if ( ! empty( $lat ) && ! empty( $lng ) ) {
						PPT_Map_System::render_map_container( $lat, $lng, get_the_title() );
					}
					?>
				</div>

				<!-- Shared FAQ accordion -->
				<div id="faq" class="section-content-boxFAQ">
					<h3 class="section-title">سوالات متداول کاربران درباره سفر به <?php the_title(); ?></h3>
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
