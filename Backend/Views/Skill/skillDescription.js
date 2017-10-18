
		$(document).ready( function() {
     
        $("#description").Editor();
        $("#description").Editor("setText",$("#description").text());
			
				$('#skillform').submit(function() {
            $('#description').text($('#description').Editor("getText"));
				});
        
    });