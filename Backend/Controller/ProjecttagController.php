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


function rendergridview($renderpath)
{

    $this->app->render($renderpath,
        array('newurl'=>$this->app->urlFor('newprojecttag')
            ,'editurl'=>$this->editurl
            ,'deleteurl'=>$this->deleteurl
            ,'obj'=>$this
            ,'option'=>$this->mainoption
            ,'route'=>''
            ,'link'=>$this->mainlink));

}

function rendernewview($projectid,$tagname,$errormessage,$globalobj,$renderpath)
{
    $this->app->render($renderpath,array('listurl'=>$this->app->urlFor('projecttags')
            ,'selfurl'=>$this->app->urlFor('newprojecttag')
            ,'projectid'=>$projectid
            ,'tagname'=>$tagname
            ,'globalobj'=>$globalobj
            ,'errormessage'=>$errormessage
            ,'option'=>$this->mainoption
            ,'route'=>'New'
            ,'link'=>$this->mainlink));

}

function rendereditview($id,$globalobj,$renderpath)
{

    $datas=$this->getprojecttagbyid($id);
    foreach($datas as $data)
    {
        $tagname = $data["tagname"];
        $projectid = $data["projectid"];
        
    }
    $this->app->render($renderpath,array( 
            'id'=>$id
            ,'projectid'=>$projectid
            ,'tagname'=>$tagname
            ,'globalobj'=>$globalobj
            ,'updateurl'=>$this->app->urlFor('updateprojecttag')
            ,'listurl'=>$this->app->urlFor('projecttags')
            ,'option'=>$this->mainoption
            ,'route'=>'Edit'
            ,'link'=>$this->mainlink));

}

function renderdeleteview($id,$globalobj,$renderpath)
{
    $datas=$this->getprojecttagbyid($id);
    foreach($datas as $data)
    {
        $tagname = $data["tagname"];
        $projectid =$data["projectid"];
    }
    $this->app->render($renderpath,array('id'=>$id
            ,'projectid'=>$projectid
            ,'tagname'=>$tagname
            ,'globalobj'=>$globalobj
            ,'deleteurl'=>$this->app->urlFor('deleteprojecttag')
            ,'listurl'=>$this->app->urlFor('projecttags')
             ,'option'=>$this->mainoption
            ,'route'=>'Delete'
            ,'link'=>$this->mainlink));


}



function validateinsert($projectid,$tagname)
{
    //Validate if exist
    $count =$this->findprojecttag($projectid,$tagname);
    $errormessage="";
    if($count>0)
    {
        $errormessage= '<div class="alert alert-error">The tagname for this project already exist</div>';

    }
    return $errormessage;
}


function addnewitem($username,$projectid,$tagname,$globalobj,$renderpath)
{
    $errormessage = $this->validateinsert($projectid,$tagname);

    if($errormessage=="")
    {
        $this->insertprojecttag($username,$projectid,$tagname);

        $this->app->response->redirect($this->app->urlFor('projecttags')
                , array('newurl'=>$this->app->urlFor('newprojecttag') 
                ,'editurl'=>$this->editurl
                ,'deleteurl'=>$this->deleteurl));

    }
    else
    {
        $this->app->render($renderpath,array('listurl'=>$this->app->urlFor('projecttags')
                ,'selfurl'=>$this->app->urlFor('newprojecttag')
                ,'projectid'=>$projectid
                ,'tagname'=>$tagname
                ,'globalobj'=>$globalobj
                ,'errormessage'=>$errormessage
                ,'option'=>$this->mainoption
                ,'route'=>'New'
                ,'link'=>$this->mainlink));
    }


}

    function updateitem($username,$id,$projectid,$tagname)
    {
        $this->updateprojecttag($username,$id,$projectid,$tagname);
        $this->app->response->redirect($this->app->urlFor('projecttags'), array('newurl'=>$this->app->urlFor('newprojecttag') ,'editurl'=>$this->editurl,'deleteurl'=>$this->deleteurl));
    }

    function deleteitem($id)
    {
        $this->deleteprojecttag($id);
        $this->app->response->redirect($this->app->urlFor('projecttags'), array('newurl'=>$this->app->urlFor('newprojecttag') ,'editurl'=>$this->editurl,'deleteurl'=>$this->deleteurl));
    }


function buildgrid($editurl,$deleteurl)
{
    $result =$this->getall();
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






function insertprojecttag($username,$projectid ,$tagname)
{
  
    $dt = date('Y-m-d H:i:s');
$this->database->insert("project_tag", ["projectid" => $projectid,
"tagname" => $tagname,
"createuser" => $username,
"createdate" => $dt ,
"modifyuser" => $username,
"modifydate" => $dt ]);



}

function getall()
{


    $sth = $this->database->pdo->prepare(''
            . 'SELECT pt.id,pt.projectid'
            . ',pt.tagname,p.name as projectname '
            . 'FROM project_tag pt inner join project p on (pt.projectid =p.id)');
    $sth->execute();
    return $sth;


}
    function updateprojecttag($username,$id,$projectid,$tagname)
    {
        
        $dt = date('Y-m-d H:i:s');
        $this->database->update("project_tag", ["projectid" => $projectid,
            "tagname" => $tagname,
            "modifyuser" => $username,
            "modifydate"=>$dt
             ],[
            "id[=]" => $id
        ]);


    }

function deleteprojecttag($id)
{

    $this->database->delete("project_tag", [
        "AND" => [
            "id" => $id

	]

]);
    //var_dump($database->error());
   //header('Location: '.$redirecturl);
}

function findprojecttag($projectid,$tagname)
{
    $count =  $this->database->count("project_tag", [
"id" 
],["AND" => [ 
    "projectid" => $projectid,
    "tagname"=>$tagname
    ]]);
   return $count;
    
}

function getprojecttagbyid($id)
{

$data = $this->database->select("project_tag", [
"id",
"projectid",    
"tagname"
], [
"id" => $id
]);
   
return $data;   
}

}
?>

