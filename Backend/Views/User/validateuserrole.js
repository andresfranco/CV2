$(function() {

    // Setup form validation on the #register-form element
    $("#appform").validate({
       	
        // Specify the validation rules
        rules: {
            userid:{
                required:true
               
            },
           roleid:{
                required:true,
               
               
            }
          
            
           
        },
        // Specify the validation error messages
        messages: {
            userid:{
                required:"You must enter a user"
                
            },
            roleid:{
                required:"You must enter a role"
      
                    
            }
          
           
           
            

        },
       
        submitHandler: function(form) {
            form.submit();
        }
    });

  });

