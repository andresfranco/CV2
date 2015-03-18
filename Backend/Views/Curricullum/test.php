<?php
require_once '../../Controller/CurricullumController.php';
require_once '../../libraries/medoo.php';
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


    //$username ="admin";
    //$count =$db->findcurricullum($name,$languagecode);

    //if($count==0)
    //{
    //$db->insertcurricullum($username,$name,$maintext,$aboutme,$contactdetails,$mainskills,'curricullumcontent.php');
    //}
    //else
    //{
        //$errormessage= '<div class="alert alert-error">The curricullum name  : "'.$name. '" already exist</div>';
    //}    
}

?>
<link id="bootstrap-style" href="../../css/bootstrap.min.css" rel="stylesheet">
	<link href="../../css/bootstrap-responsive.min.css" rel="stylesheet">
	<link id="base-style" href="../../css/style.css" rel="stylesheet">
	<link id="base-style-responsive" href="../../css/style-responsive.css" rel="stylesheet">
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&subset=latin,cyrillic-ext,latin-ext' rel='stylesheet' type='text/css'>
     <link id="bootstrap-style" href="../../css/formerror.css" rel="stylesheet">
    <link id="bootstrap-style" href="../../css/buttongroups.css" rel="stylesheet">

<!-- start: JavaScript-->
        <script src="../../js/jquery-1.11.2.js"></script>
        <script src="../../js/jquery.validate.js"></script>
		
	<script src="../../js/jquery-migrate-1.0.0.min.js"></script>
	
	<script src="../../js/jquery-ui-1.10.0.custom.min.js"></script>
	
	<script src="../../js/jquery.ui.touch-punch.js"></script>
	
	<script src="../../js/modernizr.js"></script>
	
	<script src="../../js/bootstrap.min.js"></script>
	
	<script src="../../js/jquery.cookie.js"></script>
	
	<script src='../../js/fullcalendar.min.js'></script>
	
	<script src='../../js/jquery.dataTables.min.js'></script>

	<script src="../../js/excanvas.js"></script>
	<script src="../../js/jquery.flot.js"></script>
	<script src="../../js/jquery.flot.pie.js"></script>
	<script src="../../js/jquery.flot.stack.js"></script>
	<script src="../../js/jquery.flot.resize.min.js"></script>
	
	<script src="../../js/jquery.chosen.min.js"></script>
	
	<script src="../../js/jquery.uniform.min.js"></script>
		
	<script src="../../js/jquery.cleditor.min.js"></script>
	
	<script src="../../js/jquery.noty.js"></script>
	
	<script src="../../js/jquery.elfinder.min.js"></script>
	
	<script src="../../js/jquery.raty.min.js"></script>
	
	<script src="../../js/jquery.iphone.toggle.js"></script>
	
	<script src="../../js/jquery.uploadify-3.1.min.js"></script>
	
	<script src="../../js/jquery.gritter.min.js"></script>
	
	<script src="../../js/jquery.imagesloaded.js"></script>
	
	<script src="../../js/jquery.masonry.min.js"></script>
	
	<script src="../../js/jquery.knob.modified.js"></script>
	
	<script src="../../js/jquery.sparkline.min.js"></script>
	
	<script src="../../js/counter.js"></script>
	
	<script src="../../js/retina.js"></script>

	<script src="../../js/custom.js"></script>
<script src="testscript.js"></script>
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



