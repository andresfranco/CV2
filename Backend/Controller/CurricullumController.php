<?php
Class CurricullumController {

    private $database;
    private $editurl;
    private $deleteurl;
     private $mainlink;
    private $mainoption;
    public function __construct($app,$medoo) {

        $this->database =$medoo;
        $this->app=$app;
        $this->editurl ='editcurricullum';
        $this->deleteurl='viewcurricullum';
        $this->mainlink = '/curricullumlist';
        $this->mainoption ='Curricullum';

    }

    function rendergridview($renderpath)
   {

    $this->app->render($renderpath,
        array('newurl'=>$this->app->urlFor('newcurricullum')
            ,'editurl'=>$this->editurl
            ,'deleteurl'=>$this->deleteurl
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
        <tr>   
        <th>Name</th>
        <th>Actions</th>
        </tr> 
        </thead>   
        <tbody>';
        foreach ($result as $row) 
        {
         echo '<tr>';
         echo '<td>'. $row['name'] . '</td>';
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
                <th>Name</th>
                <th class="nosort">Actions</th>
            </tr>
        </thead>
        <tbody>';
        foreach ($result as $row) 
        {
         echo '<tr>';
         echo '<td class="typename">'. $row['name'] . '</td>';
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
   
   function rendernewview($name,$maintext,$aboutme,$contactdetails,$mainskills,$errormessage,$renderpath)
{
    $this->app->render($renderpath,array('listurl'=>$this->app->urlFor('curricullumlist')
            ,'selfurl'=>$this->app->urlFor('newcurricullum')
            ,'name'=>$name
            ,'maintext'=>$maintext
            ,'aboutme'=>$aboutme
            ,'contactdetails'=>$contactdetails
            ,'mainskills'=>$mainskills
            ,'errormessage'=>$errormessage
            ,'picturename' =>''
            ,'option'=>$this->mainoption
            ,'route'=>'New'
            ,'link'=>$this->mainlink));

}

function rendereditview($id,$errormessage,$renderpath)
{

    $datas=$this->getcurricullumbyid($id);
    foreach($datas as $data)
    {
        $id=$data["id"];
        $name = $data["name"];
        $maintext =$data["maintext"];
        $aboutme =$data["aboutme"];
        $contactdetails =$data["contactdetails"];
        $mainskills =$data["mainskills"];
        $picturename=$data["profilepicture"];
    }
    $updateurl =  str_replace(':id', $id,$this->app->urlFor('updatecurricullum'));
    $this->app->render($renderpath,array('id'=>$id,'name'=>$name
            ,'maintext'=>$maintext
            ,'aboutme'=>$aboutme
            ,'contactdetails'=>$contactdetails
            ,'mainskills'=>$mainskills
            ,'picturename'=>$picturename
            ,'obj'=>$this
            ,'errormessage'=>$errormessage
            ,'updateurl'=>$updateurl
            ,'listurl'=>$this->app->urlFor('curricullumlist')
            ,'option'=>$this->mainoption
            ,'route'=>'Edit'
            ,'link'=>$this->mainlink));

}

function renderdeleteview($id,$renderpath)
{
    $datas=$this->getcurricullumbyid($id);
    foreach($datas as $data)
    {
        $id =$data["id"];
        $name = $data["name"];
        $maintext =$data["maintext"];
        $aboutme =$data["aboutme"];
        $contactdetails =$data["contactdetails"];
        $mainskills =$data["mainskills"];
    }
    $deleteurl=  str_replace(':id', $id,$this->app->urlFor('deletecurricullum'));
    $this->app->render($renderpath,array('id'=>$id,'name'=>$name
            ,'maintext'=>$maintext
            ,'aboutme'=>$aboutme
            ,'contactdetails'=>$contactdetails
            ,'mainskills'=>$mainskills
            ,'deleteurl'=>$deleteurl
            ,'listurl'=>$this->app->urlFor('curricullumlist')
            ,'option'=>$this->mainoption
            ,'route'=>'Delete'
            ,'link'=>$this->mainlink));
}

    function validateinsert($name,$files)
    {
        
        //Validate if exist
        $count =$this->findcurricullum($name);
        $errormessage="";
        if($count>0)
        {
            $errormessage= '<div class="alert alert-danger col-sms-4 errordiv" role="alert"><i class="fa fa-warning"></i>The curricullum with name  " ' .$name. ' " already exist</div>';

        }
        else
        {
            
            if($this->check_picture_file($files)!="")
            {    
             $errormessage='<div class="alert alert-danger col-sms-4 errordiv" role="alert"><i class="fa fa-warning"></i>'.$this->check_picture_file($files).'</div>'; 
            }
            else
            {
              $cvname =$this->quit_special_chars($name);
              $dirpath ='images/'.'profilepic_'.$cvname; 
              if($this->upload_picture($files,$dirpath)!="")
              {        
              $errormessage='<div class="alert alert-danger col-sms-4 errordiv" role="alert"><i class="fa fa-warning"></i>'.$this->upload_picture($files,$dirpath).'</div>'; 
              }
            }    
        
        
          return $errormessage;
       } 
    }

    
    
    
    function addnewitem($username,$name,$maintext,$aboutme,$contactdetails,$mainskills,$files,$renderpath)
    {
        $errormessage = $this->validateinsert($name,$files);
        $picturename=$files['profilepicture']['name']; 
        if($errormessage=="")
        {   
              
            $this->insertcurricullum($username,$name,$maintext,$aboutme,$contactdetails,$mainskills,$picturename);
            $this->app->response->redirect($this->app->urlFor('curricullumlist')); 
                
            
            
        }
        else
        {
            $this->app->render($renderpath,array('listurl'=>$this->app->urlFor('curricullumlist')
            ,'selfurl'=>$this->app->urlFor('newcurricullum')
            ,'name'=>$name
            ,'maintext'=>$maintext
            ,'aboutme'=>$aboutme
            ,'contactdetails'=>$contactdetails
            ,'mainskills'=>$mainskills
            ,'errormessage'=>$errormessage
            ,'picturename' =>$picturename       
            ,'option'=>$this->mainoption
            ,'route'=>'New'
            ,'link'=>$this->mainlink));
        }


    }
    
    function check_picture_file($files)
    {
     
        $picturename     = $files['profilepicture']['name'];
	$tmpName  = $files['profilepicture']['tmp_name'];
	$error    = $files['profilepicture']['error'];
	$size     = $files['profilepicture']['size'];
        $ext	  = strtolower(pathinfo($picturename, PATHINFO_EXTENSION));
        $fileerror="";
        if ($error ==0)
        {
         if ( !in_array($ext, array('jpg','jpeg','png','gif')) )
          {
	  $fileerror = 'Invalid file extension. ( valid extensions: jpg,jpeg,png,gif )';
	  }
	  //validate file size
	  if ( $size/1024/1024 > 2 ) 
          {
	  $fileerror = 'File size is exceeding maximum allowed size. ( Maximum file size is 2 Mb )';
	   } 
        }
        else
        {
         $fileerror ="Upload File Error ".$error;   
        }    
            
            
        return $fileerror;
    }
    
    function upload_picture($files,$dirpath)
    {
     $uploaderror="";
       
    $this->remove_directory($dirpath);
     
     //Create the image directory
     try {
      mkdir($dirpath,0777,true);
      chmod($dirpath, 0777);
      
    } catch(ErrorException $ex) {
     $uploaderror=$ex->getMessage();
    }
       
    if ($uploaderror=="")
    {
      try 
      {   
      move_uploaded_file($files['profilepicture']['tmp_name'],$dirpath.'/'.$files["profilepicture"]["name"]);
      chmod($files["profilepicture"]["name"], 0777);
      }
      catch(ErrorException $ex) 
      {
      $uploaderror= $ex->getMessage();
          
      }
    }
        
     //$targetPath =  $dirpath. DIRECTORY_SEPARATOR.$picturename;
    
       return $uploaderror;      
    //}
    }
    
    function remove_directory($dirpath)
    {
      //Create curricullum directory
     if(file_exists( $dirpath))
     {
        //Remove files
        // Open the directory
        $dirHandle = opendir($dirpath);
        // Loop over all of the files in the folder
        while ($file = readdir($dirHandle)) {
            // If $file is NOT a directory remove it
            if(!is_dir($file)) {
                unlink ($dirpath.'/'.$file); // unlink() deletes the files
            }
        }
        // Close the directory
        closedir($dirHandle);
        //Remove the directory
        rmdir($dirpath); 
     }   
        
    }
    
    function quit_special_chars($string)
     {
       $not_allowed= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹");
       $allowed= array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E");
       $text= str_replace($not_allowed, $allowed ,$string);
       $text = preg_replace('/\s+/','', $text);
       return $text;
       
    }
    
    function validateupdate($name,$files)
    {
           $errormessage="";
            if($this->check_picture_file($files)!="")
            {    
             $errormessage='<div class="alert alert-danger col-sms-4 errordiv" role="alert"><i class="fa fa-warning"></i>'.$this->check_picture_file($files).'</div>'; 
            }
            else
            {
              $cvname =$this->quit_special_chars($name);
              $dirpath ='images/'.'profilepic_'.$cvname; 
              if($this->upload_picture($files,$dirpath)!="")
              {        
              $errormessage='<div class="alert alert-danger col-sms-4 errordiv" role="alert"><i class="fa fa-warning"></i>'.$this->upload_picture($files,$dirpath).'</div>'; 
              }
            }    
        
        
          return $errormessage;
        
    }
    function updateitem($username,$id,$name,$maintext,$aboutme,$contactdetails,$mainskills,$files)
    {
        $picturename=$files['profilepicture']['name']; 
        $errormessage = $this->validateupdate($name,$files);
        if ($errormessage=="")
        {    
        
        $this->updatecurricullum($id, $username, $name, $maintext, $aboutme, $contactdetails, $mainskills,$picturename);
        $this->app->response->redirect(
            $this->app->urlFor('curricullumlist'),
            array(
                'newurl' => $this->app->urlFor('newcurricullum'),
                'editurl' => $this->editurl,
                'deleteurl' => $this->deleteurl
            )
        );
        }
        else
        {
           $this->rendereditview($id,$errormessage,'Views/Curricullum/curricullumedit.html.twig'); 
        }   
    }

        function deleteitem($id,$dirpath)
        {
            $this->remove_directory($dirpath);
            $this->deletecurricullum($id);
            $this->app->response->redirect(
                $this->app->urlFor('curricullumlist'),
                array(
                    'newurl' => $this->app->urlFor('newcurricullum'),
                    'editurl' => $this->editurl,
                    'deleteurl' => $this->deleteurl
                )
            );
        }

        function insertcurricullum($username,$name ,$maintext,$aboutme,$contactdetails,$mainskills,$profilepicture)
    {

        $dt = date('Y-m-d H:i:s');
        $this->database->insert("curricullum", 
        [
            "name" => $name,
            "maintext" => $maintext,
            "aboutme" => $aboutme,
            "contactdetails"=>$contactdetails,
            "mainskills"=>$mainskills,
            "profilepicture"=>$profilepicture,
            "createuser" => $username,
            "createdate" => $dt ,
            "modifyuser" => $username,
            "modifydate" => $dt 
         ]);



    }

    function getall()
    {


        $sth = $this->database->pdo->prepare('SELECT * FROM curricullum');
        $sth->execute();
        return $sth;

    }
    function updatecurricullum($id,$username,$name,$maintext,$aboutme,$contactdetails,$mainskills,$profilepicture)
    {

        $dt = date('Y-m-d H:i:s');
        $this->database->update("curricullum",
            [
             "name" => $name,
             "maintext" => $maintext,
             "aboutme" => $aboutme,
             "contactdetails"=>$contactdetails,
             "profilepicture"=>$profilepicture,   
             "mainskills"=>$mainskills,
             "modifyuser"=>$username,
             "modifydate"=>$dt
        ],
            [
            "id[=]" => $id
        ]);



    }

    function deletecurricullum($id)
    {

        $this->database->delete("curricullum", [
            "AND" => [
                "id" => $id

            ]

        ]);


    }

    function findcurricullum($name)
    {
        $count =  $this->database->count("curricullum", [
            "name" => $name
            
        ]);
        return $count;

    }

    function getcurricullumbyid($id)
    {

        $data = $this->database->select("curricullum", [
            "id",
            "name",
            "maintext",
            "aboutme",
            "contactdetails",
            "mainskills",
            "profilepicture"
        ], [
            "id" => $id
        ]);

        return $data;

    }
    

    function getcvnamebyid($id)
    {
        $name="";
        $datas = $this->database->select("curricullum", [
            "name",
        ], [
            "id" => $id
        ]);
        foreach($datas as $data)
        {
            $name= $data["name"];
        }
        return $name;



    }
    
    function get_profile_picture($cvid)
    {
     $profilepicture="";
        $datas = $this->database->select("curricullum", [
            "profilepicture",
        ], [
            "id" => $cvid
        ]);
        foreach($datas as $data)
        {
            $profilepicture= $data["profilepicture"];
        }
        return $profilepicture;   
    }
    
    //Upload Curricullum file
    
    
    function build_files_grid($cvid,$editurl,$deleteurl)
    {
      $result=$this->getallfiles($cvid);
     echo'<div id="grids" width="100%">         
       <table id="datagrid" class="table table-striped table-hover dt-responsive" cellspacing="0" width="80%">
        <thead>
            <tr>
                <th>Curricullum</th>
                <th>Language</th>
                <th>File Name</th>
                <th class="nosort">Actions</th>
            </tr>
        </thead>
        <tbody>';
        foreach ($result as $row) 
        {
         echo '<tr>';
         echo '<td class="typename">'. $row['name'] . '</td>';
         echo '<td class="typename">'. $row['language'] . '</td>';
         echo '<td class="typename">'. $row['filename'] . '</td>';
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
    
    function render_gridfiles_view($cvid,$renderpath)
    {
      $cvname = $this->getcvnamebyid($cvid); 
      $newurl =str_replace(':id',$cvid,$this->app->urlFor('newcurricullumfile') );
      $editurl =$this->app->view->getData('basepath').'/editcurricullumfile';
      $deleteurl = $this->app->view->getData('basepath').'/viewcurricullumfile';
      $listurl = str_replace(':id',$cvid,$this->app->urlFor('curricullumfiles'));
      $linkroute =str_replace(':id',$cvid,$this->app->urlFor('editcurricullum') ); 
     $this->app->render($renderpath,
        array('newurl'=>$newurl
            ,'editurl'=>$editurl
            ,'cvid'=>$cvid 
            ,'deleteurl'=>$deleteurl
            ,'obj'=>$this
            ,'option'=>'Curricullum Files'
            ,'link'=>$listurl
            ,'route'=>$cvname
            ,'linkroute'=>$linkroute));   
    }
    
    function render_new_curricullumfile($cvid,$globalobj,$renderpath)
    {
        $cvname = $this->getcvnamebyid($cvid);
        $selfurl =  str_replace(':id',$cvid,$this->app->urlFor('newcurricullumfile') );
        $listurl = str_replace(':id',$cvid,$this->app->urlFor('curricullumfiles'));
        $this->app->render($renderpath,array('id'=>$cvid,'cvname'=>$cvname
            
            ,'globalobj'=>$globalobj
            ,'errormessage'=>''
            ,'selfurl'=>$selfurl
            ,'listurl'=>$listurl 
            ,'option'=>$this->mainoption
            ,'option'=>'Curricullum Files'
            ,'link'=>$listurl
            ,'route'=>'New'
            ,'linkroute'=>''));
        
    }
    
    function render_delete_curricullumfile($id,$globalobj,$renderpath)
    {
        $datas =$this->get_cvfile_byid($id);
        foreach($datas as $data)
        {
          $cvid =$data["curricullumid"];
          $languagecode =$data["languagecode"];
          $filename =$data["filename"];
          
        } 
        
        $cvname = $this->getcvnamebyid($cvid);
        $deleteurl=  str_replace(':id',$id,$this->app->urlFor('deletecurricullumfile') );
        $listurl = str_replace(':id',$cvid,$this->app->urlFor('curricullumfiles'));
        $this->app->render($renderpath,array('id'=>$cvid,'cvname'=>$cvname
            ,'languagecode'=>$languagecode
            ,'globalobj'=>$globalobj
            ,'filename'=>$filename
            ,'deleteurl'=>$deleteurl
            ,'listurl'=>$listurl 
            ,'option'=>'Curricullum Files'
            ,'route'=>'Delete'
            ,'link'=>$listurl));
        
    }
     function render_edit_curricullumfile($id,$globalobj,$renderpath)
    {
        $datas =$this->get_cvfile_byid($id);
        foreach($datas as $data)
        {
          $cvid =$data["curricullumid"];
          $languagecode =$data["languagecode"];
          $filename =$data["filename"];
        
        }        
         
        $cvname = $this->getcvnamebyid($cvid);
        $updateurl =  str_replace(':id',$id,$this->app->urlFor('updatecurricullumfile') );
        $listurl = str_replace(':id',$cvid,$this->app->urlFor('curricullumfiles'));
        $this->app->render($renderpath,array('id'=>$cvid,'cvname'=>$cvname
            
            ,'globalobj'=>$globalobj
            ,'languagecode'=>$languagecode    
            ,'errormessage'=>''
            ,'filename'=>$filename  
            ,'updateurl'=>$updateurl
            ,'listurl'=>$listurl
            ,'option'=>'Curriculum Files'
            ,'route'=>'Edit'
            ,'link'=>$listurl));
        
    }
    
    function getallfiles($cvid)
    {
       $sth = $this->database->pdo->prepare("SELECT cf.id,cf.filename,cf.languagecode,l.language,c.name FROM cvfile cf"
               . " inner join language l on (cf.languagecode =l.code)"
               . " inner join curricullum c on(cf.curricullumid =c.id)"
               . " where curricullumid ='".$cvid."'");
        $sth->execute();
        return $sth;  
    }
    
     function check_file($files)
    {
     
        $filename     = $files['curricullumfile']['name'];
	$tmpName  = $files['curricullumfile']['tmp_name'];
	$error    = $files['curricullumfile']['error'];
	$size     = $files['curricullumfile']['size'];
        $ext	  = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $fileerror="";
        if ($error ==0)
        {
         if ( !in_array($ext, array('doc','docx','odt','pdf')) )
          {
	  $fileerror = 'Invalid file extension ( valid extensions: doc,docx,odt,pdf )';
	  }
	  //validate file size
	  if ( $size/1024/1024 > 4 ) 
          {
	  $fileerror = 'File size is exceeding maximum allowed size. ( Maximum file size is 4 Mb )';
	   } 
        }
        else
        {
         $fileerror ="Upload File Error ".$error;   
        }    
            
            
        return $fileerror;
    }
    
    function uploadcv($files,$dirpath)
    {
      $errormessage=$this->check_file($files);
      if ($errormessage =="")
      {
          
          $errormessage=$this->upload_file($files,$dirpath);
      }
         
       return $errormessage;       
        
    }
    
    function upload_file($files,$dirpath)
    {
     
     $uploaderror="";
       
    $this->remove_directory($dirpath);
     
     //Create the image directory
     try {
      mkdir($dirpath,0777,true);
      chmod($dirpath, 0777);
    } catch(ErrorException $ex) {
     $uploaderror=$ex->getMessage();
    }
       
    if ($uploaderror=="")
    {
      try 
      {   
      move_uploaded_file($files['curricullumfile']['tmp_name'],$dirpath.'/'.$files["curricullumfile"]["name"]);
      chmod($dirpath.'/'.$files["curricullumfile"]["name"], 0777); 
      }
      catch(ErrorException $ex) 
      {
      $uploaderror= $ex->getMessage();
          
      }
    }
    
       return $uploaderror;      
    }  
    
    function validate_insert_cvfile($curricullumid,$languagecode,$files,$filepath)
    {
     $errormessage="";
     $count = $this->find_cvfile($curricullumid, $languagecode);
     if ($count >0)
     {
      $errormessage=" A file for this curricullum and language already exist. ";     
     }
     else
     {
        $errormessage = $this->uploadcv($files,$filepath); 
     }    
     
     return $errormessage;
    }
    
    
    
    function add_new_cvfile($username,$cvid,$files,$languagecode,$globalobj,$renderpath)
    {
        $cvname =$this->quit_special_chars($this->getcvnamebyid($cvid)); 
        $filepath ='files/'.$cvname.'_'.$languagecode;
        $filename =$files["curricullumfile"]["name"]; 
        $errormessage =$this->validate_insert_cvfile($cvid,$languagecode,$files,$filepath);
        
        $listurl = str_replace(':id',$cvid,$this->app->urlFor('curricullumfiles'));
       if ($errormessage =="")
      {
           
          $this->insert_file($username,$cvid,$languagecode,$filename,$filepath);
          $this->app->response->redirect($listurl); 
      }
      else 
      {
         
         $selfurl = str_replace(':id',$cvid,$this->app->urlFor('newcurricullumfile'));
         $this->app->render($renderpath ,array('globalobj'=>$globalobj
            ,'errormessage'=>''
            ,'cvname'=>$cvname
            ,'languagecode'=>$languagecode 
            ,'errormessage'=>$this->format_error($errormessage)
            ,'filename'=>$filename 
            ,'selfurl'=>$selfurl
            ,'listurl'=>$listurl 
            ,'option'=>$this->mainoption
            ,'option'=>'Curricullum Files'
            ,'link'=>$listurl
            ,'route'=>'New'
            ,'linkroute'=>''));  
      }
    }
    
    function format_error($errormessage)
    {
     $errormessage = '<div class="alert alert-danger col-sms-4 errordiv" role="alert"><i class="fa fa-warning"></i> '.$errormessage.'</div>';
     return $errormessage;
    }
   
    function update_cv_file($username,$id,$cvname,$files,$languagecode,$globalobj)
    {
         $datas=$this->get_cvfile_byid($id);
         foreach($datas as $data) 
         {
            $cvid =$data["curricullumid"]; 
         }
         $filename = $files["curricullumfile"]["name"];
         $filepath ='files/'.$this->quit_special_chars($cvname).'_'.$languagecode;
         $errormessage = $this->validate_update_cvfile($id,$cvid,$languagecode,$files,$filepath);
        
         if ($errormessage =="")
         {
             
          $listurl = str_replace(':id',$cvid,$this->app->urlFor('curricullumfiles'));
          
          $this->update_file($username,$id,$languagecode,$filename,$filepath);
          $this->app->response->redirect($listurl);
          
         }
         
         
         else
         {
        $cvname = $this->getcvnamebyid($cvid);
        $updateurl =  str_replace(':id',$id,$this->app->urlFor('updatecurricullumfile') );
        $listurl = str_replace(':id',$cvid,$this->app->urlFor('curricullumfiles'));
        $this->app->render('Views/Curricullum/editcurricullumfile.html.twig',array('id'=>$cvid,'cvname'=>$cvname
            
            ,'globalobj'=>$globalobj
            ,'languagecode'=>$languagecode    
            ,'errormessage'=>$this->format_error($errormessage)
            ,'filename'=>$filename
            ,'updateurl'=>$updateurl
            ,'listurl'=>$listurl
            ,'option'=>'Curriculum Files'
            ,'route'=>'Edit'
            ,'link'=>$listurl));  
         }    
         
    }
    
    function validate_update_cvfile($id,$cvid,$languagecode,$files,$filepath)
    {
        $errormessage = $this->uploadcv($files,$filepath); 
        if ($errormessage=="")
        {
          $count = $this->find_cvfile_exist($id,$cvid, $languagecode);
          if ($count >0)
          {
            $errormessage ="A file for this curricullum and language arleady exist";  
          }
          
        }    
         return $errormessage;
    }
    
    function delete_cv_file($id)
    {
        
        
        $datas=$this->get_cvfile_byid($id);
         foreach($datas as $data) 
         {
            $cvid =$data["curricullumid"];
            $languagecode =$data["languagecode"];
         }
        $cvname =$this->getcvnamebyid($cvid); 
        $dirpath ='files/'.$this->quit_special_chars($cvname).'_'.$languagecode;
        $listurl = str_replace(':id',$cvid,$this->app->urlFor('curricullumfiles'));
        $this->remove_directory($dirpath);
        $this->delete_file($id);
        $this->app->response->redirect($listurl);
    }
    
     function insert_file($username,$curricullumid,$languagecode,$filename,$filepath)
    {
       $dt = date('Y-m-d H:i:s');
        $this->database->insert("cvfile", 
        [
            "curricullumid" => $curricullumid
            ,"languagecode" =>$languagecode   
            ,"filename" => $filename
            ,"filepath" => $filepath
            ,"createuser" => $username
            ,"createdate" => $dt
            ,"modifyuser" => $username
            ,"modifydate" => $dt 
         ]);  
    }
    function update_file($username,$id,$languagecode,$filename,$filepath)
    {
        $dt = date('Y-m-d H:i:s');
        $this->database->update("cvfile",
            [
             "languagecode"=>$languagecode,   
             "filename" => $filename,
             "filepath" => $filepath,
             "modifyuser"=>$username,
             "modifydate"=>$dt
        ],
            [
            "id[=]" => $id
        ]);  
        
        //var_dump($this->database->log());
    }
    
    function delete_file($id)
    {
       $this->database->delete("cvfile", [
            "AND" => [
                "id" => $id

            ]

        ]);  
    }
    
    function find_cvfile($curricullumid,$languagecode)
    {
     
        $count =  $this->database->count("cvfile", [
            "id" 
            
        ],["AND"=>["curricullumid"=>$curricullumid,"languagecode"=>$languagecode]]);
        return $count;

       
    }
    
    
    function find_cvfile_exist($id,$curricullumid,$languagecode)
    {
     
        $count =  $this->database->count("cvfile", [
            "id" 
            
        ],["AND"=>["curricullumid"=>$curricullumid,"languagecode"=>$languagecode ,"id[!]"=>$id]]);
        return $count;

       
    }
    
    
   
    function get_cvfile_byid($id)
    {

        $data = $this->database->select("cvfile", [
            "id",
            "curricullumid",
            "languagecode",
            "filename",
            "filepath"
           
        ], [
            "id" => $id
        ]);

        return $data;

    }
    
    function get_cvfilename_by_lang_cvid($cvid,$languagecode)
    {

        $datas = $this->database->select("cvfile", [
            "id",
            "curricullumid",
            "languagecode",
            "filename",
            "filepath"
           
        ], ["AND"=>[
            "curricullumid" => $cvid,
            "languagecode"=>$languagecode
        ]]);

        
     $filename ="";
    foreach ($datas as $data)
    {
     $filename = $data["filename"];   
    } 
      return $filename; 
        
    }
}
?>