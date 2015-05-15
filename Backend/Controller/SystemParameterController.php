<?php

class SystemParameterController {
    private $database;
    private $editurl;
    private $deleteurl;
    private $mainlink;
    private $mainoption;
    public function __construct($app,$medoo) {

        $this->database =$medoo;
        $this->app=$app;
        $this->editurl ='editsysparam';
        $this->deleteurl='viewsysparam';
        $this->mainlink = '/sysparams';
        $this->mainoption ='System Parameters';

    }


function rendergridview($renderpath)
{

    $this->app->render($renderpath,
        array('newurl'=>$this->app->urlFor('newsysparam')
            ,'editurl'=>$this->editurl
            ,'deleteurl'=>$this->deleteurl
            ,'obj'=>$this
            ,'option'=>$this->mainoption
            ,'route'=>''
            ,'link'=>$this->mainlink));

}

function rendernewview($code,$value,$description,$errormessage,$renderpath)
{
    $this->app->render($renderpath,array('listurl'=>$this->app->urlFor('sysparams')
            ,'selfurl'=>$this->app->urlFor('newsysparam')
            ,'code'=>$code
            ,'value'=>$value
            ,'description'=>$description
            ,'errormessage'=>$errormessage
            ,'option'=>$this->mainoption
            ,'route'=>'New'
            ,'link'=>$this->mainlink));

}

function rendereditview($id,$renderpath)
{

    $datas=$this->getsysparambyid($id);
    foreach($datas as $data)
    {
        $id =$data["id"];
        $code =$data["code"];
        $value =$data["value"];
        $description =$data["description"];
    }
    $updateurl =  str_replace(":id", $id, $this->app->urlFor('updatesysparam'));
    $this->app->render($renderpath,array('id'=>$id 
            ,'code'=>$code
            ,'value'=>$value
            ,'description'=>$description
            ,'updateurl'=>$updateurl
            ,'listurl'=>$this->app->urlFor('sysparams')
            ,'option'=>$this->mainoption
            ,'route'=>'Edit'
            ,'link'=>$this->mainlink));

}

function renderdeleteview($id,$renderpath)
{
    $datas=$this->getsysparambyid($id);
    foreach($datas as $data)
    {   
        $code =$data["code"];
        $value =$data["value"];
        $description =$data["description"];
    }
    $deleteurl =  str_replace(":id", $id, $this->app->urlFor('deletesysparam'));
    $this->app->render($renderpath,array('code'=>$code
             ,'value'=>$value
            ,'description'=>$description
            ,'id'=>$id
            ,'deleteurl'=>$deleteurl
            ,'listurl'=>$this->app->urlFor('sysparams')
            ,'option'=>$this->mainoption
            ,'route'=>'Delete'
            ,'link'=>$this->mainlink));


}



function validateinsert($code)
{
    //Validate if exist
    $count =$this->findsysparam($code);
    $errormessage="";
    if($count>0)
    {
        $errormessage= '<div class="alert alert-danger col-sms-4 errordiv" role="alert"><i class="fa fa-warning"></i> The system parameter with code : "'.$code. '" already exist</div>';

    }
    return $errormessage;
}


function addnewitem($username,$code,$value,$description,$renderpath)
{
    $errormessage = $this->validateinsert($code);

    if($errormessage=="")
    {
        $this->insertsysparam($username,$code,$value,$description);

       $this->app->response->redirect($this->app->urlFor('sysparams')
                , array('newurl'=>$this->app->urlFor('newsysparam') 
                ,'editurl'=>$this->editurl
                ,'deleteurl'=>$this->deleteurl));
        
    }
    else
    {
        $this->app->render($renderpath,array('listurl'=>$this->app->urlFor('sysparams')
                ,'selfurl'=>$this->app->urlFor('newsysparam')
                ,'code'=>$code
                ,'value'=>$value
                ,'description'=>$description
                ,'errormessage'=>$errormessage
                ,'option'=>$this->mainoption
                ,'route'=>'New'
                ,'link'=>$this->mainlink));
    }


}

    function updateitem($username,$id,$code,$value,$description)
    {
        $this->updatesysparam($username,$id,$code,$value,$description);
        $this->app->response->redirect($this->app->urlFor('sysparams'), array('newurl'=>$this->app->urlFor('newsysparam') ,'editurl'=>$this->editurl,'deleteurl'=>$this->deleteurl));
    }

    function deleteitem($id)
    {
        $this->deletesysparam($id);
        $this->app->response->redirect($this->app->urlFor('sysparams'), array('newurl'=>$this->app->urlFor('newsysparam') ,'editurl'=>$this->editurl,'deleteurl'=>$this->deleteurl));
    }


function buildgrid($editurl,$deleteurl)
{
    $result =$this->getall();
    echo '<table class="table table-striped table-bordered bootstrap-datatable datatable">
          <thead>
          <tr>
          <th>Code</th>
          <th>Value</th>
          <th>Description</th>
          <th>Actions</th>
          </tr>
          </thead>   
          <tbody>';
            foreach ($result as $row)
            {
                echo '<tr>';
                echo '<td>'. $row['code'] . '</td>';
                echo '<td>'. $row['value'] . '</td>';
                echo '<td>'. $row['description'] . '</td>';
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
              <th>Code</th>
              <th>Value</th>
              <th>Description</th>
              <th class="nosort">Actions</th>
            </tr>
        </thead>
        <tbody>';
        foreach ($result as $row) 
        {
         echo '<tr>';
         echo '<td>'. $row['code'] . '</td>';
         echo '<td>'. $row['value'] . '</td>';
         echo '<td>'. $row['description'] . '</td>';
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






function insertsysparam($username,$code,$value,$description)
{
  
    $dt = date('Y-m-d H:i:s');
$this->database->insert("sysparam",array("code" => $code,
"value" => $value,
"description"=>$description,    
"createuser" => $username,
"createdate" => $dt ,
"modifyuser" => $username,
"modifydate" => $dt ));


}

function getall()
{


    $sth = $this->database->pdo->prepare('SELECT * FROM sysparam');
    $sth->execute();
    return $sth;


}
    function updatesysparam($username,$id,$code,$value,$description)
    {
        
        $dt = date('Y-m-d H:i:s');
        $this->database->update("sysparam",array("code" => $code,
            "value" => $value,
            "description" => $description,
            "modifyuser" => $username,
            "modifydate"=>$dt
             ),array(
            "id[=]" => $id
        ));

    }

function deletesysparam($id)
{

    $this->database->delete("sysparam",array( "AND" =>array("id" => $id)));
    
}

function findsysparam($code)
{
 $count= $this->database->count("sysparam",array("code" => $code));
 return $count;    
}

function getsysparambyid($id)
{

$data = $this->database->select("sysparam",array(
"id",    
"code",
"value",
"description"    
),array(
"id" => $id
));
   
return $data;   
}
}

?>