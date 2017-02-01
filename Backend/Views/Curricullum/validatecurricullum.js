$(function() {

   // $("#maintext").html($("#maintext").closest("Editor-editor").text());
   
    $("#appform").validate({
        
       	 errorPlacement: function(error, element) {
             
        switch(element.attr("name"))
          {
                       case "maintext": $("#maintexterror").html( error );break;
                       case "aboutme": $("#aboutmeerror").html( error );break;
                       case "contactdetails": $("#contactdetailserror").html( error );break;
                       case "mainskills":$("#mainskillserror").html( error );break;
                       default:error.insertAfter(element);
          }     
     
         
         

     
   },
       	ignore: [],
        // Specify the validation rules
        rules: {
            name:{
                required:true
               
            },
            maintext:
             {
                required: 
               {
                 depends:function()
                {
                 var divtext =$('#maintext').Editor("getText");
                 $('#maintext').text(divtext);
                 if ($('#maintext').text()=="")
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
            aboutme:{
                  required: 
               {
                 depends:function()
                {
                 var divtext =$('#aboutme').Editor("getText");
                 $('#aboutme').text(divtext);
                 if ($('#aboutme').text()=="")
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
            contactdetails:{
                  required: 
               {
                 depends:function()
                {
                 var divtext =$('#contactdetails').Editor("getText");
                 $('#contactdetails').text(divtext);
                 if ($('#contactdetails').text()=="")
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
            mainskills:{
                  required: 
               {
                 depends:function()
                {
                 var divtext =$('#mainskills').Editor("getText");
                 $('#mainskills').text(divtext);
                 if ($('#mainskills').text()=="")
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
            name:{
                required:"You must enter the name"
                
            },
             maintext:{
                required:"You must enter the main text information"
               
            },
            aboutme:{
                required:"You must enter about me information"

            },
            contactdetails:{
                required:"You must enter contact details information"

            },
            mainskills:{
                required:"You must enter main skills information"

            }
           
           
            

        },
       
        submitHandler: function(form) {
            form.submit();
        }
    });

  });


