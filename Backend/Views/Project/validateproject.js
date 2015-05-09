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
            name:{
                required:true
               
               
            },
            description:{
                required:true

            },
            link:{
                required:true

            },
             imagename:{
                required:true

            }
            
           
            
           
        },
        // Specify the validation error messages
        messages: {
            curricullumid:{
                valueNotEquals:"You must select a curricullum"
                
            },
            name:{
                required:"You must enter a name"
               
            },
            description:{
                required:"You must enter a description"

            },
            link:{
                required:"You must enter a link"

            }
           ,
            imagename:{
                required:"You must enter a image name"

            }
           
           
            

        },
       
        submitHandler: function(form) {
            form.submit();
        }
    });

  });


