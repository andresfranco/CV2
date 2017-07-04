$(document).ready(function(){
$('#objectcode').change(function()
{
 getfields();
 getparent();
 getobjectid();
   


});
$('#parentid').change(function()
{
getobjectid();
});


function getfields()
{
  var objectcode = $('#objectcode').val();
  var objectid =$('#objectid').val();
  //if(objectcode != 0)
  //{
   $.ajax({
    type:'post',
    url:'/CV2/getfieldsajax',
    data:{objectcode:objectcode,objectid:objectid},
    cache:false,
    success: function(returndata){ 
     $('#field').html(returndata);
    }

   });   
    
}
function getparent()
{
    var objectcode = $('#objectcode').val(); 
   $.ajax({
    type:'post',
    url:'/CV2/getparentajax',
    data:{objectcode:objectcode},
    cache:false,
    success: function(returndata){
     $('#parentid').html(returndata);
    }

   });   
}

function getobjectid()
{
  var objectcode = $('#objectcode').val();
  var parentid =$('#parentid').val();
  if (objectcode =="cv")
  {
   $.ajax({
    type:'post',
    url:'/CV2/getobjectidlistajax',
    data:{id:objectcode},
    cache:false,
    success: function(returndata){
     $('#objectid').html(returndata);
    }

   });     
  }
  else
  {
      
    $.ajax({
    type:'post',
    url:'/CV2/getobjectsajax',
    data:{objectcode:objectcode,parentid:parentid},
    cache:false,
    success: function(returndata){
     $('#objectid').html(returndata);
    }

   });  
  }    
  
}


 
})
