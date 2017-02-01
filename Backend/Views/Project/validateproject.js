$(function() {

    $.validator.addMethod("valueNotEquals", function(value, element, arg){
          
        return arg != value;
         
     
   }, "");
    $("#appform").validate({
         errorPlacement: function(error, element) {
             
        switch(element.attr("name"))
          {
                       case "imagename": $("#imageerror").html( error );break;
                       default:error.insertAfter(element);
          }     
     
         
         

     
   },
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

            }
           ,
            imagename:{
                required:"You must select an image"

            }
           
           
            

        },
       
        submitHandler: function(form) {
            form.submit();
        }
    });

  });


