<?php
Class LanguageController {

    private $database;
    private $editurl;
    private $deleteurl;
    public function __construct($app,$medoo) {

        $this->database =$medoo;
        $this->app=$app;
        $this->editurl ='editlanguage';
        $this->deleteurl='viewlanguage';

    }


function rendergridview($renderpath)
{

    $this->app->render($renderpath,
        array('newurl'=>$this->app->urlFor('newlanguage'),'editurl'=>$this->editurl,'deleteurl'=>$this->deleteurl,'languageobj'=>$this));

}

function rendernewview($code,$language,$errormessage,$renderpath)
{
    $this->app->render($renderpath,array('listurl'=>$this->app->urlFor('languages'),'selfurl'=>$this->app->urlFor('newlanguage'),'code'=>$code,'language'=>$language,'errormessage'=>$errormessage));

}

function rendereditview($code,$renderpath)
{

    $datas=$this->getlanguagebyid($code);
    foreach($datas as $data)
    {
        $language = $data["language"];
        $code =$data["code"];
    }
    $this->app->render($renderpath,array('codeold'=>$code ,'code'=>$code,'language'=>$language,'updateurl'=>$this->app->urlFor('updatelanguage'),'listurl'=>$this->app->urlFor('languages')));

}

function renderdeleteview($code,$renderpath)
{
    $datas=$this->getlanguagebyid($code);
    foreach($datas as $data)
    {
        $language = $data["language"];
        $code =$data["code"];
    }
    $this->app->render($renderpath,array('code'=>$code,'language'=>$language,'deleteurl'=>$this->app->urlFor('deletelanguage'),'listurl'=>$this->app->urlFor('languages')));


}



function validateinsert($code)
{
    //Validate if exist
    $count =$this->findlanguage($code);
    $errormessage="";
    if($count>0)
    {
        $errormessage= '<div class="alert alert-error">The language of code : "'.$code. '" already exist</div>';

    }
    return $errormessage;
}


function addnewitem($username,$code,$language,$renderpath)
{
    $errormessage = $this->validateinsert($code);

    if($errormessage=="")
    {
        $this->insertlanguage($username,$code,$language);

        $this->app->response->redirect($this->app->urlFor('languages'), array('newurl'=>$this->app->urlFor('newlanguage') ,'editurl'=>$this->editurl,'deleteurl'=>$this->deleteurl));

    }
    else
    {
        $this->app->render($renderpath,array('listurl'=>$this->app->urlFor('languages'),'selfurl'=>$this->app->urlFor('newlanguage'),'code'=>$code,'language'=>$language,'errormessage'=>$errormessage));
    }


}

    function updateitem($username,$code,$codeold,$language)
    {
        $this->updatelanguage($username,$code,$codeold,$language);
        $this->app->response->redirect($this->app->urlFor('languages'), array('newurl'=>$this->app->urlFor('newlanguage') ,'editurl'=>$this->editurl,'deleteurl'=>$this->deleteurl));
    }

    function deleteitem($id)
    {
        $this->deletelanguage($id);
        $this->app->response->redirect($this->app->urlFor('languages'), array('newurl'=>$this->app->urlFor('newlanguage') ,'editurl'=>$this->editurl,'deleteurl'=>$this->deleteurl));
    }


function buildgrid($editurl,$deleteurl)
{
    $result =$this->getall();
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
}






function insertlanguage($username,$code ,$language)
{
  
    $dt = date('Y-m-d H:i:s');
$this->database->insert("language", ["code" => $code,
"language" => $language,
"createuser" => $username,
"createdate" => $dt ,
"modifyuser" => $username,
"modifydate" => $dt ]);



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
        $this->database->update("language", ["code" => $code,
            "language" => $language,
            "modifyuser" => $username,
            "modifydate"=>$dt
             ],[
            "code[=]" => $codeold
        ]);


    }

function deletelanguage($code)
{

    $this->database->delete("language", [
        "AND" => [
            "code" => $code

	]

]);
    //var_dump($database->error());
   //header('Location: '.$redirecturl);
}

function findlanguage($code)
{
    $count =  $this->database->count("language", [
"code" => $code
]);
   return $count;
    
}

function getlanguagebyid($code)
{

$data = $this->database->select("language", [
"code",
"language"
], [
"code" => $code
]);
   
return $data;   
}

}
?>