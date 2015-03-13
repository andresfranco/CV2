<?php
require_once '../Controller/LanguageController.php';
require_once '../libraries/medoo.php';

if (!empty($_POST))

{
    $code = htmlEntities($_POST['code']);
    $language = htmlEntities($_POST['language']);

    $languagedb=new LanguageController();
    $username ="admin";
    $languagedb->insertlanguage($username,$code,$language,'../Views/Language/languagelist.php');

}
?>