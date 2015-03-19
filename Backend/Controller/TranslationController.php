<?php
Class TranslationController
{
    private $database;
    public function __construct() {

        $this->database =new medoo();

    }
function inserttranslation($username,$objectcode,$parentid,$objectid,$languagecode,$field,$content,$redirecturl)
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

        header('Location: '.$redirecturl);

    }

    function getall()
    {


        $sth = $this->database->pdo->prepare('SELECT * FROM translation');
        $sth->execute();
        return $sth;


    }
    function updatetranslation($id,$username,$objectcode,$parentid,$objectid,$languagecode,$field,$content,$redirecturl)
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



        header('Location: '.$redirecturl);

    }

    function deletetranslation($id,$redirecturl)
    {

        $this->database->delete("translation", [
            "AND" => [
                "id" => $id

            ]

        ]);

        header('Location: '.$redirecturl);
    }

    function findtranslation($objectcode,$parentid,$objectid,$languagecode,$field)
    {
        $count =  $this->database->count("translation", [
            "objectcode" => $objectcode,
            "parentid"=>$parentid,
            "objectid" => $objectid,
            "languagecode" => $languagecode,
            "field"=>$field
            
        ]);
        return $count;

    }

    function gettranslationbyid($id)
    {

        $data = $this->database->select("translation", [
            "id",
            "objectcode",
            "parentid",
            "objectid" ,
            "languagecode" ,
            "field",
            "content",
            "createuser" ,
            "createdate" ,
            "modifyuser",
            "modifydate" 
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
                          <option value="0">No parent needed</option>
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
      $globalobj->getcurricullumselect('', '');  
    }
    else
    {    
    $globalobj->getselectoptionsbytable($parentid,$tablename,$fielddesc,$filterffield);  
    }    
    }
    
            
}
?>
