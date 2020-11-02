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

    $paged_rx = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
            $args_interviews = array(
                'post_type' => 'post',
                'post__not_in' => array ($ft_post_id),
                'posts_per_page' => 1,
                'paged' => $paged_rx
            );
            $rx_posts_query = new WP_Query( $args_interviews );

        ?>

        <?php


            if ( !is_paged() ) :
                $args_first_ft_post = array(
                    'cat'               => 3, // features
                    'post_type'         => 'post',
                    'posts_per_page'    => 1
                );

                $rx_ft_post_query = new WP_Query( $args_first_ft_post );

                if($rx_ft_post_query->have_posts()) : 
                    while($rx_ft_post_query->have_posts()) :
                        $rx_ft_post_query->the_post();

                        // needed later to exclude from next query
                        $ft_post_id =  get_the_ID();
                ?>

                    <article>
                        <h2 class="entry-title"><?php the_title(); ?></h2>
                        <p class="rx-post-date"><time><?php the_date(); ?></time></p>
                        <?php if(has_post_thumbnail()) { 
                            the_post_thumbnail('medium_large');
                        } ?>
                        <p class="rx-post-excerpt"><?php the_excerpt(); ?>
                    </article>

                    <?php endwhile; 
                    wp_reset_postdata(); 
                endif; 
            endif; ?>

		<?php

            if($rx_posts_query->have_posts()) : 
                while($rx_posts_query->have_posts()) :
                    $rx_posts_query->the_post();
            ?>
                <article>
                    <h2 class="entry-title"><?php the_title(); ?></h2>
                    <p class="rx-post-date"><time><?php the_date(); ?></time></p>
                    <?php if(has_post_thumbnail()) { 
                        the_post_thumbnail('medium_large');
                    } ?>
                    <p class="rx-post-excerpt"><?php the_excerpt(); ?>
                </article>
            
            <?php endwhile; ?>

            <?php
 
                $big = 999999999; // need an unlikely integer
                $translated = __( 'Page', 'mytextdomain' ); // Supply translatable string
                
                echo paginate_links( array(
                    'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                    'format' => '?paged=%#%',
                    'current' => max( 1, get_query_var('paged') ),
                    'total' => $rx_posts_query->max_num_pages,
                        'before_page_number' => '<span class="screen-reader-text">'.$translated.' </span>'
                ) );
            ?>

        <?php wp_reset_postdata(); 
        endif; ?>


	</div><!-- #primary -->


<?php get_footer(); ?>
