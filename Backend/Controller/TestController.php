<?php
Class TestController
{
    private $database;
    public function __construct() 
    {
      $this->database =new medoo();

    }
   function getlanguageselect($attribute,$languagecode)
    {
        $sth = $this->database->pdo->prepare('SELECT code ,language FROM language');
        $sth->execute();
         echo '<select id ="languagecode" name="languagecode"'.$attribute.'>';

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

    function getprojectselect($attribute,$projectid)
    {
        $sth = $this->database->pdo->prepare('SELECT id ,name FROM project');
        $sth->execute();
         echo '<select id ="projectid" name="projectid"'.$attribute.'>';
         echo'<option value="0">Please select a Project</option>';
        $selected="";
        foreach ($sth as $row) {
            if ($projectid == $row['id']) {
                $selected = 'selected';
            }
            else
            {$selected="";
            }
            echo '<option value ="'.$row['id'].'" '.$selected.' >'.$row['name'].'</option>';

        }
         echo '</select>';
    } 
     function getcurricullumselect($attribute,$id)
    {

        $sth = $this->database->pdo->prepare('SELECT id ,name FROM curricullum');
        $sth->execute();
         echo '<select id ="objectid" name="objectid" '.$attribute.'>';
         echo'<option value="0">Please select an option</option>';
        $selected="";
        foreach ($sth as $row) {
            if ($row['id'] == $id) {
                $selected = 'selected';
            }
            else
            {$selected="";
            }
            echo '<option value ="'.$row['id'].'" '.$selected.' >'.$row['name'].'</option>';

        }
         echo '</select>';
    } 
       function getcurricullumlist($attribute,$id)
    {

        $sth = $this->database->pdo->prepare('SELECT id ,name FROM curricullum');
        $sth->execute();
         echo '<select id ="curricullumid" name="curricullumid"'.$attribute.'>';
         echo'<option value="0">Please select an option</option>';
        $selected="";
        foreach ($sth as $row) {
            if ($row['id'] == $id) {
                $selected = 'selected';
            }
            else
            {$selected="";
            }
            echo '<option value ="'.$row['id'].'" '.$selected.' >'.$row['name'].'</option>';

        }
         echo '</select>';
    }

   function getobjectcodes($setvalue,$attribute)
    {
       $objectcodes= array("cv"=>"Curricullum", "ed"=>"Education", "sk"=>"Skill", "wo"=>"Work","pr"=>"Project","pt"=>"Proyect Tag");

        echo '<select id ="objectcode" name="objectcode"' .$attribute.'>';
        echo '<option value="0">Please Select a object code</option>';
        $selected="";
        foreach($objectcodes as $key=>$value) {
            if ($setvalue == $key) {
                $selected = 'selected';
            }
            else
            {$selected="";
            }
            echo '<option value ="'.$key.'" '.$selected.' >'.$value.'</option>';

        }
         echo '</select>';
        
    }
    function gettablefields($databasename,$tablename,$field,$attribute)
    {
        $sth = $this->database->pdo->prepare("SELECT COLUMN_NAME as field "
                . "FROM INFORMATION_SCHEMA.COLUMNS "
                . " WHERE TABLE_SCHEMA = '".$databasename."' AND TABLE_NAME ='".$tablename."'"
                . " AND COLUMN_NAME not in ('id','curricullumid','projectid','createdate','createuser','modifydate','modifyuser')");
        $sth->execute();
         echo '<select id="field" name="field" '.$attribute.'>';
         

        foreach ($sth as $row) {
            if ($field == $row['field']) {
                $selected = 'selected';
            }
            else
            {$selected="";
            }
            echo '<option value ="'.$row['field'].'" '.$selected.' >'.$row['field'].'</option>';

        }
         echo '</select>';
        
    }        
    
    function getcurrentuser()
    {
      $username ="admin";
      return $username ; 
    }

    function setlanguage()
    {
        $defaultlang =$this->getsysparam('lang');
        if (!empty($_GET)) {
            $_SESSION['lang'] = $_GET['lang'];
        }
        else
        {
            $_SESSION['lang'] =$defaultlang;
        }
        return $_SESSION['lang'];
    }
  function getsysparam($code)
    {
        $value="";
        $datas = $this->database->select("sysparam",[ "value"], ["code" => $code ]);
      

    }
    

}
