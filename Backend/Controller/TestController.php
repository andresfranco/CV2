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
        $datas = $this->database->select("sysparam",[
            "value"
        ], [
            "code" => $code
        ]);
        foreach($datas as $data)
        {
            $value= $data["value"];
        }
        return $value;

    }

    function getcurricullumidbyparam()
    {
        $cvid="";
        $cvname=$this->getsysparam('cvname');
        $datas = $this->database->select("curricullum", [
            "id",
        ], [
             "name" => $cvname
        ]);

        foreach($datas as $data)
        {
            $cvid= $data["id"];
        }
        return $cvid;
    }

    function getmultiparambycode($code,$setvalue,$attribute)
    {
        $sth = $this
            ->database
            ->pdo
            ->prepare("select mp.value,mp.valuedesc
                       from sysparam sp inner join multiparam mp
                       on (sp.id =mp.sysparamid)
                       where sp.code ='".$code."'");
        $sth->execute();
        echo '<select id ="objectcode" name="objectcode"' .$attribute.'>';
        echo '<option value="0">Please Select a object code</option>';
        foreach ($sth as $row) {
            if ($setvalue == $row['value']) {
                $attribute = 'selected';
            }
            else
            {
                $attribute="";
            }
            echo '<option value ="'.$row['value'].'" '.$attribute.' >'.$row['valuedesc'].'</option>';

        }
        echo '</select>';



    }
    
    function getmultiparamselect($code,$setvalue,$attribute,$emptyitemtitle,$selectid,$selectname)
    {
        $sth = $this
            ->database
            ->pdo
            ->prepare("select mp.value,mp.valuedesc
                       from sysparam sp inner join multiparam mp
                       on (sp.id =mp.sysparamid)
                       where sp.code ='".$code."'");
        $sth->execute();
        echo '<select id ="'.$selectid.'" name="'.$selectname.'"' .$attribute.'>';
        echo '<option value="0">'.$emptyitemtitle.'</option>';
        foreach ($sth as $row) {
            if ($setvalue == $row['value']) {
                $attribute = 'selected';
            }
            else
            {
                $attribute="";
            }
            echo '<option value ="'.$row['value'].'" '.$attribute.' >'.$row['valuedesc'].'</option>';

        }
        echo '</select>';



    }

  function getparentselect($attribute,$id,$objectcode,$table)
    {
    
       $query ='SELECT id ,name FROM '.$table; 
         
      $sth = $this->database->pdo->prepare($query);
        
        
        $sth->execute();
         echo '<select id ="parentid" name="parentid" '.$attribute.'>';
         echo'<option value="0">Please select a parent</option>';
        $selected="";
        foreach ($sth as $row) {
            if ($id == $row['id']) {
                $selected = 'selected';
            }
            else
            {$selected="";
            }
            echo '<option value ="'.$row['id'].'" '.$selected.' >'.$row['name'].'</option>';

        }
         echo '</select>';
    }
    
    
    function getselectoptionsbytable($cvid,$tablename,$fielddesc,$filterfield,$attribute)
    {
        
        $field =  htmlentities($fielddesc);
        $datas = $this->database->select($tablename, [
            "id",
            $field
        ], [
            $filterfield => $cvid
        ]);
        
         echo '<select id ="objectid" name="objectid" '.$attribute.'>';
         echo'<option value="0">Please select an option</option>';
      
         foreach($datas as $data)
        {
         if ($cvid == $data['id']) {
                $selected = 'selected';
            }
            else
            {
                $selected="";
            }    
            echo '<option value ="'.$data['id'].'" '.$selected.'>'.$data[$field].'</option>';
        }
         echo '</select>';
    }
    
   
     function geteducationselect($attribute,$cvid)
    {
        $sth = $this->database->pdo->prepare("SELECT id ,institution FROM education where curricullumid ='".$cvid."'");
        $sth->execute();
         echo '<select id ="objectid" name="objectid"'.$attribute.'>';
         echo'<option value="0">Please select an option</option>';
        $selected="";
        foreach ($sth as $row) {
            if ($id == $row['id']) {
                $selected = 'selected';
            }
            else
            {$selected="";
            }
            echo '<option value ="'.$row['id'].'" '.$selected.' >'.$row['institution'].'</option>';

        }
         echo '</select>';
    } 
    
   function get_actions_byusername($username)
   {
    $sth = $this->database->pdo->prepare("select distinct a.action as action from roleaction ra inner join action a on (a.id =ra.actionid)"
            . " where ra.roleid in (select ur.roleid from userrole ur  inner join systemuser su  on (ur.systemuserid =su.id) where su.username ='".$username."')"); 
    $sth->execute();
    return $sth;
   }
   function check_action_byusername($username,$actionname)
  
   {
     $has_permission =0;
     $sth =$this->get_actions_byusername($username);
     
     foreach($sth as $row)
     {
      if($actionname ==$row["action"])
      {
       $has_permission =1;
        break;
      }    
     }
     return $has_permission;
         
   }
   
    
    
}
