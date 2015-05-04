$(function() {

    // Setup form validation on the #register-form element
    $("#appform").validate({
       	 errorPlacement: function(error,element) {
             
        switch(element.attr("name"))
          {
            case "value": 
             
              $("#valueerror").html( error );
              break;
          
          default:error.insertAfter(element);       
          }   
      },
        // Specify the validation rules
        rules: {
            code:{
                required:true
               
            },
            value:{
                 required: 
               {
                 depends:function()
                {
                 var divtext =$('#value').Editor("getText");
                 $('#value').text(divtext);
                 if ($('#value').text()=="")
                  {    
                   return true;
                  }
                  else
                  {
                  return false;   
                  }
                }
               }
               
            },
            description:{
                required:true
               
            }
           
            
           
        },
        ignore: [],
        // Specify the validation error messages
        messages: {
            code:{
                required:"You must enter a code"
                
            },
             value:{
                required:"You must enter a value"
               
            },
           description:{
                required:"You must enter a description"
               
            }
                 
            

        },
       
        submitHandler: function(form) {
            form.submit();
        }
    });

  });

