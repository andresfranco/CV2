<?php

class MultiparamController {
    private $database;
    private $editurl;
    private $deleteurl;
    private $mainlink;
    private $mainoption;
    public function __construct($app,$medoo) {

        $this->database =$medoo;
        $this->app=$app;
        $this->editurl ='editmultiparam';
        $this->deleteurl='viewmultiparam';
        $this->mainlink = '/multiparams';
        $this->mainoption ='Multiparameter';

    }


function rendergridview($sysparamid,$renderpath)
{
  $newurl = str_replace(':sysparamid', $sysparamid,$this->app->urlFor('newmultiparam'));
  $route=$this->getparamdescriptionbyid($sysparamid);
  $linkroute=str_replace(':id', $sysparamid,$this->app->urlFor('editsysparam'));
  $this->app->render($renderpath,
        array('newurl'=>$newurl
            ,'editurl'=>$this->editurl
            ,'deleteurl'=>$this->deleteurl
            ,'obj'=>$this
            ,'sysparamid'=>$sysparamid
            ,'option'=>$this->mainoption
            ,'route'=>$route
            ,'linkroute'=>$linkroute
            ,'route'=>$route
            ,'link'=>$this->mainlink.'/'.$sysparamid));

}

function getparamdescriptionbyid($sysparamid)
{
  $sth = $this->database->pdo->prepare("SELECT description from sysparam where id='".$sysparamid."'");
  $sth->execute();
  foreach ($sth as $row)
            {
                $description =$row['description'];
                
            } 
  return $description;   
}

function rendernewview($sysparamid,$value,$valuedesc,$errormessage,$renderpath)
{
    $listurl = str_replace(':sysparamid', $sysparamid,$this->app->urlFor('multiparams'));
   
    $this->app->render($renderpath,array('listurl'=>$listurl
            ,'selfurl'=> $this->app->urlFor('insertmultiparam')
            ,'sysparamid'=>$sysparamid
            ,'value'=>$value
            ,'valuedesc'=>$valuedesc
            ,'errormessage'=>$errormessage
            ,'obj'=>$this
            ,'sysparamid'=>$sysparamid
            ,'option'=>$this->mainoption
            ,'route'=>'New'
            ,'link'=>$this->mainlink.'/'.$sysparamid));

}

function rendereditview($id,$renderpath)
{

    $datas=$this->getmultiparambyid($id);
    foreach($datas as $data)
    {
        $id = $data["id"];
        $sysparamid =$data["sysparamid"];
        $value =$data["value"];
        $valuedesc =$data["valuedesc"];
    }
    $updateurl = str_replace(':id', $id,$this->app->urlFor('updatemultiparam'));
    $listurl = str_replace(':sysparamid', $sysparamid,$this->app->urlFor('multiparams'));
    $this->app->render($renderpath,array(
            'sysparamid'=>$sysparamid
            ,'value'=>$value
            ,'valuedesc'=>$valuedesc
            ,'obj'=>$this
            ,'updateurl'=>$updateurl
            ,'listurl'=>$listurl
            ,'option'=>$this->mainoption
            ,'route'=>'Edit'
            ,'link'=>$this->mainlink.'/'.$sysparamid));

}

function renderdeleteview($id,$renderpath)
{
    $datas=$this->getmultiparambyid($id);
    foreach($datas as $data)
    {   
        $id=$data["id"];
        $sysparamid =$data["sysparamid"];
        $value =$data["value"];
        $valuedesc =$data["valuedesc"];
    }
    $listurl = str_replace(':sysparamid', $sysparamid,$this->app->urlFor('multiparams'));
    $deleteurl = str_replace(':id', $id,$this->app->urlFor('deletemultiparam'));
    $deleteurlink = str_replace(':sysparamid', $sysparamid,$deleteurl);
    $this->app->render($renderpath,array('sysparamid'=>$sysparamid
            ,'value'=>$value
            ,'valuedesc'=>$valuedesc
            ,'id'=>$id
            ,'obj'=>$this
            ,'deleteurl'=>$deleteurlink
            ,'listurl'=>$listurl
            ,'option'=>$this->mainoption
            ,'route'=>'Delete'
            ,'link'=>$this->mainlink.'/'.$sysparamid));


}



function validateinsert($sysparamid,$value)
{
    //Validate if exist
    $count =$this->findmultiparam($sysparamid,$value);
    $errormessage="";
    if($count>0)
    {
        $errormessage= '<div class="alert alert-danger col-sms-4 errordiv" role="alert"><i class="fa fa-warning"></i>The value for this system parameter already exist</div>';

    }
    return $errormessage;
}


function addnewitem($username,$sysparamid,$value,$valuedesc,$renderpath)
{
    $errormessage = $this->validateinsert($sysparamid,$value);

    if($errormessage=="")
    {
        
      $this->insertmultiparam($username,$sysparamid,$value,$valuedesc);
      $redirecturl =str_replace(':sysparamid',$sysparamid,$this->app->urlFor('multiparams'));
     $this->app->response->redirect($redirecturl
                , array('newurl'=>$this->app->urlFor('newmultiparam') 
                ,'editurl'=>$this->editurl
                ,'deleteurl'=>$this->deleteurl));
        
    }
    else
    {
        $this->app->render($renderpath,array('listurl'=>$this->app->urlFor('multiparams')
                ,'selfurl'=>$this->app->urlFor('newmultiparam')
                ,'sysparamid'=>$sysparamid
                ,'value'=>$value
                ,'valuedesc'=>$valuedesc
                ,'errormessage'=>$errormessage
                ,'option'=>$this->mainoption
                ,'route'=>'New'
                ,'obj'=>$this
                ,'link'=>$this->mainlink.'/'.$sysparamid));
    }


}

    function updateitem($username,$id,$sysparamid,$value,$valuedesc)
    {
        $this->updatemultiparam($username,$id,$sysparamid,$value,$valuedesc);
        $redirecturl =str_replace(':sysparamid',$sysparamid,$this->app->urlFor('multiparams'));
        $this->app->response->redirect($redirecturl);
    }

    function deleteitem($id,$sysparamid)
    {
        $this->deletemultiparam($id);
        $listurl = str_replace(':sysparamid',$sysparamid,$this->app->urlFor('multiparams'));
        $this->app->response->redirect($listurl);
    }


function buildgrid($sysparamid,$editurl,$deleteurl)
{
    $result =$this->getall($sysparamid);
    echo '<table class="table table-striped table-bordered bootstrap-datatable datatable">
          <thead>
          <tr>
          <th>Sysparam</th>
          <th>Value</th>
          <th>Description</th>
          <th>Actions</th>
          </tr>
          </thead>   
          <tbody>';
            foreach ($result as $row)
            {
                echo '<tr>';
                echo '<td>'. $row['sysparam'] . '</td>';
                echo '<td>'. $row['value'] . '</td>';
                echo '<td>'. $row['valuedesc'] . '</td>';
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

function buildresponsivegrid($sysparamid,$editurl,$deleteurl)
   {
     $result=$this->getall($sysparamid);
     echo'<div id="grids" width="100%">         
       <table id="datagrid" class="table table-striped table-hover dt-responsive" cellspacing="0" width="80%">
        <thead>
            <tr>
              <th>Sysparam</th>
              <th>Value</th>
              <th>Description</th>
              <th class="nosort">Actions</th>
            </tr>
        </thead>
        <tbody>';
        foreach ($result as $row) 
        {
         echo '<tr>';
         echo '<td>'. $row['sysparam'] . '</td>';
         echo '<td>'. $row['value'] . '</td>';
         echo '<td>'. $row['valuedesc'] . '</td>';
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






function insertmultiparam($username,$sysparamid,$value,$valuedesc)
{
  
    $dt = date('Y-m-d H:i:s');
$this->database->insert("multiparam",array("sysparamid" => $sysparamid,
"value" => $value,
"valuedesc"=>$valuedesc,    
"createuser" => $username,
"createdate" => $dt ,
"modifyuser" => $username,
"modifydate" => $dt ));


}

function getall($sysparamid)
{


    $sth = $this->database->pdo->prepare('SELECT mp.id, sp.code as sysparam,mp.value,mp.valuedesc '
            . 'from multiparam mp inner join sysparam sp on(sp.id =mp.sysparamid) where mp.sysparamid='.$sysparamid.'');
    $sth->execute();
    return $sth;


}
    function updatemultiparam($username,$id,$sysparamid,$value,$valuedesc)
    {
        
        $dt = date('Y-m-d H:i:s');
        $this->database->update("multiparam",array("sysparamid"=>$sysparamid,
            "value" => $value,
            "valuedesc" => $valuedesc,
            "modifyuser" => $username,
            "modifydate"=>$dt)
             ,array(
            "id[=]" => $id
        ));
        
       
    }

function deletemultiparam($id)
{

    $this->database->delete("multiparam",array("AND" =>array("id" => $id)));
   
}

function findmultiparam($sysparamid,$value)
{
    $count =  $this->database->count("multiparam",array("id"),array("AND" => array("sysparamid"=>$sysparamid,"value"=>$value))); 

   return $count;
    
}

function getmultiparambyid($id)
{

$data = $this->database->select("multiparam",array(
"id",    
"sysparamid",
"value",
"valuedesc"    
),array(
"id" => $id
 ));
   
return $data;   
}

  function getsysparamselect($attribute,$id)
    {

        $sth = $this->database->pdo->prepare("SELECT id ,code FROM sysparam where id ='".$id."'");
        $sth->execute();
         echo '<select id ="sysparamid" name="sysparamid"'.$attribute.'>';
        $selected="";
        foreach ($sth as $row) {
            if ($row['id'] == $id) {
                $selected = 'selected';
            }
            else
            {$selected="";
            }
            echo '<option value ="'.$row['id'].'" '.$selected.' >'.$row['code'].'</option>';

        }
         echo '</select>';
    } 
}

?>
