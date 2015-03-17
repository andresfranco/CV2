<?php
require_once '../../Controller/TranslationController.php';
require_once '../../Controller/GlobalController.php';
require_once '../../libraries/medoo.php';

$db=new TranslationController();
$globalobj=new GlobalController();
$objectcode =$_POST['id'];
switch ($objectcode) {
    case "cv":
        $globalobj->getcurricullumselect('', '');
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

