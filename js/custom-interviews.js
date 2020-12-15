(function($){
// This is only queried for the "How To Interview" page template




if($('.interview-first-name input').length > 0) {
	// vars
	var intFirstName = $('.interview-first-name input'),
		intLastName = $('.interview-last-name input'),
		intCompany = $('.interview-company-name input'),
		intUserEmail = $('.interview-your-email input'),
		intRole = $('.describe-role select'),
		intIndustry = $('.role-industry input'),
		// Social Media
		intLinkedIn = $('.linkedin-url input'),
		intTwitter = $('.twitter-url input'),
		intCrunchbase = $('.crunchbase-url input'),
		intInstagram = $('.instagram-url input'),
		intWikipedia = $('.wikipedia-url input'),
		// published works
		intPublishedWork1 = $('.published-work-1 input'),
		intPublishedWork2 = $('.published-work-2 input'),
		intPublishedWork3 = $('.published-work-3 input'),
		// Questions
		intBiography = $('.biography textarea')
		intQuestion1 = $('.question-1 textarea'),
		intQuestion2 = $('.question-2 textarea'),
		intQuestion3 = $('.question-3 textarea'),
		intQuestion4 = $('.question-4 textarea'),
		intQuestion5 = $('.question-5 textarea'),
		intQuestion6 = $('.question-6 textarea'),
		intQuestion7 = $('.question-7 textarea'),
		intQuestion8 = $('.question-8 textarea'),
		intOwnQuestion = $('.own-question input'),
		intOwnAnswer = $('.answer-own-question textarea')




	// get form data to save



	// url for admin-ajax.php
	var url = admin_url.ajax_url

	if($('#save-progress-btn').length > 0) {
		var saveButton = $('#save-progress-btn')
		

		saveButton.on('click', function(e){  
				e.preventDefault() 
			
			// AJAX
			$.ajax({
				type : "post",
				url : url, // Pon aquí tu URL
				data : {
					action: 'rx_interview_cb',          // AJAX POST Action
					'rx_first_name': intFirstName.val(), 
					'rx_last_name': intLastName.val(), 
					'rx_interview_company': intCompany.val(), 
					'rx_user_email': intUserEmail.val(),
					'rx_role': intRole.val(),
					'rx_industry': intIndustry.val(),
					'rx_linked_in': intLinkedIn.val(),
					'rx_twitter': intTwitter.val(),
					'rx_crunchbase': intCrunchbase.val(),
					'rx_instagram': intInstagram.val(),
					'rx_wikipedia': intWikipedia.val(),
					'rx_published_work_1': intPublishedWork1.val(),
					'rx_published_work_2': intPublishedWork2.val(),
					'rx_published_work_3': intPublishedWork3.val(),
					'rx_biography': intBiography.val(),
					'rx_int_question_1': intQuestion1.val(),
					'rx_int_question_2': intQuestion2.val(),
					'rx_int_question_3': intQuestion3.val(),
					'rx_int_question_4': intQuestion4.val(),
					'rx_int_question_5': intQuestion5.val(),
					'rx_int_question_6': intQuestion6.val(),
					'rx_int_question_7': intQuestion7.val(),
					'rx_int_question_8': intQuestion8.val(),
					'rx_int_own_question': intOwnQuestion.val(),
					'rx_int_own_answer': intOwnAnswer.val(),


				},
				error: function(response){
					console.log(response);
				},
				success: function(response) {
					// Actualiza el mensaje con la respuesta
					//   $('#txtMessage').text(response.message);
					console.log('success')
					console.log(response)
				},
				fail: function( data ) {
					console.log( data.responseText );
					console.log( 'Request failed: ' + data.statusText );
				} 
			})

		});
	}
} // end if .interview-first-name















})(jQuery);

