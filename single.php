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
        $rx_author_nickname = get_the_author_meta( 'nickname', $author_id );
        $rx_author_link = get_author_posts_url( $author_id );
        $rx_post_date = get_the_date( 'F j, Y' ); 
        // $rx_author_avatar = get_avatar(get_the_author_meta('ID')); 


        echo '<a href="' . $rx_author_link . '">' . $rx_author_name . '</a>';
        echo " • ";
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

        <footer class="article-footer">
            <p class="rx-cats">
                <?php echo get_the_category_list(', '); ?>
            </p>
            <?php 
                echo get_the_tag_list( sprintf( '<p class="rx-tags"> ', __( 'Tags', 'textdomain' ) ), ', ', '</p>' );
            ?>




            <p class="rx-prev-post m-0">
                <?php 
                $rx_prev_post = get_previous_post();
                if ( is_a( $rx_prev_post , 'WP_Post' ) ) : ?>
                    <a href="<?php echo get_permalink( $rx_prev_post->ID ); ?>"><?php echo get_the_title( $rx_prev_post->ID ); ?></a>
                <?php endif; ?>
            </p>
            <p class="rx-next-post m-0">
                <?php 
                $next_post = get_next_post();
                if ( is_a( $next_post , 'WP_Post' ) ) : ?>
                    <a href="<?php echo get_permalink( $next_post->ID ); ?>"><?php echo get_the_title( $next_post->ID ); ?></a>
                <?php endif; ?>
            </p>
            
        </footer>

		<?php astra_primary_content_bottom(); ?>

        


	</div><!-- #primary -->

<?php if ( astra_page_layout() == 'right-sidebar' ) : ?>

	<?php get_sidebar(); ?>

<?php endif ?>

<?php get_footer(); ?>
