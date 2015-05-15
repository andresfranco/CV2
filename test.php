<?php

require 'Slim/Slim.php';
require_once 'Slim/View.php';
require 'Views/Twig.php';
require_once 'Backend/libraries/PHPMailer/class.phpmailer.php';
require_once 'Backend/libraries/PHPMailer/PHPMailerAutoload.php';
require_once 'Backend/libraries/medoo.php';


 $database=new medoo();
 $datas = $database->select("sysparam",["value"], ["code" => "lang"]);
        foreach($datas as $data)
        {
            $value= $data["value"];
        }
        echo 'valor: '.$value;



?>
