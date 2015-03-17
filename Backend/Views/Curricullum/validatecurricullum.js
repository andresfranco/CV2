$(function() {

    // Setup form validation on the #register-form element
    $("#appform").validate({
       	
        // Specify the validation rules
        rules: {
            name:{
                required:true
               
            },
            maintext:{
                required:true
               
            },
            aboutme:{
                required:true

            }
           
            
           
        },
        // Specify the validation error messages
        messages: {
            name:{
                required:"You must enter the name"
                
            },
             maintext:{
                required:"You must enter the main text information"
               
            },
            aboutme:{
                required:"You must enter about me information"

            }
           
           
            

        },
       
        submitHandler: function(form) {
            form.submit();
        }
    });

  });

