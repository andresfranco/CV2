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
            maintext:{
                required:true
               
            },
            aboutme:{
                required:true

            },
            contactdetails:{
                required:true

            },
            mainskills:{
                required:true

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


