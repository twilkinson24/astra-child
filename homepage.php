<?php
/*
 * Template Name: Homepage
 * description: >-
  Page template for the Interviews archive on the homepage
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
				if ( has_post_thumbnail() ) : ?>
					</div> <!-- close container -->
					<div class="fw-ft-img">
						<div class="overlay">
							<div class="title-wrap">
								<h1>
									<?php if (class_exists('ACF')) { // if ACF is installed
	
 											if(get_field('rx_custom_page_title')) {
												echo get_field('rx_custom_page_title');
											} else {
												echo get_the_title();
											 } 
									} else {
										echo get_the_title();
									} 
									?>
								</h1>
								
							</div>
						</div>
						<?php the_post_thumbnail(); ?>	
					</div>
					<div class="ast-container"><!-- open container again -->
				<?php endif; 
                // normal page content
                the_content();
            endwhile;
        endif; 
    ?>

	<div id="primary" <?php astra_primary_class(); ?>>
		<div class="posts-wrapper">

        <?php
        
      //  $paged_rx = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
        $args_interviews = array(
            'post_type' => 'post',
            'post__not_in' => array ($ft_post_id),
            'posts_per_page' => 10,
         //   'paged' => $paged_rx
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
			            

        <?php wp_reset_postdata(); 
        endif; ?>
		
		<?php if (class_exists('ACF')) {
			if(get_field('rx_interviews_page')) {
				echo get_field('rx_interviews_page');
			}
		} ?>


	</div><!-- #primary -->


<?php get_footer(); ?>