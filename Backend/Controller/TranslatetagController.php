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
        $this->editurl ='edittranslationtag';
        $this->deleteurl='viewtranslationtag';
        $this->mainlink = '/translationtaglist';
        $this->mainoption ='Translation Tags';

    }
    function rendergridview($renderpath)
    {

        $this->app->render($renderpath,
            array('newurl'=>$this->app->urlFor('newtranslationtag')
                ,'editurl'=>$this->editurl,'deleteurl'=>$this->deleteurl
                ,'translationtagobj'=>$this 
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
   
   function rendernewview($objectcode,$parentid,$objectid,$languagecode,$field,$content,$errormessage,$globalobj,$db,$renderpath)
{
    $this->app->render($renderpath,array('listurl'=>$this->app->urlFor('translationtags')
            ,'selfurl'=>$this->app->urlFor('newtranslationtag')
            ,'objectcode'=>$objectcode
            ,'parentid'=>$parentid
            ,'objectid'=>$objectid
            ,'languagecode'=>$languagecode
            ,'field'=>$field
            ,'content'=>$content
            ,'errormessage'=>$errormessage
            ,'option'=>$this->mainoption
            ,'route'=>'New'
            ,'link'=>$this->mainlink
            ,'globalobj'=>$globalobj
            ,'db'=>$db));

}

function rendereditview($id,$globalobj,$renderpath)
{

    $datas=$this->gettranslationtagbyid($id);
    foreach($datas as $data)
    {
        $objectcode = $data["objectcode"];
        $parentid =$data["parentid"];
        $objectid =$data["objectid"];
        $languagecode =$data["languagecode"];
        $field =$data["field"];
        $content=$data["content"];
    }
    $this->app->render($renderpath,array('id'=>$id
            ,'objectcode'=>$objectcode
            ,'parentid'=>$parentid
            ,'objectid'=>$objectid
            ,'languagecode'=>$languagecode
            ,'field'=>$field
            ,'content'=>$content
            ,'updateurl'=>$this->app->urlFor('updatetranslationtag')
            ,'listurl'=>$this->app->urlFor('translationtags')
            ,'db'=>$this
            ,'globalobj'=>$globalobj
            ,'option'=>$this->mainoption
            ,'route'=>'Edit'
            ,'link'=>$this->mainlink));

}

function renderdeleteview($id,$globalobj,$renderpath)
{
   $datas=$this->gettranslationtagbyid($id);
    foreach($datas as $data)
    {
        $objectcode = $data["objectcode"];
        $parentid =$data["parentid"];
        $objectid =$data["objectid"];
        $languagecode =$data["languagecode"];
        $field =$data["field"];
        $content=$data["content"];
    }
    $this->app->render($renderpath,array('id'=>$id
             ,'objectcode'=>$objectcode
            ,'parentid'=>$parentid
            ,'objectid'=>$objectid
            ,'languagecode'=>$languagecode
            ,'field'=>$field
            ,'content'=>$content
            ,'deleteurl'=>$this->app->urlFor('deletetranslationtag')
            ,'listurl'=>$this->app->urlFor('translationtags')
            ,'globalobj'=>$globalobj
            ,'db'=>$this
            ,'option'=>$this->mainoption
            ,'route'=>'Delete'
            ,'link'=>$this->mainlink));
}



function validateinsert($objectcode, $parentid, $objectid, $languagecode, $field)
    {
        //Validate if exist
        $count =$this->findtranslationtag($objectcode, $parentid, $objectid, $languagecode, $field);
        $errormessage="";
        if($count>0)
        {
            $errormessage= '<div class="alert alert-error">The translationtag already exist</div>';

        }
        return $errormessage;
    }


    function addnewitem($username,$objectcode, $parentid, $objectid, $languagecode,$field,$content,$renderpath)
    {
        $errormessage = $this->validateinsert($objectcode, $parentid, $objectid, $languagecode, $field);

        if($errormessage=="")
        {
            $this->inserttranslationtag($username, $objectcode, $parentid, $objectid, $languagecode, $field, $content);

            $this->app->response->redirect($this->app->urlFor('translationtags'), array('newurl'=>$this->app->urlFor('newtranslationtag') ,'editurl'=>$this->editurl,'deleteurl'=>$this->deleteurl));

        }
        else
        {
            $this->app->render($renderpath,array('listurl'=>$this->app->urlFor('translationtags')
            ,'selfurl'=>$this->app->urlFor('newtranslationtag')
            ,'objectcode'=>$objectcode
            ,'parentid'=>$parentid
            ,'objectid'=>$objectid
            ,'languagecode'=>$languagecode
            ,'field'=>$field
            ,'content'=>$content
            ,'errormessage'=>$errormessage));
        }


    }
  
   function updateitem($username,$id,$objectcode, $parentid, $objectid, $languagecode,$field,$content)
    {
        $this->updatetranslationtag($id, $username, $objectcode, $parentid, $objectid, $languagecode, $field, $content);
        $this->app->response->redirect(
            $this->app->urlFor('translationtags'),
            array(
                'newurl' => $this->app->urlFor('newtranslationtag'),
                'editurl' => $this->editurl,
                'deleteurl' => $this->deleteurl
            )
        );

    }

        function deleteitem($id)
        {
            $this->deletetranslationtag($id);
            $this->app->response->redirect(
                $this->app->urlFor('translationtags'),
                array(
                    'newurl' => $this->app->urlFor('newtranslationtag'),
                    'editurl' => $this->editurl,
                    'deleteurl' => $this->deleteurl
                )
            );
        }   

function inserttranslationtag($username,$objectcode,$parentid,$objectid,$languagecode,$field,$content)
    {

        $dt = date('Y-m-d H:i:s');
        $this->database->insert("translationtag", 
        [
            "objectcode" => $objectcode,
            "parentid"=>$parentid,
            "objectid" => $objectid,
            "languagecode" => $languagecode,
            "field"=>$field,
            "content"=>$content,
            "createuser" => $username,
            "createdate" => $dt ,
            "modifyuser" => $username,
            "modifydate" => $dt 
         ]);

    }

    function getall()
    {


        $sth = $this->database->pdo->prepare("select t.languagecode"
                . ",t.key,t.translation , l.language as language "
                . "from translatetag t inner join language l on (t.languagecode=l.code) ");
        $sth->execute();
        return $sth;


    }
    function updatetranslationtag($id,$username,$objectcode,$parentid,$objectid,$languagecode,$field,$content)
    {

        $dt = date('Y-m-d H:i:s');
        $this->database->update("translationtag",
            [
            "objectcode" => $objectcode,
            "parentid"=>$parentid,    
            "objectid" => $objectid,
            "languagecode" => $languagecode,
            "field"=>$field,
            "content"=>$content,
            "createuser" => $username,
            "createdate" => $dt ,
            "modifyuser" => $username,
            "modifydate" => $dt 
        ],
            [
            "id[=]" => $id
        ]);

    }

    function deletetranslationtag($id)
    {

        $this->database->delete("translationtag", [
            "AND" => [
                "id" => $id

            ]

        ]);

    }

    function findtranslationtag($objectcode,$parentid,$objectid,$languagecode, $field)
    {
        $count =  $this->database->count("translationtag", [
           "id"
            
        ],["AND" => [ "objectcode" => $objectcode,
            "parentid"=>$parentid,
            "objectid" => $objectid,
            "languagecode" => $languagecode,
            "field"=>$field]]);
        
        return $count;

    }

    function gettranslationtagbyid($id)
    {

        $data = $this->database->select("translationtag", [
            "id",
            "objectcode",
            "parentid",
            "objectid" ,
            "languagecode",
            "field",
            "content"
        ], [
            "id" => $id
        ]);

        return $data;
    }
    
    function gettranslatecontent($objectcode,$objectid,$languagecode,$field)
    {
         $data = $this->database->select("translationtag", [
            
            "content",
           
        ], [
            "objectcode" => $objectcode,
            "objectid" => $objectid,
            "languagecode" => $languagecode,
            "field"=>$field
            
        ]);
         
       return $data; 
    }  
    
    function getcurricullumtranslate($objectcode,$objectid,$languagecode)
    {
         $sth = $this->database
                 ->pdo
                 ->prepare("SELECT field,content FROM translationtag "
                         . "where objectcode='".$objectcode
                         ."' and objectid ='".$objectid."' and languagecode ='".$languagecode."'");
        $sth->execute();
        return $sth;
        
    }
    function getparent($globalobj,$objectcode,$parentid)
    {
     
    
     if ($objectcode=="pt")
     { 
     $tablename ="project";  
     }
     else
     {
     $tablename ="curricullum";  
     }    
    

if ($objectcode !="cv")
{
 $globalobj->getparentselect('',$parentid,$objectcode,$tablename);   
    
}else
{
echo '<select id="parentid" name="parentid">
                          <option value="-1">No parent needed</option>
                          </select>
          '  ;  
    
}   
    }
    
    function getobject($globalobj,$objectcode,$parentid)
    {
   
     $filterffield="curricullumid";
     switch ($objectcode) {
    
    case "ed":
        $tablename='education';
        $fielddesc ="institution";
        break;
    case "sk":
        $tablename='skill';
        $fielddesc ="skill";
        break;
    case "wo":
       $tablename='work';
       $fielddesc ="company";
        break;
      case "pr":
       $tablename='project';
       $fielddesc ="name";   
        break;
    case "pt":
       $tablename='project_tag';
       $fielddesc ="tagname";
       $filterffield="projectid";
        break;
    }
    
    if ($objectcode=="cv")
    { 
      $globalobj->getcurricullumselect('', $parentid);
    }
    else
    {    
    $globalobj->getselectoptionsbytable($parentid,$tablename,$fielddesc,$filterffield);  
    }    
    }

    function getfields($globalobj,$objectcode,$field)
    {

        $databasename="curricullum";
        $tablename="";
        switch ($objectcode) {
            case "cv":
                $tablename='curricullum';
                break;
            case "ed":
                $tablename='education';
                break;
            case "sk":
                $tablename='skill';
                break;
            case "wo":
                $tablename='work';
                break;
            case "pr":
                $tablename='project';
                break;
            case "pt":
                $tablename='project_tag';

                break;
        }
        $globalobj->gettablefields($databasename, $tablename,$field);
    }
    
    function getfieldsajax($objectcode,$field,$globalobj,$databasename)
    {
       switch ($objectcode) {
    case "cv":
        $tablename='curricullum';
        break;
    case "ed":
        $tablename='education';
        break;
    case "sk":
        $tablename='skill';
        break;
    case "wo":
       $tablename='work';
        break;
      case "pr":
       $tablename='project';
        break;
  case "pt":
       $tablename='project_tag';
       
        break;
   }
    return $globalobj->gettablefields($databasename,$tablename,$field);   
        
    }
    
    
   function getparentajax($objectcode,$globalobj)
   {
   
    if ($objectcode=="pt")
    { 
     $tablename ="project";  
    }
    else
   {
    $tablename ="curricullum";  
    }    
    if ($objectcode !="cv")
   {
   $globalobj->getparentselect('','',$objectcode,$tablename);   
    
   }else
   {
    echo '<select id="parentid" name="parentid" disabled>
                          <option value="-1">No parent needed</option>
                          </select>
          '  ;  
   }   
       
   } 
   
   function getobjectidlistajax($globalobj)
   {
     $globalobj->getcurricullumselect('','');   
   }
   
   function getobjects($objectcode,$parentid,$globalobj)
   {
   $filterffield="curricullumid";
    switch ($objectcode) {
     case "ed":
        $tablename='education';
        $fielddesc ="institution";
        break;
    case "sk":
        $tablename='skill';
        $fielddesc ="skill";
        break;
    case "wo":
       $tablename='work';
       $fielddesc ="company";
        break;
      case "pr":
       $tablename='project';
       $fielddesc ="name";   
        break;
  case "pt":
       $tablename='project_tag';
       $fielddesc ="tagname";
       $filterffield="projectid";
        break;
   }
    $globalobj->getselectoptionsbytable($parentid,$tablename,$fielddesc,$filterffield); 
   }
    
}

?>