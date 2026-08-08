<?php
/**
 * Standard single blog post template fallback
 *
 * @package Premium_Persian_Tourism
 */

get_header(); ?>

<div class="container" style="margin-top: 40px; margin-bottom: 50px;">
	<div class="layout-with-sidebar">

		<main class="site-main card-box" style="line-height:1.9;">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header class="entry-header" style="margin-bottom:20px;">
						<h1 class="entry-title" style="font-size:28px; color:#1A202C;"><?php the_title(); ?></h1>
						<div class="entry-meta text-muted" style="font-size:13px; margin-top:10px;">
							<span>انتشار در تاریخ: <?php echo esc_html( get_the_date() ); ?></span>
						</div>
					</header>

					<?php if ( has_post_thumbnail() ) : ?>
						<div class="post-thumbnail" style="border-radius:12px; overflow:hidden; margin-bottom:25px;">
							<?php the_post_thumbnail( 'full' ); ?>
						</div>
					<?php endif; ?>

					<div class="entry-content text-justify">
						<?php the_content(); ?>
					</div>

					<!-- Comments Area -->
					<div class="comments-section-wrapper" style="margin-top: 50px; border-top: 2px solid #EDF2F7; padding-top: 30px;">
						<?php
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;
						?>
					</div>
				</article>
				<?php
			endwhile;
			?>
		</main>

		<!-- Right Column Sidebar fallback -->
		<aside class="sidebar-right">
			<?php if ( is_active_sidebar( 'main-sidebar' ) ) : ?>
				<?php dynamic_sidebar( 'main-sidebar' ); ?>
			<?php endif; ?>
		</aside>

	</div>
</div>

<?php get_footer(); ?>
