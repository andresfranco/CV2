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
       
        $code =$data["code"];
        $value =$data["value"];
        $description =$data["description"];
    }
    $this->app->render($renderpath,array('id'=>$id 
            ,'code'=>$code
            ,'value'=>$value
            ,'description'=>$description
            ,'updateurl'=>$this->app->urlFor('updatesysparam')
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
    $this->app->render($renderpath,array('code'=>$code
             ,'value'=>$value
            ,'description'=>$description
            ,'id'=>$id
            ,'deleteurl'=>$this->app->urlFor('deletesysparam')
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
        $errormessage= '<div class="alert alert-error">The system parameter with code :'.$code. ' already exist</div>';

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






function insertsysparam($username,$code,$value,$description)
{
  
    $dt = date('Y-m-d H:i:s');
$this->database->insert("sysparam", ["code" => $code,
"value" => $value,
"description"=>$description,    
"createuser" => $username,
"createdate" => $dt ,
"modifyuser" => $username,
"modifydate" => $dt ]);


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
        $this->database->update("sysparam", ["code" => $code,
            "value" => $value,
            "description" => $description,
            "modifyuser" => $username,
            "modifydate"=>$dt
             ],[
            "id[=]" => $id
        ]);

    }

function deletesysparam($id)
{

    $this->database->delete("sysparam", [
        "AND" => [
            "id" => $id

	]

]);
    var_dump($this->database->log());
   
}

function findsysparam($code)
{
    $count =  $this->database->count("sysparam", [
"code" => $code
]);
   return $count;
    
}

function getsysparambyid($id)
{

$data = $this->database->select("sysparam", [
"code",
"value",
"description"    
], [
"id" => $id
]);
   
return $data;   
}
}

?>