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

function rendernewview($username,$salt,$password,$email,$errormessage,$globalobj,$renderpath)
{
    $this->app->render($renderpath,array('listurl'=>$this->app->urlFor('users')
            ,'selfurl'=>$this->app->urlFor('newuser')
            ,'username'=>$username
            ,'salt'=>$salt
            ,'password'=>$password
            ,'email'=>$email
            ,'globalobj'=>$globalobj
            ,'errormessage'=>$errormessage
            ,'option'=>$this->mainoption
            ,'route'=>'New'
            ,'link'=>$this->mainlink));

}

function rendereditview($id,$globalobj,$renderpath)
{

    $datas=$this->getuserbyid($id);
    foreach($datas as $data)
    {
        $username = $data["username"];
        $salt = $data["salt"];
        $password = $data["password"];
        $email = $data["email"];
       
        
    }
    $this->app->render($renderpath,array( 
            'id'=>$id
            ,'username'=>$username
            ,'salt'=>$salt
            ,'password'=>$password
            ,'email'=>$email
            ,'globalobj'=>$globalobj
            ,'updateurl'=>$this->app->urlFor('updateuser')
            ,'listurl'=>$this->app->urlFor('users')
            ,'option'=>$this->mainoption
            ,'route'=>'Edit'
            ,'link'=>$this->mainlink));

}

function renderdeleteview($id,$globalobj,$renderpath)
{
    $datas=$this->getuserbyid($id);
    foreach($datas as $data)
    {
        $salt = $data["salt"];
        $username =$data["username"];
    }
    $this->app->render($renderpath,array('id'=>$id
            ,'username'=>$username
            ,'salt'=>$salt
            ,'password'=>$password
            ,'email'=>$email
            ,'globalobj'=>$globalobj
            ,'deleteurl'=>$this->app->urlFor('deleteuser')
            ,'listurl'=>$this->app->urlFor('users')
            ,'option'=>$this->mainoption
            ,'route'=>'Delete'
            ,'link'=>$this->mainlink));
}



function validateinsert($username)
{
    //Validate if exist
    $count =$this->finduser($username);
    $errormessage="";
    if($count>0)
    {
        $errormessage= '<div class="alert alert-error">The username already exist</div>';

    }
    return $errormessage;
}


function addnewitem($username,$password,$email,$globalobj,$renderpath)
{
    $errormessage = $this->validateinsert($username);

    if($errormessage=="")
    {
        $salt =$this->security->createsalt();
        $hashpassword =$this->security->Sethashpassword($salt,$password);
        $this->insertuser($username,$salt,$hashpassword,$email);

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
                ,'salt'=>$salt
                ,'password'=>$password
                ,'email'=>$email
                ,'globalobj'=>$globalobj
                ,'errormessage'=>$errormessage
                ,'option'=>$this->mainoption
                ,'route'=>'New'
                ,'link'=>$this->mainlink));
    }


}

    function updateitem($username,$id,$username,$salt)
    {
        $this->updateuser($id,$username);
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






function insertuser($username,$salt,$password,$email)
{
$dt = date('Y-m-d H:i:s');
$this->database->insert("systemuser", [ 'username'=>$username
,'salt'=>$salt
,'password'=>$password
,'email'=>$email
,"createuser" => $username
,"createdate" => $dt 
,"modifyuser" => $username
,"modifydate" => $dt ]);



}

function getall()
{


    $sth = $this->database->pdo->prepare(''
            . 'SELECT username,sat,password,email'
            . 'FROM systemuser');
    $sth->execute();
    return $sth;


}
    function updateuser($id,$username)
    {
        
        $dt = date('Y-m-d H:i:s');
        $this->database->update("systemuser", [
            'username'=>$username
           ,"modifyuser" => $username
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