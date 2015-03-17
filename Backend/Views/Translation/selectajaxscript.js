$(document).ready(function(){
$('#objectcode').change(function()
{
var objectcode = $('#objectcode').val();
if(objectcode != 0)
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
});
$('#objectid').change(function()
{
var objectcode = $('#objectcode').val();
var objectid =$('#objectid').val();
if(objectcode != 0)
{
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
});

})

