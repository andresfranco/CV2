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
     $maindata=array();
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
   
   function getcurricullumdata($languagecode,$cvid)
   
   {
      
     //$cvid=$this->global->getcurricullumidbyparam();
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
    
    function getheaderlanguages($cvid,$languagecode,$path)
    {
        $result=$this->language->getall();
        foreach ($result as $row)
                {
      $cvid =1;
      
                  echo '<a href="'.$path.'/'.$row['code'].'/'.$cvid.'">'.$this->gettranslation($languagecode,$row['language']).'</a>|';
                }
    }
    
    function geteducation($languagecode,$cvid)
    {
      
        
      //"select field,content from translation where objectcode='".ed."' and parentid ='".'1'."'";  
      //$cvid=$this->global->getcurricullumidbyparam();
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
where objectcode='ed'
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
    
    function getwork($languagecode,$cvid)
    {
       //$cvid=$this->global->getcurricullumidbyparam();
      if(empty($languagecode))
      {
         $languagecode =$this->global->getsysparam('lang');  
      }    
       $sth = $this->database->pdo->prepare("select distinct t.objectid 
        , (select content from translation 
        where objectcode='wo' and parentid ='".$cvid."' 
         and languagecode ='".$languagecode."' and field ='company' 
         and objectid =t.objectid) as company ,
        (select content from translation 
        where objectcode='wo' and parentid ='".$cvid."' 
         and languagecode ='".$languagecode."'  and field ='position' 
         and objectid =t.objectid) as position,
        (select content from translation 
        where objectcode='wo' and parentid ='".$cvid."'  
        and languagecode ='".$languagecode."'  and field ='from' 
        and objectid =t.objectid) as fromdate,
        (select content from translation 
        where objectcode='wo' and parentid ='".$cvid."'  
        and languagecode ='".$languagecode."'  and field ='to' 
        and objectid =t.objectid) as todate
        from
        translation t
        where objectcode='wo'
        "); 
        $sth->execute();
        foreach ($sth as $row)
        {   
       
          echo'<div class="row item">
          <div class="twelve columns">
          <h3>'.html_entity_decode($row["company"]).'</h3>
          <p class="info">'.html_entity_decode($row["position"]).'<span>&bull;</span> <em class="date">'.html_entity_decode($row["fromdate"]).' - '.html_entity_decode($row["todate"]).'</em></p>
          </div>
          </div>';
        }
    }
    
    function getskills($languagecode,$cvid)
    
    {
        // $cvid=$this->global->getcurricullumidbyparam();
      if(empty($languagecode))
      {
         $languagecode =$this->global->getsysparam('lang');  
      }    
       $sth = $this->database->pdo->prepare("select distinct t.objectid 
        , (select content from translation 
        where objectcode='sk' and parentid ='".$cvid."' 
         and languagecode ='".$languagecode."' and field ='type' 
         and objectid =t.objectid) as type ,
        (select content from translation 
        where objectcode='sk' and parentid ='".$cvid."' 
         and languagecode ='".$languagecode."'  and field ='skill' 
         and objectid =t.objectid) as skill,
        (select content from translation 
        where objectcode='sk' and parentid ='".$cvid."'  
        and languagecode ='".$languagecode."'  and field ='percentage' 
        and objectid =t.objectid) as percentage
        from
        translation t
        where objectcode='sk'
        "); 
        $sth->execute();
        
        echo'<div class="bars">'
        . '<ul class="skills">';
        foreach ($sth as $row)
        {   
        
         echo '<li><span class="bar-expand barsize" style ="width:'.$row['percentage'].';"></span><em>'.$row['skill'].'</em></li>';
        }         
                    
        echo '</ul></div>';
    }
       
   
    function getprojects($languagecode,$cvid,$basepath)
    
    {
         //$cvid=$this->global->getcurricullumidbyparam();
      if(empty($languagecode))
      {
         $languagecode =$this->global->getsysparam('lang');  
      }    
       $sth = $this->database->pdo->prepare("select distinct t.objectid 
        , (select content from translation 
        where objectcode='pr' and parentid ='".$cvid."' 
         and languagecode ='".$languagecode."' and field ='name' 
         and objectid =t.objectid) as name ,
        (select content from translation 
        where objectcode='pr' and parentid ='".$cvid."' 
         and languagecode ='".$languagecode."'  and field ='description' 
         and objectid =t.objectid) as description,
        (select content from translation
        where objectcode='pr' and parentid ='".$cvid."'  
        and languagecode ='".$languagecode."'  and field ='link' 
        and objectid =t.objectid) as link,
        (select content from translation
        where objectcode='pr' and parentid ='".$cvid."'  
        and languagecode ='".$languagecode."'  and field ='imagename' 
        and objectid =t.objectid) as imagename
        from
        translation t
        where objectcode='pr'
        "); 
        $sth->execute();
        
        $imagename='condohandler.png';
        $basepath =$basepath.'/images/portfolio/';
        $modalnumber =0;
        foreach ($sth as $row)
        {   
        $modalnumber =$modalnumber+1;
         echo ' <div class="columns portfolio-item">
                    <div class="item-wrap">

                        <a href="#modal-'.$modalnumber.'" title="">
                            <img alt="" src="'.$basepath.html_entity_decode($row['imagename']).'">
                            <div class="overlay">
                                <div class="portfolio-item-meta">
                                    <h5>'.html_entity_decode($row['name']).'</h5>
                                    <p>'.html_entity_decode($row['description']).'</p>
                                </div>
                            </div>
                            <div class="link-icon"><i class="icon-plus"></i></div>
                        </a>

                    </div>
                </div>';
        }         
                    
      
    }
    
    function getprojectmodal($languagecode,$cvid,$basepath)
    
    {
         //$cvid=$this->global->getcurricullumidbyparam();
      if(empty($languagecode))
      {
         $languagecode =$this->global->getsysparam('lang');  
      }    
       $sth = $this->database->pdo->prepare("select distinct t.objectid 
        , (select content from translation 
        where objectcode='pr' and parentid ='".$cvid."' 
         and languagecode ='".$languagecode."' and field ='name' 
         and objectid =t.objectid) as name ,
        (select content from translation 
        where objectcode='pr' and parentid ='".$cvid."' 
         and languagecode ='".$languagecode."'  and field ='description' 
         and objectid =t.objectid) as description,
        (select content from translation
        where objectcode='pr' and parentid ='".$cvid."'  
        and languagecode ='".$languagecode."'  and field ='link' 
        and objectid =t.objectid) as link,
        (select content from translation
        where objectcode='pr' and parentid ='".$cvid."'  
        and languagecode ='".$languagecode."'  and field ='imagename' 
        and objectid =t.objectid) as imagename
        from
        translation t
        where objectcode='pr'
        "); 
        $sth->execute();
        $basepath =$basepath.'/images/portfolio/';
        $modalnumber =0;
        foreach ($sth as $row)
        {   
        $modalnumber =$modalnumber+1;
         echo '<div id="modal-'.$modalnumber.'" class="popup-modal mfp-hide">
               <div class="description-box">
               <h4>'.html_entity_decode($row['name']).'</h4>
               <p>'.html_entity_decode($row['description']).'</p>
               <span class="categories"><i class="fa fa-tag"></i>'.$this->getprojecttagtranslate($languagecode,$row['objectid']).'</span>
               </div>
               <div class="link-box">
               <a href="'.html_entity_decode($row['link']).'">Details</a>
               <a class="popup-modal-dismiss">Close</a>
               </div>
              </div>';
        }         
                    
      
    }
    
    function getprojecttags($projectid)
    {
     $sth = $this->database->pdo->prepare("select tagname from project_tag where projectid ='".$projectid."'"); 
     $sth->execute();
     $projecttags="";
     foreach ($sth as $row)
        {
         $projecttags.= $row['tagname'].',';
        }
    
     return substr($projecttags, 0, -1);   
     }
     
     function getprojecttagtranslate($languagecode,$projectid)
     {
      if(empty($languagecode))
      {
         $languagecode =$this->global->getsysparam('lang');  
      }    
       $sth = $this->database->pdo->prepare("select distinct t.objectid ,
           (CASE t.field WHEN 'tagname' THEN content END) as tagname 
            from translation t
            where t.objectcode ='pt' 
            and t.languagecode ='".$languagecode."'
            and parentid ='".$projectid."'
        ");  
       
       $sth->execute();
         $projecttags="";
         foreach ($sth as $row)
        {
         $projecttags.= $row['tagname'].',';
        }
        return substr($projecttags, 0, -1);
     }
     
     function translatetag($languagecode,$key)
     {
      $translation ="";
     $sth = $this->database->pdo->prepare("select t.translation  "
              . "from translatetag t "
              . "where t.languagecode ='".$languagecode."' "
              . "and t.key ='".$key."'"); 
     $sth->execute();

          foreach ($sth as $row)
        {
         $translation= $row['translation'];
        }

        echo $translation;
     }
     
     
      function gettranslation($languagecode,$key)
     {
      $translation ="";
     $sth = $this->database->pdo->prepare("select t.translation  "
              . "from translatetag t "
              . "where t.languagecode ='".$languagecode."' "
              . "and t.key ='".$key."'"); 
     $sth->execute();

          foreach ($sth as $row)
        {
         $translation= $row['translation'];
        }

       return $translation;
     }
     
     function getsocialnetworklinks()
     {
       
     $sth = $this->database->pdo->prepare("select u.name,u.link from url u where u.type ='socialnetwork' and u.name in ('facebook','twitter','linkedin','github')");
              
     $sth->execute();

          foreach ($sth as $row)
        {
          echo'<li><a href="'.$row['link'].'"><i class="fa fa-'.$row['name'].'"></i></a></li>';
        }  
     }
     
     function getcopyright()
     {
     $sth = $this->database->pdo->prepare("select u.name,u.link from url u where u.type ='copyright'");
              
     $sth->execute();

          foreach ($sth as $row)
        {
          echo $row['link'];
        }  
     }
     
    
}


?>