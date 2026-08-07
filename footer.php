<?php
/**
 * Global Editorial Footer template
 *
 * @package Premium_Persian_Tourism
 */
?>

<!-- Render Content Ad Bottom Slot if configured -->
<?php
if ( class_exists( 'PPT_Ad_Manager' ) ) {
	PPT_Ad_Manager::render_ad_slot( 'content_ad_bottom' );
}
?>

<footer class="site-footer">
	<div class="container">
		<div class="footer-grid">
			<div>
				<h3>درباره رادیو سفر</h3>
				<p style="font-size:14px; line-height:1.7;">پلتفرم مستقل و بومی معرفی دیدنی‌های گردشگری ایران. همراه با کامل‌ترین راهنماهای صوتی، پادکست‌های اختصاصی و نقشه آنلاین مسیرهای گردشگری.</p>
			</div>
			<div>
				<h3>دسترسی سریع</h3>
				<?php
				wp_nav_menu( array(
					'theme_location' => 'footer',
					'menu_class'     => 'footer-links-list',
					'fallback_cb'    => false,
				) );
				?>
			</div>
			<div>
				<h3>دسته‌بندی‌های پیشنهادی</h3>
				<ul class="footer-links-list">
					<li><a href="<?php echo esc_url( get_post_type_archive_link( 'destination' ) ); ?>">مقاصد تفریحی و گردشگری</a></li>
					<li><a href="<?php echo esc_url( get_post_type_archive_link( 'attraction' ) ); ?>">جاذبه‌های دیدنی ایران</a></li>
					<li><a href="<?php echo esc_url( get_post_type_archive_link( 'guide' ) ); ?>">راهنماهای کاربردی سفر</a></li>
					<li><a href="<?php echo esc_url( get_post_type_archive_link( 'podcast' ) ); ?>">شنیدن پادکست رادیو سفر</a></li>
				</ul>
			</div>
		</div>

		<div class="footer-bottom">
			<p>&copy; <?php echo esc_html( date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. تمامی حقوق این وب‌سایت محفوظ و متعلق به رادیو سفر می‌باشد.</p>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
