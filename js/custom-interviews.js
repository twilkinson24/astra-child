(function($){
// This is only queried for the "How To Interview" page template




if($('.interview-first-name input').length > 0) {
	// vars
	var intFirstName = $('.interview-first-name input'),
		intLastName = $('.interview-last-name input'),
		intCompany = $('.interview-company-name input')

		var biographyVal = 'Im a rockstar';



	// get form data to save

	console.log(intFirstName.val())

	intFirstName.on('keyup', function() {
		console.log($(this).val())
	})



	// url for admin-ajax.php
	var url = admin_url.ajax_url

	if($('#save-progress-btn').length > 0) {
		var saveButton = $('#save-progress-btn')
		

		saveButton.on('click', function(e){  
				e.preventDefault() 

				console.log('click')
			
			// AJAX
			$.ajax({
				type : "post",
				url : url, // Pon aquí tu URL
				data : {
					action: 'rx_interview_cb',          // AJAX POST Action
					'rx_first_name': intFirstName.val(), 
					'rx_last_name': intLastName.val(), 
					'rx_interview_company': intCompany.val(), 
					'rx_biography': biographyVal 
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

