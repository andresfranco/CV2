<?php


class UrlController {
   private $database;
   private $editurl;
   private $deleteurl;
   private $mainlink;
   private $mainoption;
   public function __construct($app,$medoo,$curricullumdb) {

        $this->database =$medoo;
        $this->app=$app;
        $this->curricullumdata =$curricullumdb;
        $this->editurl ='editurl';
        $this->deleteurl='viewurl';
        $this->mainlink = '/urllist';
        $this->mainoption ='URL';

    }
    
 function rendergridview($cvid,$renderpath)
{
    $link =$this->mainlink.'/'.$cvid;
    $cvname =$this->curricullumdata->getcvnamebyid($cvid);
    $newurl =  str_replace(':cvid',$cvid,$this->app->urlFor('newurl'));
    $this->app->render($renderpath,
        array('newurl'=>$newurl
            ,'editurl'=>$this->editurl
            ,'deleteurl'=>$this->deleteurl
            ,'obj'=>$this
            ,'cvid'=>$cvid
            ,'option'=>$this->mainoption
            ,'route'=>$cvname
            ,'link'=>$link 
            ,'linkroute'=>'/editcurricullum/'.$cvid));

}
function buildgrid($cvid,$editurl,$deleteurl)
{
    $result =$this->getall($cvid);
    echo '<table class="table table-striped table-bordered bootstrap-datatable datatable">
          <thead>
          <tr>
          <th>Curricullum</th>
          <th>Name</th>
          <th>Link</th>
          <th>Type</th>
          <th>Actions</th>
          </tr>
          </thead>   
          <tbody>';
            foreach ($result as $row)
            {
                echo '<tr>';
                echo '<td>'. $row['cvname'] . '</td>';
                echo '<td>'. $row['name'] . '</td>';
                echo '<td>'. $row['link'] . '</td>';
                echo '<td>'. $row['type'] . '</td>';
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
function buildresponsivegrid($cvid,$editurl,$deleteurl)
   {
     $result=$this->getall($cvid);
     echo'<div id="grids" width="100%">         
       <table id="datagrid" class="table table-striped table-hover dt-responsive" cellspacing="0" width="80%">
        <thead>
            <tr>
             <th>Curricullum</th>
             <th>Name</th>
             <th>Link</th>
             <th>Type</th>
             <th class="nosort">Actions</th>
            </tr>
        </thead>
        <tbody>';
        foreach ($result as $row) 
        {
         echo '<tr>';
         echo '<td>'. $row['cvname'] . '</td>';
         echo '<td>'. $row['name'] . '</td>';
         echo '<td>'. $row['link'] . '</td>';
         echo '<td>'. $row['type'] . '</td>';
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

function rendernewview($curricullumid,$name,$link,$type,$errormessage,$globalobj,$renderpath)
{  
    $linkurl =$this->mainlink.'/'.$curricullumid;
    $listurl =str_replace(':cvid',$curricullumid,$this->app->urlFor('urllist')); 
    $selfurl =$this->app->urlFor('inserturl');
    $this->app->render($renderpath,array('listurl'=>$listurl
            ,'selfurl'=>$selfurl
            ,'curricullumid'=>$curricullumid
            ,'name'=>$name
            ,'linkurl'=>$link
            ,'type'=>$type
            ,'globalobj'=>$globalobj
            ,'errormessage'=>$errormessage
            ,'option'=>$this->mainoption
            ,'route'=>'New'
            ,'link'=>$linkurl));

}

function rendereditview($id,$globalobj,$renderpath)
{
    
    $datas=$this->geturlbyid($id);
    foreach($datas as $data)
    {
        $id =$data["id"];
        $curricullumid = $data["curricullumid"];
        $name =$data["name"];
        $link =$data["link"];
        $type =$data["type"];
    }
    $listurl =str_replace(':cvid',$curricullumid,$this->app->urlFor('urllist'));
    $linkurl =$this->mainlink.'/'.$curricullumid;
    $updateurl =str_replace(':id',$id,$this->app->urlFor('updateurl')); 
    $this->app->render($renderpath,array('id'=>$id,'curricullumid'=>$curricullumid
            ,'name'=>$name
            ,'linkurl'=>$link
            ,'type'=>$type
            ,'globalobj'=>$globalobj
            ,'updateurl'=>$updateurl
            ,'listurl'=>$listurl
            ,'option'=>$this->mainoption
            ,'route'=>'Edit'
            ,'link'=>$linkurl));

}

function renderdeleteview($id,$globalobj,$renderpath)
{
    $datas=$this->geturlbyid($id);
    foreach($datas as $data)
    {
        $id =$data["id"];
        $curricullumid = $data["curricullumid"];
        $name =$data["name"];
        $link =$data["link"];
        $type =$data["type"];
    }
    $listurl =str_replace(':cvid',$curricullumid,$this->app->urlFor('urllist'));
    $linkurl =$this->mainlink.'/'.$curricullumid;
    $deleteurl=str_replace(':id',$id,$this->app->urlFor('deleteurl'));
    $this->app->render($renderpath,array('id'=>$id,'curricullumid'=>$curricullumid
            ,'name'=>$name
            ,'linkurl'=> $link
            ,'type'=>$type
            ,'globalobj'=>$globalobj
            ,'deleteurl'=>$deleteurl
            ,'listurl'=>$listurl
            ,'option'=>$this->mainoption
            ,'route'=>'Delete'
            ,'link'=>$linkurl));


}
function validateinsert($curricullumid,$type,$name)
{
    //Valitype if exist
    $count =$this->findurl($curricullumid,$type,$name);
    $errormessage="";
    if($count>0)
    {
        $errormessage= '<div class="alert alert-danger col-sms-4 errordiv" role="alert"><i class="fa fa-warning"></i>The Url name for this curricullum already exist</div>';

    }
    return $errormessage;
}


function addnewitem($username,$curricullumid,$type,$name,$link,$globalobj,$renderpath)
{
    
    $linkurl =$this->mainlink.'/'.$curricullumid;
    $listurl =str_replace(':cvid',$curricullumid,$this->app->urlFor('urllist'));
    $newurl =str_replace(':cvid',$curricullumid,$this->app->urlFor('newurl'));
    $errormessage = $this->validateinsert($curricullumid,$type,$name);
    if($errormessage=="")
    {
        $this->inserturl($username,$curricullumid,$name,$link,$type);

        $this->app->response->redirect($listurl);

    }
    else
    {
      $this->app->render($renderpath,array('listurl'=>$listurl
                ,'selfurl'=> $this->app->urlFor('inserturl')
                ,'curricullumid'=>$curricullumid
                ,'name'=>$name
                ,'linkurl'=>$link
                ,'type'=>$type
                ,'globalobj'=>$globalobj
                ,'errormessage'=>$errormessage
                ,'option'=>$this->mainoption
                ,'route'=>'New'
                ,'link'=>$linkurl));
    }


}

  function updateitem($username,$id,$curricullumid,$type,$name,$link)
    {
        $listurl =str_replace(':cvid',$curricullumid,$this->app->urlFor('urllist'));
        $this->updateurl($id,$username,$curricullumid,$name,$link,$type);
        $this->app->response->redirect($listurl);
       
    }

        function deleteitem($id)
        {
            $cvid=$this->getcvidbyid($id);
            $listurl =str_replace(':cvid',$cvid,$this->app->urlFor('urllist'));
            $this->deleteurl($id);
            $this->app->response->redirect($listurl);
        }
function getall($cvid)
{
    $sth = $this->database->pdo->prepare(
            "select u.id,u.curricullumid,u.name
            ,u.link,u.type,c.name as cvname from url u
              inner join curricullum c on (u.curricullumid = c.id) where u.curricullumid ='".$cvid."'");
    $sth->execute();
    return $sth;

}
function getcvidbyid($id)
{
  $datas=$this->geturlbyid($id);
    foreach($datas as $data)
    {
       $cvid = $data["curricullumid"];
       
    }   
    return $cvid;
}

function geturlbyid($id)
{

$data = $this->database->select("url",array(
"id",
"curricullumid",
"name",
"link"  ,
"type"  ,    
),array(
"id" => $id
));
   
return $data;   
}

function inserturl($username,$curricullumid,$name,$link,$type)
{
  
$dt = date('Y-m-d H:i:s');
$this->database->insert("url",array("curricullumid" => $curricullumid,
"name" => $name,
"link"=>$link,
"type"=>$type,    
"createuser" => $username,
"createdate" => $dt ,
"modifyuser" => $username,
"modifydate" => $dt ));

}
function updateurl($id,$username,$curricullumid,$name,$link,$type)
    {

        $dt = date('Y-m-d H:i:s');
        $this->database->update("url",
            array(
             "curricullumid" => $curricullumid,
             "name" => $name,
             "link"=>$link,
             "type"=>$type,    
             "modifyuser"=>$username,
             "modifydate"=>$dt
        ),
         array(
            "id[=]" => $id
        ));



    }

    function deleteurl($id)
    {

     $this->database->delete("url",array("AND" =>array("id" => $id)));
       
    }
function findurl($curricullumid,$type,$name)
{
    $count =  $this->database->count("url",array( "id")
    ,array("AND"=>array("curricullumid"=>$curricullumid,"type"=>$type,"name"=>$name)));
     
   return $count;
  
}


}

