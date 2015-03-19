<?php
require_once '../../Controller/TranslationController.php';
require_once '../../Controller/GlobalController.php';
require_once '../../libraries/medoo.php';

$globalobj=new GlobalController();
$objectcode =$_POST['objectcode'];
if ($objectcode=="pt")
{ 
  $tablename ="project";  
}
else
{
  $tablename ="curricullum";  
}    


if ($objectcode !="cv")
{
 $globalobj->getparentselect('','',$objectcode,$tablename);   
    
}else
{
echo '<select id="parentid" name="parentid" disabled>
                          <option value="0">No parent needed</option>
                          </select>
          '  ;  
    
}
    
        
    
    
?>
    
    
    