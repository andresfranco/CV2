<?php

class UserController {
    private $database;
    private $editurl;
    private $deleteurl;
    private $mainlink;
    private $mainoption;
    private $security;
    public function __construct($app,$medoo,$security) {

        $this->database =$medoo;
        $this->app=$app;
        $this->security =$security;
        $this->editurl ='edituser';
        $this->deleteurl='viewuser';
        $this->mainlink = '/users';
        $this->mainoption ='Users';
        

    }


function rendergridview($renderpath)
{

    $this->app->render($renderpath,
        array('newurl'=>$this->app->urlFor('newuser')
            ,'editurl'=>$this->editurl
            ,'deleteurl'=>$this->deleteurl
            ,'obj'=>$this
            ,'option'=>$this->mainoption
            ,'route'=>''
            ,'link'=>$this->mainlink));

}

function rendernewview($username,$password,$email,$errormessage,$renderpath)
{
    $this->app->render($renderpath,array('listurl'=>$this->app->urlFor('users')
            ,'selfurl'=>$this->app->urlFor('newuser')
            ,'username'=>$username
            ,'password'=>$password
            ,'email'=>$email
            ,'errormessage'=>$errormessage
            ,'option'=>$this->mainoption
            ,'route'=>'New'
            ,'link'=>$this->mainlink));

}

function rendereditview($id,$renderpath)
{

    $datas=$this->getuserbyid($id);
    foreach($datas as $data)
    {
        $id=$data["id"];
        $username = $data["username"];
        $email = $data["email"];
       
        
    }
    $updateurl=  str_replace(":id",$id,$this->app->urlFor('updateuser'));
    $this->app->render($renderpath,array( 
            'id'=>$id
            ,'username'=>$username
            ,'email'=>$email
            ,'updateurl'=>$updateurl
            ,'listurl'=>$this->app->urlFor('users')
            ,'option'=>$this->mainoption
            ,'route'=>'Edit'
            ,'link'=>$this->mainlink));

}

function renderdeleteview($id,$renderpath)
{
    $datas=$this->getuserbyid($id);
    foreach($datas as $data)
    {
        $id =$data["id"];
        $username = $data["username"];
        $email =$data["email"];
    }
    $deleteurl=  str_replace(":id",$id,$this->app->urlFor('deleteuser'));
    $this->app->render($renderpath,array('id'=>$id
            ,'username'=>$username
            ,'email'=>$email
            ,'deleteurl'=>$deleteurl
            ,'listurl'=>$this->app->urlFor('users')
            ,'option'=>$this->mainoption
            ,'route'=>'Delete'
            ,'link'=>$this->mainlink));
}

function renderpasswordchange($id,$renderpath)
{
  $datas=$this->getuserbyid($id);
    foreach($datas as $data)
    {
        $username = $data["username"];
    }
    $updateurl = str_replace(":id",$id,$this->app->urlFor('changepassword'));
    $this->app->render($renderpath,array('id'=>$id
            ,'username'=>$username
            ,'updateurl'=>$updateurl
            ,'listurl'=>$this->app->urlFor('users')
            ,'option'=>$this->mainoption
            ,'route'=>'Password Change'
            ,'link'=>$this->mainlink));
}

function changepassword($user,$id,$password)
{
   $this->updatepassword($user, $id, $password) ;
   $url = str_replace(':id', $id, $this->app->urlFor('edituser'));
   $this->app->response->redirect($url);    
} 

function updatepassword($user,$id,$password)
{
   $salt =$this->security->createsalt();
   $hashpassword =$this->security->Sethashpassword($salt,$password);
   $dt = date('Y-m-d H:i:s');
        $this->database->update("systemuser", [
            
            "password"=>$hashpassword
           ,"salt"=>$salt            
           ,"modifyuser" => $user
           ,"modifydate"=>$dt
             ],[
            "id[=]" => $id
        ]);   
    
}        

function validateinsert($username)
{
    //Validate if exist
    $count =$this->finduser($username);
    $errormessage="";
    if($count>0)
    {
        $errormessage= '<div class="alert alert-danger col-sms-4 errordiv" role="alert"><i class="fa fa-warning"></i>The username: "'.$username.'" already exist</div>';

    }
    return $errormessage;
}


function addnewitem($user,$username,$password,$email,$renderpath)
{
    $errormessage = $this->validateinsert($username);

    if($errormessage=="")
    {
        $salt =$this->security->createsalt();
        $hashpassword =$this->security->Sethashpassword($salt,$password);
        $this->insertuser($user,$username,$salt,$hashpassword,$email);

        $this->app->response->redirect($this->app->urlFor('users')
                , array('newurl'=>$this->app->urlFor('newuser') 
                ,'editurl'=>$this->editurl
                ,'deleteurl'=>$this->deleteurl));

    }
    else
    {
        $this->app->render($renderpath,array('listurl'=>$this->app->urlFor('users')
                ,'selfurl'=>$this->app->urlFor('newuser')
                ,'username'=>$username
                ,'email'=>$email
                ,'errormessage'=>$errormessage
                ,'option'=>$this->mainoption
                ,'route'=>'New'
                ,'link'=>$this->mainlink));
    }


}

    function updateitem($user,$id,$username,$email)
    {
        $this->updateuser($user,$id,$username,$email);
        $this->app->response->redirect($this->app->urlFor('users'), array('newurl'=>$this->app->urlFor('newuser') ,'editurl'=>$this->editurl,'deleteurl'=>$this->deleteurl));
    }

    function deleteitem($id)
    {
        $this->deleteuser($id);
        $this->app->response->redirect($this->app->urlFor('users'), array('newurl'=>$this->app->urlFor('newuser') ,'editurl'=>$this->editurl,'deleteurl'=>$this->deleteurl));
    }


function buildgrid($editurl,$deleteurl)
{
    $result =$this->getall();
    echo '<table class="table table-striped table-bordered bootstrap-datatable datatable">
          <thead>
          <tr>
          <th>Username</th>
          <th>Actions</th>
          </tr>
          </thead>   
          <tbody>';
            foreach ($result as $row)
            {
                echo '<tr>';
                echo '<td>'. $row['username'] . '</td>';
                echo '<td class="center">
                 <a class="btn btn-info" href="'.$editurl.'/'.$row['id'].'">
                 <i class="halflings-icon white edit"></i>
                 </a>
                 <a href ="'.$deleteurl.'/'.$row['id'].'" class="btn btn-danger">
                 <i class="halflings-icon white trash"></i>
                 </a>
                 </td>';

                echo '</tr>';
            }
            echo'  </tbody></table>';
}

function buildresponsivegrid($editurl,$deleteurl)
   {
     $result=$this->getall();
     echo'<div id="grids" width="100%">         
       <table id="datagrid" class="table table-striped table-hover dt-responsive" cellspacing="0" width="80%">
        <thead>
            <tr>
              <th>Username</th>
              <th class="nosort">Actions</th>
            </tr>
        </thead>
        <tbody>';
        foreach ($result as $row) 
        {
         echo '<tr>';
         echo '<td>'. $row['username'] . '</td>';
         echo '<td class="center">
         <a class="btn btn-info" href="'.$editurl.'/'.$row['id'].'">
	 <i class="fa fa-edit"></i>  
	 </a>
	 <a href ="'.$deleteurl.'/'.$row['id'].'" class="btn btn-danger">
	 <i class="fa fa-trash-o"></i> 
	 </a>
	 </td>';
         echo '</tr>';
        } 
            
        echo'</tbody></table></div>';
   }




function insertuser($user,$username,$salt,$password,$email)
{
$dt = date('Y-m-d H:i:s');
$this->database->insert("systemuser", [ 'username'=>$username
,'salt'=>$salt
,'password'=>$password
,'email'=>$email
,"createuser" => $user
,"createdate" => $dt 
,"modifyuser" => $user
,"modifydate" => $dt ]);

}

function getall()
{


    $sth = $this->database->pdo->prepare("SELECT id,username FROM systemuser");
    $sth->execute();
    return $sth;


}
    function updateuser($user,$id,$username,$email)
    {
        
        $dt = date('Y-m-d H:i:s');
        $this->database->update("systemuser", [
            "username"=>$username
           ,"email"=>$email     
           ,"modifyuser" => $user
           ,"modifydate"=>$dt
             ],[
            "id[=]" => $id
        ]);

        
    }

function deleteuser($id)
{

    $this->database->delete("systemuser", [
        "AND" => [
            "id" => $id

	]

]);
    //var_dump($database->error());
   //header('Location: '.$redirecturl);
}

function finduser($username)
{
    $count =  $this->database->count("systemuser", [
"id" 
],["AND" => [ 
    "username" => $username
    ]]);
   return $count;
    
}

function getuserbyid($id)
{

$data = $this->database->select("systemuser", [
"id",
"username",    
"salt",
"password",
"email"    
], [
"id" => $id
]);
   
return $data;   
} 
}
?>