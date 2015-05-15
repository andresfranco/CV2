<?php

class TranslatetagController {
   private $database;
    private $editurl;
    private $deleteurl;
    private $mainlink;
    private $mainoption;
    public function __construct($app,$medoo) {

        $this->database =$medoo;
        $this->app=$app;
        $this->editurl ='edittranslatetag';
        $this->deleteurl='viewtranslatetag';
        $this->mainlink = '/translatetaglist';
        $this->mainoption ='Translate Tags';

    }
    function rendergridview($renderpath)
    {

        $this->app->render($renderpath,
            array('newurl'=>$this->app->urlFor('newtranslatetag')
                ,'editurl'=>$this->editurl,'deleteurl'=>$this->deleteurl
                ,'obj'=>$this 
                ,'option'=>$this->mainoption
                ,'route'=>''
                ,'link'=>$this->mainlink));

    }
    
     function buildgrid($editurl,$deleteurl)
  {
   $result =$this->getall(); 
   echo'<table class="table table-striped table-bordered bootstrap-datatable datatable">
        <thead>
        <th>Language</th>
        <th>Key</th>
        <th>Translation</th>
        <th>Actions</th>
        </thead>   
        <tbody>';
        foreach ($result as $row) 
        {
         echo '<tr>';
         echo '<td>'. $row['language'] . '</td>';
         echo '<td>'. $row['key'] . '</td>';
         echo '<td>'. $row['translation'] . '</td>';
         echo '<td class="center">
         <a class="btn btn-info" href="'.$editurl.'/'.$row['languagecode'].'-'.$row['key'].'">
	 <i class="halflings-icon white edit"></i>  
	 </a>
	 <a href ="'.$deleteurl.'/'.$row['languagecode'].'-'.$row['key'].'" class="btn btn-danger">
	 <i class="halflings-icon white trash"></i> 
	 </a>
	 </td>';
         echo '</tr>';
        }
       echo'</tbody></table>';
   }
    function buildresponsivegrid($editurl,$deleteurl)
   {
     $result=$this->getall();
     echo'<div id="grids" width="100%">         
       <table id="datagrid" class="table table-striped table-hover dt-responsive" cellspacing="0" width="80%">
        <thead>
            <tr>
            <th>Language</th>
            <th>Key</th>
            <th>Translation</th>
            <th class="nosort">Actions</th>
            </tr>
        </thead>
        <tbody>';
        foreach ($result as $row) 
        {
         echo '<tr>';
         echo '<td>'. $row['language'] . '</td>';
         echo '<td>'. $row['key'] . '</td>';
         echo '<td>'. $row['translation'] . '</td>';
         echo '<td class="center">
         <a class="btn btn-info" href="'.$editurl.'/'.$row['languagecode'].'-'.$row['key'].'">
	 <i class="fa fa-edit"></i>  
	 </a>
	 <a href ="'.$deleteurl.'/'.$row['languagecode'].'-'.$row['key'].'" class="btn btn-danger">
	 <i class="fa fa-trash-o"></i> 
	 </a>
	 </td>';
         echo '</tr>';
        } 
            
        echo'</tbody></table></div>';
   }
   
   function rendernewview($languagecode,$key,$translation,$errormessage,$globalobj,$renderpath)
{
    $this->app->render($renderpath,array('listurl'=>$this->app->urlFor('translatetags')
            ,'selfurl'=>$this->app->urlFor('newtranslatetag')
            ,'languagecode'=>$languagecode
            ,'key'=>$key
            ,'translation'=>$translation
            ,'languagecode'=>$translation
            ,'errormessage'=>$errormessage
            ,'option'=>$this->mainoption
            ,'route'=>'New'
            ,'link'=>$this->mainlink
            ,'globalobj'=>$globalobj
           ));

}

function rendereditview($parameter,$globalobj,$renderpath)
{
    $arrayparameter = explode('-', $parameter);
    $languagecode = $arrayparameter[0];
    $key = $arrayparameter[1];
    
    $datas=$this->gettranslatetagbyid($languagecode,$key);
    foreach($datas as $data)
    {
        $languagecode = $data["languagecode"];
        $key =$data["key"];
        $translation =$data["translation"];
        
    }
    $updateurl = str_replace(':parameter', $parameter,$this->app->urlFor('updatetranslatetag'));
    $this->app->render($renderpath,array(
            'languagecode'=>$languagecode
            ,'key'=>$key
            ,'translation'=>$translation
            ,'updateurl'=>$updateurl
            ,'listurl'=>$this->app->urlFor('translatetags')
            ,'db'=>$this
            ,'globalobj'=>$globalobj
            ,'option'=>$this->mainoption
            ,'route'=>'Edit'
            ,'parameter'=>$parameter
            ,'link'=>$this->mainlink));

}

function renderdeleteview($parameter,$globalobj,$renderpath)
{
    $arrayparameter = explode('-', $parameter);
    $languagecode = $arrayparameter[0];
    $key = $arrayparameter[1];
   $datas=$this->gettranslatetagbyid($languagecode,$key);
    foreach($datas as $data)
    {
        $languagecode = $data["languagecode"];
        $key =$data["key"];
        $translation =$data["translation"];
    }
    $deleteurl =str_replace(':parameter', $parameter,$this->app->urlFor('deletetranslatetag'));
    $this->app->render($renderpath,array(
             'languagecode'=>$languagecode
            ,'key'=>$key
            ,'translation'=>$translation
            ,'deleteurl'=>$deleteurl
            ,'listurl'=>$this->app->urlFor('translatetags')
            ,'globalobj'=>$globalobj
            ,'parameter'=>$parameter
            ,'option'=>$this->mainoption
            ,'route'=>'Delete'
            ,'link'=>$this->mainlink));
}



function validateinsert($languagecode, $key, $translation)
    {
        //Validate if exist
        $count =$this->findtranslatetag($languagecode, $key, $translation);
        $errormessage="";
        if($count>0)
        {
            $errormessage= '<div class="alert alert-error">The translation tag already exist</div>';

        }
        return $errormessage;
    }


    function addnewitem($username,$languagecode,$key, $translation,$renderpath)
    {
        $errormessage = $this->validateinsert($languagecode, $key, $translation);

        if($errormessage=="")
        {
            $this->inserttranslatetag($username, $languagecode, $key, $translation);

            $this->app->response->redirect($this->app->urlFor('translatetags'), array('newurl'=>$this->app->urlFor('newtranslatetag') ,'editurl'=>$this->editurl,'deleteurl'=>$this->deleteurl));

        }
        else
        {
            $this->app->render($renderpath,array('listurl'=>$this->app->urlFor('translatetags')
            ,'selfurl'=>$this->app->urlFor('newtranslatetag')
            ,'languagecode'=>$languagecode
            ,'key'=>$key
            ,'translation'=>$translation
            ,'errormessage'=>$errormessage));
        }


    }
  
   function updateitem($username,$languagecode, $key, $translation,$parameter)
    {
       
        
        $this->updatetranslatetag($username, $languagecode, $key, $translation,$parameter);
        $this->app->response->redirect(
            $this->app->urlFor('translatetags'),
            array(
                'newurl' => $this->app->urlFor('newtranslatetag'),
                'editurl' => $this->editurl,
                'deleteurl' => $this->deleteurl
            )
        );

    }

        function deleteitem($parameter)
        {
             $arrayparameter = explode('-', $parameter);
             $languagecode = $arrayparameter[0];
             $key = $arrayparameter[1];
            $this->deletetranslatetag($languagecode,$key);
            $this->app->response->redirect(
                $this->app->urlFor('translatetags'),
                array(
                    'newurl' => $this->app->urlFor('newtranslatetag'),
                    'editurl' => $this->editurl,
                    'deleteurl' => $this->deleteurl
                )
            );
        }   

function inserttranslatetag($username,$languagecode,$key,$translation)
    {

        $dt = date('Y-m-d H:i:s');
        $this->database->insert("translatetag", 
        array(
            "languagecode" => $languagecode,
            "key"=>$key,
            "translation" => $translation,
            "createuser" => $username,
            "createdate" => $dt ,
            "modifyuser" => $username,
            "modifydate" => $dt 
         ));
        
    }

    function getall()
    {


        $sth = $this->database->pdo->prepare("select t.languagecode"
                . ",t.key,t.translation,l.language as language "
                . "from translatetag t inner join language l on (t.languagecode=l.code) ");
        $sth->execute();
        return $sth;


    }
    function updatetranslatetag($username,$languagecode,$key,$translation,$parameter)
    {
        $arrayparameter = explode('-', $parameter);
        $languagecodeold = $arrayparameter[0];
        $keyold = $arrayparameter[1];
        
        $dt = date('Y-m-d H:i:s');
        $this->database->update("translatetag",
           array(
            "languagecode" => $languagecode,
            "key"=>$key,    
            "translation" => $translation,
            "modifyuser" => $username,
            "modifydate" => $dt 
        ),
           array("AND"=>array(
            "languagecode[=]" => $languagecodeold,
            "key[=]" => $keyold,    
        )));
         
    }

    function deletetranslatetag($languagecode,$key)
    {

        $this->database->delete("translatetag",
            array("AND" =>array("languagecode" => $languagecode,"key" => $key)));  
    }

    function findtranslatetag($languagecode,$key,$translation)
    {
        $count =  $this->database->count("translatetag",array( "languagecode")
        ,array("AND" =>array("languagecode" => $languagecode,
            "key"=>$key,
            "translation" => $translation,
          )));
        
        return $count;

    }

    function gettranslatetagbyid($languagecode,$key)
    {

        $data = $this->database->select("translatetag",array(
            "languagecode",
            "key",
            "translation" 
           
        ),array("AND"=>array(
            "languagecode" => $languagecode,
            "key"=>$key)));
        
        return $data;
    }
    
  
    
}

?>