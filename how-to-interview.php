<?php
/*
 * Template Name: How To Interview
 * description: >-
 * This page template is the SAME as "Top Banner" except this template enqueues
 * our custom JS
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

	<?php 

	if(is_user_logged_in()) {

		echo 'logged in';
		
		$current_user_ID = get_current_user_id();


	
		update_user_meta( $current_user_ID, 'user_phone', '239-222-2222'); 

		

		$all_meta_for_user = get_user_meta( $current_user_ID );
		print_r( $all_meta_for_user );

		//https://wordpress.stackexchange.com/questions/216140/update-user-meta-using-with-ajax
	  
	}
	?>

		<?php astra_primary_content_top(); ?>

		<?php astra_content_page_loop(); ?>

		<?php astra_primary_content_bottom(); ?>

	</div><!-- #primary -->

<?php if ( astra_page_layout() == 'right-sidebar' ) : ?>

	<?php get_sidebar(); ?>

<?php endif ?>

<?php get_footer(); ?>