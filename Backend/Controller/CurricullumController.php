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

function rendereditview($id,$renderpath)
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
	  $fileerror = 'Invalid file extension.';
	  }
	  //validate file size
	  if ( $size/1024/1024 > 2 ) 
          {
	  $fileerror = 'File size is exceeding maximum allowed size.';
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
     
     //Create the image directory
     try {
      mkdir($dirpath,0777);
    } catch(ErrorException $ex) {
     $uploaderror=$ex->getMessage();
    }
       
    if ($uploaderror=="")
    {
      try 
      {   
      move_uploaded_file($files['profilepicture']['tmp_name'],$dirpath.'/'.$files["profilepicture"]["name"]); 
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
    function quit_special_chars($string)
     {
       $not_allowed= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹");
       $allowed= array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E");
       $text= str_replace($not_allowed, $allowed ,$string);
       return $text;
    }
    
    function validateupdate($name,$files)
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
    function updateitem($username,$id,$name,$maintext,$aboutme,$contactdetails,$mainskills,$files)
    {
        $this->updatecurricullum($id, $username, $name, $maintext, $aboutme, $contactdetails, $mainskills);
        $this->app->response->redirect(
            $this->app->urlFor('curricullumlist'),
            array(
                'newurl' => $this->app->urlFor('newcurricullum'),
                'editurl' => $this->editurl,
                'deleteurl' => $this->deleteurl
            )
        );

    }

        function deleteitem($id)
        {
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

        function insertcurricullum($username,$name ,$maintext,$aboutme,$contactdetails,$mainskills,$picturename)
    {

        $dt = date('Y-m-d H:i:s');
        $this->database->insert("curricullum", 
        [
            "name" => $name,
            "maintext" => $maintext,
            "aboutme" => $aboutme,
            "contactdetails"=>$contactdetails,
            "mainskills"=>$mainskills,
            "profilepicture"=>$picturename,
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
    function updatecurricullum($id,$username,$name,$maintext,$aboutme,$contactdetails,$mainskills)
    {

        $dt = date('Y-m-d H:i:s');
        $this->database->update("curricullum",
            [
             "name" => $name,
             "maintext" => $maintext,
             "aboutme" => $aboutme,
             "contactdetails"=>$contactdetails,
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

}
?>