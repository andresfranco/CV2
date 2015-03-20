<?php
require_once '../../Controller/CurricullumController.php';
$errormessage="";
$db=new CurricullumController();
if (!empty($_GET))
{    
$id=$_GET['id'];
$datas=$db->getcurricullumbyid($id);
foreach($datas as $data)
{
$name = $data["name"];
$maintext =$data["maintext"];
$aboutme =$data["aboutme"];
$contactdetails=$data["contactdetails"];
$mainskills=$data["mainskills"];

$_SESSION["idold"] = $id;
}       
}

if (!empty($_POST))

{
    $id =$_SESSION["idold"];
    $name = htmlEntities($_POST['name']);

    $maintext=htmlEntities($_POST['maintext']);

    $aboutme=htmlEntities($_POST['aboutme']);
    $contactdetails=htmlEntities($_POST['contactdetails']);
    $mainskills=htmlEntities($_POST['mainskills']);

    $username ="admin";
    
    $db->updatecurricullum($id,$username,$name,$maintext,$aboutme,$contactdetails,$mainskills,'curricullumcontent.php');
       
}
?>
<script src="validatecurricullum.js"></script>
<label class="error"><?php echo $errormessage;?></label><br>
<form id="appform" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <table width="400" border="0" cellspacing="1" cellpadding="2">  
        <tr>
            <td width="100"><label class="control-label">Name</label></td>
            <td><input id ="name" name="name" type="text" id="language" class="span6 typeahead" value="<?php echo $name;?>"></td>
        </tr>
        <tr>
            <td width="100"><label class="control-label">Main Text</label></td>
            <td width="100"><textarea class="cleditor" id="maintext" name="maintext" rows="3"><?php echo $maintext?></textarea></td>

        </tr>
        <tr>
            <td width="100"><label class="control-label">About Me</label></td>
            <td width="100"><textarea class="cleditor" name="aboutme" id="aboutme" rows="3"><?php echo $aboutme?></textarea></td>

        </tr>
        <tr>
            <td width="100"><label class="control-label">Contact Details</label></td>
            <td width="100"><textarea class="cleditor" name="contactdetails" id="contactdetails" rows="3"><?php echo $contactdetails?></textarea></td>

        </tr>
        <tr>
            <td width="100"><label class="control-label">Main Skills</label></td>
            <td width="100"><textarea class="cleditor" name="mainskills" id="mainskills" rows="3"><?php echo $mainskills?></textarea></td>

        </tr>


    </table>
    <br>
    <div class="options btn-group">
        <input  id ="deletebutton" class="btn btn-primary" type="submit" value="Save" />
        <input onClick="window.location.href='curricullumcontent.php'"id ="cancelbutton" class=" btn input-small"  value="Cancel" />
    </div>
</form>
