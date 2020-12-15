(function($){
    // This is only queried for the "How To Interview" page template

    var intFirstName = $('.interview-first-name input'),
    intLastName = $('.interview-last-name input'),
    intCompany = $('.interview-company-name input')

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

        
        console.log('done with second, second -answer')           
        }).fail(function() {
        alert("Error occurred. Please try again.");
        }); 

    }
    
    
})(jQuery);