<?php
/**
 * Custom Tabbed WordPress Admin Settings Panel & Extensive Documentation
 * Now with full XML/WXR Demo Content Import/Export Standards and Download guidelines.
 *
 * @package Premium_Persian_Tourism
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class PPT_Admin_Panel {

	/**
	 * Instance option key.
	 */
	private static $instance = null;

	/**
	 * Singleton pattern.
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	private function __construct() {
		add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	/**
	 * Register Admin Settings Page.
	 */
	public function add_settings_page() {
		add_menu_page(
			'تنظیمات قالب رادیو سفر',
			'تنظیمات قالب',
			'manage_options',
			'ppt-settings',
			array( $this, 'render_settings_page' ),
			'dashicons-admin-generic',
			59
		);
	}

	/**
	 * Register settings groups and fields.
	 */
	public function register_settings() {
		register_setting( 'ppt_homepage_group', 'ppt_homepage_sections' );
		register_setting( 'ppt_ad_group', 'ppt_ad_slots' );
		register_setting( 'ppt_map_group', 'ppt_map_settings' );
		register_setting( 'ppt_updater_group', 'ppt_updater_settings' );
		register_setting( 'ppt_brand_group', 'ppt_brand_settings' );
	}

	/**
	 * Render settings page.
	 */
	public function render_settings_page() {
		$active_tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'homepage_sections';
		?>
		<div class="wrap ppt-admin-wrap">
			<h1>تنظیمات پوسته جامع گردشگری و رادیو سفر</h1>
			<p class="description">تنظیمات بخش‌های مختلف صفحه نخست، تبلیغات، نقشه، ساختارهای فنی و درون‌ریزی داده‌های نمونه را مدیریت کنید.</p>

			<h2 class="nav-tab-wrapper">
				<a href="?page=ppt-settings&tab=homepage_sections" class="nav-tab <?php echo 'homepage_sections' === $active_tab ? 'nav-tab-active' : ''; ?>">مدیریت صفحه نخست</a>
				<a href="?page=ppt-settings&tab=brand_settings" class="nav-tab <?php echo 'brand_settings' === $active_tab ? 'nav-tab-active' : ''; ?>">تنظیمات برند و سفارشی‌سازی</a>
				<a href="?page=ppt-settings&tab=ad_slots" class="nav-tab <?php echo 'ad_slots' === $active_tab ? 'nav-tab-active' : ''; ?>">مدیریت جایگاه‌های تبلیغاتی</a>
				<a href="?page=ppt-settings&tab=map_settings" class="nav-tab <?php echo 'map_settings' === $active_tab ? 'nav-tab-active' : ''; ?>">تنظیمات نقشه</a>
				<a href="?page=ppt-settings&tab=theme_updater" class="nav-tab <?php echo 'theme_updater' === $active_tab ? 'nav-tab-active' : ''; ?>">بروزرسانی پوسته</a>
				<a href="?page=ppt-settings&tab=demo_import" class="nav-tab <?php echo 'demo_import' === $active_tab ? 'nav-tab-active' : ''; ?>">درون‌ریزی فایل دمو (XML)</a>
				<a href="?page=ppt-settings&tab=documentation" class="nav-tab <?php echo 'documentation' === $active_tab ? 'nav-tab-active' : ''; ?>">راهنما و مستندات تخصصی</a>
			</h2>

			<form method="post" action="options.php" style="margin-top:20px;">
				<?php
				if ( 'homepage_sections' === $active_tab ) {
					settings_fields( 'ppt_homepage_group' );
					$this->render_homepage_tab();
				} elseif ( 'brand_settings' === $active_tab ) {
					settings_fields( 'ppt_brand_group' );
					$this->render_brand_tab();
				} elseif ( 'ad_slots' === $active_tab ) {
					settings_fields( 'ppt_ad_group' );
					$this->render_ad_tab();
				} elseif ( 'map_settings' === $active_tab ) {
					settings_fields( 'ppt_map_group' );
					$this->render_map_tab();
				} elseif ( 'theme_updater' === $active_tab ) {
					settings_fields( 'ppt_updater_group' );
					$this->render_updater_tab();
				} elseif ( 'demo_import' === $active_tab ) {
					$this->render_demo_import_tab();
				} elseif ( 'documentation' === $active_tab ) {
					$this->render_documentation_tab();
				}

				if ( 'documentation' !== $active_tab && 'demo_import' !== $active_tab ) {
					submit_button( 'ذخیره تنظیمات پوسته' );
				}
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Tab 1: Homepage sections manager.
	 */
	private function render_homepage_tab() {
		$homepage_data = get_option( 'ppt_homepage_sections', array() );
		$sections      = isset( $homepage_data['sections'] ) ? $homepage_data['sections'] : array();
		?>
		<div class="card-box ppt-admin-card">
			<h3>ترتیب و مدیریت ماژول‌های صفحه نخست</h3>
			<p class="description">بخش‌های مختلف صفحه اصلی وب‌سایت گردشگری را جابجا کنید، عنوان را تغییر دهید یا مواردی را فعال یا غیرفعال سازید.</p>

			<table class="wp-list-table widefat fixed striped" style="margin-top: 15px;">
				<thead>
					<tr>
						<th style="width: 5%;">ترتیب</th>
						<th style="width: 25%;">شناسه ماژول</th>
						<th style="width: 30%;">عنوان فارسی نمایشی</th>
						<th style="width: 15%;">تعداد نمایش</th>
						<th style="width: 15%;">چیدمان (Layout)</th>
						<th style="width: 10%;">وضعیت</th>
					</tr>
				</thead>
				<tbody class="ppt-sortable-sections">
					<?php foreach ( $sections as $index => $section ) : ?>
						<tr class="ppt-section-row">
							<td class="ppt-drag-handle">
								<span class="dashicons dashicons-menu" style="cursor: move;"></span>
								<input type="hidden" name="ppt_homepage_sections[sections][<?php echo esc_attr( $index ); ?>][id]" value="<?php echo esc_attr( $section['id'] ); ?>" />
							</td>
							<td>
								<strong><?php echo esc_html( $section['id'] ); ?></strong>
								<input type="hidden" name="ppt_homepage_sections[sections][<?php echo esc_attr( $index ); ?>][source]" value="<?php echo esc_attr( $section['source'] ); ?>" />
							</td>
							<td>
								<input type="text" name="ppt_homepage_sections[sections][<?php echo esc_attr( $index ); ?>][title]" value="<?php echo esc_attr( $section['title'] ); ?>" class="large-text" />
							</td>
							<td>
								<?php if ( 'hero_search' !== $section['id'] ) : ?>
									<input type="number" name="ppt_homepage_sections[sections][<?php echo esc_attr( $index ); ?>][count]" value="<?php echo esc_attr( $section['count'] ); ?>" style="width:70px;" min="1" max="20" />
								<?php else : ?>
									<span class="description">نابودگو</span>
									<input type="hidden" name="ppt_homepage_sections[sections][<?php echo esc_attr( $index ); ?>][count]" value="0" />
								<?php endif; ?>
							</td>
							<td>
								<select name="ppt_homepage_sections[sections][<?php echo esc_attr( $index ); ?>][layout]">
									<?php if ( 'hero_search' === $section['id'] ) : ?>
										<option value="hero" <?php selected( $section['layout'], 'hero' ); ?>>سربرگ قهرمان (Hero Search)</option>
									<?php else : ?>
										<option value="grid" <?php selected( $section['layout'], 'grid' ); ?>>شبکه‌ای (Grid)</option>
										<option value="carousel" <?php selected( $section['layout'], 'carousel' ); ?>>کاروسل / اسلایدر</option>
										<option value="list" <?php selected( $section['layout'], 'list' ); ?>>لیست خطی (List)</option>
									<?php endif; ?>
								</select>
							</td>
							<td>
								<select name="ppt_homepage_sections[sections][<?php echo esc_attr( $index ); ?>][enabled]">
									<option value="1" <?php selected( $section['enabled'], '1' ); ?>>فعال</option>
									<option value="0" <?php selected( $section['enabled'], '0' ); ?>>غیرفعال</option>
								</select>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<?php
	}

	/**
	 * Tab 1.5: Branding, Color, and Contact customization settings.
	 */
	private function render_brand_tab() {
		$brand = get_option( 'ppt_brand_settings', array() );
		$primary_color     = isset( $brand['primary_color'] ) ? $brand['primary_color'] : '#3182CE';
		$logo_tips         = isset( $brand['logo_tips'] ) ? $brand['logo_tips'] : '';
		$phone             = isset( $brand['contact_phone'] ) ? $brand['contact_phone'] : '۰۲۱-۸۸۸۸۸۸۸۸';
		$email             = isset( $brand['contact_email'] ) ? $brand['contact_email'] : 'info@safarnama.ir';
		$instagram         = isset( $brand['social_instagram'] ) ? $brand['social_instagram'] : '';
		$telegram          = isset( $brand['social_telegram'] ) ? $brand['social_telegram'] : '';
		$aparat            = isset( $brand['social_aparat'] ) ? $brand['social_aparat'] : '';
		$footer_text       = isset( $brand['footer_text'] ) ? $brand['footer_text'] : 'تمامی حقوق این وب‌سایت محفوظ و متعلق به رادیو سفر می‌باشد.';
		$enable_sticky_bar = isset( $brand['enable_sticky_bar'] ) ? $brand['enable_sticky_bar'] : '1';
		$header_style      = isset( $brand['header_style'] ) ? $brand['header_style'] : 'premium';
		?>
		<div class="card-box ppt-admin-card">
			<h3>تنظیمات هویت بصری برند، تماس، شبکه‌های اجتماعی و ناوبری موبایل</h3>
			<p class="description">تم رنگی، شماره تماس، ایمیل، آدرس شبکه‌های اجتماعی و رفتار نوارهای ناوبری رادیو سفر را سفارشی‌سازی کنید.</p>

			<table class="form-table" style="margin-top:15px;">
				<tr>
					<th scope="row">استایل سربرگ (Header Style)</th>
					<td>
						<select name="ppt_brand_settings[header_style]">
							<option value="premium" <?php selected( $header_style, 'premium' ); ?>>سربرگ مدرن و ادیتوریال رادیو سفر</option>
							<option value="classic" <?php selected( $header_style, 'classic' ); ?>>سربرگ مینیمال سنتی</option>
						</select>
					</td>
				</tr>
				<tr>
					<th scope="row">فعال‌سازی نوار ناوبری چسبان پایین موبایل (Sticky Mobile Bar)</th>
					<td>
						<label>
							<input type="checkbox" name="ppt_brand_settings[enable_sticky_bar]" value="1" <?php checked( '1', $enable_sticky_bar ); ?> />
							نمایش نوار ناوبری اپلیکیشنی پایین صفحه در رزولوشن‌های موبایل (خانه، مقاصد، رادیو سفر، مستندها).
						</label>
					</td>
				</tr>
				<tr>
					<th scope="row">رنگ سازمانی اصلی (Primary Theme Color)</th>
					<td>
						<input type="color" name="ppt_brand_settings[primary_color]" value="<?php echo esc_attr( $primary_color ); ?>" style="height:40px; width:80px; padding:0; cursor:pointer;" />
						<p class="description">رنگ دکمه‌ها، لینک‌ها و جزئیات گرافیکی برجسته در سراسر سایت.</p>
					</td>
				</tr>
				<tr>
					<th scope="row">توضیحات یا راهنمای بارگذاری لوگو</th>
					<td>
						<textarea name="ppt_brand_settings[logo_tips]" class="large-text" rows="2" placeholder="توصیه می‌شود لوگو در پس‌زمینه شفاف (PNG) و در ابعاد حداکثر ۸۰ در ۲۴۰ پیکسل بارگذاری شود."><?php echo esc_textarea( $logo_tips ); ?></textarea>
					</td>
				</tr>
				<tr>
					<th scope="row">شماره تلفن تماس</th>
					<td>
						<input type="text" name="ppt_brand_settings[contact_phone]" value="<?php echo esc_attr( $phone ); ?>" class="regular-text" />
					</td>
				</tr>
				<tr>
					<th scope="row">پست الکترونیکی تماس (ایمیل)</th>
					<td>
						<input type="email" name="ppt_brand_settings[contact_email]" value="<?php echo esc_attr( $email ); ?>" class="regular-text" />
					</td>
				</tr>
				<tr>
					<th scope="row">آدرس اینستاگرام</th>
					<td>
						<input type="url" name="ppt_brand_settings[social_instagram]" value="<?php echo esc_url( $instagram ); ?>" class="regular-text" placeholder="https://instagram.com/safarnama" />
					</td>
				</tr>
				<tr>
					<th scope="row">آدرس کانال تلگرام</th>
					<td>
						<input type="url" name="ppt_brand_settings[social_telegram]" value="<?php echo esc_url( $telegram ); ?>" class="regular-text" placeholder="https://t.me/safarnama" />
					</td>
				</tr>
				<tr>
					<th scope="row">آدرس کانال آپارات</th>
					<td>
						<input type="url" name="ppt_brand_settings[social_aparat]" value="<?php echo esc_url( $aparat ); ?>" class="regular-text" placeholder="https://aparat.com/safarnama" />
					</td>
				</tr>
				<tr>
					<th scope="row">متن کپی‌رایت فوتر</th>
					<td>
						<textarea name="ppt_brand_settings[footer_text]" class="large-text" rows="3"><?php echo esc_textarea( $footer_text ); ?></textarea>
					</td>
				</tr>
			</table>
		</div>
		<?php
	}

	/**
	 * Tab 2: Responsive Ad Placement Slots.
	 */
	private function render_ad_tab() {
		$ad_data = get_option( 'ppt_ad_slots', array() );
		$slots   = isset( $ad_data['slots'] ) ? $ad_data['slots'] : array();
		?>
		<div class="card-box ppt-admin-card">
			<h3>جایگاه‌های بنر تبلیغاتی مستقل (بدون Cumulative Layout Shift)</h3>
			<p class="description">کد تبلیغات مستقل دسکتاپ و موبایل خود را وارد نمایید. این بخش ابعاد مشخصی برای بنرها در فرانت‌اند رزرو می‌کند تا از پرش صفحه جلوگیری گردد.</p>

			<?php foreach ( $slots as $key => $slot ) : ?>
				<div class="ppt-ad-slot-box" style="border: 1px solid #ccc; padding: 15px; margin-bottom: 20px; background:#fafafa;">
					<h4><?php echo esc_html( $slot['label'] ); ?></h4>
					<div style="display:flex; gap:15px; margin-top:10px;">
						<div style="flex:1;">
							<label><strong>کد تبلیغات دسکتاپ (HTML/JS):</strong></label><br>
							<textarea name="ppt_ad_slots[slots][<?php echo esc_attr( $key ); ?>][desktop_code]" style="width:100%; font-family: monospace;" rows="3"><?php echo esc_textarea( $slot['desktop_code'] ); ?></textarea>
						</div>
						<div style="flex:1;">
							<label><strong>کد تبلیغات موبایل (HTML/JS):</strong></label><br>
							<textarea name="ppt_ad_slots[slots][<?php echo esc_attr( $key ); ?>][mobile_code]" style="width:100%; font-family: monospace;" rows="3"><?php echo esc_textarea( $slot['mobile_code'] ); ?></textarea>
						</div>
					</div>
					<div style="margin-top:10px; display:flex; align-items:center; gap:20px;">
						<label>
							<input type="checkbox" name="ppt_ad_slots[slots][<?php echo esc_attr( $key ); ?>][enabled]" value="1" <?php checked( isset( $slot['enabled'] ) && '1' === $slot['enabled'] ); ?> />
							<strong>فعال‌سازی این جایگاه تبلیغاتی</strong>
						</label>
						<input type="hidden" name="ppt_ad_slots[slots][<?php echo esc_attr( $key ); ?>][label]" value="<?php echo esc_attr( $slot['label'] ); ?>" />
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}

	/**
	 * Tab 3: Maps and Interactive settings.
	 */
	private function render_map_tab() {
		$map_settings = get_option( 'ppt_map_settings', array() );
		$enable_maps  = isset( $map_settings['enable_maps'] ) ? $map_settings['enable_maps'] : '1';
		$map_provider = isset( $map_settings['map_provider'] ) ? $map_settings['map_provider'] : 'osm';
		$lazy_load    = isset( $map_settings['lazy_load'] ) ? $map_settings['lazy_load'] : '1';
		?>
		<div class="card-box ppt-admin-card">
			<h3>تنظیمات کلی نقشه و توابع جغرافیایی</h3>
			<p class="description">این پوسته از کتابخانه متن‌باز و سبک Leaflet بر بستر نقشه آزاد OpenStreetMap به صورت مستقل استفاده می‌کند.</p>

			<table class="form-table" style="margin-top:15px;">
				<tr>
					<th scope="row">فعال‌سازی سراسری نقشه‌ها</th>
					<td>
						<label>
							<input type="checkbox" name="ppt_map_settings[enable_maps]" value="1" <?php checked( '1', $enable_maps ); ?> />
							نقشه‌های تعاملی موقعیت مکانی در جزییات جاذبه‌ها و مقاصد بارگذاری شوند.
						</label>
					</td>
				</tr>
				<tr>
					<th scope="row">سرویس‌دهنده نقشه (Map Provider)</th>
					<td>
						<select name="ppt_map_settings[map_provider]">
							<option value="osm" <?php selected( 'osm', $map_provider ); ?>>OpenStreetMap (بدون نیاز به کلید API)</option>
							<option value="neshan" <?php selected( 'neshan', $map_provider ); ?>>نقشه ایرانی نشان</option>
							<option value="cedarmap" <?php selected( 'cedarmap', $map_provider ); ?>>نقشه سیدارمپ (CedarMap)</option>
						</select>
					</td>
				</tr>
				<tr>
					<th scope="row">بارگذاری تنبل (Lazy Load) نقشه‌ها</th>
					<td>
						<label>
							<input type="checkbox" name="ppt_map_settings[lazy_load]" value="1" <?php checked( '1', $lazy_load ); ?> />
							لود فریم یا المان جاوا اسکریپت نقشه فقط با اسکرول کاربر یا کلیک شروع شود (بهینه‌سازی سرعت صفحه).
						</label>
					</td>
				</tr>
			</table>
		</div>
		<?php
	}

	/**
	 * Tab 4: Updater and Migrations settings.
	 */
	private function render_updater_tab() {
		$updater_settings = get_option( 'ppt_updater_settings', array() );
		$endpoint         = isset( $updater_settings['update_endpoint'] ) ? $updater_settings['update_endpoint'] : '';
		$current_db_ver   = get_option( 'ppt_db_version', '0.0.0' );
		?>
		<div class="card-box ppt-admin-card">
			<h3>تنظیمات سیستم بروزرسانی خودکار و مهاجرت دیتابیس</h3>
			<p class="description">اطلاعات سرور توسعه‌دهنده جهت بررسی نسخه‌های جدید قالب و همگام‌سازی ساختارهای داده‌ای.</p>

			<table class="form-table" style="margin-top:15px;">
				<tr>
					<th scope="row">آدرس سرور بروزرسانی (Remote Update Endpoint)</th>
					<td>
						<input type="text" name="ppt_updater_settings[update_endpoint]" value="<?php echo esc_url( $endpoint ); ?>" class="regular-text" placeholder="https://api.example.com/theme-updates" />
						<p class="description">اندپوینت API برای دریافت اطلاعات آخرین نسخه و دریافت فایل زیپ پوسته.</p>
					</td>
				</tr>
				<tr>
					<th scope="row">نسخه فعلی پایگاه‌داده (Database Migration Version)</th>
					<td>
						<code style="font-size:14px;"><?php echo esc_html( $current_db_ver ); ?></code>
						<span style="margin-right:15px;" class="dashicons dashicons-yes-alt text-success"></span> ساختار پایگاه‌داده بروز است.
					</td>
				</tr>
			</table>
		</div>
		<?php
	}

	/**
	 * Tab 6: WXR XML Demo Content Export & Import Standard Tutorials.
	 */
	private function render_demo_import_tab() {
		?>
		<div class="card-box ppt-admin-card" style="line-height:1.9;">
			<h3>استاندارد ساختار فایل‌های درون‌ریز دمو گردشگری (WordPress eXtended RSS - WXR)</h3>
			<p class="description">این بخش مشخصات و ساختار استاندارد فایل‌های <code>.xml</code> درون‌ریز را برای پست‌تایپ‌ها و متادیتاها تشریح می‌کند.</p>

			<div style="background-color:#F7FAFC; border:1px solid #E2E8F0; padding:15px; border-radius:6px; margin-bottom:20px;">
				<h4 style="margin-top:0; color:#2C5282;">📥 راهنمای درون‌ریزی فوری:</h4>
				<p style="font-size:14px; margin-bottom:0;">برای درون‌ریزی داده‌های صوتی و موقعیت‌های جغرافیایی، از منوی <strong>ابزارها &rarr; درون‌ریزی &rarr; WordPress</strong> استفاده نمایید و فایل XML ساخته شده را آپلود کنید. به طور موازی، داده‌های دمو با هر بار فعال‌سازی پوسته به صورت خودکار تولید می‌شوند.</p>
			</div>

			<h4 style="color:#2D3748;">ساختار نمونه سند WXR XML استاندارد برای جاذبه‌ها و پادکست‌ها:</h4>
			<textarea class="large-text" rows="15" readonly style="font-family:monospace; font-size:11px; direction:ltr; text-align:left; background-color:#1E293B; color:#F8FAFC;">
<?xml version="1.0" encoding="UTF-8" ?>
<rss version="2.0"
	xmlns:excerpt="http://wordpress.org/export/1.2/excerpt/"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/commentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:wp="http://wordpress.org/export/1.2/"
>
<channel>
	<title>رادیو سفر - فایل درون‌ریز نمونه</title>
	<link>https://safarnama.ir</link>
	<description>دمو جامع گردشگری صوتی</description>
	<wp:wxr_version>1.2</wp:wxr_version>

	<!-- نمونه آیتم جاذبه گردشگری همراه با متادیتاها -->
	<item>
		<title>مجموعه تاریخی باغ شازده ماهان کرمان</title>
		<link>https://safarnama.ir/attractions/shazdeh-garden/</link>
		<pubDate>Mon, 01 Jan 2026 00:00:00 +0000</pubDate>
		<wp:post_id>2001</wp:post_id>
		<wp:post_date><![CDATA[2026-01-01 00:00:00]]></wp:post_date>
		<wp:post_name><![CDATA[shazdeh-garden]]></wp:post_name>
		<wp:status><![CDATA[publish]]></wp:status>
		<wp:post_type><![CDATA[attraction]]></wp:post_type>

		<!-- دسته‌بندی و تعیین استان -->
		<category domain="province" Oregon="kerman"><![CDATA[کرمان]]></category>

		<content:encoded><![CDATA[باغ شاهزاده ماهان یکی از زیباترین باغ‌های تاریخی ایران است که در دل کویر کرمان می‌درخشد. این اثر ثبت جهانی یونسکو بوده و از سیستم آبرسانی پله‌ای فوق‌العاده‌ای بهره می‌برد.]]></content:encoded>

		<!-- اطلاعات جغرافیایی و بهای بلیت -->
		<wp:postmeta>
			<wp:meta_key><![CDATA[_ppt_address]]></wp:meta_key>
			<wp:meta_value><![CDATA[کرمان، ۶ کیلومتری مسیر ماهان]]></wp:meta_value>
		</wp:postmeta>
		<wp:postmeta>
			<wp:meta_key><![CDATA[_ppt_lat]]></wp:meta_key>
			<wp:meta_value><![CDATA[30.0242]]></wp:meta_value>
		</wp:postmeta>
		<wp:postmeta>
			<wp:meta_key><![CDATA[_ppt_lng]]></wp:meta_key>
			<wp:meta_value><![CDATA[57.2801]]></wp:meta_value>
		</wp:postmeta>
	</item>
</channel>
</rss>
			</textarea>
		</div>
		<?php
	}

	/**
	 * Tab 5: Professional Diataxis Documentation
	 */
	private function render_documentation_tab() {
		?>
		<div class="card-box ppt-admin-card" style="line-height: 1.8; direction:rtl; text-align:right;">
			<h2>مستندات مرجع و راهنمای جامع پوسته رادیو سفر (ساختار Diataxis)</h2>
			<hr style="margin:20px 0;">

			<!-- 1. TUTORIALS -->
			<div style="margin-bottom:30px;">
				<h3 style="color:#2B6CB0; border-bottom:1px solid #E2E8F0; padding-bottom:5px;">۱. آموزش‌های راه‌اندازی مقدماتی (Tutorials)</h3>
				<p><strong>چگونه سایت گردشگری رادیو سفر را در کمتر از ۵ دقیقه راه‌اندازی کنیم؟</strong></p>
				<ol>
					<li>پوسته را روی یک وردپرس تازه نصب شده فعال کنید.</li>
					<li>با فعال‌سازی پوسته، داده‌های دمو صوتی و متنی (شیراز، اصفهان، جزیره قشم و چاهکوه) همراه با نقشه‌ها به طور خودکار تولید می‌شوند.</li>
					<li>به مسیر <strong>نمایش &rarr; فهرست‌ها</strong> رفته و منوی اصلی (Primary RTL) را به دلخواه تنظیم کنید.</li>
					<li>از تب "مدیریت صفحه نخست" چیدمان لایوها و ماژول‌ها را اولویت‌بندی کرده و دکمه ذخیره را بزنید.</li>
				</ol>
			</div>

			<!-- 2. HOW-TO GUIDES -->
			<div style="margin-bottom:30px;">
				<h3 style="color:#2B6CB0; border-bottom:1px solid #E2E8F0; padding-bottom:5px;">۲. دستورالعمل‌های گام‌به‌گام (How-To Guides)</h3>

				<p><strong>چگونه یک پادکست جدید در رادیو سفر اضافه کنیم؟</strong></p>
				<ol>
					<li>به منوی <strong>پادکست‌ها &rarr; افزودن پادکست جدید</strong> مراجعه کنید.</li>
					<li>عنوان اپیزود و توضیحات متنی آن را وارد کنید.</li>
					<li>در فیلد تصویر شاخص، کاور جذاب پادکست را آپلود کنید.</li>
					<li>در انتهای صفحه و در بخش "تنظیمات فایل صوتی پادکست"، آدرس مستقیم فایل صوتی با فرمت <code>MP3</code> را الصاق کنید و دکمه انتشار را بفشارید.</li>
				</ol>

				<p><strong>چگونه ویدیوهای آپارات را بدون افت سرعت بارگذاری کنیم؟</strong></p>
				<ul>
					<li>به بخش <strong>ویدیوها &rarr; افزودن ویدیو جدید</strong> بروید.</li>
					<li>شناسه کوتاه ویدیو آپارات (مثلاً <code>fXgHe</code>) را کپی کرده و در بخش فیلد شناسه آپارات قرار دهید.</li>
					<li>پوسته به طور کاملا خودکار تصویر و دکمه پخش تنبل را لود کرده و از افت سرعت وب‌سایت شما جلوگیری می‌کند.</li>
				</ul>
			</div>

			<!-- 3. REFERENCE CODES -->
			<div style="margin-bottom:30px;">
				<h3 style="color:#2B6CB0; border-bottom:1px solid #E2E8F0; padding-bottom:5px;">۳. اطلاعات مرجع و توابع فنی (Reference)</h3>
				<p><strong>توابع کمکی و داده‌های مرجع توسعه‌دهنده:</strong></p>
				<ul>
					<li><code>ppt_get_setting( 'ppt_brand_settings', 'primary_color', '#3182CE' )</code>: دریافت تم رنگی پویای تعریف شده در تنظیمات برند.</li>
					<li><code>PPT_Ad_Manager::render_ad_slot( 'sidebar_ad' )</code>: رندر بنر ضد Cumulative Layout Shift سایدبار.</li>
					<li>کتابخانه نقشه‌ها: <strong>Leaflet JS v1.9.4</strong> با قابلیت لود تنبل تعاملی.</li>
				</ul>
			</div>

			<!-- 4. EXPLANATION -->
			<div>
				<h3 style="color:#2B6CB0; border-bottom:1px solid #E2E8F0; padding-bottom:5px;">۴. مفاهیم عمیق و منطق طراحی (Explanation)</h3>
				<p><strong>چرا عدم استفاده از افزونه‌های سنگین اهمیت دارد؟</strong></p>
				<p class="text-justify">استفاده مکرر از فریم‌ورک‌های سنگین مانند المنتور و ویژوال کامپوزر با تزریق استایل‌های تکراری و کدهای CSS/JS غیرضروری، سرعت موبایل کاربران را به شدت کاهش داده و بر سئوی محلی تاثیر منفی می‌گذارد. معماری سبک، پاک و برون‌سازمانی این پوسته تضمین می‌کند که سایت شما بر روی ضعیف‌ترین شبکه‌های موبایلی (3G) در مناطق کوهستانی یا جزایر دوردست ایران، در کمترین زمان ممکن لود گردد.</p>
			</div>
		</div>
		<?php
	}
}
