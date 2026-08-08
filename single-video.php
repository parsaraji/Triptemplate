<?php
/**
 * Single Video Page with Exquisite, Highly Professional Cinema Experience
 * Includes dark theatre-mode background, customized metadata counters, and related-videos query list.
 *
 * @package Premium_Persian_Tourism
 */

get_header();

while ( have_posts() ) :
	the_post();

	$aparat_id = get_post_meta( get_the_ID(), '_ppt_aparat_id', true );
	$duration  = get_post_meta( get_the_ID(), '_ppt_video_duration', true );
	?>

	<!-- Premium Theatre/Cinema dark hero backdrop container -->
	<section class="theatre-mode-wrapper container" style="margin-top: 40px;">
		<div style="max-width:960px; margin:0 auto;">

			<h1 class="theatre-title">🎥 <?php the_title(); ?></h1>
			<p class="theatre-meta">
				<span>🎬 مستند صوتی و تصویری رادیو سفر</span> |
				<span>⏱️ مدت زمان نمایش: <?php echo esc_html( $duration ? $duration : '۰۵:۰۰' ); ?></span> |
				<span>📅 تاریخ انتشار: <?php echo esc_html( get_the_date() ); ?></span>
			</p>

			<!-- Premium Video Container with Lazy Loading embedding -->
			<div class="theatre-player-container">
				<?php if ( ! empty( $aparat_id ) ) : ?>
					<div class="ppt-aparat-lazy-embed" data-aparat-id="<?php echo esc_attr( $aparat_id ); ?>">
						<?php if ( has_post_thumbnail() ) : ?>
							<?php the_post_thumbnail( 'full', array( 'alt' => get_the_title(), 'style' => 'width:100%; height:100%; object-fit:cover;' ) ); ?>
						<?php else : ?>
							<div style="background-color:#1E293B; aspect-ratio:16/9; display:flex; justify-content:center; align-items:center;">
								<span style="color:#64A3B8; font-size:15px; font-weight:bold;">رادیو سفر - برای بارگذاری مستند کلیک کنید</span>
							</div>
						<?php endif; ?>
						<div class="aparat-play-button">&#9658;</div>
					</div>
				<?php else : ?>
					<div class="card-box text-center" style="background:#FFF5F5; border-color:#FED7D7; color:#C53030; padding:30px; margin:0;">
						❌ متأسفانه شناسه ویدیوی معتبری برای پخش آنلاین ثبت نشده است.
					</div>
				<?php endif; ?>
			</div>

			<p class="description text-center" style="font-size:12px; color:#94A3B8; margin-top:15px; margin-bottom:0;">
				برای بارگذاری تنبل و تماشای آنلاین مستند، بر روی آیکون پخش قرمز رنگ کلیک کنید.
			</p>

		</div>
	</section>

	<!-- Main Details Column -->
	<div class="container">
		<div class="layout-with-sidebar">
			<main class="site-main card-box" style="line-height:1.9;">

				<h2 class="section-title" style="color:#1A202C; border-bottom:2px solid #EDF2F7; padding-bottom:8px; margin-bottom:15px;">داستان مستند و جزییات سفرنامه</h2>
				<div class="entry-content text-justify" style="font-size:15px; color:#4A5568;">
					<?php the_content(); ?>
				</div>

				<hr style="margin:40px 0; border:0; border-top:1px solid #EDF2F7;">

				<!-- Related Video Custom query loops -->
				<div class="related-videos-section">
					<h3 style="color:#2B6CB0; margin-bottom:20px;">🎬 سایر مستندهای پیشنهادی رادیو سفر:</h3>

					<?php
					$related_query = new WP_Query( array(
						'post_type'      => 'video',
						'posts_per_page' => 2,
						'post__not_in'   => array( get_the_ID() ),
						'post_status'    => 'publish',
					) );

					if ( $related_query->have_posts() ) :
						?>
						<div class="editorial-grid" style="grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap:20px;">
							<?php
							while ( $related_query->have_posts() ) :
								$related_query->the_post();
								get_template_part( 'template-parts/content', 'card' );
							endwhile;
							wp_reset_postdata();
							?>
						</div>
						<?php
					else :
						echo '<p class="text-muted">مستند پیشنهادی دیگری یافت نشد.</p>';
					endif;
					?>
				</div>

				<!-- Comments Area -->
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
						<h3 class="widget-title">📻 رادیو سفر صوتی</h3>
						<p style="font-size:14px; color:#4A5568;">اگر تمایل دارید تجربه‌های صوتی سفرنامه‌ها را بشنوید، اپیزودهای صوتی پادکست‌های ما را بررسی نمایید.</p>
						<a href="<?php echo esc_url( get_post_type_archive_link( 'podcast' ) ); ?>" class="button button-primary" style="display:block; text-align:center;">مشاهده اپیزودها</a>
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
