<?php
/**
 * Single Attraction Editorial Detail Page
 * Updated with localized directions, maps, ticket details, and sidebar ads.
 *
 * @package Premium_Persian_Tourism
 */

get_header();

while ( have_posts() ) :
	the_post();

	// Retrieve Metadata
	$address       = get_post_meta( get_the_ID(), '_ppt_address', true );
	$opening_hours = get_post_meta( get_the_ID(), '_ppt_opening_hours', true );
	$ticket_price  = get_post_meta( get_the_ID(), '_ppt_ticket_price', true );
	$lat           = get_post_meta( get_the_ID(), '_ppt_lat', true );
	$lng           = get_post_meta( get_the_ID(), '_ppt_lng', true );
	?>

	<div class="container" style="margin-top:40px; margin-bottom:50px;">

		<!-- 1. Editorial Image and Headline Header -->
		<div style="margin-bottom:30px;">
			<h1 style="font-size:30px; margin-bottom:10px; color:#1A202C;">جاذبه گردشگری: <?php the_title(); ?></h1>
			<div class="meta-date" style="color:#718096; margin-bottom:20px;">آخرین بروزرسانی به همراه جزییات قیمت بلیت: <?php echo esc_html( get_the_modified_date() ); ?></div>

			<?php if ( has_post_thumbnail() ) : ?>
				<div style="border-radius:12px; overflow:hidden; aspect-ratio:21/9; margin-bottom:30px; background-color:#CBD5E0;">
					<?php the_post_thumbnail( 'full', array( 'style' => 'width:100%; height:100%; object-fit:cover;' ) ); ?>
				</div>
			<?php endif; ?>
		</div>

		<!-- Render Content Ad Top Slot if configured -->
		<?php
		if ( class_exists( 'PPT_Ad_Manager' ) ) {
			PPT_Ad_Manager::render_ad_slot( 'content_ad_top' );
		}
		?>

		<!-- 2. Main Page Columns -->
		<div class="layout-with-sidebar">
			<main class="site-main card-box" style="line-height:1.9;">

				<!-- Basic Details Card -->
				<div style="background-color: #F7FAFC; border:1px solid #E2E8F0; border-radius: 8px; padding: 22px; margin-bottom: 25px;">
					<h3 style="margin-top:0; color:#2B6CB0; font-size:16px; border-bottom:1px solid #EDF2F7; padding-bottom:8px; margin-bottom:12px;">📋 جزییات و الزامات ورود جاذبه</h3>
					<ul style="list-style:none; padding:0; margin:0; font-size:14px; line-height:2.2;">
						<li><strong>📍 آدرس دقیق مسیر:</strong> <?php echo esc_html( $address ? $address : 'مشخص نشده' ); ?></li>
						<li><strong>⏱️ ساعات کار و دسترسی:</strong> <?php echo esc_html( $opening_hours ? $opening_hours : '۹:۰۰ الی ۱۷:۰۰' ); ?></li>
						<li><strong>🎟️ بهای بلیت ورودی:</strong> <span style="color:#E53E3E; font-weight:bold;"><?php echo esc_html( $ticket_price ? $ticket_price : 'رایگان یا ثبت نشده' ); ?></span></li>
					</ul>
				</div>

				<div class="entry-content text-justify" style="margin-bottom:35px;">
					<?php the_content(); ?>
				</div>

				<hr style="margin:30px 0; border:0; border-top:1px solid #EDF2F7;">

				<!-- Associated Leaflet Map -->
				<?php
				if ( ! empty( $lat ) && ! empty( $lng ) ) {
					PPT_Map_System::render_map_container( $lat, $lng, get_the_title() );
				}
				?>

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
