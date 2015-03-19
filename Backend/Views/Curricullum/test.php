<meta charset="UTF-8">
<?php
require_once '../../Controller/CurricullumController.php';
require_once '../../Controller/GlobalController.php';
require_once '../../libraries/medoo.php';

$globalobj =new GlobalController();

$globalobj->getselectoptionsbytable(1,'work','company');



//print_r($id);
//foreach($datas as $data)
//{
   // $id= $data["id"];
//}


