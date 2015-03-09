<?php
require('libraries/PHPMailer/class.phpmailer.php');
require('libraries/PHPMailer/PHPMailerAutoload.php');
if($_POST)
{
    $message = "";
    $error = [];
    $name = trim(stripslashes($_POST['contactName']));
    $email = trim(stripslashes($_POST['contactEmail']));
    $subject = trim(stripslashes($_POST['contactSubject']));
    $contact_message = trim(stripslashes($_POST['contactMessage']));

    // Check Name
    if (strlen($name) < 2) {
        $error['name'] = "Please enter your name.";
    }
    // Check Email
    if (!preg_match('/^[a-z0-9&\'\.\-_\+]+@[a-z0-9\-]+\.([a-z0-9\-]+\.)*+[a-z]{2}/is', $email)) {
        $error['email'] = "Please enter a valid email address.";
    }
    // Check Message
    if (strlen($contact_message) < 15) {
        $error['message'] = "Please enter your message. It should have at least 15 characters.";
    }
    // Subject
    if ($subject == '') {
        $subject = "Contact Form Submission";
    }


    // Set Message
    $message .= "Email from: " . $name . "<br />";
    $message .= "Email address: " . $email . "<br />";
    $message .= "Message: <br />";
    $message .= $contact_message;
    $message .= "<br /> ----- <br /> This email was sent from your site's contact form. <br />";

    if (!$error)
    {
        $mail = new PHPMailer();
        $mail->From     = $email; // Mail de origen
        $mail->FromName = $name; // Nombre del que envia
        $mail->AddAddress('andres@andresmfranco.info'); // Mail destino, podemos agregar muchas direcciones
        $mail->AddReplyTo($email); // Mail de respuesta

        $mail->WordWrap = 50; // Largo de las lineas
        $mail->IsHTML(true); // Podemos incluir tags html
        $mail->Subject  =  $subject;
        $mail->Body     =  $message;
        $mail->AltBody  =  strip_tags($mail->Body); // Este es el contenido alternativo sin html



        $mail->Host     = 'smtp.andresmfranco.info';
        $mail->IsSMTP(); // vamos a conectarnos a un servidor SMTP

        $mail->SMTPAuth = true; // usaremos autenticacion
        $mail->Port = 25;
        $mail->Username = "andres@andresmfranco.info"; // usuario
        $mail->Password = "Picoromz2509$"; // contraseña


        $mail->Send();
        echo "OK";

    }
    else {

        $response = (isset($error['name'])) ? $error['name'] . "<br /> \n" : null;
        $response .= (isset($error['email'])) ? $error['email'] . "<br /> \n" : null;
        $response .= (isset($error['message'])) ? $error['message'] . "<br />" : null;

        echo $response;

    }

}
?>