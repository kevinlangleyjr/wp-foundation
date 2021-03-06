<?php
/**
 * The template for displaying all single posts.
 *
 * @package WP Foundation
 */

get_header(); ?>
<div class="row">
	<section id="primary" class="content-area large-8 columns">
		<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', 'single' ); ?>

			<?php wp_foundation_post_nav(); ?>

			<?php
				// If comments are open or we have at least one comment, load up the comment template
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;
			?>

		<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</section><!-- #primary -->

	<?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>
