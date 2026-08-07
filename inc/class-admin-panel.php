<?php
/**
 * Custom Tabbed WordPress Admin Settings Panel
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
	}

	/**
	 * Render settings page.
	 */
	public function render_settings_page() {
		$active_tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'homepage_sections';
		?>
		<div class="wrap ppt-admin-wrap">
			<h1>تنظیمات پوسته جامع گردشگری و رادیو سفر</h1>
			<p class="description">تنظیمات بخش‌های مختلف صفحه نخست، تبلیغات، نقشه و ساختارهای فنی وب‌سایت را از این بخش مدیریت کنید.</p>

			<h2 class="nav-tab-wrapper">
				<a href="?page=ppt-settings&tab=homepage_sections" class="nav-tab <?php echo 'homepage_sections' === $active_tab ? 'nav-tab-active' : ''; ?>">مدیریت صفحه نخست</a>
				<a href="?page=ppt-settings&tab=ad_slots" class="nav-tab <?php echo 'ad_slots' === $active_tab ? 'nav-tab-active' : ''; ?>">مدیریت جایگاه‌های تبلیغاتی</a>
				<a href="?page=ppt-settings&tab=map_settings" class="nav-tab <?php echo 'map_settings' === $active_tab ? 'nav-tab-active' : ''; ?>">تنظیمات نقشه</a>
				<a href="?page=ppt-settings&tab=theme_updater" class="nav-tab <?php echo 'theme_updater' === $active_tab ? 'nav-tab-active' : ''; ?>">بروزرسانی پوسته</a>
				<a href="?page=ppt-settings&tab=documentation" class="nav-tab <?php echo 'documentation' === $active_tab ? 'nav-tab-active' : ''; ?>">راهنما و مستندات</a>
			</h2>

			<form method="post" action="options.php" style="margin-top:20px;">
				<?php
				if ( 'homepage_sections' === $active_tab ) {
					settings_fields( 'ppt_homepage_group' );
					$this->render_homepage_tab();
				} elseif ( 'ad_slots' === $active_tab ) {
					settings_fields( 'ppt_ad_group' );
					$this->render_ad_tab();
				} elseif ( 'map_settings' === $active_tab ) {
					settings_fields( 'ppt_map_group' );
					$this->render_map_tab();
				} elseif ( 'theme_updater' === $active_tab ) {
					settings_fields( 'ppt_updater_group' );
					$this->render_updater_tab();
				} elseif ( 'documentation' === $active_tab ) {
					$this->render_documentation_tab();
				}

				if ( 'documentation' !== $active_tab ) {
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
	 * Tab 2: Responsive Ad Placement Slots.
	 */
	private function render_ad_tab() {
		$ad_data = get_option( 'ppt_ad_slots', array() );
		$slots   = isset( $ad_data['slots'] ) ? $ad_data['slots'] : array();
		?>
		<div class="card-box ppt-admin-card">
			<h3>جایگاه‌های بنر تبلیغاتی مستقل (بدون تغییر چیدمان و Cumulative Layout Shift)</h3>
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
							لود فریم یا المان جاوا اسکریپت نقشه فقط با اسکرول کاربر یا کلیک شروع شود (بهینه‌سازی تضمینی سرعت صفحه).
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
	 * Tab 5: Admin & Dev documentation.
	 */
	private function render_documentation_tab() {
		?>
		<div class="card-box ppt-admin-card" style="line-height: 1.8;">
			<h2>مستندات مدیریت و راهنمای توسعه‌دهندگان پوسته جامع رادیو سفر</h2>
			<hr>

			<h3>۱. نحوه استفاده از کدهای کوتاه و ماژول‌ها</h3>
			<p>این پوسته به گونه‌ای برنامه‌نویسی شده است که نیازی به افزونه‌های سنگینی چون المنتور، المنتور پرو یا ACF ندارد. تمامی ماژول‌های صفحه نخست از تب "مدیریت صفحه نخست" قابل ترتیب‌دهی، نام‌گذاری و مدیریت هستند.</p>

			<h3>۲. سیستم آپارات تنبل (Lazy-Load Video Embed)</h3>
			<p>برای بارگذاری ویدیو بدون کاهش سرعت صفحه، در متا باکس ویدیوها، صرفاً شناسه ویدیو آپارات (به عنوان مثال <code>f1234</code>) را وارد نمایید. قالب به صورت خودکار عکس ویدیوی آپارات را نشان داده و با کلیک کاربر، فایل را لود می‌کند تا PageSpeed وب‌سایت در رتبه عالی باقی بماند.</p>

			<h3>۳. رادیو سفر (سیستم صوتی صمیمانه)</h3>
			<p>پادکست‌های خود را با فرمت صوتی مستقیم (MP3) در متا باکس مربوطه ثبت نمایید. یک پلیر کاملا بهینه‌شده و سازگار با سیستم‌های صوتی در صفحات آرشیو و سینگل نمایش داده می‌شود.</p>

			<h3>۴. تنظیمات سئو و نشانه‌گذاری اسکیما (Local SEO / JSON-LD Schema)</h3>
			<p>پوسته به طور خودکار اسکیمای غنی از نوع <code>BreadcrumbList</code> برای کل سایت و ساختارهای اختصاصی <code>TouristDestination</code>، <code>TouristAttraction</code> و <code>FAQPage</code> تولید می‌کند. این امکان رتبه‌گیری در گوگل را بدون نیاز به افزونه‌های سئو بهینه‌تر خواهد کرد.</p>

			<h3>۵. متغیرها و کدهای میانبر توسعه‌دهنده</h3>
			<ul>
				<li>آدرس فایل‌های فونت شبنم محلی: <code>assets/fonts/Shabnam.woff2</code></li>
				<li>تابع دریافت تنظیمات پوسته: <code>ppt_get_setting('ppt_ad_slots', 'slots')</code></li>
			</ul>
		</div>
		<?php
	}
}
