<?php
/**
 * Template Name: صفحه تبلیغات و جذب آگهی (Premium Advertising Showcase)
 * Description: Premium advertising information showcase page with CLS-free slot layout guides and direct contact details.
 *
 * @package Premium_Persian_Tourism
 */

get_header();

$brand_settings = get_option( 'ppt_brand_settings', array() );
$contact_phone  = isset( $brand_settings['contact_phone'] ) ? $brand_settings['contact_phone'] : '۰۲۱-۸۸۸۸۸۸۸۸';
$contact_email  = isset( $brand_settings['contact_email'] ) ? $brand_settings['contact_email'] : 'info@safarnama.ir';
$telegram_link  = isset( $brand_settings['social_telegram'] ) ? $brand_settings['social_telegram'] : '#';
?>

<div class="container" style="margin-top: 40px; margin-bottom: 60px;">

	<!-- Premium Header Showcase Section -->
	<header class="page-header text-center" style="margin-bottom: 50px; background: linear-gradient(135deg, #1A365D 0%, #2B6CB0 100%); padding: 60px 30px; border-radius: 16px; color:#FFF; position: relative; overflow: hidden; box-shadow: 0 10px 25px rgba(49, 130, 206, 0.15);">
		<span style="font-size:42px; display:block; margin-bottom:15px; filter: drop-shadow(0 2px 5px rgba(0,0,0,0.2));">📈</span>
		<h1 style="color:#FFF; font-size:32px; font-weight:800; margin-bottom:15px; letter-spacing: -0.5px;">جذب مخاطب پویا: تبلیغات در رادیو سفر</h1>
		<p style="color:#E2E8F0; font-size:16px; max-width:750px; margin:0 auto; line-height:1.8;">رسانه تخصصی، پادکست و پلتفرم صوتی تصویری رادیو سفر، بستری عالی برای معرفی آژانس‌های هواپیمایی، اقامتگاه‌های بوم‌گردی و تورهای مسافرتی به مخاطبان هدفمند است.</p>
	</header>

	<div class="layout-with-sidebar" style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">

		<!-- Main Content Pane -->
		<main class="site-main">

			<!-- Slot Pricing Grid & Table -->
			<section class="card-box" style="margin-bottom:40px; border: 1px solid #E2E8F0; border-radius: 12px; padding: 25px; background: #FFF;">
				<h2 style="color:#2B6CB0; font-size:22px; font-weight:800; border-bottom:2px solid #EDF2F7; padding-bottom:12px; margin-bottom:20px; display: flex; align-items: center; gap: 10px;">
					<span>📊</span> تعرفه و ابعاد جایگاه‌های تبلیغاتی صوتی و متنی
				</h2>

				<p style="font-size: 14px; line-height: 1.8; color: #4A5568; margin-bottom: 20px;">
					کلیه جایگاه‌های بنری به صورت کاملاً بهینه‌سازی شده و بدون هیچ‌گونه پرش صفحه (CLS-Free) طراحی شده‌اند تا به تجربه کاربری وب‌سایت آسیبی وارد نکنند.
				</p>

				<table style="width:100%; border-collapse:collapse; text-align:right; font-size:14px;">
					<thead>
						<tr style="background-color:#F7FAFC; border-bottom:2px solid #E2E8F0;">
							<th style="padding:15px; font-weight:bold; color: #2D3748;">نام جایگاه تبلیغاتی</th>
							<th style="padding:15px; font-weight:bold; color: #2D3748;">ابعاد دسکتاپ</th>
							<th style="padding:15px; font-weight:bold; color: #2D3748;">ابعاد موبایل</th>
							<th style="padding:15px; font-weight:bold; color: #2D3748; text-align:left;">تعرفه ماهیانه</th>
						</tr>
					</thead>
					<tbody>
						<tr style="border-bottom:1px solid #EDF2F7; transition: background 0.2s;" onmouseover="this.style.backgroundColor='#F7FAFC'" onmouseout="this.style.backgroundColor='transparent'">
							<td style="padding:15px;"><strong>بنر افقی هدر (بالای صفحات)</strong></td>
							<td style="padding:15px; font-family:monospace; color: #718096;">728 x 90 px</td>
							<td style="padding:15px; font-family:monospace; color: #718096;">320 x 50 px</td>
							<td style="padding:15px; text-align:left; font-weight:bold; color:#2B6CB0; font-size:15px;">۳,۰۰۰,۰۰۰ تومان</td>
						</tr>
						<tr style="border-bottom:1px solid #EDF2F7; transition: background 0.2s;" onmouseover="this.style.backgroundColor='#F7FAFC'" onmouseout="this.style.backgroundColor='transparent'">
							<td style="padding:15px;"><strong>بنر مربعی سایدبار (تک‌نوشته‌ها و مقاصد)</strong></td>
							<td style="padding:15px; font-family:monospace; color: #718096;">300 x 250 px</td>
							<td style="padding:15px; font-family:monospace; color: #718096;">300 x 250 px</td>
							<td style="padding:15px; text-align:left; font-weight:bold; color:#2B6CB0; font-size:15px;">۲,۲۰۰,۰۰۰ تومان</td>
						</tr>
						<tr style="border-bottom:1px solid #EDF2F7; transition: background 0.2s;" onmouseover="this.style.backgroundColor='#F7FAFC'" onmouseout="this.style.backgroundColor='transparent'">
							<td style="padding:15px;"><strong>بنر مستطیلی میان‌محتوا (داخل مقالات و راهنماها)</strong></td>
							<td style="padding:15px; font-family:monospace; color: #718096;">468 x 60 px</td>
							<td style="padding:15px; font-family:monospace; color: #718096;">320 x 50 px</td>
							<td style="padding:15px; text-align:left; font-weight:bold; color:#2B6CB0; font-size:15px;">۱,۸۰۰,۰۰۰ تومان</td>
						</tr>
						<tr style="border-bottom:1px solid #EDF2F7; transition: background 0.2s;" onmouseover="this.style.backgroundColor='#F7FAFC'" onmouseout="this.style.backgroundColor='transparent'">
							<td style="padding:15px;"><strong>رپورتاژ آگهی گردشگری دایمی</strong></td>
							<td style="padding:15px;">محتوای کامل به همراه ۳ لینک</td>
							<td style="padding:15px;">نمایش کامل متنی</td>
							<td style="padding:15px; text-align:left; font-weight:bold; color:#2B6CB0; font-size:15px;">۳,۵۰۰,۰۰۰ تومان</td>
						</tr>
						<tr style="border-bottom:1px solid #EDF2F7; transition: background 0.2s;" onmouseover="this.style.backgroundColor='#F7FAFC'" onmouseout="this.style.backgroundColor='transparent'">
							<td style="padding:15px;"><strong>بک‌لینک متنی ثابت در فوتر (Follow)</strong></td>
							<td style="padding:15px;">متن سفارشی شما</td>
							<td style="padding:15px;">پیوند مستقیم</td>
							<td style="padding:15px; text-align:left; font-weight:bold; color:#2B6CB0; font-size:15px;">۱,۰۰۰,۰۰۰ تومان</td>
						</tr>
					</tbody>
				</table>
			</section>

			<!-- Visual Placeholders Layout Guide -->
			<section class="card-box" style="margin-bottom:40px; border: 1px solid #E2E8F0; border-radius: 12px; padding: 25px; background: #FFF;">
				<h2 style="color:#2B6CB0; font-size:22px; font-weight:800; border-bottom:2px solid #EDF2F7; padding-bottom:12px; margin-bottom:20px; display: flex; align-items: center; gap: 10px;">
					<span>📐</span> پیش‌نمایش چیدمان و جایگاه‌های بنرها در وب‌سایت
				</h2>

				<p style="font-size: 14px; line-height: 1.8; color: #4A5568; margin-bottom: 25px;">
					نمودار زیر به صورت شماتیک محل قرارگیری بنرها را در صفحات اصلی و داخلی وب‌سایت نشان می‌دهد:
				</p>

				<!-- Layout Mockup Frame -->
				<div style="background-color: #F8FAFC; border: 2px dashed #CBD5E0; border-radius: 10px; padding: 25px; display: flex; flex-direction: column; gap: 15px;">

					<!-- Header Banner Showcase -->
					<div style="background-color: #E2E8F0; border: 1px solid #A0AEC0; border-radius: 6px; padding: 12px; text-align: center; color: #4A5568; font-weight: bold; font-size: 13px;">
						[جایگاه هدر] بنر افقی بالای سایت (728 × 90) - دید حداکثری در بدو ورود کاربر
					</div>

					<!-- Header/Body split simulating single page content -->
					<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 15px;">

						<!-- Main content simulation -->
						<div style="background-color: #FFF; border: 1px solid #E2E8F0; border-radius: 6px; padding: 15px; display: flex; flex-direction: column; gap: 15px;">
							<div style="height: 15px; background-color: #EDF2F7; border-radius: 4px; width: 80%;"></div>
							<div style="height: 15px; background-color: #EDF2F7; border-radius: 4px; width: 95%;"></div>

							<!-- In-Content Banner Showcase -->
							<div style="background-color: #FEEBC8; border: 1px solid #FBD38D; border-radius: 6px; padding: 10px; text-align: center; color: #C05621; font-weight: bold; font-size: 12px;">
								[جایگاه محتوا] بنر مستطیلی میان‌متن (468 × 60) - جذب کلیک بسیار بالا درون مقالات سفر
							</div>

							<div style="height: 15px; background-color: #EDF2F7; border-radius: 4px; width: 90%;"></div>
						</div>

						<!-- Sidebar simulation -->
						<div style="background-color: #FFF; border: 1px solid #E2E8F0; border-radius: 6px; padding: 15px; display: flex; flex-direction: column; justify-content: center; align-items: center; min-height: 150px;">
							<!-- Sidebar Banner Showcase -->
							<div style="background-color: #EBF8FF; border: 1px solid #90CDF4; border-radius: 6px; padding: 20px 10px; text-align: center; color: #2B6CB0; font-weight: bold; font-size: 12px; width: 100%; box-sizing: border-box;">
								[جایگاه سایدبار]<br>بنر مستطیلی بزرگ<br>(300 × 250)<br>دید مداوم هنگام اسکرول
							</div>
						</div>

					</div>

				</div>
			</section>

		</main>

		<!-- Right Sidebar (Direct Booking Contact Panel) -->
		<aside class="sidebar-right">

			<div class="widget card-box" style="background-color: #FFF; border: 1px solid #E2E8F0; border-radius: 12px; padding: 25px; margin-bottom: 25px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
				<h3 style="color: #2D3748; font-size: 18px; font-weight: bold; margin-bottom: 20px; border-bottom: 2px solid #EDF2F7; padding-bottom: 10px; display: flex; align-items: center; gap: 8px;">
					<span>📞</span> ارتباط با کارشناسان آگهی
				</h3>

				<p style="font-size: 14px; line-height: 1.8; color: #4A5568; margin-bottom: 20px;">
					جهت استعلام ظرفیت خالی جایگاه‌ها، ارسال طرح بنر و یا سفارش پکیج‌های اختصاصی تبلیغات با ما تماس حاصل فرمایید:
				</p>

				<!-- Direct Communication Buttons -->
				<div style="display: flex; flex-direction: column; gap: 12px;">

					<!-- Call Button -->
					<a href="tel:<?php echo esc_attr( str_replace( '-', '', $contact_phone ) ); ?>" style="display: flex; align-items: center; justify-content: space-between; background-color: #3182CE; color: #FFF; padding: 12px 18px; border-radius: 8px; font-weight: bold; text-decoration: none; transition: transform 0.2s, background-color 0.2s;" onmouseover="this.style.backgroundColor='#2B6CB0'; this.style.transform='translateY(-2px)';" onmouseout="this.style.backgroundColor='#3182CE'; this.style.transform='translateY(0)';">
						<span>تماس مستقیم تلفنی:</span>
						<span style="direction: ltr; font-family: monospace; font-size: 15px;"><?php echo esc_html( $contact_phone ); ?></span>
					</a>

					<!-- Telegram Contact -->
					<a href="<?php echo esc_url( $telegram_link ); ?>" target="_blank" rel="noopener noreferrer" style="display: flex; align-items: center; justify-content: space-between; background-color: #0088cc; color: #FFF; padding: 12px 18px; border-radius: 8px; font-weight: bold; text-decoration: none; transition: transform 0.2s, background-color 0.2s;" onmouseover="this.style.backgroundColor='#0077b3'; this.style.transform='translateY(-2px)';" onmouseout="this.style.backgroundColor='#0088cc'; this.style.transform='translateY(0)';">
						<span>ارتباط تلگرام:</span>
						<span>@RadioSafar_Ad</span>
					</a>

					<!-- Email Contact -->
					<a href="mailto:<?php echo esc_attr( $contact_email ); ?>" style="display: flex; align-items: center; justify-content: space-between; background-color: #4A5568; color: #FFF; padding: 12px 18px; border-radius: 8px; font-weight: bold; text-decoration: none; transition: transform 0.2s, background-color 0.2s;" onmouseover="this.style.backgroundColor='#2D3748'; this.style.transform='translateY(-2px)';" onmouseout="this.style.backgroundColor='#4A5568'; this.style.transform='translateY(0)';">
						<span>مکاتبه با ایمیل رسمی:</span>
						<span style="font-family: monospace; font-size: 13px;"><?php echo esc_html( $contact_email ); ?></span>
					</a>

				</div>
			</div>

			<div class="widget card-box" style="background-color:#FFFDF5; border: 1px solid #FEFCBF; border-radius: 12px; padding: 25px;">
				<h3 class="widget-title" style="color:#B7791F; font-size: 16px; margin-bottom: 12px;">⚠️ ضوابط و قوانین انتشار آگهی</h3>
				<ul style="list-style:none; padding:0; margin:0; font-size:13px; line-height:2.2; color:#744210;">
					<li>✓ آگهی‌ها باید مغایرتی با موازین بومی و فرهنگی کشور نداشته باشند.</li>
					<li>✓ از پذیرش کدهای مخرب آمارگیر پنهان معذوریم.</li>
					<li>✓ فاکتور رسمی به درخواست آژانس‌ها صادر می‌گردد.</li>
					<li>✓ پایداری ۱۰۰٪ جایگاه‌ها با CDN اختصاصی و پرسرعت.</li>
				</ul>
			</div>

		</aside>

	</div>

</div>

<?php get_footer(); ?>
