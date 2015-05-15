<?php

class ActionController {
    private $database;
    private $editurl;
    private $deleteurl;
    private $mainlink;
    private $mainoption;
    public function __construct($app,$medoo) {

        $this->database =$medoo;
        $this->app=$app;
        $this->editurl ='editaction';
        $this->deleteurl='viewaction';
        $this->mainlink = '/actions';
        $this->mainoption ='Actions';
        

    }
        function rendergridview($globalobj,$renderpath)
{

    $this->app->render($renderpath,
        array('newurl'=>$this->app->urlFor('newaction')
            ,'editurl'=>$this->editurl
            ,'deleteurl'=>$this->deleteurl
            ,'obj'=>$this
            ,'globalobj'=>$globalobj
            ,'option'=>$this->mainoption
            ,'route'=>''
            ,'link'=>$this->mainlink));

}

function rendernewview($action,$description,$errormessage,$renderpath)
{
    $this->app->render($renderpath,array('listurl'=>$this->app->urlFor('actions')
            ,'selfurl'=>$this->app->urlFor('newaction')
            ,'action'=>$action
            ,'description'=>$description
            ,'errormessage'=>$errormessage
            ,'option'=>$this->mainoption
            ,'route'=>'New'
            ,'link'=>$this->mainlink));

}

function rendereditview($id,$renderpath)
{

    $datas=$this->getactionbyid($id);
    foreach($datas as $data)
    {
        $id=$data["id"];
        $action = $data["action"];
        $description = $data["description"];
       
        
    }
    $updateurl=  str_replace(":id",$id,$this->app->urlFor('updateaction'));
    $this->app->render($renderpath,array( 
            'id'=>$id
            ,'action'=>$action
            ,'description'=>$description
            ,'updateurl'=>$updateurl
            ,'listurl'=>$this->app->urlFor('actions')
            ,'option'=>$this->mainoption
            ,'route'=>'Edit'
            ,'link'=>$this->mainlink));

}

function renderdeleteview($id,$renderpath)
{
    $datas=$this->getactionbyid($id);
    foreach($datas as $data)
    {
        $id =$data["id"];
        $action = $data["action"];
        $description =$data["description"];
    }
    $deleteurl=  str_replace(":id",$id,$this->app->urlFor('deleteaction'));
    $this->app->render($renderpath,array('id'=>$id
            ,'action'=>$action
            ,'description'=>$description
            ,'deleteurl'=>$deleteurl
            ,'listurl'=>$this->app->urlFor('actions')
            ,'option'=>$this->mainoption
            ,'route'=>'Delete'
            ,'link'=>$this->mainlink));
}

function validateinsert($action)
{
    //Validate if exist
    $count =$this->findaction($action);
    $errormessage="";
    if($count>0)
    {
        $errormessage= '<div class="alert alert-danger col-sms-4 errordiv" action="alert"><i class="fa fa-warning"></i>The action: "'.$action.'" already exist</div>';

    }
    return $errormessage;
}


    function addnewitem($user,$action,$description,$renderpath)
{
    $errormessage = $this->validateinsert($action);

    if($errormessage=="")
    {
       
        $this->insertaction($user,$action,$description);

        $this->app->response->redirect($this->app->urlFor('actions')
                , array('newurl'=>$this->app->urlFor('newaction') 
                ,'editurl'=>$this->editurl
                ,'deleteurl'=>$this->deleteurl));

    }
    else
    {
        $this->app->render($renderpath,array('listurl'=>$this->app->urlFor('actions')
                ,'selfurl'=>$this->app->urlFor('newaction')
                ,'action'=>$action
                ,'description'=>$description
                ,'errormessage'=>$errormessage
                ,'option'=>$this->mainoption
                ,'route'=>'New'
                ,'link'=>$this->mainlink));
    }


}

    function updateitem($user,$id,$action,$description)
    {
        $this->updateaction($user,$id,$action,$description);
        $this->app->response->redirect($this->app->urlFor('actions'));
    }

    function deleteitem($id)
    {
        $this->deleteaction($id);
        $this->app->response->redirect($this->app->urlFor('actions'));
    }
    
    function buildresponsivegrid($editvar,$deletevar,$editurl,$deleteurl)
   {
     $result=$this->getall();
     echo'<div id="grids" width="100%">         
       <table id="datagrid" class="table table-striped table-hover dt-responsive" cellspacing="0" width="80%">
        <thead>
            <tr>
              <th>Action</th>
              <th>Description</th>
              <th class="nosort">Actions</th>
            </tr>
        </thead>
        <tbody>';
        foreach ($result as $row) 
        {
         echo '<tr>';
         echo '<td>'. $row['action'] . '</td>';
         echo '<td>'. $row['description'] . '</td>';
         echo '<td class="center">';
          if ($editvar==1)
         { 
         echo'<a class="btn btn-info" href="'.$editurl.'/'.$row['id'].'">
	 <i class="fa fa-edit"></i>  
	 </a> ';
         }
          if ($deletevar==1)
         { 
	 echo'<a href ="'.$deleteurl.'/'.$row['id'].'" class="btn btn-danger">
	 <i class="fa fa-trash-o"></i> 
	 </a>';
         }        
	 echo'</td></tr>';
        } 
            
        echo'</tbody></table></div>';
   }




function insertaction($user,$action,$description)
{
$dt = date('Y-m-d H:i:s');
$this->database->insert("action",array('action'=>$action
,'description'=>$description
,"createuser" => $user
,"createdate" => $dt 
,"modifyuser" => $user
,"modifydate" => $dt ));

}

function getall()
{


    $sth = $this->database->pdo->prepare("SELECT id,action,description FROM action");
    $sth->execute();
    return $sth;


}
    function updateaction($user,$id,$action,$description)
    {
        
        $dt = date('Y-m-d H:i:s');
        $this->database->update("action",array(
            "action"=>$action
           ,"description"=>$description    
           ,"modifyuser" => $user
           ,"modifydate"=>$dt)
           ,array(
            "id[=]" => $id
        ));

        
    }

function deleteaction($id)
{

    $this->database->delete("action", array("AND" =>array("id" => $id)));
    
}

function findaction($action)
{
    $count =  $this->database->count("action",array("id")
    ,array("AND" =>array( "action" => $action))); 
   return $count;
    
}

function getactionbyid($id)
{
 $data = $this->database->select("action",array(
 "id",
 "action",    
 "description",  
 ),array(
 "id" => $id
 ));
 return $data;   
} 
 
}
