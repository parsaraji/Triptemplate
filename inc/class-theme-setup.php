<?php
/**
 * Theme Setup Class
 * Includes Automatic Professional Persian Demo Content on activation.
 *
 * @package Premium_Persian_Tourism
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class PPT_Theme_Setup {

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
		add_action( 'after_setup_theme', array( $this, 'theme_supports' ) );
		add_action( 'after_setup_theme', array( $this, 'register_menus' ) );
		add_action( 'after_switch_theme', array( $this, 'create_default_content' ) );
		add_action( 'widgets_init', array( $this, 'register_sidebars' ) );
	}

	/**
	 * Add theme supports.
	 */
	public function theme_supports() {
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );

		add_image_size( 'ppt-card-thumb', 400, 250, true );
		add_image_size( 'ppt-hero-large', 1200, 500, true );

		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		) );

		add_theme_support( 'custom-logo', array(
			'height'      => 80,
			'width'       => 240,
			'flex-height' => true,
			'flex-width'  => true,
		) );
	}

	/**
	 * Register Menus.
	 */
	public function register_menus() {
		register_nav_menus( array(
			'primary' => esc_html__( 'منوی اصلی (Primary RTL Menu)', 'premium-persian-tourism' ),
			'footer'  => esc_html__( 'منوی فوتر (Footer RTL Links)', 'premium-persian-tourism' ),
		) );
	}

	/**
	 * Register sidebars.
	 */
	public function register_sidebars() {
		register_sidebar( array(
			'name'          => esc_html__( 'سایدبار عمومی (Sidebar)', 'premium-persian-tourism' ),
			'id'            => 'main-sidebar',
			'description'   => esc_html__( 'ابزارک‌های خود را اینجا قرار دهید.', 'premium-persian-tourism' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s card-box">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );
	}

	/**
	 * Create Default Pages and Rich Sample Content on Theme Activation.
	 */
	public function create_default_content() {
		// Prevent running multiple times.
		if ( '1' === get_option( 'ppt_default_content_created_v2' ) ) {
			return;
		}

		// 1. Create Default General Pages
		$this->create_default_page( 'درباره ما', 'about-us', 'این یک برگه درباره ما برای پلتفرم جامع رادیو سفر است. ما با رویکردی نوآورانه و تکیه بر پادکست‌های صوتی اختصاصی، راهنمای گردشگری تعاملی ایران زمین را بنا کرده‌ایم.' );
		$this->create_default_page( 'تماس با ما', 'contact-us', 'برای برقراری ارتباط با تحریریه رادیو سفر می‌توانید از طریق ایمیل info@example.com یا شماره تلفن ۰۲۱۸۸۸۸۸۸۸۸ در تماس باشید. آدرس ما: تهران، میدان ونک، پلاک ۱۲' );

		// 2. Generate Province Terms (استان‌ها)
		$provinces_slugs = array(
			'fars'            => 'فارس',
			'isfahan'         => 'اصفهان',
			'hormozgan'       => 'هرمزگان',
			'tehran'          => 'تهران',
			'mazandaran'      => 'مازندران'
		);
		$province_ids = array();
		foreach ( $provinces_slugs as $slug => $name ) {
			$term = wp_insert_term( $name, 'province', array( 'slug' => $slug ) );
			if ( ! is_wp_error( $term ) ) {
				$province_ids[ $slug ] = $term['term_id'];
			} else {
				$existing = get_term_by( 'slug', $slug, 'province' );
				if ( $existing ) {
					$province_ids[ $slug ] = $existing->term_id;
				}
			}
		}

		// 3. Create Sample Destination (مقصد سفر) - شیراز
		$shiraz_content = 'شیراز به عنوان پایتخت فرهنگی و شهر شعر و ادب ایران، سالانه میزبان میلیون‌ها گردشگر داخلی و خارجی است. این شهر با برخورداری از جاذبه‌های بی‌نظیر تاریخی، باغ‌های ایرانی منحصر به فرد، و مردمی بسیار صمیمی، یکی از مقاصد اجتناب‌ناپذیر سفر در ایران محسوب می‌شود.';
		$shiraz_id = $this->create_sample_post( 'destination', 'شیراز زیبا، شهر راز و شعر و ادب', $shiraz_content, array(
			'_ppt_best_time'  => 'اردیبهشت ماه (بهارنارنج)',
			'_ppt_weather'    => 'معتدل و دلپذیر',
			'_ppt_cost_level' => 'medium',
			'_ppt_lat'        => '29.5918',
			'_ppt_lng'        => '52.5837',
			'_ppt_faqs'       => array(
				array( 'q' => 'بهترین فصل برای سفر به شیراز چه زمانی است؟', 'a' => 'اردیبهشت ماه بهترین زمان سفر به شیراز است که عطر بهارنارنج تمام شهر را پر می‌کند.' ),
				array( 'q' => 'آیا شیراز فرودگاه بین‌المللی فعال دارد؟', 'a' => 'بله، فرودگاه بین‌المللی شهید دستغیب شیراز پروازهای مستقیم متعددی به داخل و خارج کشور دارد.' )
			)
		), isset( $province_ids['fars'] ) ? array( $province_ids['fars'] ) : array() );

		// 4. Create Sample Destination (مقصد سفر) - جزیره قشم
		$qeshm_content = 'جزیره شگفت‌انگیز قشم بزرگ‌ترین جزیره خلیج فارس است که به واسطه عجایب هفت‌گانه زمین‌شناسی خود شهرت جهانی دارد. دره‌ها، غارها و جنگل‌های حرای قشم مقصدی بی‌نظیر برای علاقه‌مندان به طبیعت‌گردی هستند.';
		$qeshm_id = $this->create_sample_post( 'destination', 'جزیره قشم و عجایب هفت‌گانه خلیج فارس', $qeshm_content, array(
			'_ppt_best_time'  => 'اواخر پاییز و کل زمستان',
			'_ppt_weather'    => 'گرم و مرطوب معتدل زمستانه',
			'_ppt_cost_level' => 'medium',
			'_ppt_lat'        => '26.9584',
			'_ppt_lng'        => '56.2618',
			'_ppt_faqs'       => array(
				array( 'q' => 'چگونه می‌توان با خودروی شخصی به قشم سفر کرد؟', 'a' => 'باید به بندر پل در استان هرمزگان بروید و از آنجا به وسیله لندینگ‌کرافت خودروی خود را به بندر لافت در قشم انتقال دهید.' )
			)
		), isset( $province_ids['hormozgan'] ) ? array( $province_ids['hormozgan'] ) : array() );

		// 5. Create Sample Attraction (جاذبه دیدنی) - تخت جمشید
		$persepolis_content = 'مجموعه باستانی تخت جمشید یا پرسپولیس، شاهکار معماری هخامنشیان است که در نزدیکی مرودشت استان فارس قرار دارد. این اثر ثبت میراث جهانی یونسکو بوده و نماد تمدن و تاریخ باشکوه ایران باستان است.';
		$this->create_sample_post( 'attraction', 'مجموعه باستانی تخت جمشید (پرسپولیس)', $persepolis_content, array(
			'_ppt_address'       => 'استان فارس، کیلومتر ۱۰ شمال مرودشت',
			'_ppt_opening_hours' => 'همه روزه از ساعت ۸:۰۰ صبح الی ۱۷:۳۰ عصر',
			'_ppt_ticket_price'  => '۵۰,۰۰۰ تومان برای گردشگر ایرانی',
			'_ppt_lat'           => '29.9357',
			'_ppt_lng'           => '52.8900',
		), isset( $province_ids['fars'] ) ? array( $province_ids['fars'] ) : array() );

		// 6. Create Sample Attraction (جاذبه دیدنی) - میدان نقش جهان
		$naghsh_content = 'میدان نقش جهان اصفهان از بزرگ‌ترین و زیباترین میدان‌های تاریخی جهان است که با مسجد شاه، مسجد شیخ لطف‌الله، عمارت عالی‌قاپو و سردر بازار قیصریه احاطه شده است. این میدان اوج هنر دوران صفوی را به تصویر می‌کشد.';
		$this->create_sample_post( 'attraction', 'میدان نقش جهان اصفهان (میراث صفوی)', $naghsh_content, array(
			'_ppt_address'       => 'اصفهان، خیابان سپه، میدان نقش جهان',
			'_ppt_opening_hours' => '۲۴ ساعته (بازدید از مساجد طبق ساعات شرعی)',
			'_ppt_ticket_price'  => 'رایگان برای محوطه میدان',
			'_ppt_lat'           => '32.6577',
			'_ppt_lng'           => '51.6773',
		), isset( $province_ids['isfahan'] ) ? array( $province_ids['isfahan'] ) : array() );

		// 7. Create Sample Guide (راهنمای سفر)
		$guide_content = 'سفر انفرادی یا کوله‌گردی نیازمند برنامه‌ریزی دقیق مالی و اطلاع از روش‌های کاهش هزینه‌ها است. در این راهنمای جامع یاد می‌گیریم که چطور با اقامت در اقامتگاه‌های بومی‌گردی و صرف غذاهای محلی، با کمترین بودجه بیشترین لذت را ببریم.';
		$this->create_sample_post( 'guide', 'راهنمای گام‌به‌گام سفر ارزان و اقتصادی به جنوب ایران', $guide_content, array(
			'_ppt_budget_items' => array(
				array( 'title' => 'یک شب اقامتگاه بوم‌گردی محلی با صبحانه', 'cost' => '۳۰۰,۰۰۰ تومان' ),
				array( 'title' => 'سه وعده غذای محلی (قلیه ماهی و میگو)', 'cost' => '۴۵۰,۰۰۰ تومان' ),
				array( 'title' => 'بلیط قایق‌سواری در جنگل حرای قشم', 'cost' => '۱۲۰,۰۰۰ تومان' )
			),
			'_ppt_faqs' => array(
				array( 'q' => 'آیا بوم‌گردی‌های قشم اینترنت مناسب دارند؟', 'a' => 'اکثر بوم‌گردی‌های معتبر در قشم مجهز به وای‌فای هستند یا پوشش شبکه همراه عالی دارند.' )
			)
		), isset( $province_ids['hormozgan'] ) ? array( $province_ids['hormozgan'] ) : array() );

		// 8. Create Sample Podcast (رادیو سفر)
		$podcast_content = 'در این اپیزود صمیمانه از رادیو سفر، چشم بر جهان می‌بندیم و با گوش دل به کوچه پس‌کوچه‌های تاریخی شیراز قدم می‌گذاریم. عطر بهارنارنج، نوای تار شاعران و تاریخ خواندنی این خطه را با صدای گوینده توانمندمان بشنوید.';
		$this->create_sample_post( 'podcast', 'رادیو سفر - اپیزود سوم: شیرازگردی در اردیبهشت ماه', $podcast_content, array(
			'_ppt_audio_url'        => 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3', // Legal sample MP3 link
			'_ppt_podcast_duration' => '۱۲:۳۵',
			'_ppt_podcast_host'     => 'مریم راد'
		) );

		// 9. Create Sample Video (سفر تصویری)
		$video_content = 'دره چاهکوه یکی از عجیب‌ترین شگفتی‌های زمین‌شناسی هرمزگان و جزیره قشم است. باد و باران در طول میلیون‌ها سال صخره‌هایی با حفره‌ها و اشکال تخیلی پدید آورده‌اند. تماشای این مستند کوتاه ویدیویی را از دست ندهید.';
		$this->create_sample_post( 'video', 'مستند ویدیویی: شکوه دره چاهکوه قشم', $video_content, array(
			'_ppt_aparat_id'     => 'fXgHe', // Realistic mock Aparat id
			'_ppt_video_duration' => '۰۵:۴۰'
		) );

		// Save flag to prevent repeated execution.
		update_option( 'ppt_default_content_created_v2', '1' );
	}

	/**
	 * Create standard pages helper.
	 */
	private function create_default_page( $title, $slug, $content ) {
		if ( ! get_page_by_path( $slug ) && ! get_page_by_title( $title ) ) {
			wp_insert_post( array(
				'post_title'   => $title,
				'post_content' => $content,
				'post_status'  => 'publish',
				'post_type'    => 'page',
			) );
		}
	}

	/**
	 * Create dynamic Custom Post Types with custom meta meta-data.
	 */
	private function create_sample_post( $post_type, $title, $content, $meta_fields = array(), $tax_provinces = array() ) {
		$post_id = wp_insert_post( array(
			'post_title'   => $title,
			'post_content' => $content,
			'post_status'  => 'publish',
			'post_type'    => $post_type,
			'post_excerpt' => wp_trim_words( $content, 20, '...' ),
		) );

		if ( ! is_wp_error( $post_id ) ) {
			// Attach Meta Data
			foreach ( $meta_fields as $key => $val ) {
				update_post_meta( $post_id, $key, $val );
			}

			// Attach Province taxonomy
			if ( ! empty( $tax_provinces ) ) {
				wp_set_post_terms( $post_id, $tax_provinces, 'province' );
			}
		}

		return $post_id;
	}

	/**
	 * Automatically generates and configures a clean WordPress Child Theme.
	 * Excludes manual folder-making requirements for administrators.
	 */
	public static function create_child_theme_automatically() {
		global $wp_filesystem;
		if ( empty( $wp_filesystem ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
			WP_Filesystem();
		}

		$parent_dir = get_template_directory();
		$child_slug = basename( $parent_dir ) . '-child';
		$child_dir  = dirname( $parent_dir ) . '/' . $child_slug;

		if ( ! $wp_filesystem->exists( $child_dir ) ) {
			$wp_filesystem->mkdir( $child_dir );
		}

		// 1. Write Child style.css
		$style_content = "/*\nTheme Name: Premium Persian Tourism Child\nTheme URI: https://example.com/premium-persian-tourism\nDescription: Child theme for Premium Persian Tourism\nAuthor: Senior Product Designer & Engineer\nTemplate: " . basename( $parent_dir ) . "\nVersion: 1.0.0\nText Domain: " . $child_slug . "\n*/\n";
		$wp_filesystem->put_contents( $child_dir . '/style.css', $style_content );

		// 2. Write Child functions.php
		$funcs_content = "<?php\n// Child Theme Functions\nadd_action( 'wp_enqueue_scripts', function() {\n\twp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );\n} );\n";
		$wp_filesystem->put_contents( $child_dir . '/functions.php', $funcs_content );

		return true;
	}
}
