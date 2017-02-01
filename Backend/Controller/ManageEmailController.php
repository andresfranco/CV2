<?php

Class ManageEmail

{
   private $database;
  
   public function __construct($medoo) {

        $this->database =$medoo;
    } 
    
  
  function get_email_params()
  {
   $sth = $this->database->pdo->prepare("select m.value,m.valuedesc from multiparam m inner join sysparam s on (m.sysparamid =s.id) where s.code ='email'");
    $sth->execute();
    $emailparameters=array("smtpserver"=>"","smtpusername"=>"","smtppassword"=>"","emailto"=>"");
    foreach($sth as $data)
    {
        switch ($data["valuedesc"])
        {
         case"smtp":
         $emailparameters["smtpserver"]=$data["value"];      
         break;
         case"smtpusername":
         $emailparameters["smtpusername"]=$data["value"];      
         break;
         case"smtppassword":
         $emailparameters["smtppassword"]=$data["value"];      
         break;
         case"emailto":
         $emailparameters["emailto"]=$data["value"];      
         break;
        }
        
    }
    return $emailparameters;
  }  

  function setemailparams()
  {
   $emailparameters =$this->get_email_params();   
   $smtpserver =$emailparameters["smtpserver"];
   $smtpusername =$emailparameters["smtpusername"];
   $smtppassword=$emailparameters["smtppassword"];
   $emailto =$emailparameters["emailto"];
   $port =25;

   $emailparams =array("smtpserver"=>$smtpserver,
       "smtpusername"=>$smtpusername,
       "smtppassword"=>$smtppassword,
       "emailto"=>$emailto,"port"=>$port);
   return $emailparams;
  }

  function sendemail($name,$email,$subject,$message,$error)
  {
      if (!$error)
      {
          $emailparams =$this->setemailparams();

          $mail = new PHPMailer();
          $mail->From     = $email; // Mail de origen
          $mail->FromName = $name; // Nombre del que envia
          $mail->AddAddress('andresfranco@cableonda.net'); // Mail destino, podemos agregar muchas direcciones
          $mail->AddReplyTo($email); // Mail de respuesta

          $mail->WordWrap = 50; // Largo de las lineas
          $mail->IsHTML(true); // Podemos incluir tags html
          $mail->Subject  =  $subject;
          $mail->Body     =  $message;
          $mail->AltBody  =  strip_tags($mail->Body); // Este es el contenido alternativo sin html



          $mail->Host     = $emailparams["smtpserver"];
          $mail->IsSMTP(); // vamos a conectarnos a un servidor SMTP
          
          $mail->SMTPAuth = true; // usaremos autenticacion
          $mail->Port = $emailparams["port"];
          $mail->Username = $emailparams["smtpusername"]; // usuario
          $mail->Password = $emailparams["smtppassword"]; // contraseña
          
          if(!$mail->Send()) {
         echo "Mailer Error: " . $mail->ErrorInfo;
         
          } 
          
          else {

          echo 'OK';
              
          }

          //$mail->Send();
         

      }
      else {

          $response = (isset($error['name'])) ? $error['name'] . "<br /> \n" : null;
          $response .= (isset($error['email'])) ? $error['email'] . "<br /> \n" : null;
          $response .= (isset($error['message'])) ? $error['message'] . "<br />" : null;

          echo $response;

      }


  }

  function validateemail($name,$email,$contact_message)
  {
      $error =array();
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


      return $error;


  }

  function validatesubject($subject)
  {
      // Subject
      if ($subject == '') {
          $subject = "Contact Form Submission";
      }
      return $subject;
  }

  function setmailmessage($name,$email,$contact_message)
  {
      $message="";
      // Set Message
      $message .= "Email from: " . $name . "<br />";
      $message .= "Email address: " . $email . "<br />";
      $message .= "Message: <br />";
      $message .= $contact_message;
      $message .= "<br /> ----- <br /> This email was sent from your site's contact form. <br />";

      return $message;
  }
  
  function sendemailaction($name,$email,$subject,$contact_message)
  
  {
    //$message = "";
    //$error = [];
   
    //$manageemail=new ManageEmail();

    $error =$this->validateemail($name,$email,$contact_message);
    $subject =$this->validatesubject($subject);
    $message= $this->setmailmessage($name,$email,$contact_message);
    $this->sendemail($name,$email,$subject,$message,$error);
  
      
  }




}





?>