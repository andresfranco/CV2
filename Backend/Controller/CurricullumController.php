<?php
Class CurricullumController {

    private $database;
    public function __construct() {

        $this->database =new medoo();

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

}
?>