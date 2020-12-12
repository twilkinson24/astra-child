<?php
/*
 * Template Name: Top Banner
 * description: >-
  Page template for the Interviews archive
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header(); ?>

<?php if ( has_post_thumbnail() ) : ?>
	</div> <!-- close container -->
	<div class="fw-ft-img">
		<div class="overlay">
			<div class="title-wrap">
				<h1>
					<?php echo get_the_title(); ?>
				</h1>
			</div>
		</div>
		<?php the_post_thumbnail(); ?>	
	</div>
	<div class="ast-container"><!-- open container again -->
<?php endif; ?>

	<div id="primary" <?php astra_primary_class(); ?>>

		<?php astra_primary_content_top(); ?>

		<?php astra_content_page_loop(); ?>

		<?php astra_primary_content_bottom(); ?>

	</div><!-- #primary -->

<?php if ( astra_page_layout() == 'right-sidebar' ) : ?>

	<?php get_sidebar(); ?>

<?php endif ?>

<?php get_footer(); ?>