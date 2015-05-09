$(function() {

    $.validator.addMethod("valueNotEquals", function(value, element, arg){
          
        return arg != value;
         
     
   }, "");
    $("#appform").validate({
        errorPlacement: function(error, element) {
             
        switch(element.attr("name"))
          {
                       case "link": $("#linkerror").html( error );break;
                       default:error.insertAfter(element);
          }
      },ignore: [],  
       rules: {
            curricullumid:{
                required:true,
                valueNotEquals:"0"
               
            },
            type:{
                required:true,
                valueNotEquals:"0"
            },
            name:{
                required:true

            },
            link:{
                 required: 
               {
                 depends:function()
                {
                 var divtext =$('#link').Editor("getText");
                 $('#link').text(divtext);
                 if ($('#link').text()=="")
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
            curricullumid:{
                valueNotEquals:"You must select a curricullum"
                
            },
            type:{
                valueNotEquals:"You must select a type"
               
            },
            name:{
                required:"You must enter a name"

            },
            link:{
                required:"You must enter a link"

            }
         
        },
       
        submitHandler: function(form) {
            form.submit();
        }
    });

  });


