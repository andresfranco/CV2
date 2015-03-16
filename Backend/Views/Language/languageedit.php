<?php
require_once '../../Controller/LanguageController.php';
require_once '../../libraries/medoo.php';
$errormessage="";
$languagedb=new LanguageController();
$codeold="";
if (!empty($_GET))
{    
$code=$_GET['id'];
$datas=$languagedb->getlanguagebyid($code);
foreach($datas as $data)
{
$language = $data["language"];
$codeold =$data["code"];
$_SESSION["codeold"] = $codeold;
}       
}

if (!empty($_POST))

{
    
    $code = htmlEntities($_POST['code']);
    $language = htmlEntities($_POST['language']);
    $codeold=$_SESSION["codeold"];
     
    $username ="admin";
    
    $languagedb->updatelanguage($username,$code,$codeold, $language, 'languagecontent.php');
       
}
?>
<script src="../../js/jquery-1.9.1.min.js"></script>
<script src="validatelanguage.js"></script>
<label class="error"><?php echo $errormessage;?></label><br>
<form id="appform" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <table width="400" border="0" cellspacing="1" cellpadding="2">
        <tr>
            <td width="100"><label class="control-label">Code</label></td>
            <td><input name="code" type="text" id="code" class="span6 typeahead" value="<?php echo $code;?>">
       <span class="error"></span></td>
        </tr>
        <tr>
            <td width="100"><label class="control-label">Language</label></td>
            <td><input name="language" type="text" id="language" class="span6 typeahead" value="<?php echo $language;?>"></td>
        </tr>

            <td>
                <input class="btn btn-info" name="add" type="submit" id="add" value="Guardar">
            </td>
        </tr>
    </table>
</form>
