<?php
/*
 * Template Name: Interviews Archive
 * description: >-
  Page template for the Interviews archive
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function word_count($string, $limit) {
    $words = explode(' ', $string);
    return implode(' ', array_slice($words, 0, $limit));  
}

get_header(); ?>

    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); 
                // normal page content
                the_content();
            endwhile;
        endif; 
    ?>

	<div id="primary" <?php astra_primary_class(); ?>>
         <div class="posts-wrapper">

        <?php
        
        $paged_rx = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
        $args_interviews = array(
            'post_type' => 'post',
            'post__not_in' => array ($ft_post_id),
            'posts_per_page' => 11,
            'paged' => $paged_rx
        );
        $rx_posts_query = new WP_Query( $args_interviews );

            if($rx_posts_query->have_posts()) : 
                while($rx_posts_query->have_posts()) :
                    $rx_posts_query->the_post();
            ?>
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
                    
                </article>
            
            <?php endwhile; ?>
    </div><!-- end .posts-wrapper -->
			<nav class="pagination">

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
			</nav>

        <?php wp_reset_postdata(); 
        endif; ?>


	</div><!-- #primary -->


<?php get_footer(); ?>
