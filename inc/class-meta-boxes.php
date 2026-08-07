<?php
/**
 * Non-Gutenberg Custom Meta Boxes
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
		// Destination meta box
		add_meta_box(
			'ppt_destination_details',
			'اطلاعات و ویژگی‌های مقصد گردشگری',
			array( $this, 'render_destination_meta_box' ),
			'destination',
			'normal',
			'high'
		);

		// Attraction meta box
		add_meta_box(
			'ppt_attraction_details',
			'اطلاعات و ویژگی‌های جاذبه گردشگری',
			array( $this, 'render_attraction_meta_box' ),
			'attraction',
			'normal',
			'high'
		);

		// Guide meta box (repeatable budget list & FAQ)
		add_meta_box(
			'ppt_guide_details',
			'اطلاعات راهنما و جداول هزینه‌ها',
			array( $this, 'render_guide_meta_box' ),
			'guide',
			'normal',
			'high'
		);

		// Podcast meta box
		add_meta_box(
			'ppt_podcast_details',
			'تنظیمات فایل صوتی پادکست',
			array( $this, 'render_podcast_meta_box' ),
			'podcast',
			'normal',
			'high'
		);

		// Video meta box
		add_meta_box(
			'ppt_video_details',
			'کد یا لینک آپارات ویدیو',
			array( $this, 'render_video_meta_box' ),
			'video',
			'normal',
			'high'
		);
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

		// 3. Guide fields (FAQs and Budgets).
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
			// If nonce exists but faqs is missing, it means FAQs were emptied out
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

		// 4. Podcast fields.
		if ( isset( $_POST['ppt_audio_url'] ) ) {
			update_post_meta( $post_id, '_ppt_audio_url', esc_url_raw( $_POST['ppt_audio_url'] ) );
		}
		if ( isset( $_POST['ppt_podcast_duration'] ) ) {
			update_post_meta( $post_id, '_ppt_podcast_duration', sanitize_text_field( $_POST['ppt_podcast_duration'] ) );
		}
		if ( isset( $_POST['ppt_podcast_host'] ) ) {
			update_post_meta( $post_id, '_ppt_podcast_host', sanitize_text_field( $_POST['ppt_podcast_host'] ) );
		}

		// 5. Video fields.
		if ( isset( $_POST['ppt_aparat_id'] ) ) {
			$raw_id = sanitize_text_field( $_POST['ppt_aparat_id'] );
			// Extra safety: Extract ID if full URL is pasted.
			if ( preg_match( '/aparat\.com\/v\/([a-zA-Z0-9]+)/i', $raw_id, $matches ) ) {
				$raw_id = $matches[1];
			}
			update_post_meta( $post_id, '_ppt_aparat_id', $raw_id );
		}
		if ( isset( $_POST['ppt_video_duration'] ) ) {
			update_post_meta( $post_id, '_ppt_video_duration', sanitize_text_field( $_POST['ppt_video_duration'] ) );
		}
	}
}
