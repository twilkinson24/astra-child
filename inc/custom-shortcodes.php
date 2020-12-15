<?php 

/*
* Recent Posts shortcode for sidebar 
*/
// Custom Shortcode - RepX
function repx_recent_posts() {

	$repx_recent_post_args = array(
		'post_type' => 'post',
		'post_status' => 'publish',
		'posts_per_page' => 5,
		'order' => 'DESC'

	);

	$repx_recent_post_shortcode_query = new WP_Query( $repx_recent_post_args );

	if($repx_recent_post_shortcode_query->have_posts()) {
		$repx_shortcode_output = '';

		while($repx_recent_post_shortcode_query->have_posts()) : $repx_recent_post_shortcode_query->the_post();

			if(has_post_thumbnail(get_the_id())) {
				$repx_featured_img_url = get_the_post_thumbnail_url(get_the_id(),'small');
			} else {
				$repx_featured_img_url = get_template_directory_uri() . '/img/home-banner.svg';
			}

			// function to get and trim excerpt
			$repx_excerpt = get_the_excerpt();
			$repx_excerpt = preg_replace(" ([.*?])",'',$repx_excerpt);
			$repx_excerpt = strip_shortcodes($repx_excerpt);
			$repx_excerpt = strip_tags($repx_excerpt);
			$repx_excerpt = substr($repx_excerpt, 0, 200);
			$repx_excerpt = substr($repx_excerpt, 0, strripos($repx_excerpt, " "));
			$repx_excerpt .= '...';

			/* end variables - time to build shortcode */
			$repx_shortcode_output .= '<div class="repx-recent-post">';

				$repx_shortcode_output .= '<a href="' . get_the_permalink() . '">';
					$repx_shortcode_output .= '<h4 class="title">' . get_the_title() . '</h4>';
				$repx_shortcode_output .= '</a>';
				$repx_shortcode_output .= '<p class="post-date">' . get_the_date( 'F j, Y' ) . '</p>';
		
				$repx_shortcode_output .= '<div class="ft-img-wrap">';
					$repx_shortcode_output .= '<img src="' . $repx_featured_img_url . '" alt="' . get_the_title() . '">';  
				$repx_shortcode_output .= '</div>';
		
				$repx_shortcode_output .= '<p class="excerpt">' . $repx_excerpt . '</p>';
				$repx_shortcode_output .= '<p class="read-more"><a class="rx-read-more" href="' . get_the_permalink() . '">' .  get_the_title() . '</a></p>';
			$repx_shortcode_output .= '</div>';

		endwhile;
		wp_reset_postdata();  


	} else {
		$repx_shortcode_output = '<p>' . 'No posts were returned. Please remove the shortcode or add a blog post.' . '</p>'; 
	}

	return $repx_shortcode_output;

}
add_shortcode('show_5_recent_posts', 'repx_recent_posts');