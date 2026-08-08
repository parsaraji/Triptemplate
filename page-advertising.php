<?php
/**
 * Template Name: تبلیغات و رزرو جایگاه (Advertising Booking & Card-to-Card)
 * Description: Premium advertising reservation page with prices and native card-to-card checkout instructions.
 *
 * @package Premium_Persian_Tourism
 */

get_header();

$success_message = '';
$error_message   = '';

// Handle Ad Booking Form Submissions securely using standard WordPress Custom Post Types (Security Overhaul)
if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset( $_POST['ppt_booking_submit'] ) ) {
	if ( isset( $_POST['ppt_ad_booking_nonce'] ) && wp_verify_nonce( $_POST['ppt_ad_booking_nonce'], 'ppt_save_ad_booking' ) ) {
		$name  = sanitize_text_field( $_POST['booking_name'] );
		$phone = sanitize_text_field( $_POST['booking_phone'] );
		$slot  = sanitize_text_field( $_POST['booking_slot'] );
		$url   = esc_url_raw( $_POST['booking_url'] );
		$ref   = sanitize_text_field( $_POST['booking_ref'] );

		if ( ! empty( $name ) && ! empty( $phone ) && ! empty( $ref ) ) {
			// Insert as an individual non-public Custom Post of type 'ppt_booking'
			$post_id = wp_insert_post( array(
				'post_title'  => $name,
				'post_status' => 'pending',
				'post_type'   => 'ppt_booking',
			) );

			if ( ! is_wp_error( $post_id ) ) {
				update_post_meta( $post_id, '_ppt_booking_phone', $phone );
				update_post_meta( $post_id, '_ppt_booking_slot', $slot );
				update_post_meta( $post_id, '_ppt_booking_url', $url );
				update_post_meta( $post_id, '_ppt_booking_ref', $ref );

				$success_message = 'درخواست رزرو جایگاه با موفقیت ثبت شد! فیش پرداخت شما پس از تایید مدیریت فعال خواهد شد.';
			} else {
				$error_message = 'خطایی در ثبت درخواست رخ داد. لطفاً مجدداً تلاش نمایید.';
			}
		} else {
			$error_message = 'لطفاً تمامی فیلدهای الزامی را پر نمایید.';
		}
	} else {
		$error_message = 'خطای امنیتی رخ داده است. مجدداً تلاش کنید.';
	}
}
?>

<div class="container" style="margin-top: 40px; margin-bottom: 60px;">

	<!-- Header introduction -->
	<header class="page-header text-center" style="margin-bottom: 50px; background: linear-gradient(135deg, #1A365D 0%, #2A4365 100%); padding: 50px; border-radius: 16px; color:#FFF;">
		<span style="font-size:36px; display:block; margin-bottom:10px;">📈</span>
		<h1 style="color:#FFF; font-size:30px; font-weight:800; margin-bottom:10px;">جذب مخاطب پویا: رزرو جایگاه‌های تبلیغاتی رادیو سفر</h1>
		<p style="color:#E2E8F0; font-size:16px; max-width:700px; margin:0 auto; line-height:1.8;">جایگاه‌های تبلیغاتی پربازدید وب‌سایت گردشگری رادیو سفر را بررسی کنید، تعرفه‌ها را مطالعه فرمایید و اپلیکیشن رزرو خود را به همراه اطلاعات تراکنش بانکی ثبت کنید.</p>
	</header>

	<?php if ( ! empty( $success_message ) ) : ?>
		<div style="background-color: #D4EDDA; color: #155724; border: 1px solid #C3E6CB; padding: 15px; border-radius: 8px; margin-bottom: 30px; font-weight: bold; direction: rtl;">
			✅ <?php echo esc_html( $success_message ); ?>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $error_message ) ) : ?>
		<div style="background-color: #F8D7DA; color: #721C24; border: 1px solid #F5C6CB; padding: 15px; border-radius: 8px; margin-bottom: 30px; font-weight: bold; direction: rtl;">
			❌ <?php echo esc_html( $error_message ); ?>
		</div>
	<?php endif; ?>

	<div class="layout-with-sidebar">

		<!-- Main section: Pricing Table & Booking checkout -->
		<main class="site-main">

			<!-- Slot Pricing Grid -->
			<section class="card-box" style="margin-bottom:40px;">
				<h2 style="color:#2B6CB0; font-size:20px; font-weight:800; border-bottom:1px solid #EDF2F7; padding-bottom:10px; margin-bottom:20px;">💰 تعرفه ماهیانه جایگاه‌های تبلیغاتی (CLS-Free)</h2>

				<table style="width:100%; border-collapse:collapse; text-align:right; font-size:14px;">
					<thead>
						<tr style="background-color:#F7FAFC; border-bottom:2px solid #E2E8F0;">
							<th style="padding:12px; font-weight:bold;">محل جایگاه تبلیغاتی</th>
							<th style="padding:12px; font-weight:bold;">ابعاد (دسکتاپ)</th>
							<th style="padding:12px; font-weight:bold;">ابعاد (موبایل)</th>
							<th style="padding:12px; font-weight:bold; text-align:left;">تعرفه ماهیانه (تومان)</th>
						</tr>
					</thead>
					<tbody>
						<tr style="border-bottom:1px solid #EDF2F7;">
							<td style="padding:12px;"><strong>بنر بالای صفحات (هدر)</strong></td>
							<td style="padding:12px; font-family:monospace;">728 x 90</td>
							<td style="padding:12px; font-family:monospace;">320 x 50</td>
							<td style="padding:12px; text-align:left; font-weight:bold; color:#2C5282;">۲,۵۰۰,۰۰۰</td>
						</tr>
						<tr style="border-bottom:1px solid #EDF2F7;">
							<td style="padding:12px;"><strong>بنر سایدبار کناری تک‌نوشته‌ها</strong></td>
							<td style="padding:12px; font-family:monospace;">300 x 250</td>
							<td style="padding:12px; font-family:monospace;">300 x 250</td>
							<td style="padding:12px; text-align:left; font-weight:bold; color:#2C5282;">۱,۸۰۰,۰۰۰</td>
						</tr>
						<tr style="border-bottom:1px solid #EDF2F7;">
							<td style="padding:12px;"><strong>بنر بالای بدنه متن (محتوا)</strong></td>
							<td style="padding:12px; font-family:monospace;">468 x 60</td>
							<td style="padding:12px; font-family:monospace;">320 x 50</td>
							<td style="padding:12px; text-align:left; font-weight:bold; color:#2C5282;">۱,۵۰۰,۰۰۰</td>
						</tr>
						<tr style="border-bottom:1px solid #EDF2F7;">
							<td style="padding:12px;"><strong>بک‌لینک متنی فالو (Contextual Link)</strong></td>
							<td style="padding:12px;">انکر تکست دلخواه</td>
							<td style="padding:12px;">انکر تکست دلخواه</td>
							<td style="padding:12px; text-align:left; font-weight:bold; color:#2C5282;">۸۰۰,۰۰۰</td>
						</tr>
					</tbody>
				</table>
			</section>

			<!-- Booking Reservation details -->
			<section class="card-box">
				<h2 style="color:#2B6CB0; font-size:20px; font-weight:800; border-bottom:1px solid #EDF2F7; padding-bottom:10px; margin-bottom:20px;">📝 فرم رزرو آنلاین جایگاه تبلیغاتی</h2>

				<form method="post" action="" style="direction:rtl; text-align:right;">

					<?php wp_nonce_field( 'ppt_save_ad_booking', 'ppt_ad_booking_nonce' ); ?>

					<!-- Form inputs -->
					<div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px;">
						<div>
							<label style="display:block; margin-bottom:6px; font-weight:bold;">نام و نام خانوادگی متقاضی:</label>
							<input type="text" name="booking_name" required style="width:100%; padding:10px; border:1px solid #CBD5E0; border-radius:6px;" placeholder="مثال: رضا محمدی" />
						</div>
						<div>
							<label style="display:block; margin-bottom:6px; font-weight:bold;">شماره تماس (ترجیحاً موبایل):</label>
							<input type="text" name="booking_phone" required style="width:100%; padding:10px; border:1px solid #CBD5E0; border-radius:6px;" placeholder="مثال: ۰۹۱۲۳۴۵۶۷۸۹" />
						</div>
					</div>

					<div style="margin-bottom:20px;">
						<label style="display:block; margin-bottom:6px; font-weight:bold;">انتخاب جایگاه تبلیغاتی مورد نظر:</label>
						<select name="booking_slot" style="width:100%; padding:10px; border:1px solid #CBD5E0; border-radius:6px;">
							<option value="header">بنر بالای صفحات (هدر) - ۲,۵۰۰,۰۰۰ تومان</option>
							<option value="sidebar">بنر سایدبار کناری - ۱,۸۰۰,۰۰۰ تومان</option>
							<option value="content">بنر ابتدای محتوا - ۱,۵۰۰,۰۰۰ تومان</option>
							<option value="backlink">بک‌لینک متنی فالو - ۸۰۰,۰۰۰ تومان</option>
						</select>
					</div>

					<div style="margin-bottom:20px;">
						<label style="display:block; margin-bottom:6px; font-weight:bold;">آدرس وب‌سایت یا لینک بنر شما:</label>
						<input type="url" name="booking_url" required style="width:100%; padding:10px; border:1px solid #CBD5E0; border-radius:6px; text-align:left; direction:ltr;" placeholder="https://yourwebsite.com" />
					</div>

					<div style="margin-bottom:20px; background-color:#F0FFF4; border:1px solid #C6F6D5; padding:15px; border-radius:6px;">
						<h4 style="margin-top:0; color:#22543D; font-size:15px;">💳 راهنمای پرداخت کارت‌به‌کارت بانک سامان</h4>
						<p style="font-size:13px; color:#2F855A; margin-bottom:8px; line-height:1.7;">لطفاً مبلغ جایگاه مورد نظر را به شماره کارت زیر واریز نموده و سپس <strong>کد پیگیری بانکی</strong> را در کادر زیر ثبت کنید تا تاییدیه نهایی تبلیغ صادر گردد.</p>
						<p style="font-size:14px; margin-bottom:0; font-family:monospace;"><strong>شماره کارت:</strong> ۶۲۱۹-۸۶۱۰-۱۲۳۴-۵۶۷۸ (به نام مریم راد - بانک سامان)</p>
					</div>

					<div style="margin-bottom:20px;">
						<label style="display:block; margin-bottom:6px; font-weight:bold;">شماره فیش / کد پیگیری تراکنش کارت‌به‌کارت:</label>
						<input type="text" name="booking_ref" required style="width:100%; padding:10px; border:1px solid #CBD5E0; border-radius:6px;" placeholder="کد پیگیری ۶ یا ۸ رقمی رسید بانکی" />
					</div>

					<button type="submit" name="ppt_booking_submit" class="button button-primary" style="background-color:#3182CE; color:#FFF; font-weight:bold; padding:12px 30px; border:none; border-radius:6px; cursor:pointer;">ثبت درخواست و تایید فیش پرداخت</button>

				</form>
			</section>

		</main>

		<!-- Right Column: Sidebar terms -->
		<aside class="sidebar-right">
			<div class="widget card-box" style="background-color:#FFFDF5; border-color:#FEFCBF;">
				<h3 class="widget-title" style="color:#B7791F;">⚠️ قوانین و شرایط جذب آگهی</h3>
				<ul style="list-style:none; padding:0; margin:0; font-size:13px; line-height:2.2; color:#744210;">
					<li>✓ پذیرش آگهی‌های مرتبط با تورهای مجاز بوم‌گردی.</li>
					<li>✓ از پذیرش بنرهای نامتعارف یا کدهای مخرب معذوریم.</li>
					<li>✓ تغییر آدرس یا بنر آگهی در طول دوره امکان‌پذیر است.</li>
					<li>✓ تایید نهایی فیش کارت‌به‌کارت حداکثر تا ۲ ساعت انجام می‌شود.</li>
				</ul>
			</div>
		</aside>

	</div>

</div>

<?php get_footer(); ?>
