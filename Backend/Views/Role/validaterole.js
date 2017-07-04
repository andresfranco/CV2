$(function() {

    // Setup form validation on the #register-form element
    $("#appform").validate({
       	
        // Specify the validation rules
        rules: {
            role:{
                required:true
               
            },
            description:{
                required:true
                 
            }
           
        },
        // Specify the validation error messages
        messages: {
            role:{
                required:"You must enter a role"
                
            },
             description:{
                required:"You must enter a description",
                    
            }
          
        },
       
        submitHandler: function(form) {
            form.submit();
        }
    });

  });

