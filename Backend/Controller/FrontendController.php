<?php

class FrontendController {
   
    private $database;
    private $editurl;
    private $deleteurl;
    private $mainlink;
    private $mainoption;
    private $language;
    private $translation;
    private $global;
    private $education;
    private $skill;
    private $project;
    private $projecttag;
    
    public function __construct($app,$medoo,$globalobj,$languageobj,$translationobj,$educationobj,$skillobj,$projectobj,$projecttagobj) {

        $this->database =$medoo;
        $this->app=$app;
        $this->global=$globalobj;
        $this->language=$languageobj;
        $this->translation =$translationobj;
        $this->education =$educationobj;
        $this->skill =$skillobj;
        $this->project =$projectobj;
        $this->projecttag =$projecttagobj;
        $this->editurl ='editcurricullum';
        $this->deleteurl='viewcurricullum';
        $this->mainlink = '/curricullumlist';
        $this->mainoption ='Curricullum';

    } 
    
   function setdefaultlanguage()
   {
     $lang=$this->global->setlanguage('en');
     return $lang;
       
   }
   
   //test functions
   function testcvid()
   {
       $cvid=$this->global->getcurricullumidbyparam();
       return $cvid;
       
   }
   function testgetcurricullumtranslate()
   {
     $datas =$this->translation->getcurricullumtranslate('cv','1','en');  
     $maindata=[];
       foreach($datas as $data)
    {
        switch ($data["field"]) {
      case "name":
        $maindata["name"]=html_entity_decode($data["content"]);
        break;
      case "maintext":
        $maindata["maintext"]=html_entity_decode($data["content"]);
        break;
     case "aboutme":
       $maindata["aboutme"] =html_entity_decode($data["content"]);
        break;
      case "contactdetails":
       $maindata["contactdetails"]=html_entity_decode($data["content"]);
        break;
     case "mainskills":
       $maindata["mainskills"] =html_entity_decode($data["content"]);
       
        break;
   
     }    
   
      
     }
      echo 'name: '. $maindata["name"].'<br>';
      echo 'maintext: '. $maindata["maintext"].'<br>';
      echo 'aboutme: '. $maindata["aboutme"].'<br>';
      echo 'contactdetails: '. $maindata["contactdetails"].'<br>';
      echo 'mainskills: '. $maindata["mainskills"].'<br>';
   }
   
   
   //
   
   function getcurricullumdata($languagecode)
   
   {
    $cvid=$this->global->getcurricullumidbyparam();
     if(empty($languagecode))
      {
         $languagecode =$this->global->getsysparam('lang');  
      }  
    $datas =$this->translation->getcurricullumtranslate('cv',$cvid,$languagecode);
    $maindata[]="";
    $maindata["name"]="";
    $maindata["maintext"]="";
    $maindata["aboutme"] ="";
    $maindata["contactdetails"]="";
    $maindata["mainskills"]="";
    foreach($datas as $data)
    {
      switch ($data["field"]) {
      case "name":
        $maindata["name"]=html_entity_decode($data["content"]);
        break;
      case "maintext":
        $maindata["maintext"]=html_entity_decode($data["content"]);
        break;
     case "aboutme":
       $maindata["aboutme"] =html_entity_decode($data["content"]);
        break;
      case "contactdetails":
       $maindata["contactdetails"]=html_entity_decode($data["content"]);
        break;
     case "mainskills":
       $maindata["mainskills"] =html_entity_decode($data["content"]);
       
        break;
   
     }
    
    }
    return $maindata;
   }
    
    function getheaderlanguages()
    {
        $result=$this->language->getall();
        foreach ($result as $row)
                {
                  echo '<a href="./'.$row['code'].'">'.$row['language'].'</a>|';
                }
    }
    
    function geteducation($languagecode)
    {
      
        
      //"select field,content from translation where objectcode='".ed."' and parentid ='".'1'."'";  
      $cvid=$this->global->getcurricullumidbyparam();
      if(empty($languagecode))
      {
         $languagecode =$this->global->getsysparam('lang');  
      }    
     
    
      // $datas =$this->translation->gettranslatecontent('ed',$cvid,$defaultlang,$field);
        $sth = $this->database->pdo->prepare("select distinct t.objectid 
, (select content from translation 
where objectcode='ed' and parentid ='".$cvid."' 
and languagecode ='".$languagecode."' and field ='institution' 
and objectid =t.objectid) as institution ,
(select content from translation 
where objectcode='ed' and parentid ='".$cvid."' 
and languagecode ='".$languagecode."'  and field ='degree' 
and objectid =t.objectid) as degree,
(select content from translation 
where objectcode='ed' and parentid ='".$cvid."'  
and languagecode ='".$languagecode."'  and field ='datechar' 
and objectid =t.objectid) as datechar 
from
translation t
");
        $sth->execute();
       
       foreach ($sth as $row)
        {   
     
                echo'<div class="row item">
                 <div class="twelve columns">
                 <h3>'.html_entity_decode($row["institution"]).'</h3>
                 <p class="info">'.html_entity_decode($row["degree"]).'<span>&bull;</span> <em class="date">'.html_entity_decode($row["datechar"]).'</em></p>
                </div>
                </div>';   
      }
      
         
        
              
            
            
        
    }
       
   
    
    
    
}


?>