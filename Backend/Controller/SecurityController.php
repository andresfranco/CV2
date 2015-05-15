<?php

class SecurityController {
   public function __construct($app,$medoo) 
  {
   $this->database =$medoo;
   $this->app=$app;
   }    
  
   function getuserbyusername($username)
   {
    $datas = $this->database->select("systemuser",array(
    "username",
    "password",
    "salt"    
    ),array(
    "username" => $username
    ));
   
    $userdata =array();
     foreach($datas as $data)
    {
        $userdata["username"] = $data["username"];
        $userdata["password"] = $data["password"];
        $userdata["salt"] = $data["salt"];
    }  
   return $userdata;
       
   }
   
 function existuser($username)
 {
      $count =  $this->database->count("systemuser",array(
      "id")
      ,array("AND" =>array( 
            "username" => $username
            )));

    if ($count>0)
    {
     $errormessage ="";
    }
      else   
    {
     $errormessage= '<div class="errorlogin">invalid username or password</div>';
    }
      return $errormessage;
} 

function createsalt()
{
    $string = md5(uniqid(rand(), true));
    return substr($string, 0, 3);
}

function Sethashpassword($salt,$password)
{
  $hash = hash('sha256', $password);  
  $hashpassword = hash('sha256',$salt.$hash);  
  return $hashpassword;
}

function validatepassword($username,$password)
{
    $_SESSION['username'] ="";
    $_SESSION['valid'] ='0';
    $errormessage=$this->existuser($username);
    
    if ($errormessage =="")
    {
        $userdata  = $this->getuserbyusername($username);
        $passwordhash = hash('sha256', $userdata ["salt"] . hash('sha256', $password) );
        
        if($passwordhash != $userdata ["password"]) //incorrect password
         {
             $errormessage= '<div class="errorlogin">invalid password</div>';
             $this->app->render('Views/Security/login.html.twig',array('username'=>$username,'password'=>$password,'errormessage'=>$errormessage));
         }
         else
         {
           $_SESSION['username'] = $userdata ["username"];
           $_SESSION['valid'] ='1';
           $this->app->redirect('home');
         }    
             
    } 
    else
    {

       $this->app->render('Views/Security/login.html.twig',array('username'=>$username,'password'=>$password,'errormessage'=>$errormessage));
    }    
}

function logout()
{
unset($_SESSION["valid"]);
unset($_SESSION["username"]);
$this->app->redirect('login');  
}
 

 }
 
 
 
 

?>
