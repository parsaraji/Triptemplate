<?php
/**
 * Single Video Page with Lazy Loaded Aparat Embed iframe
 *
 * @package Premium_Persian_Tourism
 */

get_header();

while ( have_posts() ) :
	the_post();

	$aparat_id = get_post_meta( get_the_ID(), '_ppt_aparat_id', true );
	$duration  = get_post_meta( get_the_ID(), '_ppt_video_duration', true );
	?>

	<div class="container" style="margin-top:40px; margin-bottom:50px;">
		<div class="layout-with-sidebar">
			<main class="site-main card-box" style="line-height:1.9;">

				<div style="text-align:center; margin-bottom:30px;">
					<span class="card-badge" style="position:static; display:inline-block; margin-bottom:10px; background-color:#38A169;">🎬 مستند تصویری سفر</span>
					<h1 style="font-size:26px; color:#1A202C; margin:10px 0;"><?php the_title(); ?></h1>
					<?php if ( ! empty( $duration ) ) : ?>
						<p style="color:#718096; font-size:14px; margin:0;">مدت زمان مستند: <?php echo esc_html( $duration ); ?></p>
					<?php endif; ?>
				</div>

				<!-- Integrated Lazy-loaded Video Embed block -->
				<?php if ( ! empty( $aparat_id ) ) : ?>
					<div class="ppt-aparat-lazy-embed" data-aparat-id="<?php echo esc_attr( $aparat_id ); ?>" style="margin-bottom:30px; box-shadow: 0 4px 15px rgba(0,0,0,0.15);">
						<?php if ( has_post_thumbnail() ) : ?>
							<?php the_post_thumbnail( 'full', array( 'alt' => get_the_title() ) ); ?>
						<?php else : ?>
							<!-- Temporary mock image with CSS fallback background -->
							<div style="background-color:#1A202C; width:100%; height:100%; display:flex; justify-content:center; align-items:center;"></div>
						<?php endif; ?>
						<div class="aparat-play-button">&#9658;</div>
					</div>
					<p class="description text-center" style="font-size:12px; color:#718096; margin-top:-15px; margin-bottom:25px;">برای مشاهده ویدیو، روی آیکون پخش کلیک کنید تا بدون اتلاف حجم صفحه لود شود.</p>
				<?php else : ?>
					<div class="card-box text-center" style="background:#FFF5F5; border-color:#FED7D7; color:#C53030; padding:15px; margin-bottom:20px;">
						شناسه ویدیو آپارات برای این مستند یافت نشد.
					</div>
				<?php endif; ?>

				<div class="entry-content text-justify" style="margin-top:30px; border-top:1px solid #EDF2F7; padding-top:20px;">
					<h3 style="color:#2D3748; margin-bottom:10px;">خلاصه مستند و روایت سفر</h3>
					<?php the_content(); ?>
				</div>

			</main>

			<!-- Sidebar -->
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
