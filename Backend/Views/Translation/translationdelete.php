<?php
require_once '../../Controller/TranslationController.php';
require_once '../../libraries/medoo.php';
$db=new TranslationController();
$id=$_GET['param'];
$db->deletetranslation($id,'translationcontent.php');