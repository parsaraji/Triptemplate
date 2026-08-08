<?php
/**
 * Non-Gutenberg Custom Meta Boxes
 * Upgraded with detailed Itinerary (برنامه سفر) meta settings and manual SEO Schema injectors on all posts.
 *
 * @package Premium_Persian_Tourism
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class PPT_Meta_Boxes {

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
		add_action( 'add_meta_boxes', array( $this, 'register_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_meta_boxes' ) );
	}

	/**
	 * Register Custom Meta Boxes.
	 */
	public function register_meta_boxes() {
		$all_post_types = array( 'post', 'page', 'destination', 'attraction', 'itinerary', 'guide', 'podcast', 'video' );

		// 1. Destination meta box
		add_meta_box(
			'ppt_destination_details',
			'اطلاعات و ویژگی‌های مقصد گردشگری',
			array( $this, 'render_destination_meta_box' ),
			'destination',
			'normal',
			'high'
		);

		// 2. Attraction meta box
		add_meta_box(
			'ppt_attraction_details',
			'اطلاعات و ویژگی‌های جاذبه گردشگری',
			array( $this, 'render_attraction_meta_box' ),
			'attraction',
			'normal',
			'high'
		);

		// 3. Itinerary (برنامه سفر) meta box
		add_meta_box(
			'ppt_itinerary_details',
			'جزییات و برنامه‌ریزی روزانه سفر (Itinerary)',
			array( $this, 'render_itinerary_meta_box' ),
			'itinerary',
			'normal',
			'high'
		);

		// 4. Guide meta box (repeatable budget list & FAQ)
		add_meta_box(
			'ppt_guide_details',
			'اطلاعات راهنما و جداول هزینه‌ها',
			array( $this, 'render_guide_meta_box' ),
			'guide',
			'normal',
			'high'
		);

		// 5. Podcast meta box
		add_meta_box(
			'ppt_podcast_details',
			'تنظیمات فایل صوتی پادکست',
			array( $this, 'render_podcast_meta_box' ),
			'podcast',
			'normal',
			'high'
		);

		// 6. Video meta box
		add_meta_box(
			'ppt_video_details',
			'کد یا لینک آپارات ویدیو',
			array( $this, 'render_video_meta_box' ),
			'video',
			'normal',
			'high'
		);

		// 7. Manual SEO JSON-LD Schema Injector on ALL post types
		foreach ( $all_post_types as $pt ) {
			add_meta_box(
				'ppt_manual_schema_injector',
				'نشانه‌گذاری اسکیما اختصاصی (Manual JSON-LD Schema)',
				array( $this, 'render_manual_schema_meta_box' ),
				$pt,
				'normal',
				'low'
			);
		}
	}

	/**
	 * Render Destination Meta Box.
	 */
	public function render_destination_meta_box( $post ) {
		wp_nonce_field( 'ppt_save_meta_nonce', 'ppt_meta_nonce' );

		$best_time = get_post_meta( $post->ID, '_ppt_best_time', true );
		$weather   = get_post_meta( $post->ID, '_ppt_weather', true );
		$cost_level = get_post_meta( $post->ID, '_ppt_cost_level', true );
		$lat       = get_post_meta( $post->ID, '_ppt_lat', true );
		$lng       = get_post_meta( $post->ID, '_ppt_lng', true );
		?>
		<div class="ppt-meta-field-group">
			<label for="ppt_best_time"><strong>بهترین زمان سفر:</strong></label>
			<input type="text" id="ppt_best_time" name="ppt_best_time" value="<?php echo esc_attr( $best_time ); ?>" placeholder="مثلاً: اردیبهشت و خرداد، پاییز" class="large-text" />
		</div>
		<div class="ppt-meta-field-group" style="margin-top:15px;">
			<label for="ppt_weather"><strong>وضعیت آب و هوا:</strong></label>
			<input type="text" id="ppt_weather" name="ppt_weather" value="<?php echo esc_attr( $weather ); ?>" placeholder="مثلاً: کوهستانی و معتدل" class="large-text" />
		</div>
		<div class="ppt-meta-field-group" style="margin-top:15px;">
			<label for="ppt_cost_level"><strong>حدود هزینه سفر (سطح قیمت):</strong></label>
			<select id="ppt_cost_level" name="ppt_cost_level">
				<option value="low" <?php selected( $cost_level, 'low' ); ?>>اقتصادی و ارزان</option>
				<option value="medium" <?php selected( $cost_level, 'medium' ); ?>>متوسط</option>
				<option value="high" <?php selected( $cost_level, 'high' ); ?>>لوکس و گران</option>
			</select>
		</div>
		<div class="ppt-meta-field-group" style="margin-top:15px; display: flex; gap: 15px;">
			<div>
				<label for="ppt_lat"><strong>عرض جغرافیایی (Latitude):</strong></label><br>
				<input type="text" id="ppt_lat" name="ppt_lat" value="<?php echo esc_attr( $lat ); ?>" placeholder="مثلاً: 35.6892" />
			</div>
			<div>
				<label for="ppt_lng"><strong>طول جغرافیایی (Longitude):</strong></label><br>
				<input type="text" id="ppt_lng" name="ppt_lng" value="<?php echo esc_attr( $lng ); ?>" placeholder="مثلاً: 51.3890" />
			</div>
		</div>
		<?php
	}

	/**
	 * Render Attraction Meta Box.
	 */
	public function render_attraction_meta_box( $post ) {
		wp_nonce_field( 'ppt_save_meta_nonce', 'ppt_meta_nonce' );

		$address      = get_post_meta( $post->ID, '_ppt_address', true );
		$opening_hours = get_post_meta( $post->ID, '_ppt_opening_hours', true );
		$ticket_price = get_post_meta( $post->ID, '_ppt_ticket_price', true );
		$lat          = get_post_meta( $post->ID, '_ppt_lat', true );
		$lng          = get_post_meta( $post->ID, '_ppt_lng', true );
		?>
		<div class="ppt-meta-field-group">
			<label for="ppt_address"><strong>آدرس دقیق جاذبه:</strong></label>
			<input type="text" id="ppt_address" name="ppt_address" value="<?php echo esc_attr( $address ); ?>" placeholder="مثلاً: تهران، میدان تجریش، خیابان فناخسرو" class="large-text" />
		</div>
		<div class="ppt-meta-field-group" style="margin-top:15px;">
			<label for="ppt_opening_hours"><strong>ساعات بازدید:</strong></label>
			<input type="text" id="ppt_opening_hours" name="ppt_opening_hours" value="<?php echo esc_attr( $opening_hours ); ?>" placeholder="مثلاً: همه روزه از ساعت ۹ صبح الی ۱۸ عصر" class="large-text" />
		</div>
		<div class="ppt-meta-field-group" style="margin-top:15px;">
			<label for="ppt_ticket_price"><strong>هزینه بلیت ورودی (به تومان یا ریال):</strong></label>
			<input type="text" id="ppt_ticket_price" name="ppt_ticket_price" value="<?php echo esc_attr( $ticket_price ); ?>" placeholder="مثلاً: ۳۰,۰۰۰ تومان - اتباع خارجی ۱۰۰,۰۰۰ تومان" class="large-text" />
		</div>
		<div class="ppt-meta-field-group" style="margin-top:15px; display: flex; gap: 15px;">
			<div>
				<label for="ppt_lat"><strong>عرض جغرافیایی (Latitude):</strong></label><br>
				<input type="text" id="ppt_lat" name="ppt_lat" value="<?php echo esc_attr( $lat ); ?>" placeholder="مثلاً: 35.7201" />
			</div>
			<div>
				<label for="ppt_lng"><strong>طول جغرافیایی (Longitude):</strong></label><br>
				<input type="text" id="ppt_lng" name="ppt_lng" value="<?php echo esc_attr( $lng ); ?>" placeholder="مثلاً: 51.4100" />
			</div>
		</div>
		<?php
	}

	/**
	 * Render Itinerary Meta Box (برنامه سفر).
	 */
	public function render_itinerary_meta_box( $post ) {
		wp_nonce_field( 'ppt_save_meta_nonce', 'ppt_meta_nonce' );

		$duration   = get_post_meta( $post->ID, '_ppt_itinerary_duration', true );
		$difficulty = get_post_meta( $post->ID, '_ppt_itinerary_difficulty', true );
		$start_pt   = get_post_meta( $post->ID, '_ppt_itinerary_start_point', true );
		$season     = get_post_meta( $post->ID, '_ppt_itinerary_season', true );
		$days_plan  = get_post_meta( $post->ID, '_ppt_itinerary_days', true );

		if ( ! is_array( $days_plan ) ) {
			$days_plan = array();
		}
		?>
		<div class="ppt-meta-field-group" style="display:flex; gap:15px; margin-bottom:15px;">
			<div style="flex:1;">
				<label><strong>مدت زمان برنامه (روز):</strong></label>
				<input type="text" name="ppt_itinerary_duration" value="<?php echo esc_attr( $duration ); ?>" placeholder="مثلاً: ۳ روز و ۲ شب" style="width:100%;" />
			</div>
			<div style="flex:1;">
				<label><strong>درجه سختی مسیر:</strong></label>
				<select name="ppt_itinerary_difficulty" style="width:100%;">
					<option value="easy" <?php selected( $difficulty, 'easy' ); ?>>آسان (خانوادگی)</option>
					<option value="medium" <?php selected( $difficulty, 'medium' ); ?>>متوسط</option>
					<option value="hard" <?php selected( $difficulty, 'hard' ); ?>>سخت (طبیعت‌گردی حرفه‌ای)</option>
				</select>
			</div>
		</div>
		<div class="ppt-meta-field-group" style="display:flex; gap:15px; margin-bottom:15px;">
			<div style="flex:1;">
				<label><strong>مبداء شروع سفر:</strong></label>
				<input type="text" name="ppt_itinerary_start_point" value="<?php echo esc_attr( $start_pt ); ?>" placeholder="مثلاً: تهران یا اصفهان" style="width:100%;" />
			</div>
			<div style="flex:1;">
				<label><strong>بهترین فصل اجرای برنامه:</strong></label>
				<input type="text" name="ppt_itinerary_season" value="<?php echo esc_attr( $season ); ?>" placeholder="مثلاً: بهار و اواخر پاییز" style="width:100%;" />
			</div>
		</div>

		<hr style="margin:20px 0;">

		<div class="ppt-meta-section">
			<h3>📅 برنامه‌ریزی روز به روز سفر (روز شمار)</h3>
			<p class="description">جزییات اقدامات، مسافت‌ها و مکان‌های توقف هر روز را در زیر بنویسید:</p>

			<div id="ppt_itinerary_days_container">
				<?php for ( $i = 0; $i < 3; $i ++ ) :
					$day_title = isset( $days_plan[$i]['title'] ) ? $days_plan[$i]['title'] : '';
					$day_desc  = isset( $days_plan[$i]['desc'] ) ? $days_plan[$i]['desc'] : '';
					?>
					<div class="ppt-repeater-item" style="border:1px solid #ccd0d4; padding:15px; margin-bottom:15px; background-color:#f6f7f7; border-radius:4px;">
						<p>
							<label><strong>عنوان روز <?php echo esc_html( $i + 1 ); ?>:</strong></label>
							<input type="text" name="ppt_itinerary_days[<?php echo esc_attr( $i ); ?>][title]" value="<?php echo esc_attr( $day_title ); ?>" style="width:100%;" placeholder="مثال: روز اول - گشت و گذار در حافظیه" />
						</p>
						<p>
							<label><strong>شرح اقدامات و جزییات مسیر:</strong></label>
							<textarea name="ppt_itinerary_days[<?php echo esc_attr( $i ); ?>][desc]" style="width:100%;" rows="3" placeholder="مکان‌های بازدید، رستوران‌ها و نکات ترابری روز اول را بنویسید"><?php echo esc_textarea( $day_desc ); ?></textarea>
						</p>
					</div>
				<?php endfor; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Render Guide Meta Box (repeater lists for budgets & FAQs).
	 */
	public function render_guide_meta_box( $post ) {
		wp_nonce_field( 'ppt_save_meta_nonce', 'ppt_meta_nonce' );

		$faqs          = get_post_meta( $post->ID, '_ppt_faqs', true );
		$budget_items  = get_post_meta( $post->ID, '_ppt_budget_items', true );

		if ( ! is_array( $faqs ) ) {
			$faqs = array();
		}
		if ( ! is_array( $budget_items ) ) {
			$budget_items = array();
		}
		?>
		<div class="ppt-meta-section">
			<h3>سوالات متداول (FAQ)</h3>
			<div id="ppt_faq_repeater_container" class="ppt-repeater-container">
				<?php foreach ( $faqs as $index => $faq ) : ?>
					<div class="ppt-repeater-item" style="border:1px solid #ccc; padding:10px; margin-bottom:10px; background:#f9f9f9; position:relative;">
						<p>
							<label>سوال:</label>
							<input type="text" name="ppt_faqs[<?php echo esc_attr( $index ); ?>][q]" value="<?php echo esc_attr( $faq['q'] ); ?>" class="large-text" />
						</p>
						<p>
							<label>پاسخ:</label>
							<textarea name="ppt_faqs[<?php echo esc_attr( $index ); ?>][a]" class="large-text" rows="3"><?php echo esc_textarea( $faq['a'] ); ?></textarea>
						</p>
						<button type="button" class="button button-link-delete ppt-remove-repeater-item" style="color:#d63638;">حذف سوال</button>
					</div>
				<?php endforeach; ?>
			</div>
			<button type="button" id="ppt_add_faq_btn" class="button button-primary">افزودن سوال جدید</button>
		</div>

		<hr style="margin:20px 0;">

		<div class="ppt-meta-section">
			<h3>برآورد هزینه‌ها و بودجه (جدول قیمت‌ها)</h3>
			<table class="wp-list-table widefat fixed striped" style="margin-bottom:10px;">
				<thead>
					<tr>
						<th style="width:40%;">آیتم / خدمات</th>
						<th style="width:40%;">حدود هزینه (تومان)</th>
						<th style="width:20%;">عملیات</th>
					</tr>
				</thead>
				<tbody id="ppt_budget_repeater_container">
					<?php foreach ( $budget_items as $index => $item ) : ?>
						<tr>
							<td>
								<input type="text" name="ppt_budget[<?php echo esc_attr( $index ); ?>][title]" value="<?php echo esc_attr( $item['title'] ); ?>" style="width:100%;" />
							</td>
							<td>
								<input type="text" name="ppt_budget[<?php echo esc_attr( $index ); ?>][cost]" value="<?php echo esc_attr( $item['cost'] ); ?>" style="width:100%;" />
							</td>
							<td>
								<button type="button" class="button button-link-delete ppt-remove-budget-row" style="color:#d63638;">حذف</button>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<button type="button" id="ppt_add_budget_btn" class="button button-primary">افزودن ردیف هزینه جدید</button>
		</div>
		<?php
	}

	/**
	 * Render Podcast Meta Box.
	 */
	public function render_podcast_meta_box( $post ) {
		wp_nonce_field( 'ppt_save_meta_nonce', 'ppt_meta_nonce' );

		$audio_url = get_post_meta( $post->ID, '_ppt_audio_url', true );
		$duration  = get_post_meta( $post->ID, '_ppt_podcast_duration', true );
		$host_name = get_post_meta( $post->ID, '_ppt_podcast_host', true );
		?>
		<div class="ppt-meta-field-group">
			<label for="ppt_audio_url"><strong>آدرس مستقیم فایل صوتی (MP3):</strong></label>
			<input type="text" id="ppt_audio_url" name="ppt_audio_url" value="<?php echo esc_url( $audio_url ); ?>" placeholder="https://example.com/podcast.mp3" class="large-text" />
			<p class="description">آدرس مستقیم فایل پادکست آپلود شده در رسانه وردپرس یا هاست‌های خارجی را وارد کنید.</p>
		</div>
		<div class="ppt-meta-field-group" style="margin-top:15px; display:flex; gap:15px;">
			<div>
				<label for="ppt_podcast_duration"><strong>مدت زمان پادکست:</strong></label><br>
				<input type="text" id="ppt_podcast_duration" name="ppt_podcast_duration" value="<?php echo esc_attr( $duration ); ?>" placeholder="مثلاً: ۲۵:۴۰" />
			</div>
			<div>
				<label for="ppt_podcast_host"><strong>گوینده / میزبان:</strong></label><br>
				<input type="text" id="ppt_podcast_host" name="ppt_podcast_host" value="<?php echo esc_attr( $host_name ); ?>" placeholder="مثلاً: علی راد" />
			</div>
		</div>
		<?php
	}

	/**
	 * Render Video Meta Box.
	 */
	public function render_video_meta_box( $post ) {
		wp_nonce_field( 'ppt_save_meta_nonce', 'ppt_meta_nonce' );

		$aparat_id = get_post_meta( $post->ID, '_ppt_aparat_id', true );
		$duration  = get_post_meta( $post->ID, '_ppt_video_duration', true );
		?>
		<div class="ppt-meta-field-group">
			<label for="ppt_aparat_id"><strong>شناسه یا آدرس کامل ویدیو آپارات:</strong></label>
			<input type="text" id="ppt_aparat_id" name="ppt_aparat_id" value="<?php echo esc_attr( $aparat_id ); ?>" placeholder="مثلاً: aB1cD یا آدرس کامل ویدیو آپارات" class="large-text" />
			<p class="description">می‌توانید شناسه ویدیو آپارات (مانند: <code>fXgHe</code>) یا آدرس کامل صفحه آن را وارد کنید تا به صورت تنبل و بهینه رندر شود.</p>
		</div>
		<div class="ppt-meta-field-group" style="margin-top:15px;">
			<label for="ppt_video_duration"><strong>مدت زمان ویدیو:</strong></label><br>
			<input type="text" id="ppt_video_duration" name="ppt_video_duration" value="<?php echo esc_attr( $duration ); ?>" placeholder="مثلاً: ۱۲:۱۵" />
		</div>
		<?php
	}

	/**
	 * Render Manual SEO Schema Meta Box on all posts (Priority 3/4)
	 */
	public function render_manual_schema_meta_box( $post ) {
		wp_nonce_field( 'ppt_save_meta_nonce', 'ppt_meta_nonce' );

		$manual_schema = get_post_meta( $post->ID, '_ppt_manual_schema', true );
		?>
		<div class="ppt-meta-field-group">
			<label for="ppt_manual_schema"><strong>کد اسکیما دستی (JSON-LD Schema Script):</strong></label><br>
			<textarea id="ppt_manual_schema" name="ppt_manual_schema" rows="6" class="large-text" style="font-family:monospace; direction:ltr; text-align:left;" placeholder='<script type="application/ld+json">&#13;&#10;{&#13;&#10;  "@context": "https://schema.org",&#13;&#10;  "@type": "NewsArticle"&#13;&#10;}&#13;&#10;</script>'><?php echo esc_textarea( $manual_schema ); ?></textarea>
			<p class="description">کد نشانه‌گذاری اسکیما مدنظر خود را به صورت اسکریپت تگ کامل JSON-LD در بالا قرار دهید تا مستقیما در هدر همین نوشته تزریق گردد.</p>
		</div>
		<?php
	}

	/**
	 * Save Custom Meta Boxes.
	 */
	public function save_meta_boxes( $post_id ) {
		// Nonce check.
		if ( ! isset( $_POST['ppt_meta_nonce'] ) || ! wp_verify_nonce( $_POST['ppt_meta_nonce'], 'ppt_save_meta_nonce' ) ) {
			return;
		}

		// Check autosave.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Permissions check.
		if ( isset( $_POST['post_type'] ) && 'page' === $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return;
			}
		} else {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}

		// 1. Destination fields.
		if ( isset( $_POST['ppt_best_time'] ) ) {
			update_post_meta( $post_id, '_ppt_best_time', sanitize_text_field( $_POST['ppt_best_time'] ) );
		}
		if ( isset( $_POST['ppt_weather'] ) ) {
			update_post_meta( $post_id, '_ppt_weather', sanitize_text_field( $_POST['ppt_weather'] ) );
		}
		if ( isset( $_POST['ppt_cost_level'] ) ) {
			update_post_meta( $post_id, '_ppt_cost_level', sanitize_text_field( $_POST['ppt_cost_level'] ) );
		}

		// Shared Coordinates (Destination & Attraction).
		if ( isset( $_POST['ppt_lat'] ) ) {
			update_post_meta( $post_id, '_ppt_lat', sanitize_text_field( $_POST['ppt_lat'] ) );
		}
		if ( isset( $_POST['ppt_lng'] ) ) {
			update_post_meta( $post_id, '_ppt_lng', sanitize_text_field( $_POST['ppt_lng'] ) );
		}

		// 2. Attraction fields.
		if ( isset( $_POST['ppt_address'] ) ) {
			update_post_meta( $post_id, '_ppt_address', sanitize_text_field( $_POST['ppt_address'] ) );
		}
		if ( isset( $_POST['ppt_opening_hours'] ) ) {
			update_post_meta( $post_id, '_ppt_opening_hours', sanitize_text_field( $_POST['ppt_opening_hours'] ) );
		}
		if ( isset( $_POST['ppt_ticket_price'] ) ) {
			update_post_meta( $post_id, '_ppt_ticket_price', sanitize_text_field( $_POST['ppt_ticket_price'] ) );
		}

		// 3. Itinerary fields.
		if ( isset( $_POST['ppt_itinerary_duration'] ) ) {
			update_post_meta( $post_id, '_ppt_itinerary_duration', sanitize_text_field( $_POST['ppt_itinerary_duration'] ) );
		}
		if ( isset( $_POST['ppt_itinerary_difficulty'] ) ) {
			update_post_meta( $post_id, '_ppt_itinerary_difficulty', sanitize_text_field( $_POST['ppt_itinerary_difficulty'] ) );
		}
		if ( isset( $_POST['ppt_itinerary_start_point'] ) ) {
			update_post_meta( $post_id, '_ppt_itinerary_start_point', sanitize_text_field( $_POST['ppt_itinerary_start_point'] ) );
		}
		if ( isset( $_POST['ppt_itinerary_season'] ) ) {
			update_post_meta( $post_id, '_ppt_itinerary_season', sanitize_text_field( $_POST['ppt_itinerary_season'] ) );
		}
		if ( isset( $_POST['ppt_itinerary_days'] ) && is_array( $_POST['ppt_itinerary_days'] ) ) {
			$sanitized_days = array();
			foreach ( $_POST['ppt_itinerary_days'] as $day ) {
				$sanitized_days[] = array(
					'title' => sanitize_text_field( $day['title'] ),
					'desc'  => sanitize_textarea_field( $day['desc'] ),
				);
			}
			update_post_meta( $post_id, '_ppt_itinerary_days', $sanitized_days );
		}

		// 4. Guide fields (FAQs and Budgets).
		if ( isset( $_POST['ppt_faqs'] ) && is_array( $_POST['ppt_faqs'] ) ) {
			$sanitized_faqs = array();
			foreach ( $_POST['ppt_faqs'] as $faq ) {
				if ( ! empty( $faq['q'] ) && ! empty( $faq['a'] ) ) {
					$sanitized_faqs[] = array(
						'q' => sanitize_text_field( $faq['q'] ),
						'a' => sanitize_textarea_field( $faq['a'] ),
					);
				}
			}
			update_post_meta( $post_id, '_ppt_faqs', $sanitized_faqs );
		} else if ( isset( $_POST['ppt_meta_nonce'] ) ) {
			update_post_meta( $post_id, '_ppt_faqs', array() );
		}

		if ( isset( $_POST['ppt_budget'] ) && is_array( $_POST['ppt_budget'] ) ) {
			$sanitized_budget = array();
			foreach ( $_POST['ppt_budget'] as $item ) {
				if ( ! empty( $item['title'] ) ) {
					$sanitized_budget[] = array(
						'title' => sanitize_text_field( $item['title'] ),
						'cost'  => sanitize_text_field( $item['cost'] ),
					);
				}
			}
			update_post_meta( $post_id, '_ppt_budget_items', $sanitized_budget );
		} else if ( isset( $_POST['ppt_meta_nonce'] ) ) {
			update_post_meta( $post_id, '_ppt_budget_items', array() );
		}

		// 5. Podcast fields.
		if ( isset( $_POST['ppt_audio_url'] ) ) {
			update_post_meta( $post_id, '_ppt_audio_url', esc_url_raw( $_POST['ppt_audio_url'] ) );
		}
		if ( isset( $_POST['ppt_podcast_duration'] ) ) {
			update_post_meta( $post_id, '_ppt_podcast_duration', sanitize_text_field( $_POST['ppt_podcast_duration'] ) );
		}
		if ( isset( $_POST['ppt_podcast_host'] ) ) {
			update_post_meta( $post_id, '_ppt_podcast_host', sanitize_text_field( $_POST['ppt_podcast_host'] ) );
		}

		// 6. Video fields.
		if ( isset( $_POST['ppt_aparat_id'] ) ) {
			$raw_id = sanitize_text_field( $_POST['ppt_aparat_id'] );
			if ( preg_match( '/aparat\.com\/v\/([a-zA-Z0-9]+)/i', $raw_id, $matches ) ) {
				$raw_id = $matches[1];
			}
			update_post_meta( $post_id, '_ppt_aparat_id', $raw_id );
		}
		if ( isset( $_POST['ppt_video_duration'] ) ) {
			update_post_meta( $post_id, '_ppt_video_duration', sanitize_text_field( $_POST['ppt_video_duration'] ) );
		}

		// 7. Manual Schema.
		if ( isset( $_POST['ppt_manual_schema'] ) ) {
			update_post_meta( $post_id, '_ppt_manual_schema', trim( $_POST['ppt_manual_schema'] ) );
		}
	}
}
