//$(function() {
//twitter bootstrap script
    //$("#deletebutton").click(function(){
        //alert("entro click boton modal");


//});

$(document).ready(function() {

    $("#deletebutton").click(function(){
        var code =$("#modallink").data('id');
        alert ('code:'+ code);
        /*$.ajax({

            type: "POST",
            url: "deletelanguage.php",
            data: { 'code': code },
            success: function(){

                $("#myModal").modal('hide');
            },
            error: function(){
                alert("failure");
            }
        });
        */
    });

});