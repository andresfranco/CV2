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
            ,'curricullumobj'=>$this
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
         echo '<td>'. $row['name'] . '</td>';
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
    }
    $updateurl =  str_replace(':id', $id,$this->app->urlFor('updatecurricullum'));
    $this->app->render($renderpath,array('id'=>$id,'name'=>$name
            ,'maintext'=>$maintext
            ,'aboutme'=>$aboutme
            ,'contactdetails'=>$contactdetails
            ,'mainskills'=>$mainskills
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

    function validateinsert($name)
    {
        //Validate if exist
        $count =$this->findcurricullum($name);
        $errormessage="";
        if($count>0)
        {
            $errormessage= '<div class="alert alert-danger col-sms-4 errordiv" role="alert">The curricullum with name : "'.$name. '" already exist</div>';

        }
        return $errormessage;
    }


    function addnewitem($username,$name,$maintext,$aboutme,$contactdetails,$mainskills,$renderpath)
    {
        $errormessage = $this->validateinsert($name);

        if($errormessage=="")
        {
            $this->insertcurricullum($username,$name,$maintext,$aboutme,$contactdetails,$mainskills);

            $this->app->response->redirect($this->app->urlFor('curricullumlist'), array('newurl'=>$this->app->urlFor('newcurricullum') ,'editurl'=>$this->editurl,'deleteurl'=>$this->deleteurl));

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
            ,'option'=>$this->mainoption
            ,'route'=>'New'
            ,'link'=>$this->mainlink));
        }


    }

    function updateitem($username,$id,$name,$maintext,$aboutme,$contactdetails,$mainskills)
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

        function insertcurricullum($username,$name ,$maintext,$aboutme,$contactdetails,$mainskills)
    {

        $dt = date('Y-m-d H:i:s');
        $this->database->insert("curricullum", 
        [
            "name" => $name,
            "maintext" => $maintext,
            "aboutme" => $aboutme,
            "contactdetails"=>$contactdetails,
            "mainskills"=>$mainskills,
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
            "mainskills"
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

}
?>