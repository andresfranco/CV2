$(function() {

    // Setup form validation on the #register-form element
    $("#appform").validate({
       	
        // Specify the validation rules
        rules: {
            
            maintext:{
                required:true
               
           
            }
           
            
           
        },
        // Specify the validation error messages
        messages: {
           
             maintext:{
                required:"You must enter the main text information"
               
          
            }
        },
       
        submitHandler: function(form) {
            form.submit();
        }
    });

  });


