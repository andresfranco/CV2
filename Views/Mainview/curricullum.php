<?php
require_once '../../Backend/Controller/TranslationController.php';
require_once '../../Backend/Controller/LanguageController.php';
require_once '../../Backend/Controller/GlobalController.php';
require_once '../../Backend/libraries/medoo.php';

$globalobj= new GlobalController();
$db= new TranslationController();

//Set language
$lang=$globalobj->setlanguage('en');

//Get Curricullum data
$cvid=$globalobj->getcurricullumidbyparam();
$datas =$db->getcurricullumtranslate('CV',$cvid,$lang);
$name="";
$maintext="";
$aboutme ="";
$contactdetails="";
        
foreach($datas as $data)
{
   switch ($data["field"]) {
    case "name":
        $name=html_entity_decode($data["content"]);
        break;
    case "maintext":
        $maintext=html_entity_decode($data["content"]);
        break;
    case "aboutme":
       $aboutme =html_entity_decode($data["content"]);
        break;
      case "contactdetails":
       $contactdetails=html_entity_decode($data["content"]);
        break;
  case "mainskills":
       $mainskills =html_entity_decode($data["content"]);
       
        break;
   
}
}
?>
<!DOCTYPE html>
<!--[if lt IE 8 ]><html class="no-js ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="no-js ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 8)|!(IE)]><!--><html class="no-js" lang="en"> <!--<![endif]-->
<head>
    <?php
    include('headfiles.php');
    ?>
</head>

<body>

<?php
//Header section
include('header.php');
//About Section
include('about.php');
//Resume Section
include('resume.php');

//My projects
include('myprojects.php');
//Contact
include('contact.php');

//Footer
include('footer.php');

include('javascripts.php');

?>

</body>

</html>