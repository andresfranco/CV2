<?php
Class CurricullumController {

    private $database;
    public function __construct() {

        $this->database =new medoo();

    }



    function insertcurricullum($username,$languagecode,$name ,$maintext,$aboutme,$contactdetails,$mainskills,$redirecturl)
    {

        $dt = date('Y-m-d H:i:s');
        $this->database->insert("curricullum", ["languagecode" => $languagecode,
            "name" => $name,
            "maintext" => $maintext,
            "aboutme" => $aboutme,
            "contactdetails"=>$contactdetails,
            "mainskills"=>$mainskills,
            "createuser" => $username,
            "createdate" => $dt ,
            "modifyuser" => $username,
            "modifydate" => $dt ]);

        header('Location: '.$redirecturl);

    }

    function getall()
    {


        $sth = $this->database->pdo->prepare('SELECT c.id ,l.code as languagecode,l.language,c.name FROM curricullum c inner join language l on l.code=c.languagecode');
        $sth->execute();
        return $sth;


    }
    function updatecurricullum($id,$username,$languagecode,$name,$maintext,$aboutme,$contactdetails,$mainskills,$redirecturl)
    {

        $dt = date('Y-m-d H:i:s');
        $this->database->update("curricullum",
            ["languagecode" => $languagecode,
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

    function findcurricullum($languagecode,$name)
    {
        $count =  $this->database->count("curricullum", [
            "name" => $name,
            "languagecode"=>$languagecode
        ]);
        return $count;

    }

    function getcurricullumbyid($id)
    {

        $data = $this->database->select("curricullum", [
            "id",
            "languagecode",
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

    function getlanguageselect($readonly,$languagecode)
    {
        $sth = $this->database->pdo->prepare('SELECT code ,language FROM language');
        $sth->execute();
         echo '<select name="languagecode"'.$readonly.'>';

        $selected="";
        foreach ($sth as $row) {
            if ($languagecode == $row['code']) {
                $selected = 'selected';
            }
            else
            {$selected="";
            }
            echo '<option value ="'.$row['code'].'" '.$selected.' >'.$row['language'].'</option>';

        }
         echo '</select>';
    }

}
?>