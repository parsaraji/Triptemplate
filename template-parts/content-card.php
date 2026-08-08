<?php
/**
 * Global Editorial Post/CPT Grid Card Template Part
 * Refined to prevent any broken fallback images and display a styled CSS fallback instead.
 *
 * @package Premium_Persian_Tourism
 */

$post_type = get_post_type();
$badge_label = '';

if ( 'destination' === $post_type ) {
	$badge_label = 'مقصد سفر';
} elseif ( 'attraction' === $post_type ) {
	$badge_label = 'جاذبه دیدنی';
} elseif ( 'guide' === $post_type ) {
	$badge_label = 'راهنمای سفر';
} elseif ( 'podcast' === $post_type ) {
	$badge_label = 'شنیداری (پادکست)';
} elseif ( 'video' === $post_type ) {
	$badge_label = 'ویدیویی (سفر تصویری)';
} else {
	$badge_label = 'مطلب عمومی';
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'ppt-card' ); ?>>
	<div class="card-img-wrapper">
		<a href="<?php the_permalink(); ?>">
			<?php if ( has_post_thumbnail() ) : ?>
				<?php the_post_thumbnail( 'ppt-card-thumb', array( 'alt' => get_the_title() ) ); ?>
			<?php else : ?>
				<!-- Beautiful CSS-based placeholder container to avoid 404 console errors of missing jpg assets -->
				<div class="ppt-css-fallback-thumb" style="width:100%; height:100%; min-height:180px; background: linear-gradient(135deg, #2B6CB0 0%, #1A365D 100%); display:flex; flex-direction:column; justify-content:center; align-items:center; color:#FFF; padding:15px; text-align:center;">
					<span style="font-size:32px; margin-bottom:5px;">🎙️</span>
					<span style="font-size:12px; font-weight:bold; opacity:0.8;"><?php bloginfo( 'name' ); ?></span>
				</div>
			<?php endif; ?>
		</a>
		<span class="card-badge"><?php echo esc_html( $badge_label ); ?></span>
	</div>

	<div class="card-content">
		<div class="card-meta-info">
			<!-- Show Province Category if assigned -->
			<?php
			$provinces = get_the_terms( get_the_ID(), 'province' );
			if ( ! empty( $provinces ) && ! is_wp_error( $provinces ) ) {
				echo '<span class="meta-prov">' . esc_html( $provinces[0]->name ) . '</span>';
			}
			?>
			<span class="meta-date"><?php echo esc_html( get_the_date() ); ?></span>
		</div>

		<h3 class="card-title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>

		<p class="card-excerpt">
			<?php echo esc_html( wp_trim_words( get_the_excerpt(), 18, '...' ) ); ?>
		</p>

		<div class="card-footer">
			<a href="<?php the_permalink(); ?>" class="read-more-link" style="font-weight:bold; color:#3182CE;">مطالعه کامل &larr;</a>
			<?php if ( 'podcast' === $post_type ) : ?>
				<span class="duration-meta" style="color:#E53E3E; font-weight:bold;">⏱️ <?php echo esc_html( get_post_meta( get_the_ID(), '_ppt_podcast_duration', true ) ); ?></span>
			<?php elseif ( 'video' === $post_type ) : ?>
				<span class="duration-meta" style="color:#38A169; font-weight:bold;">▶️ <?php echo esc_html( get_post_meta( get_the_ID(), '_ppt_video_duration', true ) ); ?></span>
			<?php endif; ?>
		</div>
	</div>
</article>
