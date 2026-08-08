<?php
/**
 * Single Guide Detail with Tables and FAQs
 *
 * @package Premium_Persian_Tourism
 */

get_header();

while ( have_posts() ) :
	the_post();

	$budget_items = get_post_meta( get_the_ID(), '_ppt_budget_items', true );
	$faqs         = get_post_meta( get_the_ID(), '_ppt_faqs', true );
	?>

	<div class="container" style="margin-top:40px; margin-bottom:50px;">
		<div style="margin-bottom:30px;">
			<h1 style="font-size:30px; margin-bottom:10px; color:#1A202C;">راهنمای جامع: <?php the_title(); ?></h1>
			<div class="meta-date" style="color:#718096; margin-bottom:20px;">ارائه شده توسط تحریریه رادیو سفر</div>
		</div>

		<div class="layout-with-sidebar">
			<main class="site-main card-box" style="line-height:1.9;">
				<!-- Editorial Content -->
				<div class="entry-content text-justify" style="margin-bottom:40px;">
					<?php the_content(); ?>
				</div>

				<!-- Repeatable Budget Table -->
				<?php if ( ! empty( $budget_items ) && is_array( $budget_items ) ) : ?>
					<div style="margin-bottom:40px; border-top:2px solid #EDF2F7; padding-top:30px;">
						<h3 style="color:#2B6CB0; margin-bottom:15px;">📊 برآورد هزینه‌ها و بودجه لازم برای این سفر</h3>
						<table class="ppt-budget-table" style="width:100%; border-collapse:collapse; direction:rtl; text-align:right; margin-bottom:20px;">
							<thead>
								<tr style="background-color:#F7FAFC; border-bottom:2px solid #CBD5E0;">
									<th style="padding:12px; font-weight:bold;">نوع خدمات / آیتم هزینه</th>
									<th style="padding:12px; font-weight:bold; text-align:left;">مبلغ (تومان)</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ( $budget_items as $item ) : ?>
									<tr style="border-bottom:1px solid #E2E8F0;">
										<td style="padding:12px;"><?php echo esc_html( $item['title'] ); ?></td>
										<td style="padding:12px; text-align:left; font-weight:bold; color:#2C5282;"><?php echo esc_html( $item['cost'] ); ?></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				<?php endif; ?>

				<!-- FAQs Page Block -->
				<?php if ( ! empty( $faqs ) && is_array( $faqs ) ) : ?>
					<div style="margin-bottom:40px; border-top:2px solid #EDF2F7; padding-top:30px;">
						<h3 style="color:#2B6CB0; margin-bottom:15px;">❓ پاسخ به سوالات متداول مسافران</h3>
						<?php foreach ( $faqs as $faq ) : ?>
							<div class="faq-item" style="border:1px solid #E2E8F0; border-radius:6px; margin-bottom:10px; padding:15px; background:#F7FAFC;">
								<strong style="display:block; color:#2D3748; margin-bottom:5px;"><?php echo esc_html( $faq['q'] ); ?></strong>
								<p style="margin:0; color:#4A5568; font-size:14px;"><?php echo esc_html( $faq['a'] ); ?></p>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<!-- Comments Area -->
				<div class="comments-section-wrapper" style="margin-top: 50px; border-top: 2px solid #EDF2F7; padding-top: 30px;">
					<?php
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;
					?>
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
