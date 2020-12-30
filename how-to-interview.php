<?php
/*
 * Template Name: How To Interview
 * description: >-
 * 
 * 
 * !! This page template is the same as "Top Banner" except this template enqueues
 * our custom JS !!
 * 
 * 
 * 
 * 
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

<!-- saved progress message -->
<?php 
if (class_exists('ACF')) :

	// ACF Field
	$saved_progress_message  = get_field('interview_progress_saved_notification');

	if($saved_progress_message) : ?>

		<div id="saved-notification">
			<div class="p-relative">
				<button role="button" class="close-notification-btn">
					<span class="screen-reader-text">Close Notification</span>
				</button>
				<p><?php echo $saved_progress_message; ?></p>
			</div>
		</div>

	<?php else : // ACF installed but no field filled out ?>

	<div id="saved-notification">
		<div class="p-relative">
			<button role="button" class="close-notification-btn">
				<span class="screen-reader-text">Close Notification</span>
			</button>
			<p>Your progress has been saved!</p>
		</div>
	</div>
	<?php endif; ?>

<?php else : ?>

	<div id="saved-notification">
		<div class="p-relative">
			<button role="button" class="close-notification-btn">
				<span class="screen-reader-text">Close Notification</span>
			</button>
			<p>Your progress has been saved!</p>
		</div>
	</div>

<?php endif; // if ACF active ?>


<?php get_footer(); ?>