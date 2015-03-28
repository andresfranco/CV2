<?php
Class CurricullumController {

    private $database;
    private $editurl;
    private $deleteurl;
    public function __construct($app,$medoo) {

        $this->database =$medoo;
        $this->app=$app;
        $this->editurl ='editcurricullum';
        $this->deleteurl='viewcurricullum';

    }

    function rendergridview($renderpath)
   {

    $this->app->render($renderpath,
        array('newurl'=>$this->app->urlFor('newcurricullum'),'editurl'=>$this->editurl,'deleteurl'=>$this->deleteurl,'curricullumobj'=>$this));

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
   
   function rendernewview($name,$maintext,$aboutme,$contactdetails,$mainskills,$errormessage,$renderpath)
{
    $this->app->render($renderpath,array('listurl'=>$this->app->urlFor('curricullumlist')
            ,'selfurl'=>$this->app->urlFor('newcurricullum')
            ,'name'=>$name
            ,'maintext'=>$maintext
            ,'aboutme'=>$aboutme
            ,'contactdetails'=>$contactdetails
            ,'mainskills'=>$mainskills
            ,'errormessage'=>$errormessage));

}

function rendereditview($id,$renderpath)
{

    $datas=$this->getcurricullumbyid($id);
    foreach($datas as $data)
    {
        $name = $data["name"];
        $maintext =$data["maintext"];
        $aboutme =$data["aboutme"];
        $contactdetails =$data["contactdetails"];
        $mainskills =$data["mainskills"];
    }
    $this->app->render($renderpath,array('name'=>$name 
            ,'maintext'=>$maintext
            ,'aboutme'=>$aboutme
            ,'contactdetails'=>$contactdetails
            ,'mainskills'=>$mainskills
            ,'updateurl'=>$this->app->urlFor('updatecurricullum')
            ,'listurl'=>$this->app->urlFor('curricullumlist')));

}

function renderdeleteview($id,$renderpath)
{
    $datas=$this->getcurricullumbyid($id);
    foreach($datas as $data)
    {
        $name = $data["name"];
        $maintext =$data["maintext"];
        $aboutme =$data["aboutme"];
        $contactdetails =$data["contactdetails"];
        $mainskills =$data["mainskills"];
    }
    $this->app->render($renderpath,array('name'=>$name 
            ,'maintext'=>$maintext
            ,'aboutme'=>$aboutme
            ,'contactdetails'=>$contactdetails
            ,'mainskills'=>$mainskills
            ,'deleteurl'=>$this->app->urlFor('deletecurricullum')
            ,'listurl'=>$this->app->urlFor('curricullumlist')));
}
   

    function insertcurricullum($username,$name ,$maintext,$aboutme,$contactdetails,$mainskills,$redirecturl)
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

        header('Location: '.$redirecturl);

    }

    function getall()
    {


        $sth = $this->database->pdo->prepare('SELECT * FROM curricullum');
        $sth->execute();
        return $sth;

    }
    function updatecurricullum($id,$username,$name,$maintext,$aboutme,$contactdetails,$mainskills,$redirecturl)
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



        header('Location: '.$redirecturl);

    }

    function deletecurricullum($id,$redirecturl)
    {

        $this->database->delete("curricullum", [
            "AND" => [
                "id" => $id

            ]

        ]);

        header('Location: '.$redirecturl);
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

    function getcurricullumidbyname($name)
    {
        $id="";
        $datas = $this->database->select("curricullum", [
            "id",
        ], [
            "name" => htmlEntities($name)
        ]);
        foreach($datas as $data)
        {
            $id= $data["id"];
        }
        return $id;



    }

}
?>