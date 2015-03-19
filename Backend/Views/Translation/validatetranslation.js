$(function() {

    // Setup form validation on the #register-form element
    $("#appform").validate({

        // Specify the validation rules
        rules: {
            code:{
                required:true

            },
            language:{
                required:true

            }



        },
        // Specify the validation error messages
        messages: {
            code:{
                required:"You must enter a code"

            },
            language:{
                required:"You must enter a language"

            }




        },

        submitHandler: function(form) {
            form.submit();
        }
    });

});
