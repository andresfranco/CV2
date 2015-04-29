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
    
 function rendergridview($renderpath)
{

    $this->app->render($renderpath,
        array('newurl'=>$this->app->urlFor('newproject')
            ,'editurl'=>$this->editurl
            ,'deleteurl'=>$this->deleteurl
            ,'obj'=>$this
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

function buildresponsivegrid($editurl,$deleteurl)
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
    $this->app->render($renderpath,array('id'=>$id,'curricullumid'=>$curricullumid
            ,'name'=>$name
            ,'description'=>$description
            ,'linkproject'=>$link
            ,'imagename'=>$imagename
            ,'globalobj'=>$globalobj
            ,'updateurl'=>$this->app->urlFor('updateproject')
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
    $this->app->render($renderpath,array('id'=>$id,'curricullumid'=>$curricullumid
            ,'name'=>$name
            ,'description'=>$description
            ,'linkproject'=>$link
            ,'imagename'=>$imagename
            ,'globalobj'=>$globalobj
            ,'deleteurl'=>$this->app->urlFor('deleteproject')
            ,'listurl'=>$this->app->urlFor('projects')
            ,'option'=>$this->mainoption
            ,'route'=>'Delete'
            ,'link'=>$this->mainlink));


}
function validateinsert($curricullumid,$name,$description)
{
    //Validate if exist
    $count =$this->findproject($curricullumid,$name);
    $errormessage="";
    if($count>0)
    {
        $errormessage= '<div class="alert alert-error">The project for this curricullum already exist</div>';

    }
    return $errormessage;
}


function addnewitem($username,$curricullumid,$name,$description,$link,$imagename,$globalobj,$renderpath)
{
    $errormessage = $this->validateinsert($curricullumid,$name,$description);

    if($errormessage=="")
    {
        $this->insertproject($username,$curricullumid,$name,$description,$link,$imagename);

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
                ,'imagename'=>$imagename
                ,'globalobj'=>$globalobj
                ,'errormessage'=>$errormessage
                ,'option'=>$this->mainoption
                ,'route'=>'New'
                ,'link'=>$this->mainlink));
    }


}

  function updateitem($username,$id,$curricullumid,$name,$description,$link,$imagename)
    {
        $this->updateproject($id, $username,$curricullumid,$name,$description,$link,$imagename);
        $this->app->response->redirect(
            $this->app->urlFor('projects'),
            array(
                'newurl' => $this->app->urlFor('newproject'),
                'editurl' => $this->editurl,
                'deleteurl' => $this->deleteurl
            )
        );

    }

        function deleteitem($id)
        {
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

$data = $this->database->select("project", [
"curricullumid",
"name",
"description"  ,
"link"  ,
"imagename"    
], [
"id" => $id
]);
   
return $data;   
}

function insertproject($username,$curricullumid,$name,$description,$link,$imagename)
{
  
$dt = date('Y-m-d H:i:s');
$this->database->insert("project", ["curricullumid" => $curricullumid,
"name" => $name,
"description"=>$description,
"link"=>$link, 
"imagename"=>$imagename ,   
"createuser" => $username,
"createdate" => $dt ,
"modifyuser" => $username,
"modifydate" => $dt ]);

}
function updateproject($id,$username,$curricullumid,$name,$description,$link,$imagename)
    {

        $dt = date('Y-m-d H:i:s');
        $this->database->update("project",
            [
             "curricullumid" => $curricullumid,
             "name" => $name,
             "description"=>$description,
             "link"=>$link,
             "imagename"=>$imagename,   
             "modifyuser"=>$username,
             "modifydate"=>$dt
        ],
            [
            "id[=]" => $id
        ]);



    }

    function deleteproject($id)
    {

        $this->database->delete("project", [
            "AND" => [
                "id" => $id

            ]

        ]);


    }
function findproject($curricullumid,$name)
{
    $count =  $this->database->count("project", [
    "id"
],["AND" => [ 
    "curricullumid" => $curricullumid,
    "name"=>$name
    ]]);
   return $count;
    
}


}
?>