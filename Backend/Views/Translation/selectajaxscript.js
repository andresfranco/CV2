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
    url:'getfileds.php',
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
    url:'getparent.php',
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
    url:'getobjectidlist.php',
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
    url:'getobjects.php',
    data:{objectcode:objectcode,parentid:parentid},
    cache:false,
    success: function(returndata){
     $('#objectid').html(returndata);
    }

   });  
  }    
  //if(objectcode != 0)
  //{
    
    
}


 function objectcodechange()
 {
 
  //}
 }
 
})

