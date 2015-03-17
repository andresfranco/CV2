<?php
//require_once '../../Backend/Controller/CurricullumController.php';
require_once '../../Controller/TranslationController.php';
require_once '../../libraries/medoo.php';
//$db=new CurricullumController();
$db= new TranslationController();
$datas =$db->getcurricullumtranslate('cv',1,'en');

foreach($datas as $data)
{
   echo html_entity_decode($data["content"]).'<br>';
   
}


?>

