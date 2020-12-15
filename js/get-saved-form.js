(function($){
    // This is only queried for the "How To Interview" page template
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
		intOwnQuestion = $('.own-question textarea'),
		intOwnAnswer = $('.answer-own-question textarea')

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
                console.log( result );
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
                intLinkedIn.val(answer.rx_rx_linked_in)
            }
            if(answer.rx_twitter) {
                intTwitter.val(answer.rx_twitter)
            }
            if(answer.rx_crunchbase) {
                intCrunchbase.val(answer.rx_crunchbase)
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
            if(answer.rx_question_r) {
                intQuestion1.val(answer.rx_question_1)
            }
            if(answer.rx_question_r) {
                intQuestion2.val(answer.rx_question_2)
            }
            if(answer.rx_question_r) {
                intQuestion3.val(answer.rx_question_3)
            }
            if(answer.rx_question_r) {
                intQuestion4.val(answer.rx_question_4)
            }
            if(answer.rx_question_r) {
                intQuestion5.val(answer.rx_question_5)
            }
            if(answer.rx_question_r) {
                intQuestion6.val(answer.rx_question_6)
            }
            if(answer.rx_question_r) {
                intQuestion7.val(answer.rx_question_7)
            }
            if(answer.rx_question_r) {
                intQuestion8.val(answer.rx_question_8)
            }
            if(answer.rx_own_questior) {
                intOwnQuestion.val(answer.rx_own_question)
            }
            if(answer.rx_own_answer) {
                intOwnAnswer.val(answer.rx_own_answer)
            }
            
           
        
        console.log('done with second, second -answer')           

        
        }).fail(function() {
        alert("Error occurred. Please try again.");
        }); 

    }
    
    
})(jQuery);