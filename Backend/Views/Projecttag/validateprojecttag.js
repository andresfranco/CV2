$(function() {

    $.validator.addMethod("valueNotEquals", function(value, element, arg){
          
        return arg != value;
         
     
   }, "");
    $("#appform").validate({
       rules: {
            projectid:{
                required:true,
                valueNotEquals:"0"
               
            },
            tagname:{
                required:true
       
        }
       },
        // Specify the validation error messages
        messages: {
            projectid:{
                required:"You must select a project"
                
            },
            tagname:{
                required:"You must enter a tagname"
               
            }
         
        },
       
        submitHandler: function(form) {
            form.submit();
        }
    });

  });


