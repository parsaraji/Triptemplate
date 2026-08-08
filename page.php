<?php
/**
 * Fallback static page template
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
					</header>

					<div class="entry-content text-justify">
						<?php the_content(); ?>
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
