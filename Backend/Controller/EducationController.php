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
    
 function rendergridview($globalobj,$renderpath)
{

    $this->app->render($renderpath,
        array('newurl'=>$this->app->urlFor('neweducation')
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
                echo '<td>'. $row['cvname'] . '</td>';
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
function buildresponsivegrid($editvar,$deletevar,$editurl,$deleteurl)
   {
     $result=$this->getall();
     echo'<div id="grids" width="100%">         
       <table id="datagrid" class="table table-striped table-hover dt-responsive" cellspacing="0" width="80%">
        <thead>
            <tr>
              <th>Curricullum</th>
              <th>Institution</th>
              <th>Degree</th>
              <th>Date</th>
              <th class="nosort">Actions</th>
            </tr>
        </thead>
        <tbody>';
        foreach ($result as $row) 
        {
         echo '<tr>';
         echo '<td>'. $row['cvname'] . '</td>';
         echo '<td>'. $row['institution'] . '</td>';
         echo '<td>'. $row['degree'] . '</td>';
         echo '<td>'. $row['datechar'] . '</td>';
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
    $updateurl =  str_replace(':id', $id,$this->app->urlFor('updateeducation'));
    $this->app->render($renderpath,array('id'=>$id,'curricullumid'=>$curricullumid
            ,'institution'=>$institution
            ,'degree'=>$degree
            ,'date'=>$date
            ,'globalobj'=>$globalobj
            ,'updateurl'=>$updateurl
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
    $deleteurl =  str_replace(':id', $id,$this->app->urlFor('deleteeducation'));
    $this->app->render($renderpath,array('id'=>$id,'curricullumid'=>$curricullumid
            ,'institution'=>$institution
            ,'degree'=>$degree
            ,'date'=>$date
            ,'globalobj'=>$globalobj
            ,'deleteurl'=>$deleteurl
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
        $errormessage= '<div class="alert alert-danger col-sms-4 errordiv" role="alert"><i class="fa fa-warning"></i>The institution for this curricullum already exist</div>';

    }
    return $errormessage;
}


function addnewitem($username,$curricullumid,$institution,$degree,$date,$globalobj,$renderpath)
{
    $errormessage = $this->validateinsert($curricullumid,$institution);

    if($errormessage=="")
    {
        $this->inserteducation($username,$curricullumid,$institution,$degree,$date);

       $this->app->response->redirect($this->app->urlFor('educationlist')
                , array('newurl'=>$this->app->urlFor('neweducation') 
                ,'editurl'=>$this->editurl
                ,'deleteurl'=>$this->deleteurl
              ));

    }
    else
    {
        $this->app->render($renderpath,array('listurl'=>$this->app->urlFor('educationlist')
                ,'selfurl'=>$this->app->urlFor('neweducation')
                ,'globalobj'=>$globalobj
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

  function updateitem($username,$id,$curricullumid,$institution,$degree,$date)
    {
        $this->updateeducation($id, $username,$curricullumid,$institution,$degree,$date);
        $this->app->response->redirect(
            $this->app->urlFor('educationlist'),
            array(
                'newurl' => $this->app->urlFor('neweducation'),
                'editurl' => $this->editurl,
                'deleteurl' => $this->deleteurl
            )
        );

    }

        function deleteitem($id)
        {
            $this->deleteeducation($id);
            $this->app->response->redirect(
                $this->app->urlFor('educationlist'),
                array(
                    'newurl' => $this->app->urlFor('neweducation'),
                    'editurl' => $this->editurl,
                    'deleteurl' => $this->deleteurl
                )
            );
        }
function getall()
{
    $sth = $this->database->pdo->prepare(
            'select ed.id,ed.curricullumid, ed.institution
            ,ed.degree,ed.datechar,c.name as cvname from education ed
             inner join curricullum c on (ed.curricullumid = c.id)');
    $sth->execute();
    return $sth;

}
function geteducationbyid($id)
{

$data = $this->database->select("education",array(
"curricullumid",
"institution",
"degree"  ,
"datechar"  ,    
),array(
"id" => $id
));
   
return $data;   
}

function inserteducation($username,$curricullumid,$institution,$degree,$date)
{
  
$dt = date('Y-m-d H:i:s');
$this->database->insert("education",array("curricullumid" => $curricullumid,
"institution" => $institution,
"degree"=>$degree,
"datechar"=>$date,    
"createuser" => $username,
"createdate" => $dt ,
"modifyuser" => $username,
"modifydate" => $dt ));

}
function updateeducation($id,$username,$curricullumid,$institution,$degree,$date)
    {

        $dt = date('Y-m-d H:i:s');
        $this->database->update("education",
            array(
             "curricullumid" => $curricullumid,
             "institution" => $institution,
             "degree"=>$degree,
             "datechar"=>$date,    
             "modifyuser"=>$username,
             "modifydate"=>$dt
        ),
          array(
            "id[=]" => $id
        ));



    }

    function deleteeducation($id)
    {

        $this->database->delete("education",array(
            "AND" =>array(
                "id" => $id

            )

        ));


    }
function findeducation($curricullumid,$institution)
{
    $count =  $this->database->count("education",array(
"id"
),array("AND" =>array( "curricullumid" => $curricullumid,
"institution"=>$institution)));
   return $count;
    
}


}
?>