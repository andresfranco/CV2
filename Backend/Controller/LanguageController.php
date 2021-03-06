<?php
Class LanguageController {

    private $database;
    private $editurl;
    private $deleteurl;
    private $mainlink;
    private $mainoption;
    public function __construct($app,$medoo) {

        $this->database =$medoo;
        $this->app=$app;
        $this->editurl ='editlanguage';
        $this->deleteurl='viewlanguage';
        $this->mainlink = '/languages';
        $this->mainoption ='Languages';

    }


function rendergridview($globalobj,$renderpath)
{

    $this->app->render($renderpath,
        array('newurl'=>$this->app->urlFor('newlanguage')
            ,'editurl'=>$this->editurl
            ,'deleteurl'=>$this->deleteurl
            ,'obj'=>$this
            ,'globalobj'=>$globalobj
            ,'option'=>$this->mainoption
            ,'route'=>''
            ,'link'=>$this->mainlink));

}

function rendernewview($code,$language,$errormessage,$renderpath)
{
    $this->app->render($renderpath,array('listurl'=>$this->app->urlFor('languages')
            ,'selfurl'=>$this->app->urlFor('newlanguage')
            ,'code'=>$code
            ,'language'=>$language
            ,'errormessage'=>$errormessage
            ,'option'=>$this->mainoption
            ,'route'=>'New'
            ,'link'=>$this->mainlink));

}

function rendereditview($code,$renderpath)
{

    $datas=$this->getlanguagebyid($code);
    foreach($datas as $data)
    {
        $language = $data["language"];
        $code =$data["code"];
    }
    $updateurl =  str_replace(':id',$code, $this->app->urlFor('updatelanguage'));
    $this->app->render($renderpath,array( 
            'code'=>$code
            ,'language'=>$language
            ,'updateurl'=>$updateurl
            ,'listurl'=>$this->app->urlFor('languages')
            ,'option'=>$this->mainoption
            ,'route'=>'Edit'
            ,'link'=>$this->mainlink));

}

function renderdeleteview($code,$renderpath)
{
    $datas=$this->getlanguagebyid($code);
    foreach($datas as $data)
    {
        $language = $data["language"];
        $code =$data["code"];
    }
    $deleteurl =  str_replace(':id',$code, $this->app->urlFor('deletelanguage'));
    $this->app->render($renderpath,array('code'=>$code
            ,'language'=>$language
            ,'deleteurl'=>$deleteurl
            ,'listurl'=>$this->app->urlFor('languages')
            ,'option'=>$this->mainoption
            ,'id'=>$code
            ,'route'=>'Delete'
            ,'link'=>$this->mainlink));


}



function validateinsert($code)
{
    //Validate if exist
    $count =$this->findlanguage($code);
    $errormessage="";
    if($count>0)
    {
        $errormessage= '<div class="alert alert-danger col-sms-4 errordiv" role="alert"><i class="fa fa-warning"></i>The language of code : "'.$code. '" already exist</div>';

    }
    return $errormessage;
}


function addnewitem($username,$code,$language,$renderpath)
{
    $errormessage = $this->validateinsert($code);

    if($errormessage=="")
    {
        $this->insertlanguage($username,$code,$language);

        $this->app->response->redirect($this->app->urlFor('languages')
                , array('newurl'=>$this->app->urlFor('newlanguage') 
                ,'editurl'=>$this->editurl
                ,'deleteurl'=>$this->deleteurl));

    }
    else
    {
        $this->app->render($renderpath,array('listurl'=>$this->app->urlFor('languages')
                ,'selfurl'=>$this->app->urlFor('newlanguage')
                ,'code'=>$code,'language'=>$language
                ,'errormessage'=>$errormessage
                ,'option'=>$this->mainoption
                ,'route'=>'New'
                ,'link'=>$this->mainlink));
    }


}

    function updateitem($username,$code,$codeold,$language)
    {
        $this->updatelanguage($username,$code,$codeold,$language);
        $this->app->response->redirect($this->app->urlFor('languages'));
    }

    function deleteitem($id)
    {
        $this->deletelanguage($id);
        $this->app->response->redirect($this->app->urlFor('languages'));
    }


function buildgrid($editurl,$deleteurl)
{
    $result =$this->getall();
    echo '<table class="table table-striped table-bordered bootstrap-datatable datatable">
          <thead>
          <tr>
          <th>Code</th>
          <th>Language</th>
          <th>Actions</th>
          </tr>
          </thead>   
          <tbody>';
            foreach ($result as $row)
            {
                echo '<tr>';
                echo '<td>'. $row['code'] . '</td>';
                echo '<td>'. $row['language'] . '</td>';
                echo '<td class="center">
                 <a class="btn btn-info" href="'.$editurl.'/'.$row['code'].'">
                 <i class="halflings-icon white edit"></i>
                 </a>
                 <a href ="'.$deleteurl.'/'.$row['code'].'" class="btn btn-danger">
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
                <th>Code</th>
                <th>Language</th>
                <th class="nosort">Actions</th>
            </tr>
        </thead>
        <tbody>';
        foreach ($result as $row) 
        {
         echo '<tr>';
         echo '<td>'. $row['code'] . '</td>';
         echo '<td>'. $row['language'] . '</td>';
         echo '<td class="center">';
         if ($editvar==1)
         {    
         echo'<a class="btn btn-info" href="'.$editurl.'/'.$row['code'].'">
	 <i class="fa fa-edit"></i>  
	 </a> ';
         }
	 if ($deletevar==1)
         {
         echo'<a href ="'.$deleteurl.'/'.$row['code'].'" class="btn btn-danger">
	 <i class="fa fa-trash-o"></i> 
	 </a>';
         }
	 echo'</td></tr>';
        } 
            
        echo'</tbody></table></div>';
   }






function insertlanguage($username,$code ,$language)
{
  
 $dt = date('Y-m-d H:i:s');
 $this->database->insert("language", array("code" => $code,
 "language" => $language,
 "createuser" => $username,
 "createdate" => $dt ,
 "modifyuser" => $username,
 "modifydate" => $dt ));
}

function getall()
{


    $sth = $this->database->pdo->prepare('SELECT * FROM language');
    $sth->execute();
    return $sth;


}
    function updatelanguage($username,$code,$codeold,$language)
    {
        
        $dt = date('Y-m-d H:i:s');
        $this->database->update("language", array("code" => $code,
            "language" => $language,
            "modifyuser" => $username,
            "modifydate"=>$dt
             ),array(
            "code[=]" => $codeold
        ));

       
    }

function deletelanguage($code)
{

    $this->database->delete("language",array("AND" =>array("code" => $code)));
    //var_dump($database->error());
   //header('Location: '.$redirecturl);
}

function findlanguage($code)
{
    $count =  $this->database->count("language",array("code" => $code));
   return $count;
    
}

function getlanguagebyid($code)
{

$data = $this->database->select("language",array("code","language"),array("code" => $code));
   
return $data;   
}

}
?>