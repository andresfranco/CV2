<?php
Class LanguageController {

    private $database;
    public function __construct() {

        $this->database =new medoo();

    }



function insertlanguage($username,$code ,$language,$redirecturl)
{
  
    $dt = date('Y-m-d H:i:s');
$this->database->insert("language", ["code" => $code,
"language" => $language,
"createuser" => $username,
"createdate" => $dt ,
"modifyuser" => $username,
"modifydate" => $dt ]);

    header('Location: '.$redirecturl);

}

function getall()
{


    $sth = $this->database->pdo->prepare('SELECT * FROM language');
    $sth->execute();
    return $sth;


}
    function updatelanguage($username,$code,$codeold,$language,$redirecturl)
    {
        
        $dt = date('Y-m-d H:i:s');
        $this->database->update("language", ["code" => $code,
            "language" => $language,
            "modifyuser" => $username
             ],[
            "code[=]" => $codeold
        ]);
        
        
       
        header('Location: '.$redirecturl);

    }

function deletelanguage($code)
{

    $this->database->delete("language", [
        "AND" => [
            "code" => $code

	]
]);

}

function findlanguage($code)
{
    $count =  $this->database->count("language", [
"code" => $code
]);
   return $count;
    
}

function getlanguagebyid($code)
{

$data = $this->database->select("language", [
"code",
"language"
], [
"code" => $code
]);
   
return $data;   
}

}
?>