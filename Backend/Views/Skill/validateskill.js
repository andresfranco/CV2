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
            type:{
                required:true,
                valueNotEquals:"0"
               
            },
            skill:{
                required:true

            },
            percentage:{
                required:true

            }
            
           
            
           
        },
        // Specify the validation error messages
        messages: {
            curricullumid:{
                required:"You must select a curricullum"
                
            },
            type:{
                required:"You must enter a type"
               
            },
            skill:{
                required:"You must enter a skill"

            },
            percentage:{
                required:"You must enter a percentage"

            }
         
           
           
            

        },
       
        submitHandler: function(form) {
            form.submit();
        }
    });

  });


