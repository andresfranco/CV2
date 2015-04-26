
		$(document).ready( function() {
            
             
        $("#maintext").Editor();
        $("#aboutme").Editor();
        $("#contactdetails").Editor();
        $("#mainskills").Editor();
        
        
        $("#maintext").Editor("setText",$("#maintext").text());
        $("#aboutme").Editor("setText",$("#aboutme").text());
        $("#contactdetails").Editor("setText",$("#contactdetails").text());
        $("#mainskills").Editor("setText",$("#mainskills").text());
        
    });