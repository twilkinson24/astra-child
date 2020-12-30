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
		intBiography = $('.biography textarea'),
		intQuestion1 = $('.question-1 textarea'),
		intQuestion2 = $('.question-2 textarea'),
		intQuestion3 = $('.question-3 textarea'),
		intQuestion4 = $('.question-4 textarea'),
		intQuestion5 = $('.question-5 textarea'),
		intQuestion6 = $('.question-6 textarea'),
		intQuestion7 = $('.question-7 textarea'),
		intQuestion8 = $('.question-8 textarea'),
		intOwnQuestion = $('.own-question input'),
        intOwnAnswer = $('.answer-own-question textarea'),
        // Notifications
        notificationPopUp = $('#saved-notification')


	// SAVE PROGRESS

	// url for admin-ajax.php
	var url = admin_url.ajax_url

	if($('#save-progress-btn').length > 0) {
		var saveButton = $('#save-progress-btn')
		

		saveButton.on('click', function(e){  
				e.preventDefault() 
			
			console.log('save')
			
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
                    console.log('error')
					console.log(response);
				},
				success: function(response) {
					// Actualiza el mensaje con la respuesta
					//   $('#txtMessage').text(response.message);
					console.log('success')
                    
                    notificationPopUp.addClass('active')

                    // close popup on click
                    notificationPopUp.find('.close-notification-btn').on('click', function() {
                        notificationPopUp.removeClass('active')
                    })
                    // close popup on ESC click
                    $(document).on('keyup', function(event) {
                        if (event.keyCode === 27) {
                            notificationPopUp.removeClass('active')
                        }   // esc
                     });
                    // close popup after 3 seconds
                    setTimeout(function() {
                        notificationPopUp.removeClass('active')
                    }, 3000)


				},
				fail: function( data ) {
					console.log( data.responseText );
					console.log( 'Request failed: ' + data.statusText );
				} 
			})

		});
	}






	// CHECK FOR SAVED PROGRESS
	if(intFirstName.length > 0) {
    
        var AJAXurl = admin_url.ajax_url
        
        // AJAX to request saved progress
        $.ajax({
            url: AJAXurl,
            type: 'post',
            data: {
                action: 'interview_load_saved',
            },
            success: function( result ) {
                console.log( 'success getting saved data' );
            }
        }).done(function(answer) {
            console.log('answer')
            console.log(answer)


            if(answer.first_name) {
                intFirstName.val(answer.first_name)
            }
            if(answer.last_name) {
                intLastName.val(answer.last_name)
            }
            if(answer.company) {
                intCompany.val(answer.company)
            }
            if(answer.rx_user_email) {
                intUserEmail.val(answer.rx_user_email)
            }
            if(answer.rx_role) {
                intRole.val(answer.rx_role)
            }
            if(answer.rx_industry) {
                intIndustry.val(answer.rx_industry)
            }
            if(answer.rx_linked_in) {
                intLinkedIn.val(answer.rx_linked_in)
            }
            if(answer.rx_twitter) {
                intTwitter.val(answer.rx_twitter)
            }
            if(answer.rx_crunchbase) {
                intCrunchbase.val(answer.rx_crunchbase)
            }
            if(answer.rx_instagram) {
                intInstagram.val(answer.rx_instagram)
            } 
            if(answer.rx_wikipedia) {
                intWikipedia.val(answer.rx_wikipedia)
            }
            if(answer.rx_published_work_1) {
                intPublishedWork1.val(answer.rx_published_work_1)
            }
            if(answer.rx_published_work_2) {
                intPublishedWork2.val(answer.rx_published_work_2)
            }
            if(answer.rx_published_work_3) {
                intPublishedWork3.val(answer.rx_published_work_3)
            }
            if(answer.rx_biography) {
                intBiography.val(answer.rx_biography)
            }
            if(answer.rx_int_question_1) {
                intQuestion1.val(answer.rx_int_question_1)
            }
            if(answer.rx_int_question_2) {
                intQuestion2.val(answer.rx_int_question_2)
            }
            if(answer.rx_int_question_3) {
                intQuestion3.val(answer.rx_int_question_3)
            }
            if(answer.rx_int_question_4) {
                intQuestion4.val(answer.rx_int_question_4)
            }
            if(answer.rx_int_question_5) {
                intQuestion5.val(answer.rx_int_question_5)
            }
            if(answer.rx_int_question_6) {
                intQuestion6.val(answer.rx_int_question_6)
            }
            if(answer.rx_int_question_7) {
                intQuestion7.val(answer.rx_int_question_7)
            }
            if(answer.rx_int_question_8) {
                intQuestion8.val(answer.rx_int_question_8)
            } else {
                console.log('nahh')
            }
            if(answer.rx_int_own_question) {
                intOwnQuestion.val(answer.rx_int_own_question)
            } 
            if(answer.rx_int_question_own_answer) {
                intOwnAnswer.val(answer.rx_int_question_own_answer)
            }
            

        }).fail(function() {
        alert("Error occurred. Please try again.");
        }); 

    } 





} // end if .interview-first-name






})(jQuery);
