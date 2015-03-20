<?php
require_once '../../Controller/CurricullumController.php';
$errormessage="";
$name="";
$maintext="";
$aboutme="";
$contactdetails="";
$mainskills="";
$db=new CurricullumController();
if (!empty($_POST))

{
    
    $name = htmlEntities($_POST['name']);

    $maintext=htmlEntities($_POST['maintext']);
    $contactdetails=htmlEntities($_POST['contactdetails']);
    $mainskills=htmlEntities($_POST['mainskills']);

    $aboutme=htmlEntities($_POST['aboutme']);


    $username ="admin";
    $count =$db->findcurricullum($name,$languagecode);

    if($count==0)
    {
    $db->insertcurricullum($username,$name,$maintext,$aboutme,$contactdetails,$mainskills,'curricullumcontent.php');
    }
    else
    {
        $errormessage= '<div class="alert alert-error">The curricullum name  : "'.$name. '" already exist</div>';
    }    
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
            <td width="100"><textarea class="cleditor" name="maintext" rows="3"><?php echo $maintext?></textarea></td>
            <td><label id ="maintexterror" name="maintexterror"></label></td>
        </tr>
        <tr>
            <td width="100"><label class="control-label">About Me</label></td>
            <td width="100"><textarea class="cleditor" name="aboutme"  rows="3"><?php echo $aboutme?></textarea></td>
            <td><label id ="aboutmeerror" name="aboutmeerror"></label></td>
        </tr>
        <tr>
            <td width="100"><label class="control-label">Contact Details</label></td>
            <td width="100"><textarea class="cleditor" name="contactdetails"  rows="3"><?php echo $contactdetails?></textarea></td>
            <td><label id ="contactdetailserror" name="contactdetailserror"></label></td>
        </tr>
        <tr>
            <td width="100"><label class="control-label">Main Skills</label></td>
            <td width="100"><textarea class="cleditor" name="mainskills" rows="3"><?php echo $mainskills?></textarea></td>
            <td><label id ="mainskillserror" name="mainskillserror"></label></td> 
        </tr>

    </table>
    <br>
    <div class="options btn-group">
        <input  id ="deletebutton" class="btn btn-primary" type="submit" value="Save" />
        <input onClick="window.location.href='curricullumcontent.php'"id ="cancelbutton" class=" btn input-small"  value="Cancel" />
    </div>
</form>

