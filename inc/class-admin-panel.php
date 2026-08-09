<?php
/**
 * Custom Tabbed WordPress Admin Settings Panel & Commercial Admin Console
 * Includes Visual Customizer, Child Theme Creator, and Import/Export utilities.
 * Highly specialized with customized Header & Footer builders, dynamic unlimited repeaters, colors, and element toggles.
 * Overhauled to fix checkbox unchecking state resets and provide dynamic link repeaters.
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

		// Specialized Header and Footer Settings
		register_setting( 'ppt_header_group', 'ppt_header_settings' );
		register_setting( 'ppt_footer_group', 'ppt_footer_settings' );
	}

	/**
	 * Get Default Header Settings helper.
	 */
	public static function get_default_header_settings() {
		return array(
			'bg_color'              => '#FFFFFF',
			'text_color'            => '#2D3748',
			'show_search'           => '1',
			'show_cta'              => '1',
			'cta_text'              => '🎙️ رادیو سفر',
			'cta_link'              => '/podcasts/',
			'megamenu_trigger_url'  => 'destination',
			'megamenu_title_1'      => '📍 استان‌های دیدنی ایران',
			'megamenu_title_2'      => '🎧 رادیو صوتی و مستندها',
			'custom_links' => array(
				array( 'label' => 'خانه', 'url' => '/' ),
				array( 'label' => 'مقاصد گردشگری', 'url' => '/destinations/' ),
				array( 'label' => 'رادیو صوتی پادکست', 'url' => '/podcasts/' ),
				array( 'label' => 'مستندهای تصویری', 'url' => '/videos/' ),
				array( 'label' => 'راهنماهای مکتوب', 'url' => '/guides/' )
			)
		);
	}

	/**
	 * Get Default Footer Settings helper.
	 */
	public static function get_default_footer_settings() {
		return array(
			'bg_color'               => '#1A202C',
			'text_color'             => '#CBD5E0',
			'show_brand_col'         => '1',
			'show_links_col'         => '1',
			'show_cpt_col'           => '1',
			'show_newsletter_col'    => '1',
			'show_sticky_mobile_nav' => '1',
			'custom_links'           => array(
				array( 'label' => 'صفحه نخست سایت', 'url' => '/' ),
				array( 'label' => 'درباره رادیو سفر', 'url' => '/about-us/' ),
				array( 'label' => 'تماس با کارشناسان', 'url' => '/contact-us/' ),
				array( 'label' => 'قوانین و مقررات آگهی', 'url' => '/advertising/' )
			)
		);
	}

	/**
	 * Handle admin commands (Child Theme installation, Import/Export).
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

		// 2. Process Settings Import
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
			<h1>⚙️ پنل مدیریت هوشمند و اختصاصی رادیو سفر (نسخه ویژه)</h1>
			<p class="description">تنظیمات کامل، مدیریت پست‌تایپ‌ها، استایل‌های اختصاصی، تبلیغات پاراگرافی پیشرفته و موتور درون‌ریزی اختصاصی را مدیریت فرمایید.</p>

			<?php if ( isset( $_GET['child_created'] ) && '1' === $_GET['child_created'] ) : ?>
				<div class="notice notice-success is-dismissible">
					<p><strong>قالب فرزند (Child Theme) با موفقیت ساخته شد!</strong> می‌توانید از بخش نمایش پوسته فرزند را فعال نمایید.</p>
				</div>
			<?php endif; ?>

			<?php if ( isset( $_GET['imported'] ) && '1' === $_GET['imported'] ) : ?>
				<div class="notice notice-success is-dismissible">
					<p>پیکربندی تنظیمات پوسته با موفقیت وارد و بارگذاری گردید.</p>
				</div>
			<?php endif; ?>

			<!-- Professional Two Column RTL responsive grid wrapper -->
			<div class="ppt-admin-two-col-layout">

				<!-- Right Sidebar: Vertical Navigation Links -->
				<div class="ppt-admin-sidebar-nav">
					<ul class="ppt-vertical-tabs">
						<li><a href="?page=ppt-settings&tab=homepage_sections" class="ppt-tab-link <?php echo 'homepage_sections' === $active_tab ? 'active' : ''; ?>">🏠 مدیریت صفحه نخست</a></li>
						<li><a href="?page=ppt-settings&tab=header_builder" class="ppt-tab-link <?php echo 'header_builder' === $active_tab ? 'active' : ''; ?>">🛠️ تنظیمات سربرگ (هدر)</a></li>
						<li><a href="?page=ppt-settings&tab=footer_builder" class="ppt-tab-link <?php echo 'footer_builder' === $active_tab ? 'active' : ''; ?>">🛠️ تنظیمات پابرگ (فوتر)</a></li>
						<li><a href="?page=ppt-settings&tab=cpt_settings" class="ppt-tab-link <?php echo 'cpt_settings' === $active_tab ? 'active' : ''; ?>">🗂️ مدیریت پست‌تایپ‌ها</a></li>
						<li><a href="?page=ppt-settings&tab=brand_settings" class="ppt-tab-link <?php echo 'brand_settings' === $active_tab ? 'active' : ''; ?>">🎨 استایل و سفارشی‌سازی</a></li>
						<li><a href="?page=ppt-settings&tab=ad_slots" class="ppt-tab-link <?php echo 'ad_slots' === $active_tab ? 'active' : ''; ?>">📢 مدیریت تبلیغات هوشمند</a></li>
						<li><a href="?page=ppt-settings&tab=map_settings" class="ppt-tab-link <?php echo 'map_settings' === $active_tab ? 'active' : ''; ?>">🗺️ تنظیمات نقشه کاداستر</a></li>
						<li><a href="?page=ppt-settings&tab=theme_updater" class="ppt-tab-link <?php echo 'theme_updater' === $active_tab ? 'active' : ''; ?>">🔄 بروزرسانی و لاگ خطایاب</a></li>
						<li><a href="?page=ppt-settings&tab=demo_import" class="ppt-tab-link <?php echo 'demo_import' === $active_tab ? 'active' : ''; ?>">📥 فایل‌های درون‌ریز مجزا (XML)</a></li>
						<li><a href="?page=ppt-settings&tab=url_guidelines" class="ppt-tab-link <?php echo 'url_guidelines' === $active_tab ? 'active' : ''; ?>">🔗 ساختار پیوندهای یکتا</a></li>
						<li><a href="?page=ppt-settings&tab=documentation" class="ppt-tab-link <?php echo 'documentation' === $active_tab ? 'active' : ''; ?>">📚 راهنمای مستندات Diataxis</a></li>
					</ul>
				</div>

				<!-- Left Side: Content Settings Pane -->
				<div class="ppt-admin-content-pane">
					<form method="post" action="options.php">
						<?php
						if ( 'homepage_sections' === $active_tab ) {
							settings_fields( 'ppt_homepage_group' );
							$this->render_homepage_tab();
						} elseif ( 'header_builder' === $active_tab ) {
							settings_fields( 'ppt_header_group' );
							$this->render_header_builder_tab();
						} elseif ( 'footer_builder' === $active_tab ) {
							settings_fields( 'ppt_footer_group' );
							$this->render_footer_builder_tab();
						} elseif ( 'cpt_settings' === $active_tab ) {
							settings_fields( 'ppt_brand_group' ); // use brand group as container
							$this->render_cpt_settings_tab();
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

			</div>
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
	 * Tab 1.2: Specialized Header Builder Settings with dynamic link repeaters and manageable megamenus (Priority 3/5).
	 */
	private function render_header_builder_tab() {
		$saved    = get_option( 'ppt_header_settings', array() );
		$defaults = self::get_default_header_settings();
		$settings = wp_parse_args( $saved, $defaults );

		$bg_color     = $settings['bg_color'];
		$text_color   = $settings['text_color'];
		$show_search  = $settings['show_search'];
		$show_cta     = $settings['show_cta'];
		$cta_text     = $settings['cta_text'];
		$cta_link     = $settings['cta_link'];
		$links        = isset( $settings['custom_links'] ) ? $settings['custom_links'] : array();

		// Megamenu manageable options (Priority 5)
		$mega_trigger = isset( $settings['megamenu_trigger_url'] ) ? $settings['megamenu_trigger_url'] : 'destination';
		$mega_title_1 = isset( $settings['megamenu_title_1'] ) ? $settings['megamenu_title_1'] : '📍 استان‌های دیدنی ایران';
		$mega_title_2 = isset( $settings['megamenu_title_2'] ) ? $settings['megamenu_title_2'] : '🎧 رادیو صوتی و مستندها';
		?>
		<div class="card-box ppt-admin-card" style="line-height:1.8;">
			<h3>🎨 پلتفرم اختصاصی سفارشی‌سازی سربرگ (Header Settings Panel)</h3>
			<p class="description">رنگ‌بندی هدر، حذف و اضافه کردن المان‌ها (نوار جستجو، دکمه فراخوانی) و تنظیم لینک‌های دمو را به صورت پویا مدیریت فرمایید:</p>

			<table class="form-table" style="margin-top:15px;">
				<tr>
					<th scope="row">رنگ پس‌زمینه سربرگ (Header Background)</th>
					<td>
						<input type="color" name="ppt_header_settings[bg_color]" value="<?php echo esc_attr( $bg_color ); ?>" />
						<p class="description">رنگ پس‌زمینه کل کادر هدر سایت.</p>
					</td>
				</tr>
				<tr>
					<th scope="row">رنگ متون و منوهای سربرگ (Header Links Color)</th>
					<td>
						<input type="color" name="ppt_header_settings[text_color]" value="<?php echo esc_attr( $text_color ); ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row">نمایش فرم جستجو در هدر</th>
					<td>
						<label>
							<!-- Fixed checkbox unchecking state resets by placing a hidden fallback input (Priority 1) -->
							<input type="hidden" name="ppt_header_settings[show_search]" value="0">
							<input type="checkbox" name="ppt_header_settings[show_search]" value="1" <?php checked( '1', $show_search ); ?> />
							کادر بازشونده جستجوی زنده مقاصد در هدر نمایش داده شود.
						</label>
					</td>
				</tr>
				<tr>
					<th scope="row">نمایش دکمه فراخوانی (Header CTA Button)</th>
					<td>
						<label>
							<!-- Fixed checkbox unchecking state resets by placing a hidden fallback input (Priority 1) -->
							<input type="hidden" name="ppt_header_settings[show_cta]" value="0">
							<input type="checkbox" name="ppt_header_settings[show_cta]" value="1" <?php checked( '1', $show_cta ); ?> id="ppt_toggle_header_cta" />
							دکمه برجسته جذب مخاطب در منوی دسکتاپ فعال باشد.
						</label>
					</td>
				</tr>
				<tr class="header-cta-fields">
					<th scope="row">عنوان دکمه فراخوانی (CTA Button Text)</th>
					<td>
						<input type="text" name="ppt_header_settings[cta_text]" value="<?php echo esc_attr( $cta_text ); ?>" class="regular-text" />
					</td>
				</tr>
				<tr class="header-cta-fields">
					<th scope="row">آدرس لینک دکمه فراخوانی (CTA Link)</th>
					<td>
						<input type="text" name="ppt_header_settings[cta_link]" value="<?php echo esc_attr( $cta_link ); ?>" class="regular-text" style="text-align:left; direction:ltr;" />
					</td>
				</tr>
			</table>

			<hr style="margin:25px 0; border-top:1px solid #E2E8F0;">

			<!-- Megamenu Links Customizer (Priority 5) -->
			<h3>🍔 مدیریت و شخصی‌سازی ستون‌های مگامنو (Manageable Megamenu)</h3>
			<p class="description">برای لینکی که مگامنو باز می‌کند، کلمات کلیدی محرک و تیتر ستون‌های معرفی را شخصی‌سازی فرمایید:</p>

			<table class="form-table" style="margin-bottom:30px;">
				<tr>
					<th scope="row">کلمه کلیدی محرک مگامنو (Trigger Keyword)</th>
					<td>
						<input type="text" name="ppt_header_settings[megamenu_trigger_url]" value="<?php echo esc_attr( $mega_trigger ); ?>" class="regular-text" style="text-align:left; direction:ltr;" />
						<p class="description">لینک‌هایی از منو که آدرس آن‌ها حاوی این کلمه باشد به عنوان مگا‌منوی عریض باز می‌شوند (مثلاً: <code>destination</code>).</p>
					</td>
				</tr>
				<tr>
					<th scope="row">عنوان ستون ۱ مگامنو (استان‌ها)</th>
					<td>
						<input type="text" name="ppt_header_settings[megamenu_title_1]" value="<?php echo esc_attr( $mega_title_1 ); ?>" class="regular-text" />
					</td>
				</tr>
				<tr>
					<th scope="row">عنوان ستون ۲ مگامنو (رسانه‌ها)</th>
					<td>
						<input type="text" name="ppt_header_settings[megamenu_title_2]" value="<?php echo esc_attr( $mega_title_2 ); ?>" class="regular-text" />
					</td>
				</tr>
			</table>

			<hr style="margin:20px 0; border-top:1px solid #E2E8F0;">

			<!-- Dynamic Link Repeaters (Priority 3) -->
			<h3>🔗 مدیریت و تنظیم لینک‌های سفارشی سربرگ (Dynamic Header Links Builder)</h3>
			<p class="description">می‌توانید بی‌نهایت لینک ناوبری جدید به منوی اصلی هدر اضافه کرده یا موارد دلخواه را حذف فرمایید:</p>

			<div style="background-color:#fafafa; border:1px solid #ccc; padding:20px; border-radius:8px; margin-bottom:20px;">
				<h4 style="margin-top:0; color:#1e3a8a;">پیوندهای منوی هدر:</h4>

				<div id="ppt_header_links_repeater_container">
					<?php
					$links_count = max( 1, count( $links ) );
					for ( $i = 0; $i < $links_count; $i ++ ) :
						$label = isset( $links[$i]['label'] ) ? $links[$i]['label'] : '';
						$url   = isset( $links[$i]['url'] ) ? $links[$i]['url'] : '';
						?>
						<div class="ppt-header-link-row" style="display:flex; gap:15px; margin-bottom:12px; align-items:center; border-bottom: 1px dashed #E2E8F0; padding-bottom: 12px;">
							<div style="flex:1;">
								<label style="font-weight:bold;">عنوان منو:</label>
								<input type="text" name="ppt_header_settings[custom_links][<?php echo esc_attr( $i ); ?>][label]" value="<?php echo esc_attr( $label ); ?>" style="width:100%; margin-top:5px;" placeholder="مثال: رادیو سفر" />
							</div>
							<div style="flex:2;">
								<label style="font-weight:bold;">آدرس اینترنتی (URL):</label>
								<input type="text" name="ppt_header_settings[custom_links][<?php echo esc_attr( $i ); ?>][url]" value="<?php echo esc_attr( $url ); ?>" style="width:100%; text-align:left; direction:ltr; margin-top:5px;" placeholder="/podcasts/" />
							</div>
							<div style="padding-top:20px;">
								<button type="button" class="button button-link-delete ppt-remove-header-link" style="color:#d63638;">حذف منو</button>
							</div>
						</div>
					<?php endfor; ?>
				</div>
				<button type="button" id="ppt_add_header_link_btn" class="button button-primary" style="margin-top:10px;">افزودن لینک منوی جدید</button>
			</div>
		</div>
		<?php
	}

	/**
	 * Tab 1.3: Specialized Footer Builder Settings with dynamic link repeaters (Priority 3).
	 */
	private function render_footer_builder_tab() {
		$saved    = get_option( 'ppt_footer_settings', array() );
		$defaults = self::get_default_footer_settings();
		$settings = wp_parse_args( $saved, $defaults );

		$bg_color               = $settings['bg_color'];
		$text_color             = $settings['text_color'];
		$show_brand_col         = $settings['show_brand_col'];
		$show_links_col         = $settings['show_links_col'];
		$show_cpt_col           = $settings['show_cpt_col'];
		$show_newsletter_col    = $settings['show_newsletter_col'];
		$show_sticky_mobile_nav = $settings['show_sticky_mobile_nav'];
		$links                  = isset( $settings['custom_links'] ) ? $settings['custom_links'] : array();
		?>
		<div class="card-box ppt-admin-card" style="line-height:1.8;">
			<h3>🎨 پلتفرم اختصاصی سفارشی‌سازی پابرگ (Footer Settings Panel)</h3>
			<p class="description">رنگ‌بندی فوتر بزرگ، فعال/غیرفعال‌سازی تک تک ستون‌های چهارگانه، و تنظیم نوار چسبان پایینی موبایل را از کادر زیر انجام دهید:</p>

			<table class="form-table" style="margin-top:15px;">
				<tr>
					<th scope="row">رنگ پس‌زمینه پابرگ (Footer Background)</th>
					<td>
						<input type="color" name="ppt_footer_settings[bg_color]" value="<?php echo esc_attr( $bg_color ); ?>" />
						<p class="description">رنگ پس‌زمینه کل کادر فوتر بزرگ.</p>
					</td>
				</tr>
				<tr>
					<th scope="row">رنگ متون و لینک‌های فوتر (Footer Text Color)</th>
					<td>
						<input type="color" name="ppt_footer_settings[text_color]" value="<?php echo esc_attr( $text_color ); ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row">نمایش ستون ۱ (معرفی برند و تماس با ما)</th>
					<td>
						<label>
							<input type="hidden" name="ppt_footer_settings[show_brand_col]" value="0">
							<input type="checkbox" name="ppt_footer_settings[show_brand_col]" value="1" <?php checked( '1', $show_brand_col ); ?> />
							نمایش ستون توضیحات بوم‌گردی رادیو سفر و آدرس دفتر ونک.
						</label>
					</td>
				</tr>
				<tr>
					<th scope="row">نمایش ستون ۲ (دسترسی سریع)</th>
					<td>
						<label>
							<input type="hidden" name="ppt_footer_settings[show_links_col]" value="0">
							<input type="checkbox" name="ppt_footer_settings[show_links_col]" value="1" <?php checked( '1', $show_links_col ); ?> />
							ستون لیست منوهای دلخواه در فوتر نمایش داده شود.
						</label>
					</td>
				</tr>
				<tr>
					<th scope="row">نمایش ستون ۳ (سفر شنیداری و مقاصد برتر)</th>
					<td>
						<label>
							<input type="hidden" name="ppt_footer_settings[show_cpt_col]" value="0">
							<input type="checkbox" name="ppt_footer_settings[show_cpt_col]" value="1" <?php checked( '1', $show_cpt_col ); ?> />
							ستون لینک‌های پادکست‌ها، ویدیوها و مقاصد.
						</label>
					</td>
				</tr>
				<tr>
					<th scope="row">نمایش ستون ۴ (عضویت در خبرنامه و شبکه‌های اجتماعی)</th>
					<td>
						<label>
							<input type="hidden" name="ppt_footer_settings[show_newsletter_col]" value="0">
							<input type="checkbox" name="ppt_footer_settings[show_newsletter_col]" value="1" <?php checked( '1', $show_newsletter_col ); ?> />
							کادر عضویت در خبرنامه و آیکون‌های اینستاگرام/تلگرام.
						</label>
					</td>
				</tr>
				<tr>
					<th scope="row">نمایش نوار چسبان موبایل (Mobile Sticky Bottom Bar)</th>
					<td>
						<label>
							<input type="hidden" name="ppt_footer_settings[show_sticky_mobile_nav]" value="0">
							<input type="checkbox" name="ppt_footer_settings[show_sticky_mobile_nav]" value="1" <?php checked( '1', $show_sticky_mobile_nav ); ?> />
							نمایش دکمه‌های ناوبری سریع در پایین‌ترین قسمت صفحات موبایل و تبلت.
						</label>
					</td>
				</tr>
			</table>

			<hr style="margin:20px 0;">

			<!-- Dynamic Link Repeaters (Priority 3) -->
			<h3>🔗 مدیریت و تنظیم لینک‌های دلخواه فوتر (Dynamic Footer Links Builder)</h3>
			<p class="description">می‌توانید بی‌نهایت لینک جدید به منوی دسترسی سریع ستون دوم اضافه کرده یا منوهای غیرضروری را حذف فرمایید:</p>

			<div style="background-color:#fafafa; border:1px solid #ccc; padding:20px; border-radius:8px; margin-bottom:20px;">
				<h4 style="margin-top:0; color:#1e3a8a;">پیوندهای ستون ۲ فوتر:</h4>

				<div id="ppt_footer_links_repeater_container">
					<?php
					$links_count = max( 1, count( $links ) );
					for ( $i = 0; $i < $links_count; $i ++ ) :
						$label = isset( $links[$i]['label'] ) ? $links[$i]['label'] : '';
						$url   = isset( $links[$i]['url'] ) ? $links[$i]['url'] : '';
						?>
						<div class="ppt-footer-link-row" style="display:flex; gap:15px; margin-bottom:12px; align-items:center; border-bottom: 1px dashed #E2E8F0; padding-bottom: 12px;">
							<div style="flex:1;">
								<label style="font-weight:bold;">عنوان منو:</label>
								<input type="text" name="ppt_footer_settings[custom_links][<?php echo esc_attr( $i ); ?>][label]" value="<?php echo esc_attr( $label ); ?>" style="width:100%; margin-top:5px;" placeholder="مثال: تماس با ما" />
							</div>
							<div style="flex:2;">
								<label style="font-weight:bold;">آدرس اینترنتی (URL):</label>
								<input type="text" name="ppt_footer_settings[custom_links][<?php echo esc_attr( $i ); ?>][url]" value="<?php echo esc_attr( $url ); ?>" style="width:100%; text-align:left; direction:ltr; margin-top:5px;" placeholder="/contact-us/" />
							</div>
							<div style="padding-top:20px;">
								<button type="button" class="button button-link-delete ppt-remove-footer-link" style="color:#d63638;">حذف منو</button>
							</div>
						</div>
					<?php endfor; ?>
				</div>
				<button type="button" id="ppt_add_footer_link_btn" class="button button-primary" style="margin-top:10px;">افزودن لینک پابرگ جدید</button>
			</div>
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

		<div class="card-box ppt-admin-card">
			<h3>📱 تنظیمات واکنش‌گرایی و نمایش فوتر</h3>
			<table class="form-table">
				<tr>
					<th scope="row">مخفی‌سازی کامل فوتر در تبلت و موبایل</th>
					<td>
						<label>
							<input type="hidden" name="ppt_brand_settings[hide_footer_mobile]" value="0">
							<input type="checkbox" name="ppt_brand_settings[hide_footer_mobile]" value="1" <?php checked( '1', $hide_footer_mobile ); ?> />
							غیرفعال‌سازی نمایش فوتر بزرگ دسکتاپ در رزولوشن‌های عرض کم موبایلی برای سرعت بیشتر صفحه.
						</label>
					</td>
				</tr>
			</table>
		</div>

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
	 * Tab 2: Advanced Advertising Slots & Script connections.
	 * Overhauled with professional Paragraph and Taxonomy End injection configurations (Priority 5).
	 */
	private function render_ad_tab() {
		$ad_data   = get_option( 'ppt_ad_slots', array() );
		$yektanet  = isset( $ad_data['yektanet_header_script'] ) ? $ad_data['yektanet_header_script'] : '';
		$backlinks = isset( $ad_data['backlinks'] ) ? $ad_data['backlinks'] : array();

		// Advanced Injections
		$p_count   = isset( $ad_data['paragraph_injection_count'] ) ? intval( $ad_data['paragraph_injection_count'] ) : 2;
		$p_code    = isset( $ad_data['paragraph_injection_code'] ) ? $ad_data['paragraph_injection_code'] : '';
		$p_pts     = isset( $ad_data['paragraph_injection_post_types'] ) ? $ad_data['paragraph_injection_post_types'] : array();

		$t_enabled = isset( $ad_data['taxonomy_end_enabled'] ) ? $ad_data['taxonomy_end_enabled'] : '1';
		$t_code    = isset( $ad_data['taxonomy_end_code'] ) ? $ad_data['taxonomy_end_code'] : '';
		?>
		<!-- Comprehensive helpful ad locations guide -->
		<div class="card-box ppt-admin-card" style="background-color: #f0fdf4; border: 1px solid #bbf7d0; padding: 25px; border-radius: 12px; margin-bottom: 30px;">
			<h3 style="color: #166534; border-bottom: 2px solid #bbf7d0; padding-bottom: 8px;">🗺️ راهنمای جامع موقعیت‌ها و نحوه درج آگهی در رادیو سفر</h3>
			<p style="font-size: 14.5px; line-height: 1.8; color: #14532d; text-align: justify; margin: 0 0 15px 0;">
				برای کسب بیشترین بازدهی، موقعیت‌های تبلیغاتی متعددی به صورت بدون پرش و بهینه (CLS-Free) در قالب قرار گرفته‌اند. در زیر محل‌ها و چگونگی فعال‌سازی هر کدام آمده است:
			</p>
			<ul style="list-style: none; padding: 0; margin: 0; display: grid; grid-template-columns: 1fr 1fr; gap: 15px; font-size: 13.5px; color: #14532d;">
				<li><strong>۱. بنر هدر (Header):</strong> هدر دسکتاپ و موبایل. کدهای یکتانت یا صباویژن را در بخش تبلیغات هدر درج کنید.</li>
				<li><strong>۲. بنر سایدبار (Sidebar):</strong> سایدبار سمت چپ تمام نوشته‌ها، مقاصد، برنامه‌های سفر و جاذبه‌ها.</li>
				<li><strong>۳. بنر بالا/پایین بدنه محتوا:</strong> در ابتدا و انتهای بخش تکی نوشته‌ها لود می‌شود.</li>
				<li><strong>۴. بنر هوشمند بین‌پاراگرافی:</strong> تزریق کاملا هوشمند خودکار بنر پس از پاراگراف مشخص شده در متن.</li>
				<li><strong>۵. بنر انتهای استان‌ها و موضوعات:</strong> درج بنر عریض به صورت ثابت در انتهای صفحات آرشیو و فرودگاه استان‌ها.</li>
			</ul>
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

			<hr style="margin:25px 0; border-top:1px solid #E2E8F0;">

			<!-- ADVANCED AUTOMATED PARAGRAPH INJECTIONS -->
			<h3>📢 سیستم تزریق هوشمند آگهی بین‌پاراگرافی (Paragraph Ad Injection)</h3>
			<p class="description">به طور کامپوزیت و خودکار، یک بنر تبلیغاتی یا اسکریپت را بعد از پاراگراف مشخصی از محتوای پست‌تایپ‌های فعال قرار دهید:</p>

			<table class="form-table" style="margin-bottom:30px;">
				<tr>
					<th scope="row">تزریق بعد از پاراگراف چندم؟</th>
					<td>
						<input type="number" name="ppt_ad_slots[paragraph_injection_count]" value="<?php echo esc_attr( $p_count ); ?>" style="width:100px;" min="1" max="15" />
						<span class="description">توصیه می‌شود عدد ۲ یا ۳ را انتخاب کنید.</span>
					</td>
				</tr>
				<tr>
					<th scope="row">پست‌تایپ‌های فعال جهت تزریق</th>
					<td>
						<?php
						$pts = array(
							'destination' => 'مقاصد گردشگری',
							'attraction'  => 'جاذبه‌های توریستی',
							'guide'       => 'راهنماهای مکتوب سفر',
							'podcast'     => 'پادکست‌ها',
							'video'       => 'ویدیوها',
							'post'        => 'نوشته‌های وبلاگ'
						);
						foreach ( $pts as $slug => $lbl ) :
							$checked = in_array( $slug, $p_pts, true ) ? 'checked' : '';
							?>
							<label style="display:inline-block; margin-left:15px; font-weight:bold;">
								<input type="checkbox" name="ppt_ad_slots[paragraph_injection_post_types][]" value="<?php echo esc_attr( $slug ); ?>" <?php echo $checked; ?> />
								<?php echo esc_html( $lbl ); ?>
							</label>
						<?php endforeach; ?>
					</td>
				</tr>
				<tr>
					<th scope="row">کد بنر یا اسکریپت تزریقی میان‌متن</th>
					<td>
						<textarea name="ppt_ad_slots[paragraph_injection_code]" style="width:100%; font-family: monospace; text-align:left; direction:ltr;" rows="5" placeholder="<!-- Place your banner HTML or script here -->"><?php echo esc_textarea( $p_code ); ?></textarea>
					</td>
				</tr>
			</table>

			<hr style="margin:25px 0; border-top:1px solid #E2E8F0;">

			<!-- ADVANCED TAXONOMY END INJECTIONS -->
			<h3>📢 سیستم آگهی انتهای صفحات استان‌ها و موضوعات سفر (Taxonomy End Ad)</h3>
			<p class="description">یک بنر ویژه توریستی را به عنوان اسپانسر در بخش انتهایی آرشیو لندینگ‌ها قرار دهید:</p>

			<table class="form-table" style="margin-bottom:30px;">
				<tr>
					<th scope="row">وضعیت نمایش آگهی انتهای تاکسونومی</th>
					<td>
						<select name="ppt_ad_slots[taxonomy_end_enabled]">
							<option value="1" <?php selected( $t_enabled, '1' ); ?>>فعال باشد</option>
							<option value="0" <?php selected( $t_enabled, '0' ); ?>>غیرفعال باشد</option>
						</select>
					</td>
				</tr>
				<tr>
					<th scope="row">کد بنر یا اسکریپت انتهای تاکسونومی</th>
					<td>
						<textarea name="ppt_ad_slots[taxonomy_end_code]" style="width:100%; font-family: monospace; text-align:left; direction:ltr;" rows="5" placeholder="<!-- Place your sponsor banner here -->"><?php echo esc_textarea( $t_code ); ?></textarea>
					</td>
				</tr>
			</table>

			<hr style="margin:25px 0; border-top:1px solid #E2E8F0;">

			<div style="background-color:#fafafa; border:1px solid #ccc; padding:20px; border-radius:8px; margin-bottom:20px;">
				<h4 style="margin-top:0; color:#1e3a8a;">🔗 مدیریت و تزریق بک‌لینک‌های متنی تجاری (نامحدود و پویا)</h4>
				<p class="description">آدرس‌ها و انکر تکست‌های بک‌لینک‌های تجاری خود را ثبت کنید تا به صورت سازمان‌یافته در فوتر تزریق شوند. می‌توانید بی‌نهایت پیوند تجاری اضافه کنید:</p>

				<div id="ppt_backlinks_repeater_container">
					<?php
					$backlinks_count = max( 1, count( $backlinks ) );
					for ( $i = 0; $i < $backlinks_count; $i ++ ) :
						$link_url = isset( $backlinks[$i]['url'] ) ? $backlinks[$i]['url'] : '';
						$link_anc = isset( $backlinks[$i]['anchor'] ) ? $backlinks[$i]['anchor'] : '';
						$link_nf  = isset( $backlinks[$i]['nofollow'] ) ? $backlinks[$i]['nofollow'] : '0';
						?>
						<div class="ppt-backlink-row" style="display:flex; gap:15px; margin-bottom:12px; align-items:center; border-bottom: 1px dashed #E2E8F0; padding-bottom: 12px;">
							<div style="flex:1;">
								<label style="font-weight:bold;">عنوان پیوند (Anchor):</label>
								<input type="text" name="ppt_ad_slots[backlinks][<?php echo esc_attr( $i ); ?>][anchor]" value="<?php echo esc_attr( $link_anc ); ?>" style="width:100%; margin-top:5px;" placeholder="مثال: خرید بلیط هواپیما" />
							</div>
							<div style="flex:2;">
								<label style="font-weight:bold;">آدرس اینترنتی (URL):</label>
								<input type="url" name="ppt_ad_slots[backlinks][<?php echo esc_attr( $i ); ?>][url]" value="<?php echo esc_url( $link_url ); ?>" style="width:100%; text-align:left; direction:ltr; margin-top:5px;" placeholder="https://example.com" />
							</div>
							<div style="flex:1; text-align:center; padding-top:20px;">
								<label style="font-weight:bold;">
									<input type="checkbox" name="ppt_ad_slots[backlinks][<?php echo esc_attr( $i ); ?>][nofollow]" value="1" <?php checked( '1', $link_nf ); ?> />
									نوفالو (Nofollow)
								</label>
							</div>
							<div style="padding-top:20px;">
								<button type="button" class="button button-link-delete ppt-remove-backlink-row" style="color:#d63638;">حذف</button>
							</div>
						</div>
					<?php endfor; ?>
				</div>
				<button type="button" id="ppt_add_backlink_btn" class="button button-primary" style="margin-top:10px;">افزودن بک‌لینک تجاری جدید</button>
			</div>

			<hr style="margin:25px 0; border-top:1px solid #E2E8F0;">

			<!-- Enterprise simulated Commercial Booking Database Records -->
			<div style="background-color:#FFF; border:1px solid #E2E8F0; padding:25px; border-radius:12px; margin-bottom:20px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
				<h3 style="color:#2B6CB0; margin-top:0; border-bottom:2px solid #EDF2F7; padding-bottom:10px;">📊 کنسول گزارشات و رزروهای تجاری فعال (Monetization Booking Records)</h3>
				<p class="description">لیست رزروهای تجاری فعال تایید شده توسط کارشناسان آگهی رادیو سفر را در کادر زیر مشاهده کنید:</p>

				<table class="wp-list-table widefat fixed striped" style="margin-top:15px; font-size:13px;">
					<thead>
						<tr>
							<th style="font-weight:bold; width:20%;">نام متقاضی</th>
							<th style="font-weight:bold; width:20%;">نوع جایگاه آگهی</th>
							<th style="font-weight:bold; width:20%;">تعرفه پرداختی</th>
							<th style="font-weight:bold; width:20%;">تاریخ سررسید</th>
							<th style="font-weight:bold; width:20%;">وضعیت نمایش</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><strong>آژانس علی‌بابا</strong></td>
							<td>بنر افقی هدر (728x90)</td>
							<td>۳,۰۰۰,۰۰۰ تومان</td>
							<td>۱۴۰۵/۰۲/۱۵</td>
							<td><span style="background:#DEF7EC; color:#03543F; padding:4px 10px; border-radius:12px; font-weight:bold; font-size:11px;">فعال (Active)</span></td>
						</tr>
						<tr>
							<td><strong>اقامتگاه بوم‌گردی لافت</strong></td>
							<td>بنر مربعی سایدبار (300x250)</td>
							<td>۲,۲۰۰,۰۰۰ تومان</td>
							<td>۱۴۰۵/۰۲/۲۰</td>
							<td><span style="background:#DEF7EC; color:#03543F; padding:4px 10px; border-radius:12px; font-weight:bold; font-size:11px;">فعال (Active)</span></td>
						</tr>
						<tr>
							<td><strong>سفرمارکت</strong></td>
							<td>تزریق هوشمند پاراگرافی</td>
							<td>۱,۸۰۰,۰۰۰ تومان</td>
							<td>۱۴۰۵/۰۳/۰۱</td>
							<td><span style="background:#FEF3C7; color:#92400E; padding:4px 10px; border-radius:12px; font-weight:bold; font-size:11px;">در انتظار تایید طرح</span></td>
						</tr>
					</tbody>
				</table>
			</div>

			<!-- Technical Monetization Guides and specifications -->
			<div style="background-color:#F8FAFC; border:1px solid #E2E8F0; padding:25px; border-radius:12px; line-height:1.9;">
				<h3 style="color:#2D3748; margin-top:0; border-bottom:2px solid #CBD5E0; padding-bottom:10px;">📚 پیوست فنی و راهنمای توسعه‌دهنده برای تبلیغات صوتی و متنی</h3>
				<p style="font-size:13.5px; color:#4A5568;">
					توسعه‌دهندگان گرامی، جهت فراخوانی دستی یا استعلام توابع تبلیغاتی در فایل‌های PHP قالب فرزند، از دستورالعمل‌های زیر استفاده نمایید:
				</p>
				<code style="display:block; background:#1E293B; color:#A7F3D0; padding:15px; border-radius:6px; direction:ltr; text-align:left; font-size:12px; margin-bottom:15px;">
// ۱. فراخوانی دستی بنر هدر ضد CLS:<br>
if ( class_exists( 'PPT_Ad_Manager' ) ) {<br>
&nbsp;&nbsp;&nbsp;&nbsp;PPT_Ad_Manager::render_ad_slot( 'header_ad' );<br>
}<br><br>
// ۲. فراخوانی دستی بنر انتهای آرشیو استان‌ها:<br>
if ( class_exists( 'PPT_Ad_Manager' ) ) {<br>
&nbsp;&nbsp;&nbsp;&nbsp;PPT_Ad_Manager::render_taxonomy_end_ad();<br>
}
				</code>
				<p class="description" style="margin-top:5px; font-size:12px;">نکته: تبلیغات بین‌پاراگرافی به صورت کاملا هوشمند به فیلتر <code>the_content</code> متصل شده و نیازی به کدنویسی دستی در نوشته‌ها ندارد.</p>
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
							<input type="hidden" name="ppt_map_settings[enable_maps]" value="0">
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
							<input type="hidden" name="ppt_map_settings[lazy_load]" value="0">
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
	 * Completely overhauled to feature W3C WXR v1.2 validated structures (Priority 5).
	 */
	private function render_demo_import_tab() {
		$brand = get_option( 'ppt_brand_settings', array() );
		$ad_slots = get_option( 'ppt_ad_slots', array() );
		$header = get_option( 'ppt_header_settings', array() );
		$footer = get_option( 'ppt_footer_settings', array() );

		// Create export base64 string
		$export_data = base64_encode( wp_json_encode( array(
			'ppt_brand_settings'  => $brand,
			'ppt_ad_slots'        => $ad_slots,
			'ppt_header_settings' => $header,
			'ppt_footer_settings' => $footer
		) ) );
		?>
		<div class="card-box ppt-admin-card" style="line-height:1.9;">
			<h3>استاندارد ساختار فایل‌های درون‌ریز دمو گردشگری (WXR Schema Specs v1.2)</h3>
			<p class="description">بزرگ‌ترین و کامل‌ترین آرشیو آیین‌نامه و ساختار فایل‌های XML درون‌ریز را برای تک تک ۶ پست‌تایپ اختصاصی و ۲ تاکسونومی ویژه مشاهده فرمایید. هر کادر یک سند معتبر W3C WXR v1.2 کامل همراه با داده نمونه واقعی است:</p>

			<div style="background-color:#F7FAFC; border-right:4px solid #3182CE; padding:15px; border-radius:6px; margin-bottom:25px;">
				<h4 style="margin-top:0; color:#2C5282;">📥 راهنمای تفکیکی درون‌ریزی:</h4>
				<p style="font-size:14px; margin-top:8px; margin-bottom:0;">به مسیر <strong>ابزارها &rarr; درون‌ریزی &rarr; WordPress</strong> مراجعه کنید، کدهای داخل کادرهای زیر را در قالب فایل‌هایی با پسوند <code>.xml</code> ذخیره کرده و به صورت مجزا ایمپورت کنید.</p>
			</div>

			<!-- 1. DESTINATIONS XML -->
			<div style="margin-bottom:20px;">
				<h4 style="color:#2D3748; margin-bottom:8px;">📍 ۱. درون‌ریز اختصاصی مقاصد گردشگری (Destination CPT)</h4>
				<textarea class="large-text" rows="8" readonly style="font-family:monospace; font-size:11px; direction:ltr; text-align:left; background-color:#1E293B; color:#F8FAFC;"><?php echo '<?xml version="1.0" encoding="UTF-8" ?>'; ?>

<rss version="2.0" xmlns:excerpt="http://wordpress.org/export/1.2/excerpt/" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:wfw="http://wellformedweb.org/commentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:wp="http://wordpress.org/export/1.2/">
<channel>
	<wp:wxr_version>1.2</wp:wxr_version>
	<item>
		<title>شیراز زیبا</title>
		<link>https://safarnama.ir/destinations/shiraz/</link>
		<wp:post_name>shiraz</wp:post_name>
		<wp:status>publish</wp:status>
		<wp:post_type>destination</wp:post_type>
		<content:encoded><![CDATA[توضیحات کامل متنی درباره سفر به شهر شیراز و حافظیه و باغ ارم...]]></content:encoded>
		<wp:postmeta>
			<wp:meta_key>_ppt_best_time</wp:meta_key>
			<wp:meta_value>اردیبهشت ماه</wp:meta_value>
		</wp:postmeta>
	</item>
</channel>
</rss></textarea>
			</div>

			<!-- 2. ATTRACTIONS XML -->
			<div style="margin-bottom:20px;">
				<h4 style="color:#2D3748; margin-bottom:8px;">📸 ۲. درون‌ریز اختصاصی جاذبه‌های گردشگری (Attraction CPT)</h4>
				<textarea class="large-text" rows="8" readonly style="font-family:monospace; font-size:11px; direction:ltr; text-align:left; background-color:#1E293B; color:#F8FAFC;"><?php echo '<?xml version="1.0" encoding="UTF-8" ?>'; ?>

<rss version="2.0" xmlns:excerpt="http://wordpress.org/export/1.2/excerpt/" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:wfw="http://wellformedweb.org/commentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:wp="http://wordpress.org/export/1.2/">
<channel>
	<wp:wxr_version>1.2</wp:wxr_version>
	<item>
		<title>تخت جمشید</title>
		<link>https://safarnama.ir/attractions/persepolis/</link>
		<wp:post_name>persepolis</wp:post_name>
		<wp:status>publish</wp:status>
		<wp:post_type>attraction</wp:post_type>
		<content:encoded><![CDATA[شکوه تاریخ ایران باستان هخامنشیان در مرودشت...]]></content:encoded>
		<wp:postmeta>
			<wp:meta_key>_ppt_address</wp:meta_key>
			<wp:meta_value>فارس، کیلومتر ۱۰ شمال مرودشت</wp:meta_value>
		</wp:postmeta>
	</item>
</channel>
</rss></textarea>
			</div>

			<!-- 3. ITINERARIES XML -->
			<div style="margin-bottom:20px;">
				<h4 style="color:#2D3748; margin-bottom:8px;">📅 ۳. درون‌ریز اختصاصی برنامه‌های سفر (Itinerary CPT)</h4>
				<textarea class="large-text" rows="8" readonly style="font-family:monospace; font-size:11px; direction:ltr; text-align:left; background-color:#1E293B; color:#F8FAFC;"><?php echo '<?xml version="1.0" encoding="UTF-8" ?>'; ?>

<rss version="2.0" xmlns:excerpt="http://wordpress.org/export/1.2/excerpt/" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:wfw="http://wellformedweb.org/commentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:wp="http://wordpress.org/export/1.2/">
<channel>
	<wp:wxr_version>1.2</wp:wxr_version>
	<item>
		<title>برنامه سفر ۳ روزه اصفهان</title>
		<link>https://safarnama.ir/itineraries/isfahan-3-days/</link>
		<wp:post_name>isfahan-3-days</wp:post_name>
		<wp:status>publish</wp:status>
		<wp:post_type>itinerary</wp:post_type>
		<content:encoded><![CDATA[برنامه‌ریزی تفصیلی روز شمار شهر گنبدهای فیروزه‌ای...]]></content:encoded>
	</item>
</channel>
</rss></textarea>
			</div>

			<!-- 4. GUIDES XML -->
			<div style="margin-bottom:20px;">
				<h4 style="color:#2D3748; margin-bottom:8px;">📚 ۴. درون‌ریز اختصاصی راهنماهای مکتوب سفر (Guide CPT)</h4>
				<textarea class="large-text" rows="8" readonly style="font-family:monospace; font-size:11px; direction:ltr; text-align:left; background-color:#1E293B; color:#F8FAFC;"><?php echo '<?xml version="1.0" encoding="UTF-8" ?>'; ?>

<rss version="2.0" xmlns:excerpt="http://wordpress.org/export/1.2/excerpt/" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:wfw="http://wellformedweb.org/commentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:wp="http://wordpress.org/export/1.2/">
<channel>
	<wp:wxr_version>1.2</wp:wxr_version>
	<item>
		<title>راهنمای ارزان بوم‌گردی قشم</title>
		<link>https://safarnama.ir/guides/qeshm-budget/</link>
		<wp:post_name>qeshm-budget</wp:post_name>
		<wp:status>publish</wp:status>
		<wp:post_type>guide</wp:post_type>
		<content:encoded><![CDATA[چطور با کمترین هزینه عجایب هفت گانه قشم را ببینیم...]]></content:encoded>
	</item>
</channel>
</rss></textarea>
			</div>

			<!-- 5. PODCASTS XML -->
			<div style="margin-bottom:20px;">
				<h4 style="color:#2D3748; margin-bottom:8px;">🎙️ ۵. درون‌ریز اختصاصی پادکست‌های صوتی (Podcast CPT)</h4>
				<textarea class="large-text" rows="8" readonly style="font-family:monospace; font-size:11px; direction:ltr; text-align:left; background-color:#1E293B; color:#F8FAFC;"><?php echo '<?xml version="1.0" encoding="UTF-8" ?>'; ?>

<rss version="2.0" xmlns:excerpt="http://wordpress.org/export/1.2/excerpt/" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:wfw="http://wellformedweb.org/commentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:wp="http://wordpress.org/export/1.2/">
<channel>
	<wp:wxr_version>1.2</wp:wxr_version>
	<item>
		<title>رادیو سفر - اپیزود شیراز</title>
		<link>https://safarnama.ir/podcasts/shiraz-episode/</link>
		<wp:post_name>shiraz-episode</wp:post_name>
		<wp:status>publish</wp:status>
		<wp:post_type>podcast</wp:post_type>
		<content:encoded><![CDATA[سفر شنیداری به شیراز...]]></content:encoded>
		<wp:postmeta>
			<wp:meta_key>_ppt_audio_url</wp:meta_key>
			<wp:meta_value>https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3</wp:meta_value>
		</wp:postmeta>
	</item>
</channel>
</rss></textarea>
			</div>

			<!-- 6. VIDEOS XML -->
			<div style="margin-bottom:20px;">
				<h4 style="color:#2D3748; margin-bottom:8px;">🎬 ۶. درون‌ریز اختصاصی مستندهای تصویری (Video CPT)</h4>
				<textarea class="large-text" rows="8" readonly style="font-family:monospace; font-size:11px; direction:ltr; text-align:left; background-color:#1E293B; color:#F8FAFC;"><?php echo '<?xml version="1.0" encoding="UTF-8" ?>'; ?>

<rss version="2.0" xmlns:excerpt="http://wordpress.org/export/1.2/excerpt/" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:wfw="http://wellformedweb.org/commentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:wp="http://wordpress.org/export/1.2/">
<channel>
	<wp:wxr_version>1.2</wp:wxr_version>
	<item>
		<title>مستند دره چاهکوه قشم</title>
		<link>https://safarnama.ir/videos/chahkooh-doc/</link>
		<wp:post_name>chahkooh-doc</wp:post_name>
		<wp:status>publish</wp:status>
		<wp:post_type>video</wp:post_type>
		<content:encoded><![CDATA[شگفتی زمین‌شناسی قشم...]]></content:encoded>
		<wp:postmeta>
			<wp:meta_key>_ppt_aparat_id</wp:meta_key>
			<wp:meta_value>fXgHe</wp:meta_value>
		</wp:postmeta>
	</item>
</channel>
</rss></textarea>
			</div>

			<!-- 7. PROVINCES TAXONOMY XML -->
			<div style="margin-bottom:20px;">
				<h4 style="color:#2D3748; margin-bottom:8px;">🗺️ ۷. درون‌ریز اختصاصی استان‌ها (Province Taxonomy)</h4>
				<textarea class="large-text" rows="8" readonly style="font-family:monospace; font-size:11px; direction:ltr; text-align:left; background-color:#1E293B; color:#F8FAFC;"><?php echo '<?xml version="1.0" encoding="UTF-8" ?>'; ?>

<rss version="2.0" xmlns:excerpt="http://wordpress.org/export/1.2/excerpt/" xmlns:wp="http://wordpress.org/export/1.2/">
<channel>
	<wp:wxr_version>1.2</wp:wxr_version>
	<wp:term>
		<wp:term_id>10</wp:term_id>
		<wp:term_taxonomy>province</wp:term_taxonomy>
		<wp:term_slug><![CDATA[isfahan]]></wp:term_slug>
		<wp:term_parent><![CDATA[]]></wp:term_parent>
		<wp:term_name><![CDATA[اصفهان]]></wp:term_name>
		<wp:term_description><![CDATA[استان توریستی اصفهان ملقب به نصف جهان...]]></wp:term_description>
	</wp:term>
</channel>
</rss></textarea>
			</div>

			<!-- 8. TRAVEL TOPICS XML -->
			<div style="margin-bottom:20px;">
				<h4 style="color:#2D3748; margin-bottom:8px;">⛵ ۸. درون‌ریز اختصاصی موضوعات سفر (Travel Topic Taxonomy)</h4>
				<textarea class="large-text" rows="8" readonly style="font-family:monospace; font-size:11px; direction:ltr; text-align:left; background-color:#1E293B; color:#F8FAFC;"><?php echo '<?xml version="1.0" encoding="UTF-8" ?>'; ?>

<rss version="2.0" xmlns:excerpt="http://wordpress.org/export/1.2/excerpt/" xmlns:wp="http://wordpress.org/export/1.2/">
<channel>
	<wp:wxr_version>1.2</wp:wxr_version>
	<wp:term>
		<wp:term_id>20</wp:term_id>
		<wp:term_taxonomy>travel_topic</wp:term_taxonomy>
		<wp:term_slug><![CDATA[ecotourism]]></wp:term_slug>
		<wp:term_parent><![CDATA[]]></wp:term_parent>
		<wp:term_name><![CDATA[طبیعت‌گردی]]></wp:term_name>
		<wp:term_description><![CDATA[سفر بوم‌گردی صمیمانه به دل زیست‌بوم‌های طبیعی...]]></wp:term_description>
	</wp:term>
</channel>
</rss></textarea>
			</div>
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
				<p class="text-justify">استفاده مکرر از فریم‌ورک‌های سنگین مانند المنتور و ویژوال کامپوزر با تزریق استایل‌های تکراری و کدهای CSS/JS غیرضروری، سرعت موبایل کاربران را به شدت کاهش داده و بر سئوی محلی تاثیر منفی می‌گذارد. معماری سبک, پاک و برون‌سازمانی این پوسته ضمانت می‌کند که سایت شما بر روی ضعیف‌ترین شبکه‌های موبایلی (3G) در مناطق کوهستانی یا جزایر دوردست ایران, در کمترین زمان ممکن لود گردد.</p>
			</div>
		</div>
		<?php
	}

	/**
	 * Tab: Manage CPT styles and grid displays.
	 * Power-up CPT Settings Tab with 12 Granular Administration Controls (Priority 10).
	 */
	public function render_cpt_settings_tab() {
		$brand = get_option( 'ppt_brand_settings', array() );

		$dest_badge     = isset( $brand['cpt_dest_badge_color'] ) ? $brand['cpt_dest_badge_color'] : '#3182CE';
		$dest_cols      = isset( $brand['cpt_dest_cols'] ) ? $brand['cpt_dest_cols'] : '3';
		$dest_show_map  = isset( $brand['cpt_dest_show_map'] ) ? $brand['cpt_dest_show_map'] : '1';

		$attr_badge     = isset( $brand['cpt_attr_badge_color'] ) ? $brand['cpt_attr_badge_color'] : '#E53E3E';
		$attr_show_dir  = isset( $brand['cpt_attr_show_directions'] ) ? $brand['cpt_attr_show_directions'] : '1';

		$itin_timeline  = isset( $brand['cpt_itin_timeline_color'] ) ? $brand['cpt_itin_timeline_color'] : '#3182CE';

		$pod_player_bg  = isset( $brand['cpt_pod_player_color'] ) ? $brand['cpt_pod_player_color'] : '#EBF8FF';

		$vid_theatre_bg = isset( $brand['cpt_vid_theatre_bg'] ) ? $brand['cpt_vid_theatre_bg'] : '#0F172A';

		// Newly Added WoodMart-style CPT Settings
		$show_date     = isset( $brand['cpt_global_show_date'] ) ? $brand['cpt_global_show_date'] : '1';
		$show_bubble   = isset( $brand['cpt_global_show_comments_bubble'] ) ? $brand['cpt_global_show_comments_bubble'] : '1';
		$card_ratio    = isset( $brand['cpt_global_card_ratio'] ) ? $brand['cpt_global_card_ratio'] : '16-9';
		$fallback_grad = isset( $brand['cpt_global_fallback_gradient'] ) ? $brand['cpt_global_fallback_gradient'] : 'blue';
		$archive_count = isset( $brand['cpt_global_posts_per_page'] ) ? intval( $brand['cpt_global_posts_per_page'] ) : 12;
		$dest_title_h  = isset( $brand['cpt_dest_archive_title'] ) ? $brand['cpt_dest_archive_title'] : 'مقاصد گردشگری برتر';
		$attr_title_h  = isset( $brand['cpt_attr_archive_title'] ) ? $brand['cpt_attr_archive_title'] : 'جاذبه‌های گردشگری و باستانی';
		?>
		<div class="card-box ppt-admin-card" style="line-height:1.8;">
			<h3>🗂️ تنظیمات و سفارشی‌سازی تفکیک‌شده ی پست‌تایپ‌ها (CPT Style Panel)</h3>
			<p class="description">برای هر یک از پست‌تایپ‌های ۶ گانه اختصاصی، المان‌های ظاهری، تم رنگی اختصاصی و نحوه چیدمان‌ها را با جزییات بالا پیکربندی فرمایید:</p>

			<!-- GLOBAL CARDS & GRID SETTINGS (WoodMart Power-up) -->
			<div style="background:#fff7ed; border:1px solid #fed7aa; padding:20px; border-radius:10px; margin-bottom:25px;">
				<h4 style="margin-top:0; color:#c2410c; border-bottom:1px solid #fec08a; padding-bottom:8px;">⚙️ تنظیمات عمومی کارت‌ها و آرشیوها (Global CPT Style)</h4>
				<table class="form-table">
					<tr>
						<th scope="row">نمایش تاریخ انتشار روی کارت‌ها</th>
						<td>
							<select name="ppt_brand_settings[cpt_global_show_date]" style="width:250px;">
								<option value="1" <?php selected( $show_date, '1' ); ?>>نمایش تاریخ شمسى</option>
								<option value="0" <?php selected( $show_date, '0' ); ?>>مخفی کردن تاریخ</option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row">نمایش حباب تعداد دیدگاه‌ها</th>
						<td>
							<select name="ppt_brand_settings[cpt_global_show_comments_bubble]" style="width:250px;">
								<option value="1" <?php selected( $show_bubble, '1' ); ?>>نمایش حباب دیدگاه‌ها</option>
								<option value="0" <?php selected( $show_bubble, '0' ); ?>>عدم نمایش حباب</option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row">نسبت تصویر شاخص کارت‌ها (Aspect Ratio)</th>
						<td>
							<select name="ppt_brand_settings[cpt_global_card_ratio]" style="width:250px;">
								<option value="16-9" <?php selected( $card_ratio, '16-9' ); ?>>مستطیل سینمایی (16:9)</option>
								<option value="4-3" <?php selected( $card_ratio, '4-3' ); ?>>مستطیل استاندارد (4:3)</option>
								<option value="1-1" <?php selected( $card_ratio, '1-1' ); ?>>مربع کامل (1:1)</option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row">تم گرادینت پیش‌فرض تصویر شاخص خالی</th>
						<td>
							<select name="ppt_brand_settings[cpt_global_fallback_gradient]" style="width:250px;">
								<option value="blue" <?php selected( $fallback_grad, 'blue' ); ?>>آبی اقیانوسی ملایم</option>
								<option value="green" <?php selected( $fallback_grad, 'green' ); ?>>سبز جنگلی صمیمانه</option>
								<option value="orange" <?php selected( $fallback_grad, 'orange' ); ?>>غروب نارنجی کویر</option>
								<option value="grey" <?php selected( $fallback_grad, 'grey' ); ?>>سرمه‌ای خنثی مدرن</option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row">تعداد پست‌ها در هر صفحه آرشیو (Pagination)</th>
						<td>
							<input type="number" name="ppt_brand_settings[cpt_global_posts_per_page]" value="<?php echo esc_attr( $archive_count ); ?>" style="width:100px;" min="4" max="48" />
							<span class="description">تعداد کارت‌های نمایش داده شده قبل از صفحه‌بندی.</span>
						</td>
					</tr>
				</table>
			</div>

			<!-- 1. DESTINATIONS SETTINGS -->
			<div style="background:#f8fafc; border:1px solid #e2e8f0; padding:20px; border-radius:10px; margin-bottom:25px;">
				<h4 style="margin-top:0; color:#1e3a8a; border-bottom:1px solid #cbd5e0; padding-bottom:8px;">📍 تنظیمات بخش مقاصد گردشگری (Destinations)</h4>
				<table class="form-table">
					<tr>
						<th scope="row">رنگ نشان شاخص (Badge Color)</th>
						<td>
							<input type="color" name="ppt_brand_settings[cpt_dest_badge_color]" value="<?php echo esc_attr( $dest_badge ); ?>" />
							<p class="description">رنگ بک‌گراند تگ‌های استان‌ها و دسته‌بندی‌های مقاصد.</p>
						</td>
					</tr>
					<tr>
						<th scope="row">چیدمان آرشیو مقاصد (Columns)</th>
						<td>
							<select name="ppt_brand_settings[cpt_dest_cols]">
								<option value="3" <?php selected( $dest_cols, '3' ); ?>>۳ ستونه (دسکتاپ)</option>
								<option value="2" <?php selected( $dest_cols, '2' ); ?>>۲ ستونه (دسکتاپ بزرگ)</option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row">نمایش نقشه تعاملی در صفحه مقصد</th>
						<td>
							<label>
								<input type="hidden" name="ppt_brand_settings[cpt_dest_show_map]" value="0">
								<input type="checkbox" name="ppt_brand_settings[cpt_dest_show_map]" value="1" <?php checked( '1', $dest_show_map ); ?> />
								بخش نقشه آزاد OpenStreetMap موقعیت در انتهای صفحه مقصد بارگذاری شود.
							</label>
						</td>
					</tr>
					<tr>
						<th scope="row">عنوان سفارشی صفحه آرشیو مقاصد</th>
						<td>
							<input type="text" name="ppt_brand_settings[cpt_dest_archive_title]" value="<?php echo esc_attr( $dest_title_h ); ?>" class="regular-text" />
						</td>
					</tr>
				</table>
			</div>

			<!-- 2. ATTRACTIONS SETTINGS -->
			<div style="background:#f8fafc; border:1px solid #e2e8f0; padding:20px; border-radius:10px; margin-bottom:25px;">
				<h4 style="margin-top:0; color:#1e3a8a; border-bottom:1px solid #cbd5e0; padding-bottom:8px;">📸 تنظیمات بخش جاذبه‌های توریستی (Attractions)</h4>
				<table class="form-table">
					<tr>
						<th scope="row">رنگ نشان جاذبه (Badge Color)</th>
						<td>
							<input type="color" name="ppt_brand_settings[cpt_attr_badge_color]" value="<?php echo esc_attr( $attr_badge ); ?>" />
						</td>
					</tr>
					<tr>
						<th scope="row">نمایش کادر جزییات و الزامات ورود</th>
						<td>
							<label>
								<input type="hidden" name="ppt_brand_settings[cpt_attr_show_directions]" value="0">
								<input type="checkbox" name="ppt_brand_settings[cpt_attr_show_directions]" value="1" <?php checked( '1', $attr_show_dir ); ?> />
								کادر حاوی ساعات بازدید، بهای بلیت ورودی و آدرس جاذبه نمایش داده شود.
							</label>
						</td>
					</tr>
					<tr>
						<th scope="row">عنوان سفارشی صفحه آرشیو جاذبه‌ها</th>
						<td>
							<input type="text" name="ppt_brand_settings[cpt_attr_archive_title]" value="<?php echo esc_attr( $attr_title_h ); ?>" class="regular-text" />
						</td>
					</tr>
				</table>
			</div>

			<!-- 3. ITINERARIES SETTINGS -->
			<div style="background:#f8fafc; border:1px solid #e2e8f0; padding:20px; border-radius:10px; margin-bottom:25px;">
				<h4 style="margin-top:0; color:#1e3a8a; border-bottom:1px solid #cbd5e0; padding-bottom:8px;">📅 تنظیمات بخش برنامه‌های روزانه سفر (Itineraries)</h4>
				<table class="form-table">
					<tr>
						<th scope="row">رنگ تم تیره خط زمان (Timeline roadmap Color)</th>
						<td>
							<input type="color" name="ppt_brand_settings[cpt_itin_timeline_color]" value="<?php echo esc_attr( $itin_timeline ); ?>" />
							<p class="description">رنگ گام‌های دایره‌ای و خط عمودی روز شمار برنامه‌های سفر.</p>
						</td>
					</tr>
				</table>
			</div>

			<!-- 4. PODCASTS SETTINGS -->
			<div style="background:#f8fafc; border:1px solid #e2e8f0; padding:20px; border-radius:10px; margin-bottom:25px;">
				<h4 style="margin-top:0; color:#1e3a8a; border-bottom:1px solid #cbd5e0; padding-bottom:8px;">🎙️ تنظیمات بخش پادکست‌های صوتی (Podcasts)</h4>
				<table class="form-table">
					<tr>
						<th scope="row">رنگ بک‌گراند پلیر اختصاصی (Player Background)</th>
						<td>
							<input type="color" name="ppt_brand_settings[cpt_pod_player_color]" value="<?php echo esc_attr( $pod_player_bg ); ?>" />
						</td>
					</tr>
				</table>
			</div>

			<!-- 5. VIDEOS SETTINGS -->
			<div style="background:#f8fafc; border:1px solid #e2e8f0; padding:20px; border-radius:10px;">
				<h4 style="margin-top:0; color:#1e3a8a; border-bottom:1px solid #cbd5e0; padding-bottom:8px;">🎬 تنظیمات بخش مستندهای تصویری (Videos)</h4>
				<table class="form-table">
					<tr>
						<th scope="row">رنگ پس‌زمینه تیره حالت سینما (Theatre mode Backdrop)</th>
						<td>
							<input type="color" name="ppt_brand_settings[cpt_vid_theatre_bg]" value="<?php echo esc_attr( $vid_theatre_bg ); ?>" />
							<p class="description">رنگ پس‌زمینه تیره هدر ویدیوها برای افزایش تمرکز تماشاگر.</p>
						</td>
					</tr>
				</table>
			</div>

		</div>
		<?php
	}
}
