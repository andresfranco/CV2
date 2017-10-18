
// Contants Defined in /js/parameters.js
$(document).on("click", ".open-DeleteDialog", function () {
    var Id = $(this).data('id');
    var name= $(this).data('name');
    var routes= {}
    $.ajax({ type: "POST",url: MAIN_URL+"/skillroutes", success: function(result){
      result?routes =JSON.parse(result):routes =DEFAULT_DELETE_SKILL_ROUTE; 
      $("#deleteform").attr("action",routes.deleteurl+Id );
    }});    
     $(".modal-body #name").text(name);
});
