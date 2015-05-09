$(function() {

    // Setup form validation on the #register-form element
    $("#appform").validate({
       	
        // Specify the validation rules
        rules: {
           
           password : {
                    required:true,
                    minlength : 5
                },
           password_confirm : 
                   {
                    required:true,   
                    minlength : 5,
                    equalTo : "#password"
                }
            
           
        },
        // Specify the validation error messages
        messages: {
          
            password : {
                    required:"You must enter a password",
                    minlength : "The password must be at least 5 characters"
                },
            password_confirm : 
                   {
                    required:"You must confirm the password",   
                    minlength : "The password must be at least 5 characters",
                    equalTo : "Password and password confirmation must be equal"
                }
           
           
            

        },
       
        submitHandler: function(form) {
            form.submit();
        }
    });

  });

