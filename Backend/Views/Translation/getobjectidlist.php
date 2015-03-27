<?php
require_once '../../Controller/TranslationController.php';
require_once '../../Controller/GlobalController.php';
require_once '../../libraries/medoo.php';

$db=new TranslationController();
$globalobj=new GlobalController();
$objectcode =$_POST['id'];
$parentid = $_POST['parentid'];
switch ($objectcode) {
    case "cv":
        $globalobj->getcurricullumselect('', '');
        break;
    case "ed":
       $globalobj->geteducationselect('', '');
        break;
    case "sk":
        
        break;
    case "wo":
       
        break;
      case "pr":
       
        break;
  case "pt":
       
        break;
}

?>

