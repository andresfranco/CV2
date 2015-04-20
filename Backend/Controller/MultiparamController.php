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
    $this->app->render($renderpath,
        array('newurl'=>$newurl
            ,'editurl'=>$this->editurl
            ,'deleteurl'=>$this->deleteurl
            ,'obj'=>$this
            ,'sysparamid'=>$sysparamid
            ,'option'=>$this->mainoption
            ,'route'=>''
            ,'link'=>$this->mainlink));

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
            ,'link'=>$this->mainlink));

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
    $this->app->render($renderpath,array(
            'sysparamid'=>$sysparamid
            ,'value'=>$value
            ,'valuedesc'=>$valuedesc
            ,'obj'=>$this
            ,'updateurl'=>$updateurl
            ,'listurl'=>$this->app->urlFor('multiparams')
            ,'option'=>$this->mainoption
            ,'route'=>'Edit'
            ,'link'=>$this->mainlink));

}

function renderdeleteview($id,$renderpath)
{
    $datas=$this->getmultiparambyid($id);
    foreach($datas as $data)
    {   
        $sysparamid =$data["sysparamid"];
        $value =$data["value"];
        $valuedesc =$data["valuedesc"];
    }
    $this->app->render($renderpath,array('sysparamid'=>$sysparamid
             ,'value'=>$value
            ,'valuedesc'=>$valuedesc
            ,'id'=>$id
            ,'obj'=>$this
            ,'deleteurl'=>$this->app->urlFor('deletemultiparam')
            ,'listurl'=>$this->app->urlFor('multiparams')
            ,'option'=>$this->mainoption
            ,'route'=>'Delete'
            ,'link'=>$this->mainlink));


}



function validateinsert($sysparamid,$value)
{
    //Validate if exist
    $count =$this->findmultiparam($sysparamid,$value);
    $errormessage="";
    if($count>0)
    {
        $errormessage= '<div class="alert alert-error">The value for this system parameter already exist</div>';

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
                ,'link'=>$this->mainlink));
    }


}

    function updateitem($username,$id,$sysparamid,$value,$valuedesc)
    {
        $this->updatemultiparam($username,$id,$sysparamid,$value,$valuedesc);
        $redirecturl =str_replace(':sysparamid',$sysparamid,$this->app->urlFor('multiparams'));
        $this->app->response->redirect($redirecturl);
    }

    function deleteitem($id)
    {
        $this->deletemultiparam($id);
        $this->app->response->redirect($this->app->urlFor('multiparams'), array('newurl'=>$this->app->urlFor('newmultiparam') ,'editurl'=>$this->editurl,'deleteurl'=>$this->deleteurl));
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






function insertmultiparam($username,$sysparamid,$value,$valuedesc)
{
  
    $dt = date('Y-m-d H:i:s');
$this->database->insert("multiparam", ["sysparamid" => $sysparamid,
"value" => $value,
"valuedesc"=>$valuedesc,    
"createuser" => $username,
"createdate" => $dt ,
"modifyuser" => $username,
"modifydate" => $dt ]);


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
        $this->database->update("multiparam", ["sysparamid" => $sysparamid,
            "value" => $value,
            "valuedesc" => $valuedesc,
            "modifyuser" => $username,
            "modifydate"=>$dt
             ],[
            "id[=]" => $id
        ]);
        var_dump($this->database->log());

    }

function deletemultiparam($id)
{

    $this->database->delete("multiparam", [
        "AND" => [
            "id" => $id

	]

]);
    
   
}

function findmultiparam($sysparamid,$value)
{
    $count =  $this->database->count("multiparam", [
"id"
],["AND" => ["sysparam"=>$sysparamid,"value"=>$value 
          ]]);
   return $count;
    
}

function getmultiparambyid($id)
{

$data = $this->database->select("multiparam", [
"id",    
"sysparamid",
"value",
"valuedesc"    
], [
"id" => $id
]);
   
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
