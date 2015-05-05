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
    $linkurl = str_replace(":id",$id,$this->app->urlFor('edituser'));
    $this->app->render($renderpath,array('id'=>$id
            ,'username'=>$username
            ,'updateurl'=>$updateurl
            ,'listurl'=>$linkurl 
            ,'option'=>'Password change'
            ,'route'=>$username
            ,'linkroute'=>$linkurl
            ,'link'=>''));
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

function get_username_byid($id)
{
 $username="";   
 $data = $this->database->select("systemuser", [
"username"       
], [
"id" => $id
]);
   
foreach ($data as $row) 
{
 $username = $row["username"];          
}
 return $username;
}
//----------------USER ROLES----------------------------------------------------
function build_userroles_grid($userid,$deleteurl)
{
   $result=$this->get_userroles($userid);
     echo'<div id="grids" width="100%">         
       <table id="datagrid" class="table table-striped table-hover dt-responsive" cellspacing="0" width="80%">
        <thead>
            <tr>
              <th>Role</th>
              <th class="nosort">Actions</th>
            </tr>
        </thead>
        <tbody>';
        foreach ($result as $row) 
        {
         echo '<tr>';
         echo '<td>'. $row['role'] . '</td>';
         echo '<td class="center">
	 <a href ="'.$deleteurl.'/'.$row['userid'].'/'.$row['roleid'].'" class="btn btn-danger">
	 <i class="fa fa-trash-o"></i> 
	 </a>
	 </td>';
         echo '</tr>';
        } 
            
        echo'</tbody></table></div>';  
}

function render_userroles_grid($userid,$renderpath)
{
   $username =$this->get_username_byid($userid);
   $newurl = str_replace(':userid', $userid,$this->app->urlFor('newuserrole'));
   $linkroute= str_replace(':id', $userid,$this->app->urlFor('edituser'));
  // $editurl = str_replace(':roleid', $roleid,$editurl_temp);
   $link =str_replace(':id', $userid,$this->app->urlFor('userroles')); 
   $this->app->render($renderpath,
        array('newurl'=>$newurl 
            ,'userid'=>$userid
            ,'editurl'=>$this->app->view->getData('basepath').'/edituserrole'
            ,'deleteurl'=>$this->app->view->getData('basepath').'/viewuserrole'
            ,'obj'=>$this
            ,'option'=>'User Roles'
            ,'route'=>$username
            ,'link'=>$link
            ,'linkroute'=>$linkroute));
   
}


function get_user_select($userid,$attribute)
{
  
  $sth = $this->database->pdo->prepare("SELECT id ,username FROM systemuser where id ='".$userid."'");
        $sth->execute();
       
         echo '<select id ="userid" name="userid"'.$attribute.'>';

        $selected="";
        foreach ($sth as $row) {
            if ($userid == $row['id']) {
                $selected = 'selected';
            }
            else
            {$selected="";
            }
            echo '<option value ="'.$row['id'].'" '.$selected.' >'.$row['username'].'</option>';

        }
   echo '</select>';
}

function get_role_select($roleid,$attribute)
{
   $sth = $this->database->pdo->prepare('SELECT id ,role FROM role');
        $sth->execute();
         echo '<select id ="roleid" name="roleid"'.$attribute.'>';

        $selected="";
        foreach ($sth as $row) {
            if ($roleid == $row['id']) {
                $selected = 'selected';
            }
            else
            {$selected="";
            }
            echo '<option value ="'.$row['id'].'" '.$selected.' >'.$row['role'].'</option>';

        }
   echo '</select>';  
}

function get_userroles($userid)
{
    $sth = $this->database->pdo->prepare("select ur.systemuserid as userid,ur.roleid as roleid,u.username,r.role as role from userrole ur
         inner join systemuser u on (ur.systemuserid=u.id) inner join role r on (ur.roleid =r.id) where ur.systemuserid ='".$userid."'");
    $sth->execute(); 
    return $sth;
}

function render_new_userrole($userid,$renderpath)
{
  $listurl = str_replace(":id",$userid,$this->app->urlFor('userroles')); 
  $selfurl = str_replace(":userid",$userid,$this->app->urlFor('newuserrole')); 
  $this->app->render($renderpath,array('listurl'=>$listurl
            ,'selfurl'=>$selfurl
            ,'roleid'=>''
            ,'userid'=>$userid
            ,'obj'=>$this
            ,'errormessage'=>''
            ,'option'=>'User Role'
            ,'route'=>'New'
            ,'link'=>$listurl));  
  
    
}


function render_delete_userrole($userid,$roleid,$renderpath)
{
    $datas=$this->get_userrole_byid($userid,$roleid);
    foreach($datas as $data)
    {
        $userid =$data["systemuserid"];
        $roleid= $data["roleid"];
    }
    $deleteurl=$this->app->view->getData('basepath').'/deleteuserrole/'.$userid.'/'.$roleid;
    $listurl = str_replace(":id",$userid,$this->app->urlFor('userroles')); 
    $this->app->render($renderpath,array(
            'userid'=>$userid
            ,'roleid'=>$roleid
            ,'deleteurl'=>$deleteurl
            ,'obj'=>$this
            ,'listurl'=> $listurl
            ,'option'=>$this->mainoption
            ,'route'=>'Delete'
            ,'link'=>$this->mainlink));
}

function get_userrole_byid($userid,$roleid)
{
 $data = $this->database->select("userrole", [
"systemuserid",
"roleid",      
], ["AND" => [ 
    "systemuserid" => $userid
    ,"roleid"=>$roleid
    ]]); 
 return $data;
}
function validate_insert_userrole($userid,$roleid)
{
 //Validate if exist
    $count =$this->find_userrole($userid,$roleid);
    $errormessage="";
    if($count>0)
    {
        $errormessage= '<div class="alert alert-danger col-sms-4 errordiv" role="alert"><i class="fa fa-warning"></i>The role for this user already exist</div>';

    }
    return $errormessage;   
}

function find_userrole($userid,$roleid)
{
    $count =  $this->database->count("userrole", [
"systemuserid" 
],["AND" => [ 
    "systemuserid" => $userid
    ,"roleid"=>$roleid
    ]]);
   return $count;
    
}

function add_new_userrole($user,$userid,$roleid,$renderpath)
{
    $errormessage = $this->validate_insert_userrole($userid,$roleid);
    $listurl = str_replace(":id",$userid,$this->app->urlFor('userroles'));
    $selfurl = str_replace(":userid",$userid,$this->app->urlFor('newuserrole'));
    if($errormessage=="")
    {
       
        $this->insert_userrole($user,$userid,$roleid);
        $this->app->response->redirect($listurl);

    }
    else
    {
        $this->app->render($renderpath,array('listurl'=>$listurl
                ,'selfurl'=>$selfurl 
                ,'userid'=>$userid
                ,'roleid'=>$roleid
                ,'obj'=>$this
                ,'errormessage'=>$errormessage
                ,'option'=>'User Role'
                ,'route'=>'New'
                ,'link'=>$listurl));
        
    }


}
   
    function insert_userrole($user,$userid,$roleid)
    {
        $dt = date('Y-m-d H:i:s');
        $this->database->insert("userrole", [ 'systemuserid'=>$userid
        ,'roleid'=>$roleid
        ,"createuser" => $user
        ,"createdate" => $dt 
        ,"modifyuser" => $user
        ,"modifydate" => $dt ]);

    }
   

    function delete_userrole_item($userid,$roleid)
    {
        $this->delete_userrole($userid,$roleid);
        $redirecturl =str_replace(":id",$userid,$this->app->urlFor('userroles')); 
        $this->app->response->redirect($redirecturl);
    }
   

function delete_userrole($userid,$roleid)
{

    $this->database->delete("userrole", [
        "AND" => [
            "systemuserid"=>$userid
           ,"roleid"=>$roleid  

	]

]);


}
// ---------------END USER ROLES----------------------------------------------------
}
?>