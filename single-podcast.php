<?php
/**
 * Single Podcast Page with custom Audio Player
 *
 * @package Premium_Persian_Tourism
 */

get_header();

while ( have_posts() ) :
	the_post();

	$audio_url = get_post_meta( get_the_ID(), '_ppt_audio_url', true );
	$duration  = get_post_meta( get_the_ID(), '_ppt_podcast_duration', true );
	$host      = get_post_meta( get_the_ID(), '_ppt_podcast_host', true );
	?>

	<div class="container" style="margin-top:40px; margin-bottom:50px;">
		<div class="layout-with-sidebar">
			<main class="site-main card-box" style="line-height:1.9;">

				<div style="text-align:center; margin-bottom:30px;">
					<span class="card-badge" style="position:static; display:inline-block; margin-bottom:10px; background-color:#E53E3E;">🎙️ اپیزود رادیو سفر</span>
					<h1 style="font-size:26px; color:#1A202C; margin:10px 0;"><?php the_title(); ?></h1>
					<p style="color:#718096; font-size:14px; margin:0;">میزبان پادکست: <?php echo esc_html( $host ? $host : 'رادیو سفر' ); ?> | زمان تقریبی: <?php echo esc_html( $duration ); ?></p>
				</div>

				<?php if ( has_post_thumbnail() ) : ?>
					<div style="border-radius:12px; overflow:hidden; aspect-ratio:16/9; margin-bottom:30px; max-width:600px; margin-left:auto; margin-right:auto;">
						<?php the_post_thumbnail( 'full', array( 'style' => 'width:100%; height:100%; object-fit:cover;' ) ); ?>
					</div>
				<?php endif; ?>

				<!-- Integrated Accessible Premium Audio Player component -->
				<?php if ( ! empty( $audio_url ) ) : ?>
					<div class="ppt-player-box">
						<button class="audio-control-btn" data-audio-url="<?php echo esc_url( $audio_url ); ?>" aria-label="پخش صوتی">&#9654;</button>
						<div class="audio-progress-bar">
							<div class="audio-progress"></div>
						</div>
						<div class="audio-time">۰۰:۰۰</div>
					</div>
					<p class="description text-center" style="font-size:12px; color:#718096;">بر روی دکمه پخش صمیمانه کلیک کنید تا رادیو سفر لود شود.</p>
				<?php else : ?>
					<div class="card-box text-center" style="background:#FFF5F5; border-color:#FED7D7; color:#C53030; padding:15px; margin-bottom:20px;">
						فایل صوتی برای پخش صوتی ثبت نشده است.
					</div>
				<?php endif; ?>

				<div class="entry-content text-justify" style="margin-top:30px; border-top:1px solid #EDF2F7; padding-top:20px;">
					<h3 style="color:#2D3748; margin-bottom:10px;">توضیحات و خلاصه متنی این اپیزود</h3>
					<?php the_content(); ?>
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

			<!-- Sidebar right -->
			<aside class="sidebar-right">
				<?php if ( is_active_sidebar( 'main-sidebar' ) ) : ?>
					<?php dynamic_sidebar( 'main-sidebar' ); ?>
				<?php endif; ?>
			</aside>
		</div>
	</div>

	<?php
endwhile;

get_footer();
