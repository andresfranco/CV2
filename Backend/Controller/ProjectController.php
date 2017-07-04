<?php

class ProjectController {
   private $database;
   private $editurl;
   private $deleteurl;
   private $mainlink;
   private $mainoption;
   public function __construct($app,$medoo) {

        $this->database =$medoo;
        $this->app=$app;
        $this->editurl ='editproject';
        $this->deleteurl='viewproject';
        $this->mainlink = '/projects';
        $this->mainoption ='Project';

    }
    
 function rendergridview($globalobj,$renderpath)
{

    $this->app->render($renderpath,
        array('newurl'=>$this->app->urlFor('newproject')
            ,'editurl'=>$this->editurl
            ,'deleteurl'=>$this->deleteurl
            ,'obj'=>$this
            ,'globalobj'=>$globalobj
            ,'option'=>$this->mainoption
            ,'route'=>''
            ,'link'=>$this->mainlink));

}
function buildgrid($editurl,$deleteurl)
{
    $result =$this->getall();
    echo '<table class="table table-striped table-bordered bootstrap-datatable datatable">
          <thead>
          <tr>
          <th>Curricullum</th>
          <th>Name</th>
          <th>Description</th>
          <th>Link</th>
          <th>Actions</th>
          </tr>
          </thead>   
          <tbody>';
            foreach ($result as $row)
            {
                echo '<tr>';
                echo '<td>'. $row['cvname'] . '</td>';
                echo '<td>'. $row['name'] . '</td>';
                echo '<td>'. $row['description'] . '</td>';
                echo '<td>'. $row['link'] . '</td>';
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

function buildresponsivegrid($editvar,$deletevar,$editurl,$deleteurl)
   {
     $result=$this->getall();
     echo'<div id="grids" width="100%">         
       <table id="datagrid" class="table table-striped table-hover dt-responsive" cellspacing="0" width="80%">
        <thead>
            <tr>
             <th>Curricullum</th>
             <th>Name</th>
             <th>Description</th>
             <th>Link</th>
             <th class="nosort">Actions</th>
            </tr>
        </thead>
        <tbody>';
        foreach ($result as $row) 
        {
         echo '<tr>';
         echo '<td>'. $row['cvname'] . '</td>';
         echo '<td>'. $row['name'] . '</td>';
         echo '<td>'. $row['description'] . '</td>';
         echo '<td>'. $row['link'] . '</td>';
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
	
         echo ' </td></tr>';
        } 
        }
            
        echo'</tbody></table></div>';
   }
   
function rendernewview($curricullumid,$name,$description,$link,$imagename,$errormessage,$globalobj,$renderpath)
{
    $this->app->render($renderpath,array('listurl'=>$this->app->urlFor('projects')
            ,'selfurl'=>$this->app->urlFor('newproject')
            ,'curricullumid'=>$curricullumid
            ,'name'=>$name
            ,'description'=>$description
            ,'linkproject'=>$link
            ,'imagename'=>$imagename
            ,'globalobj'=>$globalobj
            ,'errormessage'=>$errormessage
            ,'option'=>$this->mainoption
            ,'route'=>'New'
            ,'link'=>$this->mainlink));

}

function rendereditview($id,$globalobj,$renderpath)
{

    $datas=$this->getprojectbyid($id);
    foreach($datas as $data)
    {
        
        $curricullumid = $data["curricullumid"];
        $name =$data["name"];
        $description =$data["description"];
        $link =$data["link"];
        $imagename=$data["imagename"];
    }
    $updateurl = str_replace(':id',$id,$this->app->urlFor('updateproject'));
    $this->app->render($renderpath,array('id'=>$id,'curricullumid'=>$curricullumid
            ,'name'=>$name
            ,'description'=>$description
            ,'linkproject'=>$link
            ,'imagename'=>$imagename
            ,'globalobj'=>$globalobj
            ,'updateurl'=>$updateurl 
            ,'listurl'=>$this->app->urlFor('projects')
            ,'option'=>$this->mainoption
            ,'route'=>'Edit'
            ,'link'=>$this->mainlink));

}

function renderdeleteview($id,$globalobj,$renderpath)
{
    $datas=$this->getprojectbyid($id);
    foreach($datas as $data)
    {
        $curricullumid = $data["curricullumid"];
        $name =$data["name"];
        $description =$data["description"];
        $link =$data["link"];
        $imagename=$data["imagename"];
    }
    $deleteurl = str_replace(':id',$id,$this->app->urlFor('deleteproject'));
    $this->app->render($renderpath,array('id'=>$id,'curricullumid'=>$curricullumid
            ,'name'=>$name
            ,'description'=>$description
            ,'linkproject'=>$link
            ,'imagename'=>$imagename
            ,'globalobj'=>$globalobj
            ,'deleteurl'=>$deleteurl
            ,'listurl'=>$this->app->urlFor('projects')
            ,'option'=>$this->mainoption
            ,'route'=>'Delete'
            ,'link'=>$this->mainlink));


}

function render_project_picture($id,$renderpath,$errormessage)
{
   $datas=$this->getprojectbyid($id);
    foreach($datas as $data)
    {
        $imagename=$data["imagename"];
        $projectname =$data["name"];
    }  
    $updateurl = str_replace(':id',$id,$this->app->urlFor('updatepicture'));
    $listurl =  str_replace(':id', $id,$this->app->urlFor('editproject'));
    $this->app->render($renderpath,array(
        'errormessage'=>$errormessage,
        'imagename'=>$imagename,
        'updateurl'=>$updateurl,
        'listurl'=>$listurl,
        'option'=>'Change Picture',
        'route'=>$projectname,
        'link'=>'/projectpicture/'.$id,
        'linkroute'=>$listurl));
}

function update_picture_item($username,$id,$files,$renderpath)
{
     $dirpath ='images/'.'portfolio'; 
     //Remove old picture
     $oldpicture=$this->get_projectimage_name($id);
     $errormessage =$this->remove_file($dirpath,$oldpicture);
     //Upload new picture.
     $picturename=$files['projectpicture']['name'];
     $errormessage =$this->check_picture_error($files,$dirpath);
     $listurl =  str_replace(':id', $id,$this->app->urlFor('editproject'));
     $updateurl = str_replace(':id',$id,$this->app->urlFor('updatepicture'));
     if ($errormessage =="")
     {
    $this->update_picture($username,$id,$picturename); 
    
    $this->app->redirect($listurl);
    }
    else
    {
      $this->render_project_picture($id, $renderpath,$errormessage);
    }     
}
function update_picture($username,$id,$imagename)
{
   $dt = date('Y-m-d H:i:s');
        $this->database->update("project",
            array(
             "imagename"=>$imagename,   
             "modifyuser"=>$username,
             "modifydate"=>$dt
        ),
          array(
            "id[=]" => $id
        )); 
}


function validateinsert($curricullumid,$name,$description,$files)
{
    //Validate pickture 
    
    $dirpath ='images/'.'portfolio';
    $count =$this->findproject($curricullumid,$name);
    $errormessage="";
    if($count>0)
    {
        $errormessage= '<div class="alert alert-danger col-sms-4 errordiv" role="alert"><i class="fa fa-warning"></i>The project for this curricullum already exist</div>';

    }
    else
    {
       $errormessage=$this->check_picture_error($files,$dirpath);    
    }    
    return $errormessage;
}
function check_picture_error($files,$dirpath)
{
 $errormessage="";   
 if($this->check_picture_file($files)!="")
            {    
             $errormessage='<div class="alert alert-danger col-sms-4 errordiv" role="alert"><i class="fa fa-warning"></i>'.$this->check_picture_file($files).'</div>'; 
            }
            else
            {
              
              $dirpath ='images/'.'portfolio'; 
              if($this->upload_picture($files,$dirpath)!="")
              {        
              $errormessage='<div class="alert alert-danger col-sms-4 errordiv" role="alert"><i class="fa fa-warning"></i>'.$this->upload_picture($files,$dirpath).'</div>'; 
              }
            }
 return $errormessage;         
}

function addnewitem($username,$curricullumid,$name,$description,$link,$files,$globalobj,$renderpath)
{
    $errormessage = $this->validateinsert($curricullumid,$name,$description,$files);
    $picturename=$files['projectpicture']['name']; 
    if($errormessage=="")
    {
        $this->insertproject($username,$curricullumid,$name,$description,$link,$picturename);

       $this->app->response->redirect($this->app->urlFor('projects')
                , array('newurl'=>$this->app->urlFor('newproject') 
                ,'editurl'=>$this->editurl
                ,'deleteurl'=>$this->deleteurl));

    }
    else
    {
        $this->app->render($renderpath,array('listurl'=>$this->app->urlFor('projects')
                ,'selfurl'=>$this->app->urlFor('newproject')
                ,'curricullumid'=>$curricullumid
                ,'name'=>$name
                ,'description'=>$description
                ,'linkproject'=>$link
                ,'globalobj'=>$globalobj
                ,'errormessage'=>$errormessage
                ,'option'=>$this->mainoption
                ,'route'=>'New'
                ,'link'=>$this->mainlink));
    }


}

  function updateitem($username,$id,$curricullumid,$name,$description,$link)
    {
   
      $this->updateproject($id, $username,$curricullumid,$name,$description,$link);
      $this->app->response->redirect($this->app->urlFor('projects'));
    }

        function deleteitem($id)
        {
            
            $dirpath ='images/'.'portfolio'; 
            $imagename =$this->get_projectimage_name($id);
            $this->remove_file($dirpath,$imagename);
            $this->deleteproject($id);
            $this->app->response->redirect(
                $this->app->urlFor('projects'),
                array(
                    'newurl' => $this->app->urlFor('newproject'),
                    'editurl' => $this->editurl,
                    'deleteurl' => $this->deleteurl
                )
            );
        
        }
        
 function get_projectimage_name($id)
 {
  $sth = $this->database->pdo->prepare("select imagename from project where id ='".$id."'");
   $sth->execute();
   $imagename=""; 
   foreach($sth as $data)
    {
     $imagename=$data["imagename"];   
    } 
   return $imagename; 
 }       
function getall()
{
    $sth = $this->database->pdo->prepare(
    'select p.id 
     ,p.curricullumid,p.name
     ,p.description,p.link,p.imagename
     ,c.name as cvname from project p
     inner join curricullum c on (p.curricullumid = c.id) ');
    $sth->execute();
    return $sth;

}
function getprojectbyid($id)
{

$data = $this->database->select("project",array(
"curricullumid",
"name",
"description"  ,
"link"  ,
"imagename"    
),array(
"id" => $id
));
   
return $data;   
}

function insertproject($username,$curricullumid,$name,$description,$link,$imagename)
{
  
$dt = date('Y-m-d H:i:s');
$this->database->insert("project",array("curricullumid" => $curricullumid,
"name" => $name,
"description"=>$description,
"link"=>$link, 
"imagename"=>$imagename ,   
"createuser" => $username,
"createdate" => $dt ,
"modifyuser" => $username,
"modifydate" => $dt ));

}
function updateproject($id,$username,$curricullumid,$name,$description,$link)
    {

        $dt = date('Y-m-d H:i:s');
        $this->database->update("project",
            array(
             "curricullumid" => $curricullumid,
             "name" => $name,
             "description"=>$description,
             "link"=>$link,  
             "modifyuser"=>$username,
             "modifydate"=>$dt
        ),
          array(
            "id[=]" => $id
        ));



    }

    function deleteproject($id)
    {

    $this->database->delete("project",array("AND" =>array("id" => $id)));
    }
function findproject($curricullumid,$name)
{
    $count =  $this->database->count("project",array("id")
   ,array("AND" =>array( 
    "curricullumid" => $curricullumid,
    "name"=>$name
    )));
   return $count;
    
}

//Project picture upload functions
function check_picture_file($files)
    {
     
        $picturename     = $files['projectpicture']['name'];
	$tmpName  = $files['projectpicture']['tmp_name'];
	$error    = $files['projectpicture']['error'];
	$size     = $files['projectpicture']['size'];
        $ext	  = strtolower(pathinfo($picturename, PATHINFO_EXTENSION));
        $fileerror="";
        if ($error ==0)
        {
         if ( !in_array($ext, array('jpg','jpeg','png','gif')) )
          {
	  $fileerror = 'Invalid file extension. ( valid extensions: jpg,jpeg,png,gif )';
	  }
	  //validate file size
	  if ( $size/1024/1024 > 2 ) 
          {
	  $fileerror = 'File size is exceeding maximum allowed size. ( Maximum file size is 2 Mb )';
	   } 
        }
        else
        {
         $fileerror ="Upload File Error ".$error;   
        }    
            
            
        return $fileerror;
    }
    
    function upload_picture($files,$dirpath)
    {
     $uploaderror="";
       
      try 
      {   
      move_uploaded_file($files['projectpicture']['tmp_name'],$dirpath.'/'.$files["projectpicture"]["name"]);
      }
      catch(ErrorException $ex) 
      {
      $uploaderror= $ex->getMessage();
          
      }
    
       return $uploaderror; 
    }
    
    function remove_file($dirpath,$filename)
    {
        $errormessage="";
        try {
        if (file_exists($dirpath.'/'.$filename))
        {        
        unlink ($dirpath.'/'.$filename);
        }      
        } catch (Exception $ex) {
         $errormessage =$ex->getMessage();
          
        }
       return $errormessage;    
    }




}
?>