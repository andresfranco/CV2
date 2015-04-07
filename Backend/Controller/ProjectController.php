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
function rendernewview($curricullumid,$name,$description,$link,$errormessage,$globalobj,$renderpath)
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

function rendereditview($id,$globalobj,$renderpath)
{

    $datas=$this->getprojectbyid($id);
    foreach($datas as $data)
    {
        
        $curricullumid = $data["curricullumid"];
        $name =$data["name"];
        $description =$data["description"];
        $link =$data["link"];
    }
    $this->app->render($renderpath,array('id'=>$id,'curricullumid'=>$curricullumid
            ,'name'=>$name
            ,'description'=>$description
            ,'linkproject'=>$link
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
    }
    $this->app->render($renderpath,array('id'=>$id,'curricullumid'=>$curricullumid
            ,'name'=>$name
            ,'description'=>$description
            ,'linkproject'=>$link
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


function addnewitem($username,$curricullumid,$name,$description,$link,$globalobj,$renderpath)
{
    $errormessage = $this->validateinsert($curricullumid,$name,$description);

    if($errormessage=="")
    {
        $this->insertproject($username,$curricullumid,$name,$description,$link);

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
     ,p.description,p.link
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
], [
"id" => $id
]);
   
return $data;   
}

function insertproject($username,$curricullumid,$name,$description,$link)
{
  
$dt = date('Y-m-d H:i:s');
$this->database->insert("project", ["curricullumid" => $curricullumid,
"name" => $name,
"description"=>$description,
"link"=>$link,    
"createuser" => $username,
"createdate" => $dt ,
"modifyuser" => $username,
"modifydate" => $dt ]);

}
function updateproject($id,$username,$curricullumid,$name,$description,$link)
    {

        $dt = date('Y-m-d H:i:s');
        $this->database->update("project",
            [
             "curricullumid" => $curricullumid,
             "name" => $name,
             "description"=>$description,
             "link"=>$link,    
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