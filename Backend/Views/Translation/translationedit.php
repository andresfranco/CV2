<?php
require_once '../../Controller/TranslationController.php';
$globalobj=new GlobalController();
$db=new TranslationController();
$errormessage="";
$username =$globalobj->getcurrentuser();
$parentid ="";
$objectcode="";
if (!empty($_GET))
{    
$id=$_GET['id'];
$datas=$db->gettranslationbyid($id);
foreach($datas as $data)
{
$objectcode =$data['objectcode'];
$parentid =$data['parentid'];
$objectid=$data['objectid']; 
$languagecode=$data['languagecode']; 
$field=$data['field']; 
$content=$data['content']; 
$_SESSION["idold"] = $id;
}
}

if (!empty($_POST))

{
    $id=$_SESSION["idold"];
    $objectcode =$_POST['objectcode'];
    $parentid =$_POST['parentid'];  
    $objectid=$_POST['objectid']; 
    $languagecode=$_POST['languagecode']; 
    $field=$_POST['field']; 
    $content=$_POST['content']; 
    
    $db->updatetranslation($id, $username, $objectcode,$parentid,$objectid, $languagecode, $field, $content, 'translationcontent.php');
       
}
?>
<script src="../../js/jquery-1.9.1.min.js"></script>
<script src="selectajaxscript.js"></script>

</script>
<label class="error"><?php echo $errormessage;?></label><br>
<form id="appform" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <table width="400" border="0" cellspacing="1" cellpadding="2">
        <tr>
            <td width="100"><label class="control-label">Object Code</label></td>
            <td><?php $globalobj->getmultiparambycode('objcode',$objectcode,'');?></td>
        </tr>
        
        <tr>
            <td width="100"><label class="control-label">Parent</label></td>
           
            <td width="100"><?php $db->getparent($globalobj,$objectcode,$parentid);?>
           </td>

        </tr>
        <tr>
            <td width="100"><label class="control-label">Object ID</label></td>
           
           <td width="100"><?php $db->getobject($globalobj,$objectcode,$objectid);?>
           </td>

        </tr>
        <tr>
            <td width="100"><label class="control-label">Language</label></td>
            <td width="100"><?php $globalobj->getlanguageselect('',$languagecode);?></td>

        </tr>
        <tr>
           <td width="100"><label class="control-label">Field</label></td> 
           <td width="100"><?php $db->getfields($globalobj,$objectcode,$field);?>
           </td>

        </tr>
        <tr>
            <td width="100"><label class="control-label">Content</label></td>
            <td width="100"><textarea id ="content" class="cleditor" name="content" id="mainskills" rows="3" required><?php echo $content?></textarea></td>

        </tr>

    </table>
    <br>
    <div class="options btn-group">
        <input  id ="deletebutton" class="btn btn-primary" type="submit" value="Save" />
        <input onClick="window.location.href='translationcontent.php'"id ="cancelbutton" class=" btn input-small"  value="Cancel" />
    </div>
</form>
