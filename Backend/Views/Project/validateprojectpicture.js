$(function() {

    $.validator.addMethod("valueNotEquals", function(value, element, arg){
          
        return arg != value;
         
     
   }, "");
    $("#appform").validate({
         errorPlacement: function(error, element) {
             
        switch(element.attr("name"))
          {
                       case "projectpicture": $("#imageerror").html( error );break;
                       default:error.insertAfter(element);
          }     
     
         
         

     
   },
       rules: {
            
            
             projectpicture:{
                 required: 
               {
                 depends:function()
                {
                
                 if ($('#projectpicture').val()=="")
                  {    
                   return true;
                  }
                  else
                  {
                  return false;   
                  }
                }
               } 

            }
            
           
            
           
        },
        // Specify the validation error messages
        messages: {
            
            projectpicture:{
                required:"You must select an image"

            }
           
           
            

        },
       
        submitHandler: function(form) {
            form.submit();
        }
    });

  });
