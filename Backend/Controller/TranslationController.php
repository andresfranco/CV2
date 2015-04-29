<?php
Class TranslationController
{
    private $database;
    private $editurl;
    private $deleteurl;
    private $mainlink;
    private $mainoption;
    public function __construct($app,$medoo) {

        $this->database =$medoo;
        $this->app=$app;
        $this->editurl ='edittranslation';
        $this->deleteurl='viewtranslation';
        $this->mainlink = '/translationlist';
        $this->mainoption ='Translations';

    }
    function rendergridview($renderpath)
    {

        $this->app->render($renderpath,
            array('newurl'=>$this->app->urlFor('newtranslation')
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
        <th>Object Code</th>
        <th>Parent ID</th>
        <th>Object ID</th>
        <th>Language</th>
        <th>Field</th>
        <th>Content</th>
        <th>Actions</th>
        </thead>   
        <tbody>';
        foreach ($result as $row) 
        {
         echo '<tr>';
         echo '<td>'. $row['objectcode'] . '</td>';
         echo '<td>'. $row['parent'] . '</td>';
         echo '<td>'. $row['objectid'] . '</td>';
         echo '<td>'. $row['language'] . '</td>';
         echo '<td>'. $row['field'] . '</td>';
         echo '<td>'. $row['content'] . '</td>';
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
       echo'</tbody></table>';
   }
   
   function buildresponsivegrid($editurl,$deleteurl)
   {
     $result=$this->getall();
     echo'<div id="grids" width="100%">         
       <table id="datagrid" class="table table-striped table-hover dt-responsive" cellspacing="0" width="80%">
        <thead>
            <tr>
             <th>Object Code</th>
             <th>Parent ID</th>
             <th>Object ID</th>
             <th>Language</th>
             <th>Field</th>
             <th>Content</th>
             <th class="nosort">Actions</th>
            </tr>
        </thead>
        <tbody>';
        foreach ($result as $row) 
        {
         echo '<tr>';
         echo '<td>'. $row['objectcode'] . '</td>';
         echo '<td>'. $row['parent'] . '</td>';
         echo '<td>'. $row['objectid'] . '</td>';
         echo '<td>'. $row['language'] . '</td>';
         echo '<td>'. $row['field'] . '</td>';
         echo '<td>'. $row['content'] . '</td>';
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
   
   function rendernewview($objectcode,$parentid,$objectid,$languagecode,$field,$content,$errormessage,$globalobj,$renderpath)
{
    $this->app->render($renderpath,array('listurl'=>$this->app->urlFor('translations')
            ,'selfurl'=>$this->app->urlFor('newtranslation')
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
            ,'db'=>$this));

}

function rendereditview($id,$globalobj,$renderpath)
{

    $datas=$this->gettranslationbyid($id);
    foreach($datas as $data)
    {
        $id =$data["id"];
        $objectcode = $data["objectcode"];
        $parentid =$data["parentid"];
        $objectid =$data["objectid"];
        $languagecode =$data["languagecode"];
        $field =$data["field"];
        $content=$data["content"];
    }
    $updateurl =  str_replace(':id',$id,$this->app->urlFor('updatetranslation'));
    $this->app->render($renderpath,array('id'=>$id
            ,'objectcode'=>$objectcode
            ,'parentid'=>$parentid
            ,'objectid'=>$objectid
            ,'languagecode'=>$languagecode
            ,'field'=>$field
            ,'content'=>$content
            ,'updateurl'=>$updateurl 
            ,'listurl'=>$this->app->urlFor('translations')
            ,'db'=>$this
            ,'globalobj'=>$globalobj
            ,'option'=>$this->mainoption
            ,'route'=>'Edit'
            ,'link'=>$this->mainlink));

}

function renderdeleteview($id,$globalobj,$renderpath)
{
   $datas=$this->gettranslationbyid($id);
    foreach($datas as $data)
    {
        $id =$data["id"];
        $objectcode = $data["objectcode"];
        $parentid =$data["parentid"];
        $objectid =$data["objectid"];
        $languagecode =$data["languagecode"];
        $field =$data["field"];
        $content=$data["content"];
    }
    $deleteurl =  str_replace(':id',$id,$this->app->urlFor('deletetranslation'));
    $this->app->render($renderpath,array('id'=>$id
             ,'objectcode'=>$objectcode
            ,'parentid'=>$parentid
            ,'objectid'=>$objectid
            ,'languagecode'=>$languagecode
            ,'field'=>$field
            ,'content'=>$content
            ,'deleteurl'=>$deleteurl
            ,'listurl'=>$this->app->urlFor('translations')
            ,'globalobj'=>$globalobj
            ,'db'=>$this
            ,'option'=>$this->mainoption
            ,'route'=>'Delete'
            ,'link'=>$this->mainlink));
}



function validateinsert($objectcode, $parentid, $objectid, $languagecode, $field)
    {
        //Validate if exist
        $count =$this->findtranslation($objectcode, $parentid, $objectid, $languagecode, $field);
        $errormessage="";
        if($count>0)
        {
            $errormessage= '<div class="alert alert-error">The translation already exist</div>';

        }
        return $errormessage;
    }


    function addnewitem($username,$objectcode, $parentid, $objectid, $languagecode,$field,$content,$renderpath)
    {
        $errormessage = $this->validateinsert($objectcode, $parentid, $objectid, $languagecode, $field);

        if($errormessage=="")
        {
            $this->inserttranslation($username, $objectcode, $parentid, $objectid, $languagecode, $field, $content);

            $this->app->response->redirect($this->app->urlFor('translations'), array('newurl'=>$this->app->urlFor('newtranslation') ,'editurl'=>$this->editurl,'deleteurl'=>$this->deleteurl));

        }
        else
        {
            $this->app->render($renderpath,array('listurl'=>$this->app->urlFor('translations')
            ,'selfurl'=>$this->app->urlFor('newtranslation')
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
        $this->updatetranslation($id, $username, $objectcode, $parentid, $objectid, $languagecode, $field, $content);
        $this->app->response->redirect(
            $this->app->urlFor('translations'),
            array(
                'newurl' => $this->app->urlFor('newtranslation'),
                'editurl' => $this->editurl,
                'deleteurl' => $this->deleteurl
            )
        );

    }

        function deleteitem($id)
        {
            $this->deletetranslation($id);
            $this->app->response->redirect(
                $this->app->urlFor('translations'),
                array(
                    'newurl' => $this->app->urlFor('newtranslation'),
                    'editurl' => $this->editurl,
                    'deleteurl' => $this->deleteurl
                )
            );
        }   

function inserttranslation($username,$objectcode,$parentid,$objectid,$languagecode,$field,$content)
    {

        $dt = date('Y-m-d H:i:s');
        $this->database->insert("translation", 
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


        $sth = $this->database->pdo->prepare("select t.id,(select valuedesc from multiparam where value =t.objectcode and sysparamid ='3')as objectcode,(CASE objectcode WHEN 'cv' THEN 'No parent' 
 WHEN 'pt' THEN (select name from project where id = t.parentid)
ELSE (select name from curricullum where id =t.parentid) END) as parent ,
(case objectcode 
when 'cv' then (select name from curricullum where id =t.objectid)  
when 'ed' then (select institution from education where id =t.objectid)
when 'sk' then (select skill from skill where id =t.objectid)
when 'wo' then (select company from work where id =t.objectid)
when 'pr' then (select name from project where id =t.objectid)
when 'pt' then (select tagname from project_tag where id =t.objectid)
 END ) as objectid,
(select language from language where code =t.languagecode) as language
,t.field as field
,t.content as content
 from translation t");
        $sth->execute();
        return $sth;


    }
    function updatetranslation($id,$username,$objectcode,$parentid,$objectid,$languagecode,$field,$content)
    {

        $dt = date('Y-m-d H:i:s');
        $this->database->update("translation",
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

    function deletetranslation($id)
    {

        $this->database->delete("translation", [
            "AND" => [
                "id" => $id

            ]

        ]);

    }

    function findtranslation($objectcode,$parentid,$objectid,$languagecode, $field)
    {
        $count =  $this->database->count("translation", [
           "id"
            
        ],["AND" => [ "objectcode" => $objectcode,
            "parentid"=>$parentid,
            "objectid" => $objectid,
            "languagecode" => $languagecode,
            "field"=>$field]]);
        
        return $count;

    }

    function gettranslationbyid($id)
    {

        $data = $this->database->select("translation", [
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
         $data = $this->database->select("translation", [
            
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
                 ->prepare("SELECT field,content FROM translation "
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
      $globalobj->getcurricullumselect('class="form-control"', $parentid);
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
