<?php
/**
 * Astra Child Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Astra Child
 * @since 1.0.0
 */

/**
 * Define Constants
 */
define( 'CHILD_THEME_ASTRA_CHILD_VERSION', '1.0.0' );

/**
 * Enqueue styles
 */
function child_enqueue_styles() {

	wp_enqueue_style( 'astra-child-theme-css', get_stylesheet_directory_uri() . '/style.css', array('astra-theme-css'), CHILD_THEME_ASTRA_CHILD_VERSION, 'all' );
	
    if(is_page_template('how-to-interview.php')) {
		wp_enqueue_script( 'custom-interviews-js', get_stylesheet_directory_uri() . '/js/custom-interviews.js', array('jquery'), '0', true );
	}

}

add_action( 'wp_enqueue_scripts', 'child_enqueue_styles', 15 );



/* Custom  Below */
// Change default excerpt
function rx_new_excerpt_read_more($more) {
	global $post;
	return '<a class="rx-read-more" href="'. get_permalink($post->ID) . '">Meet ' . get_the_title($post->ID) . '</a>';
}
add_filter('excerpt_more', 'rx_new_excerpt_read_more');



// Remove default Astra Prev/Next page from single posts
add_filter( 'astra_single_post_navigation_enabled', '__return_false' );


/**
 * Disable Featured image on all post types.
 */
function rx_featured_image() {
 $post_types = array('post', 'page');

 // bail early if the current post type if not the one we want to customize.
 if ( ! in_array( get_post_type(), $post_types ) ) {
 return;
 }
 
 // Disable featured image.
 add_filter( 'astra_featured_image_enabled', '__return_false' );
}

add_action( 'wp', 'rx_featured_image' );


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


// used for CF7 to interview submission
function rx_update_post_meta( $post_id, $field_name, $value = '' ) {
    if ( empty( $value ) OR ! $value )
    {
        delete_post_meta( $post_id, $field_name );
    }
    elseif ( ! get_post_meta( $post_id, $field_name ) )
    {
        add_post_meta( $post_id, $field_name, $value );
    }
    else
    {
        update_post_meta( $post_id, $field_name, $value );
    }
}


// used for CF7 to interview submission
function Generate_Featured_Image( $image_url, $post_id  ){
    $upload_dir = wp_upload_dir();
    $image_data = file_get_contents($image_url);
    $filename = basename($image_url);
    if(wp_mkdir_p($upload_dir['path']))
      $file = $upload_dir['path'] . '/' . $filename;
    else
      $file = $upload_dir['basedir'] . '/' . $filename;
    file_put_contents($file, $image_data);

    $wp_filetype = wp_check_filetype($filename, null );
    $attachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_title' => sanitize_file_name($filename),
        'post_content' => '',
        'post_status' => 'inherit'
    );
    $attach_id = wp_insert_attachment( $attachment, $file, $post_id );
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
    $res1= wp_update_attachment_metadata( $attach_id, $attach_data );
    $res2= set_post_thumbnail( $post_id, $attach_id );
}



// NEW TEST
function rx_create_attachment($filename, $postID)
{
    // Check the type of file. We'll use this as the 'post_mime_type'.
    $filetype = wp_check_filetype(basename($filename), null);

    // Get the path to the upload directory.
    $wp_upload_dir = wp_upload_dir();

    $attachFileName = $wp_upload_dir['path'] . '/' . basename($filename);
    copy($filename, $attachFileName);
    // Prepare an array of post data for the attachment.
    $attachment = array(
        'guid'           => $attachFileName,
        'post_mime_type' => $filetype['type'],
        'post_title'     => preg_replace('/\.[^.]+$/', '', basename($filename)),
        'post_content'   => '',
        'post_status'    => 'inherit'
    );

    // Insert the attachment.
    $attach_id = wp_insert_attachment($attachment, $attachFileName);

    // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
    require_once(ABSPATH . 'wp-admin/includes/image.php');

    // Generate the metadata for the attachment, and update the database record.
    $attach_data = wp_generate_attachment_metadata($attach_id, $attachFileName);
	wp_update_attachment_metadata($attach_id, $attach_data);
	
	update_post_meta($postID, "_thumbnail_id", $attach_id);


    return $attach_data;
}





// https://wordpress.stackexchange.com/questions/328429/how-to-save-contact-form-7-data-in-custom-post-types-cpt/328447
// useful hooks - we need to submit 'before sent' for ft image
// add_action('wpcf7_mail_sent','save_my_form_data_to_my_cpt');
// add_action('wpcf7_mail_failed','save_my_form_data_to_my_cpt');

/*
*	Everything below is for interview submission through CF7
*/
add_action('wpcf7_before_send_mail','save_my_form_data_to_my_cpt');

function save_my_form_data_to_my_cpt($contact_form){
	
	
    $submission = WPCF7_Submission::get_instance();
    if (!$submission){
        return;
    }
	
	$uploaded_files = $submission->uploaded_files();
	
    $posted_data = $submission->get_posted_data();
    //The Sent Fields are now in an array
    //Let's say you got 4 Fields in your Contact Form
    //my-email, my-name, my-subject and my-message
    //you can now access them with $posted_data['my-email']
    //Do whatever you want like:
   
	 if(isset($posted_data['your-name'])) {
		 return;
		 // This is IMPORTANT!; return if it's the general Contact form
	 }
		
    $new_post = array();
    if(isset($posted_data['interview-first-name']) && !empty($posted_data['interview-first-name'])){
        $new_post['post_title'] = $posted_data['interview-first-name'] . ' ' . $posted_data['interview-last-name'];
    } else {
        $new_post['post_title'] = 'Incomplete Submission';
    }
    $new_post['post_type'] = 'post'; //insert here your CPT
	

	
	if(isset($posted_data['interview-first-name'])){
        
		if(isset($posted_data['biography'])){
			if(strlen($posted_data['biography']) > 0) {
				$new_post['post_content'] .= '<h3>Biography</h3>';
				$new_post['post_content'] .= '<p>' . $posted_data['biography'] . '</p>';
				
				$new_post['post_excerpt'] .= $posted_data['biography'];
			} 
		}
		$new_post['post_content'] .= '<h3>Questions</h3>';
		
		if(isset($posted_data['question-1'])){
			if(strlen($posted_data['question-1']) > 0) {
				$new_post['post_content'] .= '<h4>Where did the idea for your business come from?</h4>';
					$new_post['post_content'] .= $posted_data['question-1'];
			}
		}
		if(isset($posted_data['question-2'])){
			if(strlen($posted_data['question-2']) > 0) {
				$new_post['post_content'] .= '<h4>How do you typically start your day?</h4>';
					$new_post['post_content'] .= $posted_data['question-2'];
			}
		}
		if(isset($posted_data['question-3'])){
			if(strlen($posted_data['question-3']) > 0) {
				$new_post['post_content'] .= '<h4>What is an interesting thing about you that few people know?</h4>';
					$new_post['post_content'] .= $posted_data['question-3'];
			}
		}
		
		if(isset($posted_data['question-4'])){
			if(strlen($posted_data['question-4']) > 0) {
				$new_post['post_content'] .= '<h4>What three performance metrics do you pay most attention to?</h4>';
					$new_post['post_content'] .= $posted_data['question-4'];
			}
		}
		if(isset($posted_data['question-5'])){
			if(strlen($posted_data['question-5']) > 0) {
				$new_post['post_content'] .= '<h4>What business software can you not live without?</h4>';
					$new_post['post_content'] .= $posted_data['question-5'];
			}
		}
		if(isset($posted_data['question-6'])){
			if(strlen($posted_data['question-6']) > 0) {
				$new_post['post_content'] .= "<h4>What is the best advice you've ever been given?</h4>";
					$new_post['post_content'] .= $posted_data['question-6'];
			}
		}
		if(isset($posted_data['question-7'])){
			if(strlen($posted_data['question-7']) > 0) {
				$new_post['post_content'] .= "<h4>What is the best book recommendation for young businesspeople?</h4>";
					$new_post['post_content'] .= $posted_data['question-7'];
			}
		}
		if(isset($posted_data['question-8'])){
			if(strlen($posted_data['question-8']) > 0) {
				$new_post['post_content'] .= "<h4>How will your industry change in the next five years?</h4>";
					$new_post['post_content'] .= $posted_data['question-8'];
			}
		}
		if(isset($posted_data['own-question'])){
			if(strlen($posted_data['own-question']) > 0) {
					$new_post['post_content'] .= '<h4>Custom question: ' . $posted_data['own-question'] . '</h4>';
			}
		}
		if(isset($posted_data['answer-own-question'])){
			if(strlen($posted_data['answer-own-question']) > 0) {
					$new_post['post_content'] .= $posted_data['answer-own-question'];
			}
		}	
		if(isset($posted_data['your-headshot'])){ 

			foreach($uploaded_files as $result) {
				$the_file = $result;
			}

		}
		
    } else {
        $new_post['post_content'] = 'No Message was submitted';
    }
    $new_post['post_status'] = 'draft';
    //you can also build your post_content from all of the fields of the form, or you can save them into some meta fields
    if(isset($posted_data['your-email']) && !empty($posted_data['your-email'])){
        $new_post['meta_input']['sender_email_address'] = $posted_data['your-email'];
    }
    if(isset($posted_data['my-name']) && !empty($posted_data['my-name'])){
        $new_post['meta_input']['sender_name'] = $posted_data['my-name'];
    }
	
	
	
 	   // When everything is prepared, insert the post into your Wordpress Database
        if($post_id = wp_insert_post($new_post)){
       //Everything worked, you can stop here or do whatever
       
			 if(isset($posted_data['your-headshot'])){ 

				rx_create_attachment($the_file, $post_id);
			 }

			
			
			if(strlen($posted_data['linkedin-url']) > 0 || strlen($posted_data['twitter-url']) > 0 || strlen($posted_data['crunchbase-url']) > 0 || strlen($posted_data['instagram-url']) > 0 || strlen($posted_data['wikipedia-url']) > 0)  {

				
				$contact_interviewee_text .= '<h3 class="widget-title">Contact ' . $posted_data['interview-first-name'] . ' ' . $posted_data['interview-last-name'] . '</h3>';
				
				$contact_interviewee_text .= '<ul>';
				
					if(strlen($posted_data['linkedin-url']) > 0) {
						$contact_interviewee_text .= '<li><a href="' . $posted_data['linkedin-url'] . '" target="_blank">Linkedin</a></li>';
					}
					if(strlen($posted_data['twitter-url']) > 0) {
						$contact_interviewee_text .= '<li><a href="' . $posted_data['twitter-url'] . '" target="_blank">Twitter</a></li>';	
					}
					if(strlen($posted_data['crunchbase-url']) > 0) {
						$contact_interviewee_text .= '<li><a href="' . $posted_data['crunchbase-url'] . '" target="_blank">Crunchbase</a></li>';
					}
					if(strlen($posted_data['instagram-url']) > 0) {
						$contact_interviewee_text .= '<li><a href="' . $posted_data['instagram-url'] . '" target="_blank">Instagram</a></li>';
					}
					if(strlen($posted_data['wikipedia-url']) > 0) {
						$contact_interviewee_text .= '<li><a href="' . $posted_data['wikipedia-url'] . '" target="_blank">Wikipedia</a></li>';
					}
				
				$contact_interviewee_text .= '</ul>';
				
				// Add ACF Field data
				rx_update_post_meta($post_id, 'rx_contact_interviewee', $contact_interviewee_text);
			}
				
			if(strlen($posted_data['published-work-1']) > 0 || strlen($posted_data['published-work-2']) > 0 || strlen($posted_data['published-work-3']) > 0) {

					$interviewee_published_works_text .= '<h3 class="widget-title">Published Works</h3>';
					$interviewee_published_works_text .= '<ul>';

					if(strlen($posted_data['published-work-1']) > 0) {
						$interviewee_published_works_text .= '<li><a href="' . $posted_data['published-work-1'] . '" target="_blank">Work 1</a></li>';
					}
					if(strlen($posted_data['published-work-2']) > 0) {
						$interviewee_published_works_text .= '<li><a href="' . $posted_data['published-work-2'] . '" target="_blank">Work 2</a></li>';
					}
					if(strlen($posted_data['published-work-3']) > 0) {
						$interviewee_published_works_text .= '<li><a href="' . $posted_data['published-work-3'] . '" target="_blank">Work 3</a></li>';
					}
					$interviewee_published_works_text .= '</ul>';
				
					// Add ACF Field data
					rx_update_post_meta($post_id, 'rx_published_works', $interviewee_published_works_text);
				}
			

           // end Everything worked, you can stop here or do whatever
        } else {
           //The post was not inserted correctly, do something (or don't ;) )
        }
        return;
}


