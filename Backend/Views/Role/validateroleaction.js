$(function() {

    // Setup form validation on the #register-form element
    $("#appform").validate({
       	
        // Specify the validation rules
        rules: {
            actionid:{
                required:true
               
            },
           roleid:{
                required:true
               
               
            }
          
            
           
        },
        // Specify the validation error messages
        messages: {
            actionid:{
                required:"You must enter a action"
                
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

