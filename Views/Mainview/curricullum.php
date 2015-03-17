<?php
require_once '../../Backend/Controller/CurricullumController.php';
require_once '../../Backend/libraries/medoo.php';
$db=new CurricullumController();

$datas=$db->getcurricullumbyid(3);
foreach($datas as $data)
{
    $languagecode=$data["languagecode"];
    $name = $data["name"];
    $maintext =$data["maintext"];
    $aboutme =$data["aboutme"];
    $contactdetails =$data["contactdetails"];
    $mainskills =$data["mainskills"];
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