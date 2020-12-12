(function($){
// This is only queried for the "How To Interview" page template
 
if($('#save-progress-btn').length > 0) {
	var saveButton = $('#save-progress-btn')
	
	saveButton.on('click', function(event) {
		event.preventDefault();
		console.log('save btn clicked')
	})
	
	
}

})(jQuery);