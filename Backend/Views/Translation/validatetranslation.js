$(function() {
    $.validator.addMethod("valueNotEquals", function(value, element, arg){
          
        return arg != value;
         
     
   }, "");
    
    $("#appform").validate({
           errorPlacement: function(error, element) {
             
        switch(element.attr("name"))
          {
          case "content": $("#contenterror").html( error );break;
          
          default:error.insertAfter(element);       
          }   
      },  
        
        rules: {
            objectcode:{
                required:true,
                valueNotEquals: "0"
                 
            },
            parentid:{
                required:true,
                valueNotEquals: "0"
                
            },
            objectid:{
                required:true,
                valueNotEquals: "0"

            },
            languagecode:{
                required:true,
               valueNotEquals: "0"

            },
             field:{
                required:true,
                valueNotEquals: "0"

            },
            content:{
                required:true

            }



        },
        ignore: [],
        // Specify the validation error messages
        messages: {
            objectcode:{
                required:"You must select a object code",
                
            },
            parentid:{
                required:"You must select a parent",
                

            },
            objectid:{
                required:"You must select a objectid",
                

            },
            languagecode:{
                required:"You must select a language",
                

            },
             field:{
                required:"You must select a field",
                

            },
            content:{
                required:"You must enter a content",
                

            }


        },

        submitHandler: function(form) {
            form.submit();
        }
    });

});
