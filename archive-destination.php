<?php
/**
 * Destinations Archive landing page
 * Now featuring the Premium Advanced AJAX Multi-criteria discovery search & filter panel (Priority 2).
 *
 * @package Premium_Persian_Tourism
 */

get_header(); ?>

<div class="container" style="margin-top: 40px; margin-bottom: 50px;">
	<header class="archive-header" style="background-color:#E2E8F0; padding:40px; border-radius:12px; margin-bottom:40px; text-align:center;">
		<h1 class="archive-title" style="margin-bottom:10px; font-weight:800; color:#1A365D;">اکتشاف همه‌جانبه مقاصد و پادکست‌های رادیو سفر</h1>
		<p class="description" style="max-width:700px; margin:0 auto; font-size:15px; color:#4A5568;">از طریق فیلترها و مرتب‌سازی‌های پیشرفته زیر، به صورت هوشمند و آنی در مقاصد، جاذبه‌ها و فایل‌های صوتی ما به جستجو بپردازید.</p>
	</header>

	<!-- Premium Advanced AJAX Discovery Filter & Search Bar (Priority 2) -->
	<div class="ppt-ajax-filter-row card-box" style="display:flex; flex-wrap:wrap; gap:15px; justify-content:space-between; align-items:center;">

		<!-- Right Side: Filter selections -->
		<div style="display:flex; flex-wrap:wrap; gap:15px; align-items:center;">

			<!-- Province Selector -->
			<div class="ppt-filter-group">
				<label>📍 استان:</label>
				<select name="province" class="ppt-filter-select">
					<option value="">همه استان‌ها</option>
					<?php
					$provinces = get_terms( array( 'taxonomy' => 'province', 'hide_empty' => false ) );
					if ( ! empty( $provinces ) && ! is_wp_error( $provinces ) ) {
						foreach ( $provinces as $prov ) {
							echo '<option value="' . esc_attr( $prov->slug ) . '">' . esc_html( $prov->name ) . '</option>';
						}
					}
					?>
				</select>
			</div>

			<!-- Travel Topic Selector -->
			<div class="ppt-filter-group">
				<label>🏷️ موضوع:</label>
				<select name="topic" class="ppt-filter-select">
					<option value="">همه موضوعات</option>
					<?php
					$topics = get_terms( array( 'taxonomy' => 'travel_topic', 'hide_empty' => false ) );
					if ( ! empty( $topics ) && ! is_wp_error( $topics ) ) {
						foreach ( $topics as $topic ) {
							echo '<option value="' . esc_attr( $topic->slug ) . '">' . esc_html( $topic->name ) . '</option>';
						}
					}
					?>
				</select>
			</div>

			<!-- Content Type Selector -->
			<div class="ppt-filter-group">
				<label>📂 نوع محتوا:</label>
				<select name="content_type" class="ppt-filter-select">
					<option value="any">همه رسانه‌ها</option>
					<option value="destination" selected>مقاصد سفر</option>
					<option value="attraction">جاذبه‌های دیدنی</option>
					<option value="podcast">رادیو صوتی (پادکست)</option>
					<option value="video">فیلم مستند (ویدیو)</option>
					<option value="guide">راهنمای مکتوب</option>
				</select>
			</div>

			<!-- Sorting Selector -->
			<div class="ppt-filter-group">
				<label>⚙️ مرتب‌سازی:</label>
				<select name="sort_by" class="ppt-filter-select">
					<option value="newest">جدیدترین انتشار</option>
					<option value="popular">محبوب‌ترین (بر اساس نظرات)</option>
					<option value="title">ترتیب الفبایی (الف-ی)</option>
				</select>
			</div>

		</div>

		<!-- Left Side: Live Keyword Input -->
		<div style="min-width:240px; flex-grow:0;">
			<input type="text" name="s" class="ppt-filter-select" style="width:100%;" placeholder="جستجوی آنی کلیدواژه..." />
		</div>

	</div>

	<!-- Results Loop Grid -->
	<?php if ( have_posts() ) : ?>
		<div class="editorial-grid">
			<?php
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/content', 'card' );
			endwhile;
			?>
		</div>

		<?php
		the_posts_navigation( array(
			'prev_text' => 'صفحه قبلی',
			'next_text' => 'صفحه بعدی',
		) );
		?>

	<?php else : ?>
		<div class="card-box text-center">
			<p>هیچ مقصد گردشگری یافت نشد.</p>
		</div>
	<?php endif; ?>
</div>

<?php get_footer(); ?>
