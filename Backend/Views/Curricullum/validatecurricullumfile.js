$(function() {

    $.validator.addMethod("valueNotEquals", function(value, element, arg){
          
        return arg != value;
         
     
   }, "");
    $("#appform").validate({
        
        errorPlacement: function(error, element) {
             
        switch(element.attr("name"))
          {
                       case "curricullumfile": $("#fileerror").html( error );break;
                       default:error.insertAfter(element);
          }     
     
         
         

     
     },
       	ignore: [], 
       rules: {
            curricullumfile:{
                required: 
               {
                 depends:function()
                {
                
                 if ($('#curricullumfile').val()=="")
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
            curricullumfile:{
                required:"You must select a file"
                
            }
        },
       
        submitHandler: function(form) {
            form.submit();
        }
    });

  });

