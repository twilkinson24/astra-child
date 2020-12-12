<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Astra
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header(); ?>

</div> <!-- end .container -->
<?php if(has_post_thumbnail()) : ?>
	<div class="ft-img-wrap">
		<?php the_post_thumbnail(); ?>		
	</div>
<?php endif; ?>

<?php if ( class_exists('ACF') ) : ?>	
	<?php 
		$rx_contact_field = get_field('rx_contact_interviewee');
		$rx_published_works = get_field('rx_published_works');
	?>
	<?php if($rx_contact_field || $rx_published_works) : ?>

		<div class="ast-container <?php if($rx_contact_field || $rx_published_works) { echo 'custom-sidebar'; } ?>">	
			
	<?php else : ?>
		<div class="ast-container">
	<?php endif; ?>
<?php endif; ?>

    <div id="primary" <?php astra_primary_class(); ?>>

		<?php astra_primary_content_top(); ?>

		<?php astra_content_loop(); ?>

        

		<?php astra_primary_content_bottom(); ?>

        


	</div><!-- #primary -->

<?php if ( class_exists('ACF') ) : ?>
	
	<?php if($rx_contact_field || $rx_published_works) : ?>
		<div class="widget-area secondary" id="secondary">
			<div class="sidebar-main custom">
				<aside>
					<?php if($rx_contact_field) : ?>
						<div class="widget">
							<?php echo $rx_contact_field; ?>
						</div>
					<?php endif; ?>
					<?php if($rx_published_works) : ?>
						<div class="widget">
							<?php echo $rx_published_works; ?>
						</div>
					<?php endif; ?>
				</aside>
			</div>
		</div>
	<?php endif; ?>
			
<?php endif ?>

<?php get_footer(); ?>
