<?php
require_once '../../Controller/TranslationController.php';
require_once '../../Controller/GlobalController.php';
require_once '../../libraries/medoo.php';

$db=new TranslationController();
$globalobj=new GlobalController();
$objectcode =$_POST['objectcode'];
$parentid =$_POST['parentid'];
$filterffield="curricullumid";
switch ($objectcode) {
     
    case "ed":
        $tablename='education';
        $fielddesc ="institution";
        break;
    case "sk":
        $tablename='skill';
        $fielddesc ="skill";
        break;
    case "wo":
       $tablename='work';
       $fielddesc ="company";
        break;
      case "pr":
       $tablename='project';
       $fielddesc ="name";   
        break;
  case "pt":
       $tablename='project_tag';
       $fielddesc ="tagname";
       $filterffield="projectid";
        break;
}
    $globalobj->getselectoptionsbytable($parentid,$tablename,$fielddesc,$filterffield);
?>