<?php
require('../Controller/ManageEmailController.php');

if($_POST)
{
    $message = "";
    $error = [];
    $name = trim(stripslashes($_POST['contactName']));
    $email = trim(stripslashes($_POST['contactEmail']));
    $subject = trim(stripslashes($_POST['contactSubject']));
    $contact_message = trim(stripslashes($_POST['contactMessage']));

    $manageemail=new ManageEmail();

    $error =$manageemail->validateemail($name,$email,$contact_message);
    $subject =$manageemail->validatesubject($subject);
    $message= $manageemail->setmailmessage($name,$email,$contact_message);
    $manageemail->sendemail($name,$email,$subject,$message,$error);


}
?>