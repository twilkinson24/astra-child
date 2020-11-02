<?php
/*
 * Template Name: Interviews Archive
 * description: >-
  Page template for the Interviews archive
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header(); ?>

    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); 
                // normal page content
                the_content();
            endwhile;
        endif; 
    ?>

	<div id="primary" <?php astra_primary_class(); ?>>

        <?php

            $args_first_ft_post = array(
                'cat'               => 3, // features
                'post_type'         => 'post',
                'posts_per_page'    => 1
            );

            $rx_ft_post_query = new WP_Query( $args_first_ft_post );

            if($rx_ft_post_query->have_posts()) : 
                $rx_ft_post_query->the_post();
            ?>

                <article>
                    <h2 class="entry-title"><?php the_title(); ?></h2>

                    <p><?php the_date(); ?></p>

                    <?php if(has_post_thumbnail()) { 
                        the_post_thumbnail('medium_large');
                    } ?>

                    <p><?php the_excerpt(); ?>


                </article>
                
        <?php endif; ?>

		<?php

            $args_interviews = array(
                'post_type' => 'post',
                'posts_per_page' => 11
            );
            
            $rx_posts_query = new WP_Query( $args );

        ?>


	</div><!-- #primary -->


<?php get_footer(); ?>
