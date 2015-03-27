<?php
require_once '../../Controller/TranslationController.php';
$db=new TranslationController();
$globalobj=new GlobalController();
$objectcode="";
$parentid="";
$objectid="";
$languagecode="";
$field="";
$content="";
$errormessage="";
if (!empty($_POST))
{
    $objectcode=$_POST['objectcode'];
    
    if ($objectcode =="cv")
    {
     $parentid="0";  
    }
    else
    {
     $parentid= htmlEntities($_POST['parentid']); 
    }
    
    
    $objectid=htmlEntities($_POST['objectid']);
    $languagecode=htmlEntities($_POST['languagecode']);
    $field=htmlEntities($_POST['field']);
    $content=htmlEntities($_POST['content']);
    $username =$globalobj->getcurrentuser();
    $count =$db->findtranslation($objectcode,$parentid,$objectid,$languagecode,$field);
     
    if($count==0)
    {
    $db->inserttranslation($username, $objectcode,$parentid, $objectid, $languagecode, $field, $content, 'translationcontent.php');
    }
    else
    {
      $errormessage= '<div class="alert alert-error">The translation already exist</div>';
    }    
}

?>
<script src="validatetranslation.js"></script>
<script src="selectajaxscript.js"></script>
</script>
<label class="error"><?php echo $errormessage;?></label><br>
<form id="appform" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <table width="400" border="0" cellspacing="1" cellpadding="2">
        <tr>
            <td width="100"><label class="control-label">Object Code</label></td>
            <td><?php $globalobj->getmultiparambycode('objcode',$objectcode,'');?></td>
            <td width="100"><label id ="objectcodeerror" name="objectcodeerror"></label></td> 
        </tr>
        
        <tr>
            <td width="100"><label class="control-label">Parent</label></td>
           
           <td width="100">
                    <?php
                      if($errormessage!="") 
                      {
                        $db->getparent($globalobj,$objectcode,$parentid);       
                      } 
                      else  
                       {  
                          echo'<select id="parentid" name="parentid">
                          <option value="0">Please Select a Parent</option>
                          <option></option>
                      </select>';
                      
                      }
                      ?>
           </td>

        </tr>
        <tr>
            <td width="100"><label class="control-label">Object ID</label></td>
           
           <td width="100">
                        <?php 
                          if($errormessage!="") 
                          {
                          $db->getobject($globalobj,$objectcode,$objectid);
                          }
                          else
                          {
                         echo'<select id="objectid" name="objectid">
                          <option value="0">Please select an Object</option>
                          <option></option>
                          </select>';
                          } 
                          ?>
           </td>

        </tr>
        <tr>
            <td width="100"><label class="control-label">Language</label></td>
            <td width="100"><?php $globalobj->getlanguageselect('',$languagecode)?></td>

        </tr>
        <tr>
           <td width="100"><label class="control-label">Field</label></td> 
           <td width="100"><?php
                          if($errormessage!="")
                          { 
                          $db->getfields($globalobj,$objectcode,$field);    
                          }
                          else
                          {
                          echo'<select id="field" name="field">
                          <option value="0">Please select a field</option>
                          <option></option>
                          </select>';
                          }
                          ?>
           </td>

        </tr>
        <tr>
            <td width="100"><label class="control-label">Content</label></td>
            <td width="100"><textarea id ="content" class="cleditor" name="content" id="mainskills" rows="3" required><?php echo $content?></textarea></td>
            <td><label id ="contenterror" name="contenterror"></label></td> 
        </tr>

    </table>
    <br>
    <div class="options btn-group">
        <input  id ="deletebutton" class="btn btn-primary" type="submit" value="Save" />
        <input onClick="window.location.href='translationcontent.php'"id ="cancelbutton" class=" btn input-small"  value="Cancel" />
    </div>
</form>


