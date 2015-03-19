<?php
require_once '../../Controller/TranslationController.php';
require_once '../../Controller/GlobalController.php';
require_once '../../libraries/medoo.php';
$db=new TranslationController();
$globalobj=new GlobalController();
$objectcode="";
$objectid="";
$languagecode="";
$field="";
$content="";
$errormessage="";
if (!empty($_POST))
{
    
    $objectcode=htmlEntities($_POST['objectcode']);;
    $objectid=htmlEntities($_POST['objectid']);
    $languagecode=htmlEntities($_POST['languagecode']);
    $field=htmlEntities($_POST['field']);
    $content=htmlEntities($_POST['content']);
    $username =$globalobj->getcurrentuser();
    $count =$db->findtranslation($objectcode, $objectid, $languagecode, $field);

    if($count==0)
    {
    $db->inserttranslation($username, $objectcode, $objectid, $languagecode, $field, $content, 'translationcontent.php');
    }
    else
    {
        $errormessage= '<div class="alert alert-error">The translation already exist</div>';
    }    
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
            <td width="100"><label class="control-label">Object ID</label></td>
           
           <td width="100"><select id="objectid" name="objectid">
                          <option value="0">Please select an Object</option>
                          <option></option>
                          </select>
           </td>

        </tr>
        <tr>
            <td width="100"><label class="control-label">Language</label></td>
            <td width="100"><?php $globalobj->getlanguageselect('',$languagecode)?></td>

        </tr>
        <tr>
           <td width="100"><label class="control-label">Field</label></td> 
           <td width="100"><select id="field" name="field">
                          <option value="0">Please select a field</option>
                          <option></option>
                          </select>
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


