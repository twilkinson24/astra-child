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
	
    if(is_page_template('how-to-interview.php') && is_user_logged_in()) {
		wp_enqueue_script( 'custom-interviews-js', get_stylesheet_directory_uri() . '/js/custom-interviews.js', array('jquery'), '0', true );

		wp_localize_script('custom-interviews-js', 'admin_url', array(
			'ajax_url' => admin_url('admin-ajax.php'),
			'home_url' => home_url()
		  ));


		wp_enqueue_script( 'retrieve-interview-js', get_stylesheet_directory_uri() . '/js/get-saved-form.js', array('jquery'), '0', true );

		wp_localize_script('retrieve-interview-js', 'admin_url', array(
			'ajax_url' => admin_url('admin-ajax.php'),
			'home_url' => home_url()
		));
		
	}

}

add_action( 'wp_enqueue_scripts', 'child_enqueue_styles', 15 );


/**
 * Custom Below 
 */

// Remove default Astra Prev/Next page from single posts
add_filter( 'astra_single_post_navigation_enabled', '__return_false' );


/**
 * Include required files.
 */
// Shortcodes
include_once(get_stylesheet_directory() . '/inc/custom-shortcodes.php');

// Custom theme functions
include_once(get_stylesheet_directory() . '/inc/theme-functions.php');



/*
* Everything below this is for the contact form on the "How to Interview" page
*/ 
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
				$new_post['post_content'] .= '<h2 class="h4">Where did the idea for your business come from?</h2>';
					$new_post['post_content'] .= $posted_data['question-1'];
			}
		}
		if(isset($posted_data['question-2'])){
			if(strlen($posted_data['question-2']) > 0) {
				$new_post['post_content'] .= '<h2 class="h4">How do you typically start your day?</h2>';
					$new_post['post_content'] .= $posted_data['question-2'];
			}
		}
		if(isset($posted_data['question-3'])){
			if(strlen($posted_data['question-3']) > 0) {
				$new_post['post_content'] .= '<h2 class="h4">What is an interesting thing about you that few people know?</h2>';
					$new_post['post_content'] .= $posted_data['question-3'];
			}
		}
		
		if(isset($posted_data['question-4'])){
			if(strlen($posted_data['question-4']) > 0) {
				$new_post['post_content'] .= '<h2 class="h4">What three performance metrics do you pay most attention to?</h4>';
					$new_post['post_content'] .= $posted_data['question-4'];
			}
		}
		if(isset($posted_data['question-5'])){
			if(strlen($posted_data['question-5']) > 0) {
				$new_post['post_content'] .= '<h2 class="h4">What business software can you not live without?</h4>';
					$new_post['post_content'] .= $posted_data['question-5'];
			}
		}
		if(isset($posted_data['question-6'])){
			if(strlen($posted_data['question-6']) > 0) {
				$new_post['post_content'] .= "<h2 class='h4'>What is the best advice you've ever been given?</h4>";
					$new_post['post_content'] .= $posted_data['question-6'];
			}
		}
		if(isset($posted_data['question-7'])){
			if(strlen($posted_data['question-7']) > 0) {
				$new_post['post_content'] .= "<h2 class='h4'>What is the best book recommendation for young businesspeople?</h4>";
					$new_post['post_content'] .= $posted_data['question-7'];
			}
		}
		if(isset($posted_data['question-8'])){
			if(strlen($posted_data['question-8']) > 0) {
				$new_post['post_content'] .= "<h2 class='h4'>How will your industry change in the next five years?</h4>";
					$new_post['post_content'] .= $posted_data['question-8'];
			}
		}
		if(isset($posted_data['own-question'])){
			if(strlen($posted_data['own-question']) > 0) {
					$new_post['post_content'] .= '<h2 class="h4">Custom question: ' . $posted_data['own-question'] . '</h4>';
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





function rx_modifications_callback() {

    // Ensure we have the data we need to continue
    if( ! isset( $_POST ) || empty( $_POST ) || ! is_user_logged_in() ) {

        // If we don't - return custom error message and exit
        header( 'HTTP/1.1 400 Empty POST Values' );
        echo 'Could Not Verify POST Values.';
        exit;
    }

	 $user_id        = get_current_user_id();                            // Get our current user ID


	 $rx_first_name_val = sanitize_text_field( $_POST['rx_first_name'] );      
	 $rx_last_name_val  = sanitize_text_field( $_POST['rx_last_name'] );      
	 $rx_company_val    = sanitize_text_field( $_POST['rx_interview_company'] );
	 $rx_user_email   = sanitize_text_field( $_POST['rx_user_email'] );  
	 $rx_role         = sanitize_text_field( $_POST['rx_role'] ); 
	 $rx_industry 	  = sanitize_text_field( $_POST['rx_industry'] ); 
	 $rx_linked_in 	  = sanitize_text_field( $_POST['rx_linked_in'] ); 
	 $rx_twitter  	  = sanitize_text_field( $_POST['rx_twitter'] ); 
	 $rx_crunchbase   = sanitize_text_field( $_POST['rx_crunchbase'] ); 
	 $rx_instagram    = sanitize_text_field( $_POST['rx_instagram'] ); 
	 $rx_wikipedia    = sanitize_text_field( $_POST['rx_wikipedia'] ); 
	 $rx_published_work_1  = sanitize_text_field( $_POST['rx_published_work_1'] ); 
	 $rx_published_work_2  = sanitize_text_field( $_POST['rx_published_work_2'] ); 
	 $rx_published_work_3  = sanitize_text_field( $_POST['rx_published_work_3'] );
	 $rx_biography_val = sanitize_text_field( $_POST['rx_biography'] );      // Sanitize our user meta value 
	 $rx_int_question_1  = sanitize_text_field( $_POST['rx_int_question_1'] ); 
	 $rx_int_question_2  = sanitize_text_field( $_POST['rx_int_question_2'] ); 
	 $rx_int_question_3  = sanitize_text_field( $_POST['rx_int_question_3'] ); 
	 $rx_int_question_4  = sanitize_text_field( $_POST['rx_int_question_4'] ); 
	 $rx_int_question_5  = sanitize_text_field( $_POST['rx_int_question_5'] ); 
	 $rx_int_question_6  = sanitize_text_field( $_POST['rx_int_question_6'] ); 
	 $rx_int_question_7  = sanitize_text_field( $_POST['rx_int_question_7'] ); 
	 $rx_int_question_8  = sanitize_text_field( $_POST['rx_int_question_8'] ); 
	 $rx_int_own_question  = sanitize_text_field( $_POST['rx_int_own_question'] ); 
	 $rx_int_own_answer  = sanitize_text_field( $_POST['rx_int_own_answer'] ); 
	

	update_user_meta( $user_id, 'rx_first_name', $rx_first_name_val );                // Update our user meta
	update_user_meta( $user_id, 'rx_last_name', $rx_last_name_val );                
	update_user_meta( $user_id, 'rx_interview_company', $rx_company_val);                
	update_user_meta( $user_id, 'rx_user_email', $rx_user_email );
	update_user_meta( $user_id, 'rx_role', $rx_role );
	update_user_meta( $user_id, 'rx_industry', $rx_industry );
	update_user_meta( $user_id, 'rx_linked_in', $rx_linked_in );
	update_user_meta( $user_id, 'rx_twitter', $rx_twitter );
	update_user_meta( $user_id, 'rx_crunchbase', $rx_crunchbase );
	update_user_meta( $user_id, 'rx_instagram', $rx_instagram );
	update_user_meta( $user_id, 'rx_wikipedia', $rx_wikipedia );
	update_user_meta( $user_id, 'rx_published_work_1', $rx_published_work_1 );
	update_user_meta( $user_id, 'rx_published_work_2', $rx_published_work_2 );
	update_user_meta( $user_id, 'rx_published_work_3', $rx_published_work_3 );
	update_user_meta( $user_id, 'rx_int_question_1', $rx_int_question_1 );
	update_user_meta( $user_id, 'rx_int_question_2', $rx_int_question_2 );
	update_user_meta( $user_id, 'rx_int_question_3', $rx_int_question_3 );
	update_user_meta( $user_id, 'rx_int_question_4', $rx_int_question_4 );
	update_user_meta( $user_id, 'rx_int_question_5', $rx_int_question_5 );
	update_user_meta( $user_id, 'rx_int_question_6', $rx_int_question_6 );
	update_user_meta( $user_id, 'rx_int_question_7', $rx_int_question_7 );
	update_user_meta( $user_id, 'rx_int_question_8', $rx_int_question_8 );
	update_user_meta( $user_id, 'rx_int_own_question', $rx_int_own_question );
	update_user_meta( $user_id, 'rx_int_own_answer', $rx_int_own_answer );
	
	
    exit;
}
add_action( 'wp_ajax_nopriv_rx_interview_cb', 'rx_modifications_callback' );
add_action( 'wp_ajax_rx_interview_cb', 'rx_modifications_callback' );










function load_saved_interview() {

	$rx_current_user_ID = get_current_user_id();
	$rx_current_user_meta = get_user_meta( $rx_current_user_ID );

	$rx_first_name = $rx_current_user_meta['rx_first_name'][0];
	$rx_last_name = $rx_current_user_meta['rx_last_name'][0];
	$rx_company = $rx_current_user_meta['rx_interview_company'][0];
	$rx_user_email   = $rx_current_user_meta['rx_user_email'][0]; 
	$rx_role   = $rx_current_user_meta['rx_role'][0]; 
	$rx_industry   = $rx_current_user_meta['rx_industry'][0]; 

	$rx_linked_in   = $rx_current_user_meta['rx_linked_in'][0]; 
	$rx_twitter   = $rx_current_user_meta['rx_twitter'][0]; 
	$rx_crunchbase   = $rx_current_user_meta['rx_crunchbase'][0]; 
	$rx_instagram   = $rx_current_user_meta['rx_instagram'][0]; 
	$rx_wikipedia   = $rx_current_user_meta['rx_wikipedia'][0]; 

	$rx_published_work_1   = $rx_current_user_meta['rx_published_work_1'][0]; 
	$rx_published_work_2   = $rx_current_user_meta['rx_published_work_2'][0]; 
	$rx_published_work_3   = $rx_current_user_meta['rx_published_work_3'][0]; 

	$rx_biography   = $rx_current_user_meta['rx_biography'][0]; 
	$rx_int_question_1   = $rx_current_user_meta['rx_int_question_1'][0]; 
	$rx_int_question_2   = $rx_current_user_meta['rx_int_question_2'][0]; 
	$rx_int_question_3   = $rx_current_user_meta['rx_int_question_3'][0]; 
	$rx_int_question_4   = $rx_current_user_meta['rx_int_question_4'][0]; 
	$rx_int_question_5   = $rx_current_user_meta['rx_int_question_5'][0]; 
	$rx_int_question_6   = $rx_current_user_meta['rx_int_question_6'][0]; 
	$rx_int_question_7   = $rx_current_user_meta['rx_int_question_7'][0]; 
	$rx_int_question_8   = $rx_current_user_meta['rx_int_question_8'][0]; 
	$rx_int_question_own_question   = $rx_current_user_meta['rx_int_question_own_question'][0]; 
	$rx_int_question_own_answer   = $rx_current_user_meta['rx_int_question_own_answer'][0]; 





	$rx_saved_fields = [
		"first_name" => $rx_first_name,
		"last_name" => $rx_last_name,
		"company" => $rx_company,
		"rx_user_email" => $rx_user_email,
		"rx_role" => $rx_role,
		"rx_industry" => $rx_industry,
		"rx_linked_in" => $rx_linked_in,
		"rx_twitter" => $rx_twitter,
		"rx_crunchbase" => $rx_crunchbase,
		"rx_instagram" => $rx_instagram,
		"rx_wikipedia" => $rx_wikipedia,
		"rx_published_work_1" => $rx_published_work_1,
		"rx_published_work_2" => $rx_published_work_2,
		"rx_published_work_3" => $rx_published_work_3,
		"rx_biography" => $rx_biography,
		"rx_int_question_1" => $rx_int_question_1,
		"rx_int_question_2" => $rx_int_question_2,
		"rx_int_question_3" => $rx_int_question_3,
		"rx_int_question_4" => $rx_int_question_4,
		"rx_int_question_5" => $rx_int_question_5,
		"rx_int_question_6" => $rx_int_question_6,
		"rx_int_question_7" => $rx_int_question_7,
		"rx_int_question_8" => $rx_int_question_8,
		"rx_int_own_question" => $rx_int_own_question,
		"rx_int_own_answer" => $rx_int_own_answer			
	];



    // The Query
    $ajaxposts = get_posts( $args ); // changed to get_posts from wp_query, because `get_posts` returns an array

    header('Content-type: application/json');
    echo json_encode($rx_saved_fields);
    die();

}

add_action( 'wp_ajax_nopriv_rx_interview_load_saved', 'load_saved_interview' );
add_action( 'wp_ajax_interview_load_saved', 'load_saved_interview' );