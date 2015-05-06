$(function() {

    
    $("#appform").validate({
        errorPlacement: function(error,element) {
             
        switch(element.attr("name"))
          {
            case "translation":$("#translationerror").html( error );break;
          
          default:error.insertAfter(element);       
          }   
      },
      ignore: [],
       rules: {
            languagecode:{
                required:true,
                
               
            },
            key:{
                required:true
       
        },
         translation:{
                required: 
               {
                 depends:function()
                {
                 var divtext =$('#translation').Editor("getText");
                 $('#translation').text(divtext);
                 
                 if ($('#translation').text().length==0)
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
            languagecode:{
                required:"You must select a language"
                
            },
            key:{
                required:"You must enter a key"
               
            },
             translation:{
                required:"You must enter a translation"
       
           }
         
        },
       
        submitHandler: function(form) {
            form.submit();
        }
    });

  });


