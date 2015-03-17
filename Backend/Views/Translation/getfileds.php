<?php
require_once '../../Controller/TranslationController.php';
require_once '../../Controller/GlobalController.php';
require_once '../../libraries/medoo.php';

$db=new TranslationController();
$globalobj=new GlobalController();
$objectcode =$_POST['objectcode'];
$objectid=$_POST['objectid'];
$databasename="curricullum";
switch ($objectcode) {
    case "cv":
        $tablename='curricullum';
        break;
    case "sk":
        $tablename='skill';
        break;
    case "wo":
       $tablename='curricullum';
        break;
      case "pr":
       $tablename='project';
        break;
  case "pt":
       $tablename='project_tag';
       
        break;
}
    $globalobj->gettablefields($databasename, $tablename);
?>