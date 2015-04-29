<?php


class WorkController {
    private $database;
    private $editurl;
    private $deleteurl;
    private $mainlink;
    private $mainoption;
    public function __construct($app,$medoo) {
        $this->database =$medoo;
        $this->app=$app;
        $this->editurl ='editwork';
        $this->deleteurl='viewwork';
        $this->mainlink = '/worklist';
        $this->mainoption ='Works';

    }


function rendergridview($renderpath)
{

    $this->app->render($renderpath,
        array('newurl'=>$this->app->urlFor('newwork')
            ,'editurl'=>$this->editurl
            ,'deleteurl'=>$this->deleteurl
            ,'obj'=>$this
            ,'option'=>$this->mainoption
            ,'route'=>''
            ,'link'=>$this->mainlink));

}

function rendernewview($curricullumid,$company,$position,$from,$to,$errormessage,$globalobj,$renderpath)
{
    $this->app->render($renderpath,array('listurl'=>$this->app->urlFor('worklist')
            ,'selfurl'=>$this->app->urlFor('newwork')
            ,'curricullumid'=>$curricullumid
            ,'company'=>$company
            ,'position'=>$position
            ,'from'=>$from
            ,'to'=>$to
            ,'errormessage'=>$errormessage
            ,'globalobj'=>$globalobj
            ,'option'=>$this->mainoption
            ,'route'=>'New'
            ,'link'=>$this->mainlink));

}

function rendereditview($id,$globalobj,$renderpath)
{

    $datas=$this->getworkbyid($id);
    foreach($datas as $data)
    {   
        $curricullumid = $data["curricullumid"];
        $company =$data["company"];
        $position =$data["position"];
        $from =$data["from"];
        $to =$data["to"];
    }
    $this->app->render($renderpath,array('id'=>$id 
            ,'curricullumid'=>$curricullumid
            ,'company'=>$company
            ,'position'=>$position
            ,'from'=>$from
            ,'to'=>$to
            ,'globalobj'=>$globalobj
            ,'updateurl'=>$this->app->urlFor('updatework')
            ,'listurl'=>$this->app->urlFor('worklist')
            ,'option'=>$this->mainoption
            ,'route'=>'Edit'
            ,'link'=>$this->mainlink));

}

function renderdeleteview($id,$globalobj,$renderpath)
{
     $datas=$this->getworkbyid($id);
    foreach($datas as $data)
    {
        $curricullumid = $data["curricullumid"];
        $company =$data["company"];
        $position =$data["position"];
        $from =$data["from"];
        $to =$data["to"];
    }
    $this->app->render($renderpath,array('id'=>$id 
            ,'curricullumid'=>$curricullumid
            ,'company'=>$company
            ,'position'=>$position
            ,'from'=>$from
            ,'to'=>$to
            ,'globalobj'=>$globalobj
            ,'deleteurl'=>$this->app->urlFor('deletework')
            ,'listurl'=>$this->app->urlFor('worklist')
             ,'option'=>$this->mainoption
            ,'route'=>'Delete'
            ,'link'=>$this->mainlink));
}


function validateinsert($curricullumid,$company)
{
    //Validate if exist
    $count =$this->findwork($curricullumid,$company);
    $errormessage="";
    if($count>0)
    {
        $errormessage= '<div class="alert alert-error">Already exist a company for this curricullum</div>';

    }
    return $errormessage;
}


function addnewitem($username,$curricullumid,$company,$position,$from,$to,$renderpath)
{
    $errormessage = $this->validateinsert($curricullumid,$company);

    if($errormessage=="")
    {
        $this->insertwork($username,$curricullumid,$company,$position,$from,$to);

        $this->app->response->redirect($this->app->urlFor('worklist')
                , array('newurl'=>$this->app->urlFor('newwork') 
                ,'editurl'=>$this->editurl
                ,'deleteurl'=>$this->deleteurl));

    }
    else
    {
        $this->app->render($renderpath,array('listurl'=>$this->app->urlFor('worklist')
                ,'selfurl'=>$this->app->urlFor('newwork')
                ,'curricullumid'=>$curricullumid
                ,'company'=>$company
                ,'position'=>$position
                ,'from'=>$from
                ,'to'=>$to
                ,'errormessage'=>$errormessage
                ,'option'=>$this->mainoption
                ,'route'=>'New'
                ,'link'=>$this->mainlink));
    }


}

    function updateitem($username,$id,$curricullumid,$company,$position,$from,$to)
    {
        $this->updatework($username,$id,$curricullumid,$company,$position,$from,$to);
        $this->app->response->redirect($this->app->urlFor('worklist')
                , array('newurl'=>$this->app->urlFor('newwork') 
                ,'editurl'=>$this->editurl
                ,'deleteurl'=>$this->deleteurl));
    }

    function deleteitem($id)
    {
        $this->deletework($id);
        $this->app->response->redirect($this->app->urlFor('worklist')
                , array('newurl'=>$this->app->urlFor('newwork') 
                ,'editurl'=>$this->editurl,'deleteurl'=>$this->deleteurl));
    }


function buildgrid($editurl,$deleteurl)
{
    $result =$this->getall();
    echo '<table class="table table-striped table-bordered bootstrap-datatable datatable">
          <thead>
          <tr>
          <th>Curricullum</th>
          <th>Company</th>
          <th>Position</th>
          <th>Actions</th>
          </tr>
          </thead>   
          <tbody>';
            foreach ($result as $row)
            {
                echo '<tr>';
                echo '<td>'. $row['cvname'] . '</td>';
                echo '<td>'. $row['company'] . '</td>';
                echo '<td>'. $row['position'] . '</td>';
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
              <th>Company</th>
              <th>Position</th>
              <th class="nosort">Actions</th>
            </tr>
        </thead>
        <tbody>';
        foreach ($result as $row) 
        {
         echo '<tr>';
         echo '<td>'. $row['cvname'] . '</td>';
         echo '<td>'. $row['company'] . '</td>';
         echo '<td>'. $row['position'] . '</td>';
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






function insertwork($username,$curricullumid,$company,$position,$from,$to)
{
  
    $dt = date('Y-m-d H:i:s');
$this->database->insert("work", [ 'curricullumid'=>$curricullumid
 ,'company'=>$company
,'position'=>$position
,'from'=>$from
,'to'=>$to
,"createuser" => $username
,"createdate" => $dt 
,"modifyuser" => $username
,"modifydate" => $dt ]);



}

function getall()
{


    $sth = $this->database->pdo->prepare(
            'select w.id, w.curricullumid,w.company
        ,w.position,w.from,w.to ,c.name as cvname from work w 
        inner join curricullum c on (w.curricullumid = c.id)');
    $sth->execute();
    return $sth;


}
    function updatework($username,$id,$curricullumid,$company,$position,$from,$to)
    {
        
        $dt = date('Y-m-d H:i:s');
        $this->database->update("work", ["id" => $id,
            'curricullumid'=>$curricullumid
            ,'company'=>$company
            ,'position'=>$position
            ,'from'=>$from
            ,'to'=>$to
            ,"modifyuser" => $username
            ,"modifydate"=>$dt
             ],[
            "id[=]" => $id
        ]);


    }

function deletework($id)
{

    $this->database->delete("work", [
        "AND" => [
            "id" => $id

	]

]);
    //var_dump($database->error());
   //header('Location: '.$redirecturl);
}

function findwork($curricullumid,$company)
{
    $count =  $this->database->count("work", [
"id" 
      
],["AND" => [ "curricullumid" => $curricullumid,
            "company"=>$company
            ]]);
   return $count;
    
}

function getworkbyid($id)
{

$data = $this->database->select("work", [
"curricullumid",
"company",
"position",
"from",
"to"    
], [
"id" => $id
]);
   
return $data;   
}
}


?>