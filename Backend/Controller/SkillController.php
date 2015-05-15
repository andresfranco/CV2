<?php

class SkillController {
   private $database;
   private $editurl;
   private $deleteurl;
   private $mainlink;
   private $mainoption;
   public function __construct($app,$medoo) {

        $this->database =$medoo;
        $this->app=$app;
        $this->editurl ='editskill';
        $this->deleteurl='viewskill';
        $this->mainlink = '/skills';
        $this->mainoption ='Skill';

    }
    
 function rendergridview($renderpath)
{

    $this->app->render($renderpath,
        array('newurl'=>$this->app->urlFor('newskill')
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
          <th>Type</th>
          <th>Skill</th>
          <th>Percentage</th>
          <th>Actions</th>
          </tr>
          </thead>   
          <tbody>';
            foreach ($result as $row)
            {
                echo '<tr>';
                echo '<td>'. $row['cvname'] . '</td>';
                echo '<td>'. $row['type'] . '</td>';
                echo '<td>'. $row['skill'] . '</td>';
                echo '<td>'. $row['percentage'] . '</td>';
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
              <th>Type</th>
              <th>Skill</th>
              <th>Percentage</th>
              <th class="nosort">Actions</th>
            </tr>
        </thead>
        <tbody>';
        foreach ($result as $row) 
        {
         echo '<tr>';
         echo '<td>'. $row['cvname'] . '</td>';
         echo '<td>'. $row['type'] . '</td>';
         echo '<td>'. $row['skill'] . '</td>';
         echo '<td>'. $row['percentage'] . '</td>';
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

function rendernewview($curricullumid,$type,$skill,$percentage,$errormessage,$globalobj,$renderpath)
{
    $this->app->render($renderpath,array('listurl'=>$this->app->urlFor('skills')
            ,'selfurl'=>$this->app->urlFor('newskill')
            ,'curricullumid'=>$curricullumid
            ,'type'=>$type
            ,'skill'=>$skill
            ,'percentage'=>$percentage
            ,'globalobj'=>$globalobj
            ,'errormessage'=>$errormessage
            ,'option'=>$this->mainoption
            ,'route'=>'New'
            ,'link'=>$this->mainlink));

}

function rendereditview($id,$globalobj,$renderpath)
{

    $datas=$this->getskillbyid($id);
    foreach($datas as $data)
    {
        $id=$data["id"];
        $curricullumid = $data["curricullumid"];
        $type =$data["type"];
        $skill =$data["skill"];
        $percentage =$data["percentage"];
    }
    $updateurl =  str_replace(':id',$id, $this->app->urlFor('updateskill'));
    $this->app->render($renderpath,array('id'=>$id,'curricullumid'=>$curricullumid
            ,'type'=>$type
            ,'skill'=>$skill
            ,'percentage'=>$percentage
            ,'globalobj'=>$globalobj
            ,'updateurl'=>$updateurl
            ,'listurl'=>$this->app->urlFor('skills')
            ,'option'=>$this->mainoption
            ,'route'=>'Edit'
            ,'link'=>$this->mainlink));

}

function renderdeleteview($id,$globalobj,$renderpath)
{
    $datas=$this->getskillbyid($id);
    foreach($datas as $data)
    {
        $curricullumid = $data["curricullumid"];
        $type =$data["type"];
        $skill =$data["skill"];
        $percentage =$data["percentage"];
    }
    $deleteurl =  str_replace(':id',$id, $this->app->urlFor('deleteskill'));
    $this->app->render($renderpath,array('id'=>$id,'curricullumid'=>$curricullumid
            ,'type'=>$type
            ,'skill'=>$skill
            ,'percentage'=>$percentage
            ,'globalobj'=>$globalobj
            ,'deleteurl'=>$deleteurl
            ,'listurl'=>$this->app->urlFor('skills')
            ,'option'=>$this->mainoption
            ,'route'=>'Delete'
            ,'link'=>$this->mainlink));


}
function validateinsert($curricullumid,$type,$skill)
{
    //Validate if exist
    $count =$this->findskill($curricullumid,$type,$skill);
    $errormessage="";
    if($count>0)
    {
        $errormessage= '<div class="alert alert-danger col-sms-4 errordiv" role="alert"><i class="fa fa-warning"></i>The skill for this curricullum and this type already exist</div>';

    }
    return $errormessage;
}


function addnewitem($username,$curricullumid,$type,$skill,$percentage,$globalobj,$renderpath)
{
    $errormessage = $this->validateinsert($curricullumid,$type,$skill);

    if($errormessage=="")
    {
        $this->insertskill($username,$curricullumid,$type,$skill,$percentage);

       $this->app->response->redirect($this->app->urlFor('skills')
                , array('newurl'=>$this->app->urlFor('newskill') 
                ,'editurl'=>$this->editurl
                ,'deleteurl'=>$this->deleteurl));

    }
    else
    {
        $this->app->render($renderpath,array('listurl'=>$this->app->urlFor('skills')
                ,'selfurl'=>$this->app->urlFor('newskill')
                ,'curricullumid'=>$curricullumid
                ,'type'=>$type
                ,'skill'=>$skill
                ,'percentage'=>$percentage
                ,'globalobj'=>$globalobj
                ,'errormessage'=>$errormessage
                ,'option'=>$this->mainoption
                ,'route'=>'New'
                ,'link'=>$this->mainlink));
    }


}

  function updateitem($username,$id,$curricullumid,$type,$skill,$percentage)
    {
        $this->updateskill($id, $username,$curricullumid,$type,$skill,$percentage);
        $this->app->response->redirect(
            $this->app->urlFor('skills'),
            array(
                'newurl' => $this->app->urlFor('newskill'),
                'editurl' => $this->editurl,
                'deleteurl' => $this->deleteurl
            )
        );

    }

        function deleteitem($id)
        {
            $this->deleteskill($id);
            $this->app->response->redirect(
                $this->app->urlFor('skills'),
                array(
                    'newurl' => $this->app->urlFor('newskill'),
                    'editurl' => $this->editurl,
                    'deleteurl' => $this->deleteurl
                )
            );
        }
function getall()
{
    $sth = $this->database->pdo->prepare(
    'select sk.id 
     ,sk.curricullumid,sk.type
     ,sk.skill,sk.percentage
     ,c.name as cvname from skill sk 
     inner join curricullum c on (sk.curricullumid = c.id) ');
    $sth->execute();
    return $sth;

}
function getskillbyid($id)
{

$data = $this->database->select("skill",array(
"id",    
"curricullumid",
"type",
"skill"  ,
"percentage"  ,    
),array(
"id" => $id
));
   
return $data;   
}

function insertskill($username,$curricullumid,$type,$skill,$percentage)
{
  
$dt = date('Y-m-d H:i:s');
$this->database->insert("skill",array("curricullumid" => $curricullumid,
"type" => $type,
"skill"=>$skill,
"percentage"=>$percentage,    
"createuser" => $username,
"createdate" => $dt ,
"modifyuser" => $username,
"modifydate" => $dt ));

}
function updateskill($id,$username,$curricullumid,$type,$skill,$percentage)
    {

        $dt = date('Y-m-d H:i:s');
        $this->database->update("skill",
            array(
             "curricullumid" => $curricullumid,
             "type" => $type,
             "skill"=>$skill,
             "percentage"=>$percentage,    
             "modifyuser"=>$username,
             "modifydate"=>$dt
        ),
          array(
            "id[=]" => $id
        ));



    }

    function deleteskill($id)
    {
      $this->database->delete("skill",array("AND" => array("id" => $id)));
    }
function findskill($curricullumid,$type,$skill)
{
    $count =  $this->database->count("skill",array("id")
    ,array("AND" =>array( 
    "curricullumid" => $curricullumid,
    "type"=>$type,
    "skill"=>$skill)));
    return $count;   
}




}
?>
