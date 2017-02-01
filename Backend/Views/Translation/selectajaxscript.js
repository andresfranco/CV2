$(document).ready(function(){
    
    getparent();
    getobjectid();
    getfields();
$('#objectcode').change(function()
{

 getparent();
 getobjectid();
 getfields(); 

});
$('#parentid').change(function()
{
getobjectid();
});
$('#objectid').change(function()
{
 getfields();
});

function getfields()
{
  var objectcode = $('#objectcode').val();
  var objectid =$('#objectid').val();
  //if(objectcode != 0)
  //{
   $.ajax({
    type:'post',
    url:'getfieldsajax',
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
    url:'getparentajax',
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
    url:'getobjectidlistajax',
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
    url:'getobjectsajax',
    data:{objectcode:objectcode,parentid:parentid},
    cache:false,
    success: function(returndata){
     $('#objectid').html(returndata);
    }

   });  
  }    
  
}


 
})

