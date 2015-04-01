<?php

class SecurityController {
   public function __construct($app,$medoo) 
  {
   $this->database =$medoo;
   $this->app=$app;
   }    
  
   function getuserbyusername($username)
   {
    $data = $this->database->select("systemuser", [
    "username",
    "password",
    "salt"    
    ], [
    "username" => $username
    ]);
   
    $userdata =[];
     foreach($datas as $data)
    {
        $userdata["username"] = $data["username"];
        $userdata["password"] = $data["password"];
        $userdata["salt"] = $data["salt"];
    }  
   return $userdata;
       
   }
   
 function existuser($username,$password) 
 {
      $count =  $this->database->count("systemuser", [
      "id" ]
      ,["AND" => [ 
            "username" => $username,
            "password"=>$password
            ]]);
 
    if ($count>0)
    {
     $erromessage ="";
    }
      else   
    {
     $errormessage= '<div class="alert alert-error">invalid username or password</div>'; 
    }
       
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
    $errormessage=$this->existuser($username, $password);
    
    if ($errormessage =="")
    {
        $userdata  = $this->getuserbyusername($username);
        $passwordhash = hash('sha256', $userdata ["salt"] . hash('sha256', $password) );
        
        if($passwordhash != $userdata ["password"]) //incorrect password
         {
          $this->app->render('Views/Security/login.html.twig',array('username'=>$username,'password'=>$password,'errormessage'=>$errormessage)); 
         }
         else
         {
           $_SESSION['username'] = $userdata ["username"];
           $this->app->render('Views/Home/home.html.twig');  
         }    
             
    } 
    else
    {
       $this->app->render('Views/Security/login.html.twig',array('username'=>$username,'password'=>$password,'errormessage'=>$errormessage)); 
    }    
}
 

 }
 
 
 
 

?>
