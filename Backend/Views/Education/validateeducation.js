$(function() {

    $.validator.addMethod("valueNotEquals", function(value, element, arg){
          
        return arg != value;
         
     
   }, "");
    $("#appform").validate({
       rules: {
            curricullumid:{
                required:true,
                valueNotEquals:"0"
               
            },
            institution:{
                required:true
               
            },
            degree:{
                required:true

            },
            date:{
                required:true

            }
            
           
            
           
        },
        // Specify the validation error messages
        messages: {
            curricullumid:{
                valueNotEquals:"You must select a curricullum"
                
            },
            institution:{
                required:"You must enter a institution"
               
            },
            degree:{
                required:"You must enter a degree"

            },
            date:{
                required:"You must enter a date"

            }
         
           
           
            

        },
       
        submitHandler: function(form) {
            form.submit();
        }
    });

  });


