<?php

class EducationController {
   private $database;
   private $editurl;
   private $deleteurl;
   private $mainlink;
   private $mainoption;
   public function __construct($app,$medoo) {

        $this->database =$medoo;
        $this->app=$app;
        $this->editurl ='editeducation';
        $this->deleteurl='vieweducation';
        $this->mainlink = '/educationlist';
        $this->mainoption ='Education';

    }
    
 function rendergridview($renderpath)
{

    $this->app->render($renderpath,
        array('newurl'=>$this->app->urlFor('neweducation')
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
          <th>Institution</th>
          <th>Degree</th>
          <th>Date</th>
          <th>Actions</th>
          </tr>
          </thead>   
          <tbody>';
            foreach ($result as $row)
            {
                echo '<tr>';
                echo '<td>'. $row['curricullumid'] . '</td>';
                echo '<td>'. $row['institution'] . '</td>';
                echo '<td>'. $row['degree'] . '</td>';
                echo '<td>'. $row['datechar'] . '</td>';
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
function rendernewview($curricullumid,$institution,$degree,$date,$errormessage,$globalobj,$renderpath)
{
    $this->app->render($renderpath,array('listurl'=>$this->app->urlFor('educationlist')
            ,'selfurl'=>$this->app->urlFor('neweducation')
            ,'curricullumid'=>$curricullumid
            ,'institution'=>$institution
            ,'degree'=>$degree
            ,'date'=>$date
            ,'globalobj'=>$globalobj
            ,'errormessage'=>$errormessage
            ,'option'=>$this->mainoption
            ,'route'=>'New'
            ,'link'=>$this->mainlink));

}

function rendereditview($id,$globalobj,$renderpath)
{

    $datas=$this->geteducationbyid($id);
    foreach($datas as $data)
    {
        $curricullumid = $data["curricullumid"];
        $institution =$data["institution"];
        $degree =$data["degree"];
        $date =$data["datechar"];
    }
    $this->app->render($renderpath,array('curricullumid'=>$curricullumid
            ,'institution'=>$institution
            ,'degree'=>$degree
            ,'date'=>$date
            ,'globalobj'=>$globalobj
            ,'updateurl'=>$this->app->urlFor('updateeducation')
            ,'listurl'=>$this->app->urlFor('educationlist')
            ,'option'=>$this->mainoption
            ,'route'=>'Edit'
            ,'link'=>$this->mainlink));

}

function renderdeleteview($id,$globalobj,$renderpath)
{
    $datas=$this->geteducationbyid($id);
    foreach($datas as $data)
    {
        $curricullumid = $data["curricullumid"];
        $institution =$data["institution"];
        $degree =$data["degree"];
        $date =$data["datechar"];
    }
    $this->app->render($renderpath,array('curricullumid'=>$curricullumid
            ,'institution'=>$institution
            ,'degree'=>$degree
            ,'date'=>$date
            ,'globalobj'=>$globalobj
            ,'deleteurl'=>$this->app->urlFor('deleteeducation')
            ,'listurl'=>$this->app->urlFor('educationlist')
            ,'option'=>$this->mainoption
            ,'route'=>'Delete'
            ,'link'=>$this->mainlink));


}
function validateinsert($curricullumid,$institution)
{
    //Validate if exist
    $count =$this->findeducation($curricullumid,$institution);
    $errormessage="";
    if($count>0)
    {
        $errormessage= '<div class="alert alert-error">The institution for this curricullum already exist</div>';

    }
    return $errormessage;
}


function addnewitem($username,$curricullumid,$institution,$degree,$date,$renderpath)
{
    $errormessage = $this->validateinsert($curricullumid,$institution);

    if($errormessage=="")
    {
        $this->inserteducation($username,$curricullumid,$institution,$degree,$date);

       $this->app->response->redirect($this->app->urlFor('educationlist')
                , array('newurl'=>$this->app->urlFor('neweducation') 
                ,'editurl'=>$this->editurl
                ,'deleteurl'=>$this->deleteurl));

    }
    else
    {
        $this->app->render($renderpath,array('listurl'=>$this->app->urlFor('educationlist')
                ,'selfurl'=>$this->app->urlFor('neweducation')
                ,'curricullumid'=>$curricullumid
                ,'institution'=>$institution
                ,'degree'=>$degree
                ,'date'=>$date
                ,'errormessage'=>$errormessage
                ,'option'=>$this->mainoption
                ,'route'=>'New'
                ,'link'=>$this->mainlink));
    }


}


function getall()
{
    $sth = $this->database->pdo->prepare('SELECT * FROM education');
    $sth->execute();
    return $sth;

}
function geteducationbyid($id)
{

$data = $this->database->select("education", [
"curricullumid",
"institution",
"degree"  ,
"datechar"  ,    
], [
"id" => $id
]);
   
return $data;   
}

function inserteducation($username,$curricullumid,$institution,$degree,$date)
{
  
$dt = date('Y-m-d H:i:s');
$this->database->insert("education", ["curricullumid" => $curricullumid,
"institution" => $institution,
"degree"=>$degree,
"datechar"=>$date,    
"createuser" => $username,
"createdate" => $dt ,
"modifyuser" => $username,
"modifydate" => $dt ]);

}

function findeducation($curricullumid,$institution)
{
    $count =  $this->database->count("education", [
"curricullumid" => $curricullumid,
"institution"=>$institution
]);
   return $count;
    
}


}
?>