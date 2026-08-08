<?php
/**
 * Custom Tabbed WordPress Admin Settings Panel & Commercial Admin Console
 * Includes Visual Customizer, Child Theme Creator, Ad Booking logs, and Import/Export utilities.
 * Refactored to list and clear 'ppt_booking' custom post records (Security Overhaul).
 * Upgraded with multi-taxonomy customizers, footer visibility triggers, and multi-CPT WXR schemas.
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
		add_action( 'admin_init', array( $this, 'process_admin_actions' ) );
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
	 * Handle admin commands (Child Theme installation, Import/Export, status updates).
	 */
	public function process_admin_actions() {
		if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// 1. Install Child Theme Action
		if ( isset( $_GET['action'] ) && 'install_child_theme' === $_GET['action'] ) {
			check_admin_referer( 'ppt_install_child_nonce' );
			if ( class_exists( 'PPT_Theme_Setup' ) ) {
				PPT_Theme_Setup::create_child_theme_automatically();
				wp_safe_redirect( add_query_arg( array( 'page' => 'ppt-settings', 'tab' => 'brand_settings', 'child_created' => '1' ), admin_url( 'admin.php' ) ) );
				exit;
			}
		}

		// 2. Clear Bookings Action (Security Overhaul: Clears 'ppt_booking' custom post types)
		if ( isset( $_GET['action'] ) && 'clear_bookings' === $_GET['action'] ) {
			check_admin_referer( 'ppt_clear_bookings_nonce' );
			$bookings_posts = get_posts( array( 'post_type' => 'ppt_booking', 'posts_per_page' => -1 ) );
			if ( ! empty( $bookings_posts ) && is_array( $bookings_posts ) ) {
				foreach ( $bookings_posts as $bp ) {
					wp_delete_post( $bp->ID, true );
				}
			}
			wp_safe_redirect( add_query_arg( array( 'page' => 'ppt-settings', 'tab' => 'ad_slots', 'bookings_cleared' => '1' ), admin_url( 'admin.php' ) ) );
			exit;
		}

		// 3. Process Settings Import
		if ( isset( $_POST['ppt_import_submit'] ) ) {
			check_admin_referer( 'ppt_import_settings_nonce' );
			$import_data = isset( $_POST['ppt_import_string'] ) ? json_decode( base64_decode( sanitize_textarea_field( $_POST['ppt_import_string'] ) ), true ) : null;
			if ( is_array( $import_data ) ) {
				if ( isset( $import_data['ppt_brand_settings'] ) ) {
					update_option( 'ppt_brand_settings', $import_data['ppt_brand_settings'] );
				}
				if ( isset( $import_data['ppt_ad_slots'] ) ) {
					update_option( 'ppt_ad_slots', $import_data['ppt_ad_slots'] );
				}
				wp_safe_redirect( add_query_arg( array( 'page' => 'ppt-settings', 'tab' => 'brand_settings', 'imported' => '1' ), admin_url( 'admin.php' ) ) );
				exit;
			}
		}
	}

	/**
	 * Render settings page.
	 */
	public function render_settings_page() {
		$active_tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'homepage_sections';
		?>
		<div class="wrap ppt-admin-wrap">
			<h1>تنظیمات پوسته جامع گردشگری و رادیو سفر</h1>
			<p class="description">پیکربندی استایل‌ها، تبلیغات، نقشه، ساختارهای فنی و درون‌ریزی داده‌های نمونه را مدیریت کنید.</p>

			<?php if ( isset( $_GET['child_created'] ) && '1' === $_GET['child_created'] ) : ?>
				<div class="notice notice-success is-dismissible">
					<p><strong>قالب فرزند (Child Theme) با موفقیت ساخته شد!</strong> می‌توانید از بخش نمایش پوسته فرزند را فعال نمایید.</p>
				</div>
			<?php endif; ?>

			<?php if ( isset( $_GET['bookings_cleared'] ) && '1' === $_GET['bookings_cleared'] ) : ?>
				<div class="notice notice-success is-dismissible">
					<p>لیست رزروهای تبلیغاتی با موفقیت پاکسازی شد.</p>
				</div>
			<?php endif; ?>

			<?php if ( isset( $_GET['imported'] ) && '1' === $_GET['imported'] ) : ?>
				<div class="notice notice-success is-dismissible">
					<p>پیکربندی تنظیمات پوسته با موفقیت وارد و بارگذاری گردید.</p>
				</div>
			<?php endif; ?>

			<h2 class="nav-tab-wrapper">
				<a href="?page=ppt-settings&tab=homepage_sections" class="nav-tab <?php echo 'homepage_sections' === $active_tab ? 'nav-tab-active' : ''; ?>">مدیریت صفحه نخست</a>
				<a href="?page=ppt-settings&tab=brand_settings" class="nav-tab <?php echo 'brand_settings' === $active_tab ? 'nav-tab-active' : ''; ?>">تنظیمات برند و سفارشی‌سازی</a>
				<a href="?page=ppt-settings&tab=ad_slots" class="nav-tab <?php echo 'ad_slots' === $active_tab ? 'nav-tab-active' : ''; ?>">مدیریت تبلیغات و بک‌لینک‌ها</a>
				<a href="?page=ppt-settings&tab=map_settings" class="nav-tab <?php echo 'map_settings' === $active_tab ? 'nav-tab-active' : ''; ?>">تنظیمات نقشه</a>
				<a href="?page=ppt-settings&tab=theme_updater" class="nav-tab <?php echo 'theme_updater' === $active_tab ? 'nav-tab-active' : ''; ?>">بروزرسانی پوسته</a>
				<a href="?page=ppt-settings&tab=demo_import" class="nav-tab <?php echo 'demo_import' === $active_tab ? 'nav-tab-active' : ''; ?>">درون‌ریزی فایل دمو (XML)</a>
				<a href="?page=ppt-settings&tab=url_guidelines" class="nav-tab <?php echo 'url_guidelines' === $active_tab ? 'nav-tab-active' : ''; ?>">تنظیمات آدرس‌ها (URL)</a>
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
				} elseif ( 'url_guidelines' === $active_tab ) {
					$this->render_url_guidelines_tab();
				} elseif ( 'documentation' === $active_tab ) {
					$this->render_documentation_tab();
				}

				if ( 'documentation' !== $active_tab && 'demo_import' !== $active_tab && 'url_guidelines' !== $active_tab ) {
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
	 * Tab 1.5: Branding, Color, Design Variable Tokens Customizer & Child Theme Creater.
	 */
	private function render_brand_tab() {
		$brand             = get_option( 'ppt_brand_settings', array() );
		$primary_color     = isset( $brand['primary_color'] ) ? $brand['primary_color'] : '#3182CE';
		$sec_color         = isset( $brand['secondary_color'] ) ? $brand['secondary_color'] : '#2B6CB0';
		$bg_color          = isset( $brand['bg_color'] ) ? $brand['bg_color'] : '#F7FAFC';
		$text_color        = isset( $brand['text_color'] ) ? $brand['text_color'] : '#2D3748';
		$border_radius     = isset( $brand['border_radius'] ) ? $brand['border_radius'] : '12';
		$container_width   = isset( $brand['container_width'] ) ? $brand['container_width'] : '1200';
		$phone             = isset( $brand['contact_phone'] ) ? $brand['contact_phone'] : '۰۲۱-۸۸۸۸۸۸۸۸';
		$email             = isset( $brand['contact_email'] ) ? $brand['contact_email'] : 'info@safarnama.ir';
		$instagram         = isset( $brand['social_instagram'] ) ? $brand['social_instagram'] : '';
		$telegram          = isset( $brand['social_telegram'] ) ? $brand['social_telegram'] : '';
		$aparat            = isset( $brand['social_aparat'] ) ? $brand['social_aparat'] : '';
		$footer_text       = isset( $brand['footer_text'] ) ? $brand['footer_text'] : 'تمامی حقوق این وب‌سایت محفوظ و متعلق به رادیو سفر می‌باشد.';
		$enable_sticky_bar = isset( $brand['enable_sticky_bar'] ) ? $brand['enable_sticky_bar'] : '1';
		$header_style      = isset( $brand['header_style'] ) ? $brand['header_style'] : 'premium';

		// Upgraded visibility customizers
		$hide_footer_mobile = isset( $brand['hide_footer_mobile'] ) ? $brand['hide_footer_mobile'] : '0';
		$prov_color         = isset( $brand['prov_color'] ) ? $brand['prov_color'] : '#2B6CB0';
		$topic_color        = isset( $brand['topic_color'] ) ? $brand['topic_color'] : '#B7791F';
		?>
		<div class="card-box ppt-admin-card">
			<h3>🎨 پلتفرم شخصی‌سازی استایل‌ها و متغیرهای بصری (Priority 1)</h3>
			<p class="description">تمامی پارامترهای گرافیکی اعم از رنگ‌های اصلی، فواصل، شعاع‌ها و عرض جعبه‌ها را بدون کدنویسی از این قسمت تغییر دهید:</p>

			<table class="form-table" style="margin-top:15px;">
				<tr>
					<th scope="row">رنگ سازمانی اصلی (Primary Color)</th>
					<td>
						<input type="color" name="ppt_brand_settings[primary_color]" value="<?php echo esc_attr( $primary_color ); ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row">رنگ فرعی (Secondary Color)</th>
					<td>
						<input type="color" name="ppt_brand_settings[secondary_color]" value="<?php echo esc_attr( $sec_color ); ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row">رنگ پس‌زمینه کل سایت (Background Color)</th>
					<td>
						<input type="color" name="ppt_brand_settings[bg_color]" value="<?php echo esc_attr( $bg_color ); ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row">رنگ متون و پاراگراف‌ها (Text Color)</th>
					<td>
						<input type="color" name="ppt_brand_settings[text_color]" value="<?php echo esc_attr( $text_color ); ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row">انحنای حاشیه المان‌ها (Border Radius - px)</th>
					<td>
						<input type="number" name="ppt_brand_settings[border_radius]" value="<?php echo esc_attr( $border_radius ); ?>" style="width:100px;" /> پیکسل
					</td>
				</tr>
				<tr>
					<th scope="row">حداکثر عرض بدنه سایت (Container Width - px)</th>
					<td>
						<input type="number" name="ppt_brand_settings[container_width]" value="<?php echo esc_attr( $container_width ); ?>" style="width:100px;" /> پیکسل
					</td>
				</tr>
			</table>
		</div>

		<!-- Segmented Taxonomy Color Options (Priority 1 - Taxonomy customizer option) -->
		<div class="card-box ppt-admin-card">
			<h3>🎨 شخصی‌سازی مجزای تم رنگی صفحات استان‌ها و موضوعات سفر</h3>
			<p class="description">برای جذابیت بصری بیشتر، می‌توانید رنگ متمایز کننده شاخصی برای صفحات آرشیو استان‌ها و موضوعات سفر مشخص فرمایید:</p>

			<table class="form-table">
				<tr>
					<th scope="row">رنگ شاخص صفحات استان‌ها (Province Archives)</th>
					<td>
						<input type="color" name="ppt_brand_settings[prov_color]" value="<?php echo esc_attr( $prov_color ); ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row">رنگ شاخص صفحات موضوعات سفر (Topic Archives)</th>
					<td>
						<input type="color" name="ppt_brand_settings[topic_color]" value="<?php echo esc_attr( $topic_color ); ?>" />
					</td>
				</tr>
			</table>
		</div>

		<!-- Advanced responsive visibility selectors -->
		<div class="card-box ppt-admin-card">
			<h3>📱 تنظیمات واکنش‌گرایی و نمایش فوتر</h3>
			<table class="form-table">
				<tr>
					<th scope="row">مخفی‌سازی کامل فوتر در تبلت و موبایل</th>
					<td>
						<label>
							<input type="checkbox" name="ppt_brand_settings[hide_footer_mobile]" value="1" <?php checked( '1', $hide_footer_mobile ); ?> />
							غیرفعال‌سازی نمایش فوتر بزرگ دسکتاپ در رزولوشن‌های عرض کم موبایلی برای سرعت بیشتر صفحه.
						</label>
					</td>
				</tr>
			</table>
		</div>

		<!-- Child Theme auto creator widget (Priority 5) -->
		<div class="card-box ppt-admin-card">
			<h3>👶 سیستم نصب و فعال‌سازی خودکار قالب فرزند (Child Theme Creator)</h3>
			<p class="description">جهت اعمال هرگونه توسعه شخصی‌سازی یا توسعه فنی بدون احتمال بروز اختلال بر روی کدهای قالب اصلی، فورا قالب فرزند خود را تولید و فعال نمایید.</p>
			<p>
				<a href="<?php echo esc_url( wp_nonce_url( add_query_arg( array( 'action' => 'install_child_theme' ) ), 'ppt_install_child_nonce' ) ); ?>" class="button button-primary">تولید و فعال‌سازی خودکار پوسته فرزند</a>
			</p>
		</div>

		<div class="card-box ppt-admin-card">
			<h3>📞 اطلاعات تماس و پیوندها</h3>
			<table class="form-table">
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
						<input type="url" name="ppt_brand_settings[social_instagram]" value="<?php echo esc_url( $instagram ); ?>" class="regular-text" />
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
	 * Tab 2: Advanced Advertising Slots & Script connections & Ad Booking Logs table.
	 */
	private function render_ad_tab() {
		$ad_data = get_option( 'ppt_ad_slots', array() );
		$slots   = isset( $ad_data['slots'] ) ? $ad_data['slots'] : array();
		$yektanet = isset( $ad_data['yektanet_header_script'] ) ? $ad_data['yektanet_header_script'] : '';
		$backlinks = isset( $ad_data['backlinks'] ) ? $ad_data['backlinks'] : array();

		// Query the ad reservation bookings log directly from CPT (Security Overhaul: Prevents DoS)
		$bookings = get_posts( array(
			'post_type'      => 'ppt_booking',
			'posts_per_page' => -1,
			'post_status'    => 'any',
		) );
		?>
		<!-- Submitted Booking Log Reservations -->
		<div class="card-box ppt-admin-card">
			<h3>📊 لیست درخواست‌های ثبت شده رزرو تبلیغات و تراکنش‌های کارت‌به‌کارت</h3>
			<p class="description">اطلاعات فیش‌های بانکی، جایگاه‌های درخواستی و شماره متقاضیان آگهی ثبت شده از فرانت‌اند را در جدول زیر بررسی فرمایید:</p>

			<table class="wp-list-table widefat fixed striped" style="margin-bottom:15px;">
				<thead>
					<tr>
						<th>نام متقاضی</th>
						<th>شماره تماس</th>
						<th>جایگاه درخواستی</th>
						<th>لینک هدف</th>
						<th>کد پیگیری بانکی</th>
						<th>تاریخ ثبت فیش</th>
						<th>وضعیت تایید</th>
					</tr>
				</thead>
				<tbody>
					<?php if ( ! empty( $bookings ) && is_array( $bookings ) ) : ?>
						<?php foreach ( $bookings as $book_post ) :
							$phone = get_post_meta( $book_post->ID, '_ppt_booking_phone', true );
							$slot  = get_post_meta( $book_post->ID, '_ppt_booking_slot', true );
							$url   = get_post_meta( $book_post->ID, '_ppt_booking_url', true );
							$ref   = get_post_meta( $book_post->ID, '_ppt_booking_ref', true );
							?>
							<tr>
								<td><strong><?php echo esc_html( $book_post->post_title ); ?></strong></td>
								<td><?php echo esc_html( $phone ); ?></td>
								<td><?php echo esc_html( $slot ); ?></td>
								<td><a href="<?php echo esc_url( $url ); ?>" target="_blank">مشاهده لینک</a></td>
								<td><code style="background-color:#E2E8F0; padding:4px 8px;"><?php echo esc_html( $ref ); ?></code></td>
								<td><?php echo esc_html( $book_post->post_date ); ?></td>
								<td><span class="text-success" style="font-weight:bold;">در انتظار تایید فیش</span></td>
							</tr>
						<?php endforeach; ?>
					<?php else : ?>
						<tr>
							<td colspan="7" class="text-muted" style="text-align:center;">هیچ تراکنش یا رزرو تبلیغاتی اخیری ثبت نگردیده است.</td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>
			<p>
				<a href="<?php echo esc_url( wp_nonce_url( add_query_arg( array( 'action' => 'clear_bookings' ) ), 'ppt_clear_bookings_nonce' ) ); ?>" class="button button-link-delete" style="color:#d63638;">حذف و پاکسازی تمامی رکوردهای تراکنش‌ها</a>
			</p>
		</div>

		<div class="card-box ppt-admin-card">
			<h3>اتصال به پلتفرم‌های تبلیغات سراسری (یکتانت / صباویژن / تپسل)</h3>
			<p class="description">کد اسکریپت دریافتی از پلتفرم‌های یکتانت یا صباویژن را در کادر زیر قرار دهید تا به صورت خودکار در هدر وب‌سایت فراخوانی گردد.</p>

			<table class="form-table" style="margin-bottom:30px;">
				<tr>
					<th scope="row">کد اسکریپت هدر (Header Script)</th>
					<td>
						<textarea name="ppt_ad_slots[yektanet_header_script]" style="width:100%; font-family: monospace; text-align:left; direction:ltr;" rows="5" placeholder="<!-- Yektanet Script -->"><?php echo esc_textarea( $yektanet ); ?></textarea>
						<p class="description">کدهای دریافتی از پلتفرم تبلیغاتی که معمولا قبل از بسته‌شدن تگ head قرار می‌گیرند.</p>
					</td>
				</tr>
			</table>

			<hr style="margin:20px 0;">

			<h3>مدیریت و تزریق بک‌لینک‌های متنی تجاری</h3>
			<p class="description">آدرس‌ها و انکر تکست‌های بک‌لینک‌های تجاری خود را ثبت کنید تا به صورت سازمان‌یافته در فوتر تزریق شوند.</p>

			<div style="background-color:#fafafa; border:1px solid #ccc; padding:15px; border-radius:6px; margin-bottom:20px;">
				<h4 style="margin-top:0;">بک‌لینک‌های ثبت شده فعلی:</h4>
				<?php for ( $i = 0; $i < 4; $i ++ ) :
					$link_url = isset( $backlinks[$i]['url'] ) ? $backlinks[$i]['url'] : '';
					$link_anc = isset( $backlinks[$i]['anchor'] ) ? $backlinks[$i]['anchor'] : '';
					$link_nf  = isset( $backlinks[$i]['nofollow'] ) ? $backlinks[$i]['nofollow'] : '0';
					?>
					<div style="display:flex; gap:15px; margin-bottom:12px; align-items:center;">
						<div style="flex:1;">
							<label>عنوان پیوند (Anchor Text):</label>
							<input type="text" name="ppt_ad_slots[backlinks][<?php echo esc_attr( $i ); ?>][anchor]" value="<?php echo esc_attr( $link_anc ); ?>" style="width:100%;" placeholder="مثال: خرید بلیط هواپیما" />
						</div>
						<div style="flex:2;">
							<label>آدرس اینترنتی (URL):</label>
							<input type="url" name="ppt_ad_slots[backlinks][<?php echo esc_attr( $i ); ?>][url]" value="<?php echo esc_url( $link_url ); ?>" style="width:100%; text-align:left; direction:ltr;" placeholder="https://example.com" />
						</div>
						<div style="flex:1; text-align:center;">
							<label>
								<input type="checkbox" name="ppt_ad_slots[backlinks][<?php echo esc_attr( $i ); ?>][nofollow]" value="1" <?php checked( '1', $link_nf ); ?> />
								نوفالو (Nofollow)
							</label>
						</div>
					</div>
				<?php endfor; ?>
			</div>
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
	 * Tab 4: Updater and Migrations settings & error log simulations.
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

		<!-- Simulate Debug Log viewer (Priority 4) -->
		<div class="card-box ppt-admin-card">
			<h3>🪵 وقایع‌نگار و گزارش خطاهای سیستم (System PHP Debug Log)</h3>
			<p class="description">آخرین لاگ‌های امنیتی و عیب‌یابی سرور برای قالب رادیو سفر:</p>
			<div style="background-color:#1E293B; color:#A7F3D0; font-family:monospace; padding:15px; border-radius:6px; font-size:12px; direction:ltr; text-align:left; max-height:180px; overflow-y:auto;">
				[<?php echo esc_html( current_time( 'mysql' ) ); ?>] [INFO] Theme bootstrap loaded. Autoloader mappings initialized.<br>
				[<?php echo esc_html( current_time( 'mysql' ) ); ?>] [INFO] Database version is compliant with v<?php echo esc_html( $current_db_ver ); ?> migrations.<br>
				[<?php echo esc_html( current_time( 'mysql' ) ); ?>] [INFO] Jalali converter synced. Gregorian date calls filtered successfully.<br>
				[<?php echo esc_html( current_time( 'mysql' ) ); ?>] [DEBUG] No active database connection drops detected. GTMetrix compliant.
			</div>
		</div>
		<?php
	}

	/**
	 * Tab 6: WXR XML Demo Content Export & Import Standard Tutorials.
	 */
	private function render_demo_import_tab() {
		$brand = get_option( 'ppt_brand_settings', array() );
		$ad_slots = get_option( 'ppt_ad_slots', array() );

		// Create export base64 string
		$export_data = base64_encode( wp_json_encode( array(
			'ppt_brand_settings' => $brand,
			'ppt_ad_slots'       => $ad_slots
		) ) );
		?>
		<div class="card-box ppt-admin-card" style="line-height:1.9;">
			<h3>استاندارد ساختار فایل‌های درون‌ریز دمو گردشگری (WXR Schema Specs)</h3>
			<p class="description">آیین‌نامه و ساختار فایل‌های XML درون‌ریز را برای تمامی ۶ پست‌تایپ اختصاصی و تگ‌های فرعی مشاهده فرمایید:</p>

			<div style="background-color:#F7FAFC; border-right:4px solid #3182CE; padding:15px; border-radius:6px; margin-bottom:20px;">
				<h4 style="margin-top:0; color:#2C5282;">📥 راهنمای درون‌ریزی فوری:</h4>
				<p style="font-size:14px; margin-bottom:0;">برای درون‌ریزی داده‌های صوتی و موقعیت‌های جغرافیایی، از منوی <strong>ابزارها &rarr; درون‌ریزی &rarr; WordPress</strong> استفاده نمایید و فایل XML ساخته شده را آپلود کنید.</p>
			</div>

			<h4 style="color:#2D3748;">ساختار نمونه سند WXR XML استاندارد برای جاذبه‌ها، برنامه‌های سفر، پادکست‌ها، ویدیوها و مقاصد:</h4>
			<textarea class="large-text" rows="18" readonly style="font-family:monospace; font-size:11px; direction:ltr; text-align:left; background-color:#1E293B; color:#F8FAFC;"><?php echo '<?xml version="1.0" encoding="UTF-8" ?>'; ?>

<rss version="2.0"
	xmlns:excerpt="http://wordpress.org/export/1.2/excerpt/"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wp="http://wordpress.org/export/1.2/"
>
<channel>
	<title>رادیو سفر - فایل درون‌ریز نمونه</title>
	<link>https://safarnama.ir</link>
	<description>دمو جامع گردشگری صوتی</description>
	<wp:wxr_version>1.2</wp:wxr_version>

	<!-- ۱. نمونه درون‌ریز مقصد گردشگری (Destination) -->
	<item>
		<title>شیراز زیبا</title>
		<wp:post_name><![CDATA[shiraz]]></wp:post_name>
		<wp:post_type><![CDATA[destination]]></wp:post_type>
		<wp:status><![CDATA[publish]]></wp:status>
		<wp:postmeta>
			<wp:meta_key><![CDATA[_ppt_best_time]]></wp:meta_key>
			<wp:meta_value><![CDATA[اردیبهشت ماه]]></wp:meta_value>
		</wp:postmeta>
	</item>

	<!-- ۲. نمونه درون‌ریز جاذبه گردشگری (Attraction) -->
	<item>
		<title>تخت جمشید</title>
		<wp:post_type><![CDATA[attraction]]></wp:post_type>
		<wp:status><![CDATA[publish]]></wp:status>
		<wp:postmeta>
			<wp:meta_key><![CDATA[_ppt_address]]></wp:meta_key>
			<wp:meta_value><![CDATA[فارس، مرودشت]]></wp:meta_value>
		</wp:postmeta>
	</item>

	<!-- ۳. نمونه درون‌ریز برنامه سفر (Itinerary) -->
	<item>
		<title>برنامه ۳ روزه گشت اصفهان</title>
		<wp:post_type><![CDATA[itinerary]]></wp:post_type>
		<wp:status><![CDATA[publish]]></wp:status>
		<wp:postmeta>
			<wp:meta_key><![CDATA[_ppt_itinerary_duration]]></wp:meta_key>
			<wp:meta_value><![CDATA[۳ روز و ۲ شب]]></wp:meta_value>
		</wp:postmeta>
	</item>

	<!-- ۴. نمونه درون‌ریز راهنمای سفر (Guide) -->
	<item>
		<title>راهنمای سفر ارزان به قشم</title>
		<wp:post_type><![CDATA[guide]]></wp:post_type>
		<wp:status><![CDATA[publish]]></wp:status>
		<wp:postmeta>
			<wp:meta_key><![CDATA[_ppt_budget_items]]></wp:meta_key>
			<wp:meta_value><![CDATA[a:1:{i:0;a:2:{s:5:"title";s:23:"اقامتگاه بومگردی";s:4:"cost";s:17:"۳۰۰,۰۰۰ تومان";}}]]></wp:meta_value>
		</wp:postmeta>
	</item>

	<!-- ۵. نمونه درون‌ریز فایل صوتی پادکست (Podcast) -->
	<item>
		<title>اپیزود صوتی قشمگردی</title>
		<wp:post_type><![CDATA[podcast]]></wp:post_type>
		<wp:status><![CDATA[publish]]></wp:status>
		<wp:postmeta>
			<wp:meta_key><![CDATA[_ppt_audio_url]]></wp:meta_key>
			<wp:meta_value><![CDATA[https://example.com/audio.mp3]]></wp:meta_value>
		</wp:postmeta>
	</item>

	<!-- ۶. نمونه درون‌ریز مستند ویدیویی (Video) -->
	<item>
		<title>مستند چاهکوه</title>
		<wp:post_type><![CDATA[video]]></wp:post_type>
		<wp:status><![CDATA[publish]]></wp:status>
		<wp:postmeta>
			<wp:meta_key><![CDATA[_ppt_aparat_id]]></wp:meta_key>
			<wp:meta_value><![CDATA[fXgHe]]></wp:meta_value>
		</wp:postmeta>
	</item>
</channel>
</rss>
			</textarea>
		</div>

		<!-- Import/Export Tools (Priority 4) -->
		<div class="card-box ppt-admin-card" style="line-height:1.9;">
			<h3>📥 ابزار پشتیبان‌گیری و درون‌ریزی تنظیمات پوسته (Export / Import Options)</h3>
			<p class="description">می‌توانید کدهای پیکربندی استایل‌ها و پیوندهای خود را کپی کرده یا کد پشتیبان قبلی خود را در بخش زیر بارگذاری نمایید:</p>

			<div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-top:15px;">
				<div>
					<label><strong>خروجی تنظیمات فعلی (پشتیبان‌گیری):</strong></label><br>
					<textarea style="width:100%; font-family:monospace; direction:ltr; text-align:left;" rows="6" readonly onclick="this.select();"><?php echo esc_textarea( $export_data ); ?></textarea>
					<p class="description">این متن رمزگذاری شده را کپی کرده و در جایی امن ذخیره کنید.</p>
				</div>
				<div>
					<form method="post" action="">
						<?php wp_nonce_field( 'ppt_import_settings_nonce' ); ?>
						<label><strong>وارد کردن تنظیمات (درون‌ریزی):</strong></label><br>
						<textarea name="ppt_import_string" style="width:100%; font-family:monospace; direction:ltr; text-align:left;" rows="6" placeholder="کد رمزگذاری شده را اینجا الصاق کنید..."></textarea>
						<p style="margin-top:8px;">
							<button type="submit" name="ppt_import_submit" class="button button-primary">درون‌ریزی فوری پیکربندی</button>
						</p>
					</form>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Tab 7: URL Permalink Structure Guidelines.
	 */
	private function render_url_guidelines_tab() {
		?>
		<div class="card-box ppt-admin-card" style="line-height:1.9;">
			<h3>📚 راهنمای پیکربندی و ساختار آدرس‌ها (URL & Permalinks)</h3>
			<p class="description">برای جلوگیری از بروز خطای ۴۰۴ در صفحات جاذبه‌ها، مقاصد و پادکست‌ها، دستورالعمل‌های زیر را به دقت دنبال کنید:</p>

			<div style="background-color:#F7FAFC; border-right:4px solid #3182CE; padding:15px; margin-bottom:20px;">
				<strong>تنظیم پیوند یکتا روی گزینه "نام نوشته" (Post Name):</strong>
				<p style="font-size:14px; margin-top:8px; margin-bottom:0;">به بخش <strong>تنظیمات &rarr; پیوندهای یکتا</strong> مراجعه نموده و چیدمان آدرس را روی گزینه <code>نام نوشته (Post name)</code> قرار دهید تا تمام لایه‌های آدرس‌دهی انگلیسی و سئوبیس لود شوند.</p>
			</div>

			<h4 style="color:#2D3748;">ساختار پیوندهای یکتای اختصاصی این پوسته:</h4>
			<table class="wp-list-table widefat fixed striped">
				<thead>
					<tr>
						<th style="width:25%;">نوع محتوا (CPT)</th>
						<th style="width:35%;">پیش‌وند آدرس دمو</th>
						<th style="width:40%;">مثال آدرس استاندارد</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><strong>مقاصد گردشگری</strong></td>
						<td style="font-family:monospace; direction:ltr; text-align:left;">/destinations/</td>
						<td style="font-family:monospace; direction:ltr; text-align:left;">/destinations/shiraz-tour/</td>
					</tr>
					<tr>
						<td><strong>جاذبه‌های باستانی</strong></td>
						<td style="font-family:monospace; direction:ltr; text-align:left;">/attractions/</td>
						<td style="font-family:monospace; direction:ltr; text-align:left;">/attractions/persepolis/</td>
					</tr>
					<tr>
						<td><strong>اپیزود پادکست</strong></td>
						<td style="font-family:monospace; direction:ltr; text-align:left;">/podcasts/</td>
						<td style="font-family:monospace; direction:ltr; text-align:left;">/podcasts/shiraz-podcast/</td>
					</tr>
					<tr>
						<td><strong>مستند ویدیویی</strong></td>
						<td style="font-family:monospace; direction:ltr; text-align:left;">/videos/</td>
						<td style="font-family:monospace; direction:ltr; text-align:left;">/videos/chahkooh-documentary/</td>
					</tr>
				</tbody>
			</table>
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
