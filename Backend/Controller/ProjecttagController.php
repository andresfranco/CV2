<?php

class ProjecttagController {
    private $database;
    private $editurl;
    private $deleteurl;
    private $mainlink;
    private $mainoption;
    public function __construct($app,$medoo) {

        $this->database =$medoo;
        $this->app=$app;
        $this->editurl ='editprojecttag';
        $this->deleteurl='viewprojecttag';
        $this->mainlink = '/projecttags';
        $this->mainoption ='Project tag';

    }


function rendergridview($projectid,$renderpath)
{
    $link =$this->mainlink.'/'.$projectid;
    $projectname =$this->getprojectnamebyid($projectid);
    $newurl =  str_replace(':projectid',$projectid,$this->app->urlFor('newprojecttag'));
    $linkroute = str_replace(':id',$projectid,$this->app->urlFor('editproject'));
    $this->app->render($renderpath,
        array('newurl'=>$newurl
            ,'editurl'=>$this->editurl
            ,'deleteurl'=>$this->deleteurl
            ,'obj'=>$this
            ,'projectid'=>$projectid
            ,'option'=>$this->mainoption
            ,'route'=>$projectname
            ,'link'=>$link
            ,'linkroute'=>$linkroute));

}

function rendernewview($projectid,$tagname,$errormessage,$renderpath)
{   
    $link =$this->mainlink.'/'.$projectid;
    $listurl = str_replace(':projectid',$projectid,$this->app->urlFor('projecttags'));
    $this->app->render($renderpath,array('listurl'=>$listurl
            ,'selfurl'=>$this->app->urlFor('insertprojecttag')
            ,'projectid'=>$projectid
            ,'tagname'=>$tagname
            ,'obj'=>$this
            ,'errormessage'=>$errormessage
            ,'option'=>$this->mainoption
            ,'route'=>'New'
            ,'link'=>$link));

}

function getprojectnamebyid($projectid)
{
  $sth = $this->database->pdo->prepare("SELECT name from project where id='".$projectid."'");
  $sth->execute();
  foreach ($sth as $row)
            {
                $projectname =$row['name'];
                
            } 
  return $projectname;  
}

function rendereditview($id,$renderpath)
{
    
    $datas=$this->getprojecttagbyid($id);
    foreach($datas as $data)
    {
        $tagname = $data["tagname"];
        $projectid = $data["projectid"];
        
    }
    $link =$this->mainlink.'/'.$projectid;
    $listurl =  str_replace(':projectid',$projectid,$this->app->urlFor('projecttags'));
    $updateurl =str_replace(':id',$id,$this->app->urlFor('updateprojecttag'));
    $this->app->render($renderpath,array( 
            'projectid'=>$projectid
            ,'tagname'=>$tagname
            ,'obj'=>$this
            ,'updateurl'=>$updateurl
            ,'listurl'=>$listurl
            ,'option'=>$this->mainoption
            ,'route'=>'Edit'
            ,'link'=>$link));

}

function renderdeleteview($id,$renderpath)
{
    $datas=$this->getprojecttagbyid($id);
    foreach($datas as $data)
    {
        $tagname = $data["tagname"];
        $projectid =$data["projectid"];
    }
    $link =$this->mainlink.'/'.$projectid;
    $deleteurl =str_replace(':id',$id,$this->app->urlFor('deleteprojecttag'));
    $listurl =  str_replace(':projectid',$projectid,$this->app->urlFor('projecttags'));
    $this->app->render($renderpath,array(
            'projectid'=>$projectid
            ,'tagname'=>$tagname
            ,'obj'=>$this
            ,'deleteurl'=>$deleteurl
            ,'listurl'=>$listurl 
             ,'option'=>$this->mainoption
            ,'route'=>'Delete'
            ,'link'=>$link));


}



function validateinsert($projectid,$tagname)
{
    //Validate if exist
    $count =$this->findprojecttag($projectid,$tagname);
    $errormessage="";
    if($count>0)
    {
        $errormessage= '<div class="alert alert-danger col-sms-4 errordiv" role="alert"><i class="fa fa-warning"></i>The tagname for this project already exist</div>';

    }
    return $errormessage;
}


function addnewitem($username,$projectid,$tagname,$renderpath)
{
    $errormessage = $this->validateinsert($projectid,$tagname);
    $listurl =  str_replace(':projectid',$projectid,$this->app->urlFor('projecttags'));
    if($errormessage=="")
    {
       
        
        $this->insertprojecttag($username,$projectid,$tagname);
        
        $this->app->response->redirect($listurl);

    }
    else
    {
        $link =$this->mainlink.'/'.$projectid;
        $this->app->render($renderpath,array('listurl'=>$listurl
                ,'selfurl'=>$this->app->urlFor('insertprojecttag')
                ,'projectid'=>$projectid
                ,'tagname'=>$tagname
                ,'obj'=>$this
                ,'errormessage'=>$errormessage
                ,'option'=>$this->mainoption
                ,'route'=>'New'
                ,'link'=>$link));
    }


}

    function updateitem($username,$id,$projectid,$tagname)
    { 
        $listurl =  str_replace(':projectid',$projectid,$this->app->urlFor('projecttags'));
        $this->updateprojecttag($username,$id,$projectid,$tagname);
        $this->app->response->redirect($listurl);
    }

    function deleteitem($id)
    {
        $projectid =$this->getprojectidbyid($id);
        $listurl =  str_replace(':projectid',$projectid,$this->app->urlFor('projecttags'));
        $this->deleteprojecttag($id);
        
        $this->app->response->redirect($listurl);
    }
    
    function getprojectidbyid($id)
    {
     $sth = $this->database->pdo->prepare("SELECT projectid from project_tag where id='".$id."'");
    $sth->execute();
    
      foreach ($sth as $row)
            {
             $projectid =$row['projectid'];
            }
       return $projectid; 
    }


function buildgrid($projectid,$editurl,$deleteurl)
{
    $result =$this->getall($projectid);
    echo '<table class="table table-striped table-bordered bootstrap-datatable datatable">
          <thead>
          <tr>
          <th>Project</th>
          <th>Tag Name</th>
          <th>Actions</th>
          </tr>
          </thead>   
          <tbody>';
            foreach ($result as $row)
            {
                echo '<tr>';
                echo '<td>'. $row['projectname'] . '</td>';
                echo '<td>'. $row['tagname'] . '</td>';
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

function buildresponsivegrid($projectid,$editurl,$deleteurl)
   {
     $result=$this->getall($projectid);
     echo'<div id="grids" width="100%">         
       <table id="datagrid" class="table table-striped table-hover dt-responsive" cellspacing="0" width="80%">
        <thead>
            <tr>
             <th>Project</th>
             <th>Tag Name</th>
             <th class="nosort">Actions</th>
            </tr>
        </thead>
        <tbody>';
        foreach ($result as $row) 
        {
         echo '<tr>';
         echo '<td>'. $row['projectname'] . '</td>';
         echo '<td>'. $row['tagname'] . '</td>';
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
   






function insertprojecttag($username,$projectid ,$tagname)
{
  
    $dt = date('Y-m-d H:i:s');
$this->database->insert("project_tag",array("projectid" => $projectid,
"tagname" => $tagname,
"createuser" => $username,
"createdate" => $dt ,
"modifyuser" => $username,
"modifydate" => $dt ));



}

function getall($projectid)
{


    $sth = $this->database->pdo->prepare("SELECT pt.id,pt.projectid,pt.tagname,p.name as projectname"
            . " FROM project_tag pt inner join project p on (pt.projectid =p.id) where pt.projectid='".$projectid."'");
    $sth->execute();
    return $sth;


}
    function updateprojecttag($username,$id,$projectid,$tagname)
    {
        
        $dt = date('Y-m-d H:i:s');
        $this->database->update("project_tag",array("projectid" => $projectid,
            "tagname" => $tagname,
            "modifyuser" => $username,
            "modifydate"=>$dt
             ),array(
            "id[=]" => $id
        ));


    }

function deleteprojecttag($id)
{

    $this->database->delete("project_tag",array("AND" =>array("id" => $id)));
  
}

function findprojecttag($projectid,$tagname)
{
    $count =  $this->database->count("project_tag",array("id" )
    ,array("AND" =>array("projectid" => $projectid, "tagname"=>$tagname)));
    return $count;   
}

function getprojecttagbyid($id)
{
 $data = $this->database->select("project_tag",array(
 "id",
 "projectid",    
 "tagname")
 ,array("id" => $id));
   
return $data;   
}

  function getprojectselectbyid($attribute,$projectid)
    {
        $sth = $this->database->pdo->prepare("SELECT id ,name FROM project where id='".$projectid."'");
        $sth->execute();
         echo '<select id ="projectid" name="projectid"'.$attribute.'>';
        $selected="";
        foreach ($sth as $row) {
            if ($projectid == $row['id']) {
                $selected = 'selected';
            }
            else
            {$selected="";
            }
            echo '<option value ="'.$row['id'].'" '.$selected.' >'.$row['name'].'</option>';

        }
         echo '</select>';
    } 

}
?>