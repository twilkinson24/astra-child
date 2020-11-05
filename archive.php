<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Astra
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function word_count($string, $limit) {
    $words = explode(' ', $string);
    return implode(' ', array_slice($words, 0, $limit));  
}

get_header(); ?>



<?php if ( astra_page_layout() == 'left-sidebar' ) : ?>

	<?php get_sidebar(); ?>

<?php endif ?>

	<div id="primary" <?php astra_primary_class(); ?>>

		<?php astra_primary_content_top(); ?>
		<?php astra_archive_header(); ?>


        <?php 
            // astra_content_loop(); 

            if(have_posts()) : ?>
                <div class="posts-wrapper">

                    <?php while(have_posts()) : the_post(); ?>

                    <article>
                            <header>
                                <a href="<?php the_permalink(); ?>">
                                    <h2 class="entry-title"><?php the_title(); ?></h2>
                                </a>
                                <p class="rx-post-date"><time><?php echo get_the_date(); ?></time></p>
                            </header>
                            <?php if(has_post_thumbnail()) : ?>
                                <a href="<?php the_permalink(); ?>" class="rx-ft-img-wrap">
                                    <?php the_post_thumbnail('medium_large'); ?>
                                </a>
                            <?php endif; ?>
                            <p class="rx-post-excerpt">
                                <?php
                                    // word_count function above
                                    echo sprintf("%s&hellip;", word_count(get_the_excerpt(), 50)); 
                                ?>
                            </p>
                            <a class="rx-read-more" href="<?php the_permalink(); ?>">
                                Meet <?php the_title(); ?>
                            </a>
                            <footer>
                                <p class="rx-cats">
                                    <?php echo get_the_category_list(', '); ?>
                                <p>
                            </footer>
                        </article>

                    <?php endwhile; ?>

                </div>
            <?php endif; ?>


		<?php astra_pagination(); ?>

		<?php astra_primary_content_bottom(); ?>

	</div><!-- #primary -->

<?php if ( astra_page_layout() == 'right-sidebar' ) : ?>

	<?php get_sidebar(); ?>

<?php endif ?>

<?php get_footer(); ?>
