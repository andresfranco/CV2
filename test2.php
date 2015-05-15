<?php

require 'Slim/Slim.php';
require_once 'Slim/View.php';
require 'Views/Twig.php';
require_once 'Backend/libraries/PHPMailer/class.phpmailer.php';
require_once 'Backend/libraries/PHPMailer/PHPMailerAutoload.php';
require_once 'Backend/libraries/medoo.php';


$code ='jp';
$language ='Japanese';
$username ='admin';
//$dt ='2015-04-20 20:20:05';
$database=new medoo();
   $dt = date('Y-m-d H:i:s');
    $sth = $database->pdo->prepare('INSERT INTO language (code,
language,
createuser,
createdate,
modifyuser,
modifydate)
VALUES
(:code,
:language,
:username,
:date,
:username,
:date)');
$sth->execute(array(':code'=>$code,':language'=>$language,':username'=>$username,':date'=>$dt));
 /*foreach($sth as $data)
        {
            $value= $data["value"];
        }
        echo 'valor: '.$value;
*/
?>