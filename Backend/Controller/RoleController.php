<?php

class RoleController {
    private $database;
    private $editurl;
    private $deleteurl;
    private $mainlink;
    private $mainoption;
    public function __construct($app,$medoo) {

        $this->database =$medoo;
        $this->app=$app;
        $this->editurl ='editrole';
        $this->deleteurl='viewrole';
        $this->mainlink = '/roles';
        $this->mainoption ='Roles';
        

    }
    
    function rendergridview($renderpath)
{

    $this->app->render($renderpath,
        array('newurl'=>$this->app->urlFor('newrole')
            ,'editurl'=>$this->editurl
            ,'deleteurl'=>$this->deleteurl
            ,'obj'=>$this
            ,'option'=>$this->mainoption
            ,'route'=>''
            ,'link'=>$this->mainlink));

}

function rendernewview($role,$description,$errormessage,$renderpath)
{
    $this->app->render($renderpath,array('listurl'=>$this->app->urlFor('roles')
            ,'selfurl'=>$this->app->urlFor('newrole')
            ,'role'=>$role
            ,'description'=>$description
            ,'errormessage'=>$errormessage
            ,'option'=>$this->mainoption
            ,'route'=>'New'
            ,'link'=>$this->mainlink));

}

function rendereditview($id,$renderpath)
{

    $datas=$this->getrolebyid($id);
    foreach($datas as $data)
    {
        $id=$data["id"];
        $role = $data["role"];
        $description = $data["description"];
       
        
    }
    $updateurl=  str_replace(":id",$id,$this->app->urlFor('updaterole'));
    $this->app->render($renderpath,array( 
            'id'=>$id
            ,'role'=>$role
            ,'description'=>$description
            ,'updateurl'=>$updateurl
            ,'listurl'=>$this->app->urlFor('roles')
            ,'option'=>$this->mainoption
            ,'route'=>'Edit'
            ,'link'=>$this->mainlink));

}

function renderdeleteview($id,$renderpath)
{
    $datas=$this->getrolebyid($id);
    foreach($datas as $data)
    {
        $id =$data["id"];
        $role = $data["role"];
        $description =$data["description"];
    }
    $deleteurl=  str_replace(":id",$id,$this->app->urlFor('deleterole'));
    $this->app->render($renderpath,array('id'=>$id
            ,'role'=>$role
            ,'description'=>$description
            ,'deleteurl'=>$deleteurl
            ,'listurl'=>$this->app->urlFor('roles')
            ,'option'=>$this->mainoption
            ,'route'=>'Delete'
            ,'link'=>$this->mainlink));
}
function validateinsert($role)
{
    //Validate if exist
    $count =$this->findrole($role);
    $errormessage="";
    if($count>0)
    {
        $errormessage= '<div class="alert alert-danger col-sms-4 errordiv" role="alert"><i class="fa fa-warning"></i>The role: "'.$role.'" already exist</div>';

    }
    return $errormessage;
}


function addnewitem($user,$role,$description,$renderpath)
{
    $errormessage = $this->validateinsert($role);

    if($errormessage=="")
    {
       
        $this->insertrole($user,$role,$description);

        $this->app->response->redirect($this->app->urlFor('roles'));

    }
    else
    {
        $this->app->render($renderpath,array('listurl'=>$this->app->urlFor('roles')
                ,'selfurl'=>$this->app->urlFor('newrole')
                ,'role'=>$role
                ,'description'=>$description
                ,'errormessage'=>$errormessage
                ,'option'=>$this->mainoption
                ,'route'=>'New'
                ,'link'=>$this->mainlink));
    }


}

    function updateitem($user,$id,$role,$description)
    {
        $this->updaterole($user,$id,$role,$description);
        $this->app->response->redirect($this->app->urlFor('roles'));
    }

    function deleteitem($id)
    {
        $this->deleterole($id);
        $this->app->response->redirect($this->app->urlFor('roles'));
    }
    
    function buildresponsivegrid($editurl,$deleteurl)
   {
     $result=$this->getall();
     echo'<div id="grids" width="100%">         
       <table id="datagrid" class="table table-striped table-hover dt-responsive" cellspacing="0" width="80%">
        <thead>
            <tr>
              <th>Role</th>
              <th>Description</th>
              <th class="nosort">Actions</th>
            </tr>
        </thead>
        <tbody>';
        foreach ($result as $row) 
        {
         echo '<tr>';
         echo '<td>'. $row['role'] . '</td>';
         echo '<td>'. $row['description'] . '</td>';
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




function insertrole($user,$role,$description)
{
$dt = date('Y-m-d H:i:s');
$this->database->insert("role", [ 'role'=>$role
,'description'=>$description
,"createuser" => $user
,"createdate" => $dt 
,"modifyuser" => $user
,"modifydate" => $dt ]);

}

function getall()
{


    $sth = $this->database->pdo->prepare("SELECT id,role,description FROM role");
    $sth->execute();
    return $sth;


}
    function updaterole($user,$id,$role,$description)
    {
        
        $dt = date('Y-m-d H:i:s');
        $this->database->update("role", [
            "role"=>$role
           ,"description"=>$description    
           ,"modifyuser" => $user
           ,"modifydate"=>$dt
             ],[
            "id[=]" => $id
        ]);

        
    }

function deleterole($id)
{

    $this->database->delete("role", [
        "AND" => [
            "id" => $id

	]

]);
    
}

function findrole($role)
{
    $count =  $this->database->count("role", [
"id" 
],["AND" => [ 
    "role" => $role
    ]]);
   return $count;
    
}

function getrolebyid($id)
{

$data = $this->database->select("role", [
"id",
"role",    
"description",  
], [
"id" => $id
]);
   
return $data;   
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

function get_action_select($actionid,$attribute)
{
   $sth = $this->database->pdo->prepare('SELECT id ,action FROM role');
        $sth->execute();
         echo '<select id ="actionid" name="actionid"'.$attribute.'>';

        $selected="";
        foreach ($sth as $row) {
            if ($actionid == $row['id']) {
                $selected = 'selected';
            }
            else
            {$selected="";
            }
            echo '<option value ="'.$row['id'].'" '.$selected.' >'.$row['action'].'</option>';

        }
   echo '</select>';  
}
//----------------ROLE ACTIONS----------------------------------------------------
function build_roleactions_grid($roleid,$deleteurl)
{
   $result=$this->get_roleactions($roleid);
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
	 <a href ="'.$deleteurl.'/'.$row['roleid'].'/'.$row['actionid'].'" class="btn btn-danger">
	 <i class="fa fa-trash-o"></i> 
	 </a>
	 </td>';
         echo '</tr>';
        } 
            
        echo'</tbody></table></div>';  
}

function render_roleactions_grid($roleid,$renderpath)
{
   $username =$this->get_username_byid($roleid);
   $newurl = str_replace(':roleid', $roleid,$this->app->urlFor('newroleaction'));
   $linkroute= str_replace(':id', $roleid,$this->app->urlFor('edituser'));
  // $editurl = str_replace(':actionid', $actionid,$editurl_temp);
   $link =str_replace(':id', $roleid,$this->app->urlFor('roleactions')); 
   $this->app->render($renderpath,
        array('newurl'=>$newurl 
            ,'roleid'=>$roleid
            ,'editurl'=>$this->app->view->getData('basepath').'/editroleaction'
            ,'deleteurl'=>$this->app->view->getData('basepath').'/viewroleaction'
            ,'obj'=>$this
            ,'option'=>'ROLE ACTIONS'
            ,'route'=>$username
            ,'link'=>$link
            ,'linkroute'=>$linkroute));
   
}


function get_user_select($roleid,$attribute)
{
  
  $sth = $this->database->pdo->prepare("SELECT id ,username FROM systemuser where id ='".$roleid."'");
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
            echo '<option value ="'.$row['id'].'" '.$selected.' >'.$row['username'].'</option>';

        }
   echo '</select>';
}


function get_roleactions($roleid)
{
    $sth = $this->database->pdo->prepare("select ur.systemroleid as roleid,ur.actionid as actionid,u.username,r.role as role from roleaction ur
         inner join systemuser u on (ur.systemroleid=u.id) inner join role r on (ur.actionid =r.id) where ur.systemroleid ='".$roleid."'");
    $sth->execute(); 
    return $sth;
}

function render_new_roleaction($roleid,$renderpath)
{
  $listurl = str_replace(":id",$roleid,$this->app->urlFor('roleactions')); 
  $selfurl = str_replace(":roleid",$roleid,$this->app->urlFor('newroleaction')); 
  $this->app->render($renderpath,array('listurl'=>$listurl
            ,'selfurl'=>$selfurl
            ,'actionid'=>''
            ,'roleid'=>$roleid
            ,'obj'=>$this
            ,'errormessage'=>''
            ,'option'=>'User Role'
            ,'route'=>'New'
            ,'link'=>$listurl));  
  
    
}


function render_delete_roleaction($roleid,$actionid,$renderpath)
{
    $datas=$this->get_roleaction_byid($roleid,$actionid);
    foreach($datas as $data)
    {
        $roleid =$data["systemroleid"];
        $actionid= $data["actionid"];
    }
    $deleteurl=$this->app->view->getData('basepath').'/deleteroleaction/'.$roleid.'/'.$actionid;
    $listurl = str_replace(":id",$roleid,$this->app->urlFor('roleactions')); 
    $this->app->render($renderpath,array(
            'roleid'=>$roleid
            ,'actionid'=>$actionid
            ,'deleteurl'=>$deleteurl
            ,'obj'=>$this
            ,'listurl'=> $listurl
            ,'option'=>$this->mainoption
            ,'route'=>'Delete'
            ,'link'=>$this->mainlink));
}

function get_roleaction_byid($roleid,$actionid)
{
 $data = $this->database->select("roleaction", [
"systemroleid",
"actionid",      
], ["AND" => [ 
    "systemroleid" => $roleid
    ,"actionid"=>$actionid
    ]]); 
 return $data;
}
function validate_insert_roleaction($roleid,$actionid)
{
 //Validate if exist
    $count =$this->find_roleaction($roleid,$actionid);
    $errormessage="";
    if($count>0)
    {
        $errormessage= '<div class="alert alert-danger col-sms-4 errordiv" role="alert"><i class="fa fa-warning"></i>The role for this user already exist</div>';

    }
    return $errormessage;   
}

function find_roleaction($roleid,$actionid)
{
    $count =  $this->database->count("roleaction", [
"systemroleid" 
],["AND" => [ 
    "systemroleid" => $roleid
    ,"actionid"=>$actionid
    ]]);
   return $count;
    
}

function add_new_roleaction($user,$roleid,$actionid,$renderpath)
{
    $errormessage = $this->validate_insert_roleaction($roleid,$actionid);
    $listurl = str_replace(":id",$roleid,$this->app->urlFor('roleactions'));
    $selfurl = str_replace(":roleid",$roleid,$this->app->urlFor('newroleaction'));
    if($errormessage=="")
    {
       
        $this->insert_roleaction($user,$roleid,$actionid);
        $this->app->response->redirect($listurl);

    }
    else
    {
        $this->app->render($renderpath,array('listurl'=>$listurl
                ,'selfurl'=>$selfurl 
                ,'roleid'=>$roleid
                ,'actionid'=>$actionid
                ,'obj'=>$this
                ,'errormessage'=>$errormessage
                ,'option'=>'User Role'
                ,'route'=>'New'
                ,'link'=>$listurl));
        
    }


}
   
    function insert_roleaction($user,$roleid,$actionid)
    {
        $dt = date('Y-m-d H:i:s');
        $this->database->insert("roleaction", [ 'systemroleid'=>$roleid
        ,'actionid'=>$actionid
        ,"createuser" => $user
        ,"createdate" => $dt 
        ,"modifyuser" => $user
        ,"modifydate" => $dt ]);

    }
   

    function delete_roleaction_item($roleid,$actionid)
    {
        $this->delete_roleaction($roleid,$actionid);
        $redirecturl =str_replace(":id",$roleid,$this->app->urlFor('roleactions')); 
        $this->app->response->redirect($redirecturl);
    }
   

function delete_roleaction($roleid,$actionid)
{

    $this->database->delete("roleaction", [
        "AND" => [
            "systemroleid"=>$roleid
           ,"actionid"=>$actionid  

	]

]);


}
// ---------------END ROLE ACTIONS----------------------------------------------------
    
}