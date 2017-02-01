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
            company:{
                required:true
               
            },
            position:{
                required:true

            },
            from:{
                required:true

            },
             to:{
                required:true

            }
            
           
            
           
        },
        // Specify the validation error messages
        messages: {
            curricullumid:{
                valueNotEquals:"You must select a curricullum"
                
            },
            company:{
                required:"You must enter a company"
               
            },
            position:{
                required:"You must enter a position"

            },
            from:{
                required:"You must enter a from date description"

            },
             to:{
                required:"You must enter a to date description"

            }
         
           
           
            

        },
       
        submitHandler: function(form) {
            form.submit();
        }
    });

  });


