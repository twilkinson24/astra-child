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
<header class="page-header">
    <h1><?php echo get_the_title(); ?></h1>

    <!-- Add Interviewee's title -->

    <?php 
        global $post;
        $author_id = $post->post_author;

        $rx_author_name = get_the_author_meta( 'nicename', $author_id );
        $rx_author_link = get_author_posts_url( $author_id );
        $rx_post_date = get_the_date( 'F j, Y' ); 

        echo '<a href="' . $rx_author_link . '">' . $rx_author_name . '</a>';

        echo $rx_post_date;

    ?>
</header>

<div class="ast-container">

<?php if ( astra_page_layout() == 'left-sidebar' ) : ?>

	<?php get_sidebar(); ?>

<?php endif ?>

    <div id="primary" <?php astra_primary_class(); ?>>

		<?php astra_primary_content_top(); ?>

		<?php astra_content_loop(); ?>

		<?php astra_primary_content_bottom(); ?>

	</div><!-- #primary -->

<?php if ( astra_page_layout() == 'right-sidebar' ) : ?>

	<?php get_sidebar(); ?>

<?php endif ?>

<?php get_footer(); ?>
