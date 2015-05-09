$(function() {
    $.validator.addMethod("valueNotEquals", function(value, element, arg){
          
        return arg != value;
         
     
   }, "");
    
    $("#appform").validate({
           errorPlacement: function(error, element) {
             
        switch(element.attr("name"))
          {
          case "translationcontent": 
              $("#contenterror").html( error );
              
              break;
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
            translationcontent:{
                 required: 
               {
                 depends:function()
                {
                 var divtext =$('#translationcontent').Editor("getText");
                 $('#translationcontent').text(divtext);
                 if ($('#translationcontent').text()=="")
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
        ignore: [],
        // Specify the validation error messages
        messages: {
            objectcode:{
                valueNotEquals:"You must select a object code"
                
            },
            parentid:{
                valueNotEquals:"You must select a parent"
                

            },
            objectid:{
                valueNotEquals:"You must select a objectid"
                

            },
            languagecode:{
                required:"You must select a language",
                

            },
             field:{
                valueNotEquals:"You must select a field"
                

            },
            translationcontent:{
                required:"You must enter a content",
                

            }


        },

        submitHandler: function(form) {
            form.submit();
        }
    });

});
