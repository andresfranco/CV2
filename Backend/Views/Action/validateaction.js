$(function() {

    // Setup form validation on the #register-form element
    $("#appform").validate({
       	
        // Specify the validation rules
        rules: {
            action:{
                required:true
               
            },
            description:{
                required:true
                 
            }
           
        },
        // Specify the validation error messages
        messages: {
            action:{
                required:"You must enter a action"
                
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

