<?php
//---------------Require Classes----------------------------
require 'Slim/Slim.php';
require_once 'Slim/View.php';
require 'Views/Twig.php';
require_once 'Backend/libraries/PHPMailer/class.phpmailer.php';
require_once 'Backend/libraries/PHPMailer/PHPMailerAutoload.php';
require_once 'Backend/libraries/medoo.php';
require_once 'Backend/Controller/GlobalController.php';
require_once 'Backend/Controller/LanguageController.php';
require_once 'Backend/Controller/CurricullumController.php';
require_once 'Backend/Controller/TranslationController.php';
require_once 'Backend/Controller/EducationController.php';
require_once 'Backend/Controller/WorkController.php';
require_once 'Backend/Controller/SecurityController.php';
require_once 'Backend/Controller/SkillController.php';
require_once 'Backend/Controller/ProjectController.php';
require_once 'Backend/Controller/ProjecttagController.php';
require_once 'Backend/Controller/FrontendController.php';
require_once 'Backend/Controller/ManageEmailController.php';
require_once 'Backend/Controller/TranslatetagController.php';
require_once 'Backend/Controller/UserController.php';
require_once 'Backend/Controller/SystemParameterController.php';
require_once 'Backend/Controller/MultiparamController.php';
require_once 'Backend/Controller/UrlController.php';
require_once 'Backend/Controller/RoleController.php';
require_once 'Backend/Controller/ActionController.php';

session_cache_limiter(false);
session_start();
\Slim\Slim::registerAutoloader();
Twig_Autoloader::register();
//------------------------------------------------------------
$twigView = new Slim\Views\Twig();



// Configure Slim --------------------------------------------
$app = new \Slim\Slim(array(
    'templates.path' => './Backend',
    'view' => $twigView
));
//------------------------------------------------------------
//Not found route--------------------------------------------

$app->notFound(function () use ($app) {
$app->render('Views/Error/404error.html.twig');
});
//-----------------------------------------------------------

//-------Set objects from classes-----------------------------
$medoo =new medoo();
$globalobj =new GlobalController();
$securityobj=new SecurityController($app, $medoo);
$languagedb = new LanguageController($app,$medoo);
$curricullumdb = new CurricullumController($app,$medoo);
$translationdb = new TranslationController($app,$medoo);
$educationdb = new EducationController($app, $medoo);
$workdb = new WorkController($app, $medoo);
$skilldb = new SkillController($app,$medoo);
$projectdb =new ProjectController($app, $medoo);
$projecttagdb =new ProjecttagController($app, $medoo);
$frontendobj = new FrontendController($app, $medoo, $globalobj, $languagedb, $translationdb, $educationdb, $skilldb, $projectdb, $projecttagdb);
$manageemailobj =new ManageEmail();
$translatetagobj = new TranslatetagController($app, $medoo);
$userobj =new UserController($app,$medoo,$securityobj);
$sysparamobj =new SystemParameterController($app, $medoo);
$multiparamobj= new MultiparamController($app,$medoo);
$urlobj = new UrlController($app, $medoo,$curricullumdb);
$roleobj = new RoleController($app, $medoo);
$actionobj = new ActionController($app,$medoo);
//--Set twig globals 
$twig = $app->view()->getEnvironment();
$twig->addGlobal("session", $_SESSION);

$twigobj =$app->view()->getInstance();
//----Define enviroment variables-----------------------------
$env = $app->environment();
$env['globalobj'] = $globalobj;
$env['securityobj'] = $securityobj;
$env['languagedb'] = $languagedb;
$env['curricullumdb'] = $curricullumdb;
$env['translationdb'] = $translationdb;
$env['educationdb'] = $educationdb;
$env['workdb'] = $workdb;
$env['skilldb'] = $skilldb;
$env['projectdb'] = $projectdb;
$env['projecttagdb'] = $projecttagdb;
$env['frontend'] = $frontendobj;
$env['manageemailobj'] =$manageemailobj;
$env['translatetagdb']=$translatetagobj;
$env['userdb']=$userobj;
$env['sysparamdb']=$sysparamobj;
$env['multiparamdb']=$multiparamobj;
$env['urldb']=$urlobj;
$env['roledb']=$roleobj;
$env['actiondb']=$actionobj;
//------------------------------------------------------------
//-----------------Login-----------------------------------
$twig->addGlobal("securityobj", $securityobj);

$app->get(
    '/',
    function ()use($app,$env) {
    $languagecode=$env['globalobj']->getsysparam('lang');
    $cvid=$env['globalobj']->getcurricullumidbyparam();
    $app->response->redirect('./main'.'/'.$languagecode.'/'.$cvid);
    }
)->name('root');



$app->get(
    '/main/:lang/:cvid',
    function ($lang,$cvid)use($app,$twigobj,$env) {
    $loader = $twigobj->getLoader();
    $loader->setPaths('Views');
    $maindata =$env['frontend']->getcurricullumdata($lang,$cvid);
    $profilepicture=$env['curricullumdb']->get_profile_picture($cvid);
    $cvname=$env['curricullumdb']->quit_special_chars($env['curricullumdb']->getcvnamebyid($cvid));
    $filename =$env['curricullumdb']->get_cvfilename_by_lang_cvid($cvid,$lang);
    $webname =$env['globalobj']->getsysparam('webname');
    
    $mainpath =$app->view->getdata('basepath').'/main';
    $app->render('Mainview/frontendtemplate.html.twig',array('aboutme'=>$maindata["aboutme"]
            ,'contactdetails'=>$maindata["contactdetails"]
            ,'name'=>$maindata["name"]
            ,'maintext'=>$maindata["maintext"]
            ,'frontendobj'=>$env['frontend']
            ,'profilepicture'=>$profilepicture
            ,'mainpath'=>$mainpath
            ,'webname'=>$webname
            ,'filename'=>$filename
            ,'cvname'=>$cvname
            ,'lang'=>$lang
            ,'cvid'=>$cvid
            ));
    }
)->name('cv_mainpage');


$app->get(
    '/login',
    function ()use($app,$twigobj) {
         
         $app->render('Views/Security/login.html.twig',array('username'=>'','password'=>'','errormessage'=>'','loginaction'=>$app->urlFor('validateuser')));
    }
)->name('login');

$app->get(
    '/logout',
    function ()use($app,$env) {
    $env['securityobj']->logout();
    }
)->name('logout');

$app->post(
    '/validateuser',
    function ()use($app,$env) {
         $username=$app->request()->post('username');
         $password = $app->request()->post('password');
         $env['securityobj']->validatepassword($username,$password);
    }
)->name('validateuser');
$app->get(
    '/home',
    function ()use($app) {
        $app->render('Views/Home/home.html.twig',array('link'=>'/home','option'=>'Home','route'=>''));
    }
)->name('home');

//---------------------------------------------------


//---------Set Base Url for css and scripts----------
$app->hook('slim.before', function () use ($app,$env) {
    $basepath = $env['globalobj']->getsysparam('basepath');
    $templatepath=$env['globalobj']->getsysparam('templatepath');
    $backendtitle=$env['globalobj']->getsysparam('apptitle');
    $app->view()->appendData(array('basepath' => $basepath,'templatepath'=>$templatepath,'backendtitle'=>$backendtitle));
});
//---------------------------------------------------

//-----------------Language CRUD---------------------
$app->get(
    '/languages',
    function () use($app,$env) {

        $env['languagedb']->rendergridview('Views/Language/languagelist.html.twig');

    })->name('languages');

$app->get(
    '/newlanguage',
    function () use($app,$env) {
        $env['languagedb']->rendernewview('','','','Views/Language/languagenew.html.twig');
        
    })->name('newlanguage');

$app->post(
    '/newlanguage',
    function () use($app,$env) {

        $env['languagedb']->addnewitem($env['globalobj']->getcurrentuser()
                ,$app->request()->post('code')
                ,$app->request()->post('language')
                ,'Views/Language/languagenew.html.twig') ;
        
    })->name('insertlanguage');

$app->get(
    '/editlanguage/:id',
    function ($id) use($app,$env) {
        $env['languagedb']->rendereditview($id,'Views/Language/languageedit.html.twig');

    })->name('editlanguage');

$app->post(
    '/updatelanguage/:id',
    function ($id) use($app,$env) {
    $env['languagedb']->updateitem($env['globalobj']->getcurrentuser(),$app->request()->post('code'),$id,$app->request()->post('language')) ;
    })->name('updatelanguage');  

$app->get(
    '/viewlanguage/:id',
    function ($id) use($app,$env) {
        $env['languagedb']->renderdeleteview($id,'Views/Language/languagedelete.html.twig');

    })->name('viewlanguage');

$app->post(
    '/deletelanguage/:id',
    function ($id) use($app,$env) {
        $env['languagedb']->deleteitem($id);
    })->name('deletelanguage');

//-----------------End Language CRUD----------------------
    
//-----------------Curricullum CRUD---------------------
$app->get(
    '/curricullumlist',
    function () use($app,$env) {

        $env['curricullumdb']->rendergridview('Views/Curricullum/curricullumlist.html.twig');

    })->name('curricullumlist');

$app->get(
    '/newcurricullum',
    function () use($app,$env) {
        $env['curricullumdb']->rendernewview('','','','','','','Views/Curricullum/curricullumnew.html.twig');
        
    })->name('newcurricullum');

$app->post(
    '/newcurricullum',
    function () use($app,$env) {
     
        
       
        $env['curricullumdb']->addnewitem($env['globalobj']->getcurrentuser()
                ,$app->request()->post('name')
                ,$app->request()->post('maintext')
                ,$app->request()->post('aboutme')
                ,$app->request()->post('contactdetails')
                ,$app->request()->post('mainskills')
                ,$_FILES
                ,'Views/Curricullum/curricullumnew.html.twig') ;
        
    })->name('insertcurricullum');

$app->get(
    '/editcurricullum/:id',
    function ($id) use($app,$env) {
        $env['curricullumdb']->rendereditview($id,'','Views/Curricullum/curricullumedit.html.twig');

    })->name('editcurricullum');

$app->post(
    '/updatecurricullum/:id',
    function ($id) use($app,$env) {
    $env['curricullumdb']->updateitem($env['globalobj']->getcurrentuser()
                ,$id
                ,$app->request()->post('name')
                ,$app->request()->post('maintext')
                ,$app->request()->post('aboutme')
                ,$app->request()->post('contactdetails')
                ,$app->request()->post('mainskills')
                ,$_FILES);
    })->name('updatecurricullum');  

$app->get(
    '/viewcurricullum/:id',
    function ($id) use($app,$env) {
        $env['curricullumdb']->renderdeleteview($id,'Views/Curricullum/curricullumdelete.html.twig');

    })->name('viewcurricullum');

$app->post(
    '/deletecurricullum/:id',
    function ($id) use($app,$env) {
    $cvname=$env['curricullumdb']->getcvnamebyid($id);
    $dirpath ='images/'.'profilepic_'.$env['curricullumdb']->quit_special_chars($cvname);
        $env['curricullumdb']->deleteitem($id,$dirpath);
    })->name('deletecurricullum');
    
 //----Upload Curricullum file------------------------------
    $app->get(
    '/curricullumfiles/:id',
    function ($id) use($app,$env) {
        $env['curricullumdb']->render_gridfiles_view($id,'Views/Curricullum/curricullumfiles.html.twig');

    })->name('curricullumfiles');
    
    
    $app->get(
    '/newcurricullumfile/:id',
    function ($id) use($app,$env) {
             
        $env['curricullumdb']->render_new_curricullumfile($id,$env['globalobj'],'Views/Curricullum/newcurricullumfile.html.twig');

    })->name('newcurricullumfile');
    
    $app->post(
    '/newcurricullumfile/:id',
    function ($id) use($app,$env) {
       $env['curricullumdb']->add_new_cvfile($env['globalobj']->getcurrentuser()
                ,$id
                ,$_FILES
                ,$app->request()->post('languagecode')
                ,$env['globalobj']
                ,'Views/Curricullum/newcurricullumfile.html.twig');

    })->name('insertcurricullumfile');
    
    $app->get(
    '/editcurricullumfile/:id',
    function ($id) use($app,$env) {
        $env['curricullumdb']->render_edit_curricullumfile($id,$env['globalobj'],'Views/Curricullum/editcurricullumfile.html.twig');

    })->name('editcurricullumfile');
    
    $app->get(
    '/viewcurricullumfile/:id',
    function ($id) use($app,$env) {
        $env['curricullumdb']->render_delete_curricullumfile($id,$env['globalobj'],'Views/Curricullum/deletecurricullumfile.html.twig');

    })->name('viewcurricullumfile');
    
     $app->post(
    '/updatecurricullumfile/:id',
    function ($id) use($app,$env) {
       $env['curricullumdb']->update_cv_file($env['globalobj']->getcurrentuser()
                ,$id
                ,$app->request()->post('cvname')
                ,$_FILES
                ,$app->request()->post('languagecode')
                ,$env['globalobj']);

    })->name('updatecurricullumfile');
    
    $app->post(
    '/deletecurricullumfile/:id',
    function ($id) use($app,$env) {
        
    $env['curricullumdb']->delete_cv_file($id);
    })->name('deletecurricullumfile');
    
 
//---------------------------------------------------------    
    

//-----------------End Curricullum CRUD----------------------

//-----------------Translation CRUD----------------------
$app->get(
    '/translations',
    function () use($app,$env) {

        $env['translationdb']->rendergridview('Views/Translation/translationlist.html.twig');

    })->name('translations');

$app->get(
    '/newtranslation',
    function () use($app,$env) {
        $env['translationdb']->rendernewview('','','','','','','',$env['globalobj'],'Views/Translation/translationnew.html.twig');

    })->name('newtranslation');

$app->post(
    '/newtranslation',
    function () use($app,$env) {
        $env['translationdb']->addnewitem($env['globalobj']->getcurrentuser()
            ,$app->request()->post('objectcode')
            ,$app->request()->post('parentid')    
            ,$app->request()->post('objectid')
            ,$app->request()->post('languagecode')
            ,$app->request()->post('field')
            ,$app->request()->post('translationcontent')
            ,$env['globalobj']    
            ,'Views/Translation/translationnew.html.twig') ;

    })->name('inserttranslation');
    
  $app->post(
    '/getfieldsajax',
    function () use($app,$env) {
     $env['translationdb']->getfieldsajax($app->request->post('objectcode'),$app->request->post('field'),$env['globalobj'],'curricullum');    
    
    })->name('getfields');  
    
   $app->post(
    '/getparentajax',
    function () use($app,$env) {
     $env['translationdb']->getparentajax($app->request->post('objectcode'),$env['globalobj']);  
       
    })->name('getparent');  
    
    $app->post(
    '/getobjectidlistajax',
    function () use($app,$env) {
     $env['translationdb']->getobjectidlistajax($env['globalobj']);  
       
    })->name('getobjectidlist');
    
     $app->post(
    '/getobjectsajax',
    function () use($app,$env) {
     $env['translationdb']->getobjects($app->request->post('objectcode'),$app->request->post('parentid'),$app->request->post('objectid'),$env['globalobj']);  
       
    })->name('getobjects');

$app->get(
    '/edittranslation/:id',
    function ($id) use($app,$env) {
        $env['translationdb']->rendereditview($id,$env['globalobj'],'Views/Translation/translationedit.html.twig');

    })->name('edittranslation');

$app->post(
    '/updatetranslation/:id',
    function ($id) use($app,$env) {
        $env['translationdb']->updateitem($env['globalobj']->getcurrentuser()
            ,$id
            ,$app->request()->post('objectcode')
            ,$app->request()->post('parentid')
            ,$app->request()->post('objectid')
            ,$app->request()->post('languagecode')
            ,$app->request()->post('field')
            ,$app->request()->post('translationcontent')
        )

           ;
    })->name('updatetranslation');

$app->get(
    '/viewtranslation/:id',
    function ($id) use($app,$env) {
        $env['translationdb']->renderdeleteview($id,$env['globalobj'],'Views/Translation/translationdelete.html.twig');

    })->name('viewtranslation');

$app->post(
    '/deletetranslation/:id',
    function ($id) use($app,$env) {
        $env['translationdb']->deleteitem($id);
    })->name('deletetranslation');




//-----------------End TranslationCRUD----------------------

//-----------------Education CRUD--------------------------
$app->get(
    '/educationlist',
    function () use($app,$env) {

        $env['educationdb']->rendergridview('Views/Education/educationlist.html.twig');

    })->name('educationlist');

$app->get(
    '/neweducation',
    function () use($app,$env) {
       $env['educationdb']->rendernewview('','','','','',$env['globalobj'],'Views/Education/educationnew.html.twig');

    })->name('neweducation');
    
 $app->post(
    '/neweducation',
    function () use($app,$env) {
        $env['educationdb']->addnewitem($env['globalobj']->getcurrentuser()
            ,$app->request()->post('curricullumid')
            ,$app->request()->post('institution')    
            ,$app->request()->post('degree')
            ,$app->request()->post('date')
            ,$env['globalobj']    
            ,'Views/Education/educationnew.html.twig') ;

    })->name('inserteducation');
    
  $app->get(
    '/editeducation/:id',
    function ($id) use($app,$env) {
        $env['educationdb']->rendereditview($id,$env['globalobj'],'Views/Education/educationedit.html.twig');

    })->name('editeducation');

$app->post(
    '/updateeducation/:id',
    function ($id) use($app,$env) {
        $env['educationdb']->updateitem($env['globalobj']->getcurrentuser()
            ,$id    
            ,$app->request()->post('curricullumid')
            ,$app->request()->post('institution')    
            ,$app->request()->post('degree')
            ,$app->request()->post('date')  
        )

           ;
    })->name('updateeducation');

$app->get(
    '/vieweducation/:id',
    function ($id) use($app,$env) {
        $env['educationdb']->renderdeleteview($id,$env['globalobj'],'Views/Education/educationdelete.html.twig');

    })->name('vieweducation');

$app->post(
    '/deleteeducation/:id',
    function ($id) use($app,$env) {
        $env['educationdb']->deleteitem($id);
    })->name('deleteeducation');
  
    
    
//-----------------End Education CRUD---------------------- 

    
    
//-----------------Work CRUD----------------------     
  $app->get(
    '/worklist',
    function () use($app,$env) {

        $env['workdb']->rendergridview('Views/Work/worklist.html.twig');

    })->name('worklist');

$app->get(
    '/newwork',
    function () use($app,$env) {
       $env['workdb']->rendernewview('','','','','','',$env['globalobj'],'Views/Work/worknew.html.twig');

    })->name('newwork');
    
 $app->post(
    '/newwork',
    function () use($app,$env) {
        $env['workdb']->addnewitem($env['globalobj']->getcurrentuser()
            ,$app->request()->post('curricullumid')
            ,$app->request()->post('company')    
            ,$app->request()->post('position')
            ,$app->request()->post('from')
            ,$app->request()->post('to') 
            ,$env['globalobj']    
            ,'Views/Work/worknew.html.twig') ;

    })->name('insertwork');
    
  $app->get(
    '/editwork/:id',
    function ($id) use($app,$env) {
        $env['workdb']->rendereditview($id,$env['globalobj'],'Views/Work/workedit.html.twig');

    })->name('editwork');

$app->post(
    '/updatework/:id',
    function ($id) use($app,$env) {
        $env['workdb']->updateitem($env['globalobj']->getcurrentuser()
            ,$id   
            ,$app->request()->post('curricullumid')
            ,$app->request()->post('company')   
            ,$app->request()->post('position')
            ,$app->request()->post('from')
            ,$app->request()->post('to')   
        )

           ;
    })->name('updatework');

$app->get(
    '/viewwork/:id',
    function ($id) use($app,$env) {
        $env['workdb']->renderdeleteview($id,$env['globalobj'],'Views/Work/workdelete.html.twig');

    })->name('viewwork');

$app->post(
    '/deletework/:id',
    function ($id) use($app,$env) {
        $env['workdb']->deleteitem($id);
    })->name('deletework');
  
    
//-----------------End Work CRUD---------------------- 
//-----------------skill CRUD--------------------------
$app->get(
    '/skills',
    function () use($app,$env) {

        $env['skilldb']->rendergridview('Views/Skill/skills.html.twig');

    })->name('skills');

$app->get(
    '/newskill',
    function () use($app,$env) {
       $env['skilldb']->rendernewview('','','','','',$env['globalobj'],'Views/Skill/skillnew.html.twig');

    })->name('newskill');
    
 $app->post(
    '/newskill',
    function () use($app,$env) {
        $env['skilldb']->addnewitem($env['globalobj']->getcurrentuser()
            ,$app->request()->post('curricullumid')
            ,$app->request()->post('type')    
            ,$app->request()->post('skill')
            ,$app->request()->post('percentage')
            ,$env['globalobj']    
            ,'Views/Skill/skillnew.html.twig') ;

    })->name('insertskill');
    
  $app->get(
    '/editskill/:id',
    function ($id) use($app,$env) {
        $env['skilldb']->rendereditview($id,$env['globalobj'],'Views/Skill/skilledit.html.twig');

    })->name('editskill');

$app->post(
    '/updateskill/:id',
    function ($id) use($app,$env) {
        $env['skilldb']->updateitem($env['globalobj']->getcurrentuser()
            ,$id    
            ,$app->request()->post('curricullumid')
            ,$app->request()->post('type')    
            ,$app->request()->post('skill')
            ,$app->request()->post('percentage')  
        )

           ;
    })->name('updateskill');

$app->get(
    '/viewskill/:id',
    function ($id) use($app,$env) {
        $env['skilldb']->renderdeleteview($id,$env['globalobj'],'Views/Skill/skilldelete.html.twig');

    })->name('viewskill');

$app->post(
    '/deleteskill/:id',
    function ($id) use($app,$env) {
        $env['skilldb']->deleteitem($id);
    })->name('deleteskill');
  
    
    
//-----------------End skill CRUD----------------------     
//-----------------project CRUD--------------------------
$app->get(
    '/projects',
    function () use($app,$env) {

        $env['projectdb']->rendergridview('Views/Project/projects.html.twig');

    })->name('projects');

$app->get(
    '/newproject',
    function () use($app,$env) {
       $env['projectdb']->rendernewview('','','','','','',$env['globalobj'],'Views/Project/projectnew.html.twig');

    })->name('newproject');
    
 $app->post(
    '/newproject',
    function () use($app,$env) {
        $env['projectdb']->addnewitem($env['globalobj']->getcurrentuser()
            ,$app->request()->post('curricullumid')
            ,$app->request()->post('name')   
            ,$app->request()->post('description')
            ,$app->request()->post('link')
            ,$app->request()->post('imagename')
            ,$env['globalobj']    
            ,'Views/Project/projectnew.html.twig') ;

    })->name('insertproject');
    
  $app->get(
    '/editproject/:id',
    function ($id) use($app,$env) {
        $env['projectdb']->rendereditview($id,$env['globalobj'],'Views/Project/projectedit.html.twig');

    })->name('editproject');

$app->post(
    '/updateproject/:id',
    function ($id) use($app,$env) {
        $env['projectdb']->updateitem($env['globalobj']->getcurrentuser()
            ,$id  
            ,$app->request()->post('curricullumid')
            ,$app->request()->post('name')    
            ,$app->request()->post('description')
            ,$app->request()->post('link')
            ,$app->request()->post('imagename')    
        )

           ;
    })->name('updateproject');

$app->get(
    '/viewproject/:id',
    function ($id) use($app,$env) {
        $env['projectdb']->renderdeleteview($id,$env['globalobj'],'Views/Project/projectdelete.html.twig');

    })->name('viewproject');

$app->post(
    '/deleteproject/:id',
    function ($id) use($app,$env) {
        $env['projectdb']->deleteitem($id);
    })->name('deleteproject');
  
    
    
//-----------------End project CRUD----------------------      
//-----------------projecttag CRUD---------------------
$app->get(
    '/projecttags/:projectid',
    function ($projectid) use($app,$env) {

        $env['projecttagdb']->rendergridview($projectid,'Views/Projecttag/projecttags.html.twig');

    })->name('projecttags');

$app->get(
    '/newprojecttag/:projectid',
    function ($projectid) use($app,$env) {
        $env['projecttagdb']->rendernewview($projectid,'','','Views/Projecttag/projecttagnew.html.twig');
        
    })->name('newprojecttag');

$app->post(
    '/newprojecttag',
    function () use($app,$env) {

        $env['projecttagdb']->addnewitem($env['globalobj']->getcurrentuser()
                ,$app->request()->post('projectid')
                ,$app->request()->post('tagname')
                ,'Views/Projecttag/projecttagnew.html.twig') ;
        
    })->name('insertprojecttag');

$app->get(
    '/editprojecttag/:id',
    function ($id) use($app,$env) {
        $env['projecttagdb']->rendereditview($id,'Views/Projecttag/projecttagedit.html.twig');

    })->name('editprojecttag');

$app->post(
    '/updateprojecttag/:id',
    function ($id) use($app,$env) {
    $env['projecttagdb']->updateitem($env['globalobj']->getcurrentuser(),$id,$app->request()->post('projectid'),$app->request()->post('tagname')) ;
    })->name('updateprojecttag');  

$app->get(
    '/viewprojecttag/:id',
    function ($id) use($app,$env) {
        $env['projecttagdb']->renderdeleteview($id,'Views/Projecttag/projecttagdelete.html.twig');

    })->name('viewprojecttag');

$app->post(
    '/deleteprojecttag/:id',
    function ($id) use($app,$env) {
        $env['projecttagdb']->deleteitem($id);
    })->name('deleteprojecttag');

//-----------------End projecttag CRUD----------------------    
    
//-----------------Contact Form--------------------------------
    
    $app->post(
    '/sendemail',
    function () use($app,$env) {
        $env['manageemailobj']->sendemailaction($app->request()->post('contactName')
                ,$app->request()->post('contactEmail')
                ,$app->request()->post('contactSubject')
                ,$app->request()->post('contactMessage'));
    })->name('sendemail');
    
 //-----------------End Contact Form--------------------------------  
    
//-----------------Translatetag CRUD---------------------
$app->get(
    '/translatetags',
    function () use($app,$env) {

        $env['translatetagdb']->rendergridview('Views/Translatetag/translatetags.html.twig');

    })->name('translatetags');

$app->get(
    '/newtranslatetag',
    function () use($app,$env) {
        $env['translatetagdb']->rendernewview('','','','',$env['globalobj'],'Views/Translatetag/translatetagnew.html.twig');
        
    })->name('newtranslatetag');

$app->post(
    '/newtranslatetag',
    function () use($app,$env) {

        $env['translatetagdb']->addnewitem($env['globalobj']->getcurrentuser()
                ,$app->request()->post('languagecode')
                ,$app->request()->post('key')
                ,$app->request()->post('translation')
                ,'Views/Translatetag/translatetagnew.html.twig') ;
        
    })->name('inserttranslatetag');

$app->get(
    '/edittranslatetag/:parameter',
    function ($parameter) use($app,$env) {
        $env['translatetagdb']->rendereditview($parameter,$env['globalobj'],'Views/Translatetag/translatetagedit.html.twig');

    })->name('edittranslatetag');

$app->post(
    '/updatetranslatetag/:parameter',
    function ($parameter) use($app,$env) {
    
    $env['translatetagdb']->updateitem($env['globalobj']->getcurrentuser()
            ,$app->request()->post('languagecode')
            ,$app->request()->post('key')
            ,$app->request()->post('translation')
            ,$parameter) ;
    })->name('updatetranslatetag');  

$app->get(
    '/viewtranslatetag/:parameter',
    function ($parameter) use($app,$env) {
        $env['translatetagdb']->renderdeleteview($parameter,$env['globalobj'],'Views/Translatetag/translatetagdelete.html.twig');

    })->name('viewtranslatetag');

$app->post(
    '/deletetranslatetag/:parameter',
    function ($parameter) use($app,$env) {
        $env['translatetagdb']->deleteitem($parameter);
    })->name('deletetranslatetag');

//-----------------End translatetag CRUD----------------------        
//-----------------User CRUD---------------------
$app->get(
    '/users',
    function () use($app,$env) {

        $env['userdb']->rendergridview($env['globalobj'],'Views/User/users.html.twig');

    })->name('users');

$app->get(
    '/newuser',
    function () use($app,$env) {
        $env['userdb']->rendernewview('','','','','Views/User/usernew.html.twig');
        
    })->name('newuser');

$app->post(
    '/newuser',
    function () use($app,$env) {

        $env['userdb']->addnewitem($env['globalobj']->getcurrentuser()
                ,$app->request()->post('username')
                ,$app->request()->post('password')
                ,$app->request()->post('email')
                ,'Views/User/usernew.html.twig') ;
        
    })->name('insertuser');

$app->get(
    '/edituser/:id',
    function ($id) use($app,$env) {
        $env['userdb']->rendereditview($id,'Views/User/useredit.html.twig');

    })->name('edituser');

$app->post(
    '/updateuser/:id',
    function ($id) use($app,$env) {
    $env['userdb']->updateitem($env['globalobj']->getcurrentuser(),$id,$app->request()->post('username'),$app->request()->post('email')) ;
    })->name('updateuser');  

$app->get(
    '/viewuser/:id',
    function ($id) use($app,$env) {
        $env['userdb']->renderdeleteview($id,'Views/User/userdelete.html.twig');

    })->name('viewuser');

$app->post(
    '/deleteuser/:id',
    function ($id) use($app,$env) {
        $env['userdb']->deleteitem($id);
    })->name('deleteuser');

 $app->get(
    '/passwordchange/:id',
    function ($id) use($app,$env) {
        $env['userdb']->renderpasswordchange($id,'Views/User/userpasswordchange.html.twig');

    })->name('passwordchange');   
    
  $app->post(
    '/changepassword/:id',
    function ($id) use($app,$env) {
    $env['userdb']->changepassword($env['globalobj']->getcurrentuser(),$id,$app->request()->post('password')) ;
    })->name('changepassword');   
    
     //--- User Roles---------------------------------
    $app->get(
    '/userroles/:id',
    function ($id) use($app,$env) {
        $env['userdb']->render_userroles_grid($id,'Views/User/userroles.html.twig');

    })->name('userroles');
    
     $app->get(
    '/newuserrole/:userid',
    function ($userid) use($app,$env) {
        $env['userdb']->render_new_userrole($userid,'Views/User/newuserrole.html.twig');

    })->name('newuserrole');
    $app->post(
    '/newuserrole/:userid',
    function ($userid) use($app,$env) {
     $env['userdb']->add_new_userrole($env['globalobj']->getcurrentuser(),$userid,$app->request()->post('roleid'),'Views/User/newuserrole.html.twig');

    })->name('insertuserrole');
    
    $app->get(
    '/viewuserrole/:userid/:roleid',
    function ($userid,$roleid) use($app,$env) {
        $env['userdb']->render_delete_userrole($userid,$roleid,'Views/User/deleteuserrole.html.twig');

    })->name('viewuserrole');

    $app->post(
    '/deleteuserrole/:userid/:roleid',
    function ($userid,$roleid) use($app,$env) {
        $env['userdb']->delete_userrole_item($userid,$roleid);
    })->name('deleteuserrole');

    //--------End User Roles------------------------------
    
//-----------------End user CRUD----------------------
//-----------------sysparam CRUD---------------------
$app->get(
    '/sysparams',
    function () use($app,$env) {

        $env['sysparamdb']->rendergridview('Views/Sysparam/sysparams.html.twig');

    })->name('sysparams');

$app->get(
    '/newsysparam',
    function () use($app,$env) {
        $env['sysparamdb']->rendernewview('','','','','Views/Sysparam/sysparamnew.html.twig');
        
    })->name('newsysparam');

$app->post(
    '/newsysparam',
    function () use($app,$env) {

        $env['sysparamdb']->addnewitem($env['globalobj']->getcurrentuser()
                ,$app->request()->post('code')
                ,$app->request()->post('value')
                ,$app->request()->post('description')
                ,'Views/Sysparam/sysparamnew.html.twig') ;
        
    })->name('insertsysparam');

$app->get(
    '/editsysparam/:id',
    function ($id) use($app,$env) {
        $env['sysparamdb']->rendereditview($id,'Views/Sysparam/sysparamedit.html.twig');

    })->name('editsysparam');

$app->post(
    '/updatesysparam/:id',
    function ($id) use($app,$env) {
    $env['sysparamdb']->updateitem($env['globalobj']->getcurrentuser()
            ,$id
            ,$app->request()->post('code')
            ,$app->request()->post('value')
            ,$app->request()->post('description')) ;
    })->name('updatesysparam');  

$app->get(
    '/viewsysparam/:id',
    function ($id) use($app,$env) {
        $env['sysparamdb']->renderdeleteview($id,'Views/Sysparam/sysparamdelete.html.twig');

    })->name('viewsysparam');

$app->post(
    '/deletesysparam/:id',
    function ($id) use($app,$env) {
        $env['sysparamdb']->deleteitem($id);
    })->name('deletesysparam');

//-----------------End sysparam CRUD---------------------- 
//-----------------multiparam CRUD---------------------
$app->get(
    '/multiparams/:sysparamid',
    function ($sysparamid) use($app,$env) {

        $env['multiparamdb']->rendergridview($sysparamid,'Views/Multiparam/multiparams.html.twig');

    })->name('multiparams');

$app->get(
    '/newmultiparam/:sysparamid',
    function ($sysparamid) use($app,$env) {
        $env['multiparamdb']->rendernewview($sysparamid,'','','','Views/Multiparam/multiparamnew.html.twig');
        
    })->name('newmultiparam');

$app->post(
    '/newmultiparam',
    function () use($app,$env) {

        $env['multiparamdb']->addnewitem($env['globalobj']->getcurrentuser()
                ,$app->request()->post('sysparamid')
                ,$app->request()->post('value')
                ,$app->request()->post('valuedesc')
                ,'Views/Multiparam/multiparamnew.html.twig') ;
        
    })->name('insertmultiparam');

$app->get(
    '/editmultiparam/:id',
    function ($id) use($app,$env) {
        $env['multiparamdb']->rendereditview($id,'Views/Multiparam/multiparamedit.html.twig');

    })->name('editmultiparam');

$app->post(
    '/updatemultiparam/:id',
    function ($id) use($app,$env) {
    $env['multiparamdb']->updateitem($env['globalobj']->getcurrentuser()
            ,$id
            ,$app->request()->post('sysparamid')
            ,$app->request()->post('value')
            ,$app->request()->post('valuedesc')) ;
    })->name('updatemultiparam');  

$app->get(
    '/viewmultiparam/:id',
    function ($id) use($app,$env) {
        $env['multiparamdb']->renderdeleteview($id,'Views/Multiparam/multiparamdelete.html.twig');

    })->name('viewmultiparam');

$app->post(
    '/deletemultiparam/:id/:sysparamid',
    function ($id,$sysparamid) use($app,$env) {
        $env['multiparamdb']->deleteitem($id,$sysparamid);
    })->name('deletemultiparam');

//-----------------End multiparam CRUD----------------------

//-----------------System Admin Options----------------------
$app->get(
    '/adminoptions',
    function () use($app) {
        $app->render('Views/Systemadmin/adminoptions.html.twig',array('link'=>'/home','option'=>'Home','route'=>''));

    })->name('adminoptions');
//-----------------------------------------------------------
//-----------Translation Options-----------------------------
$app->get(
    '/translationoptions',
    function () use($app) {
        $app->render('Views/Translation/translationshome.html.twig',array('link'=>'/home','option'=>'Home','route'=>''));

    })->name('translationoptions');
    
//-----------------------------------------------------------
//-----------------url CRUD--------------------------
$app->get(
    '/urllist/:cvid',
    function ($cvid) use($app,$env) {

        $env['urldb']->rendergridview($cvid,'Views/Url/urllist.html.twig');

    })->name('urllist');

$app->get(
    '/newurl/:cvid',
    function ($cvid) use($app,$env) {
       $env['urldb']->rendernewview($cvid,'','','','',$env['globalobj'],'Views/Url/urlnew.html.twig');

    })->name('newurl');
    
 $app->post(
    '/newurl',
    function () use($app,$env) {
        $env['urldb']->addnewitem($env['globalobj']->getcurrentuser()
            ,$app->request()->post('curricullumid')
            ,$app->request()->post('type')   
            ,$app->request()->post('name')
            ,$app->request()->post('link')
            ,$env['globalobj'] 
            ,'Views/Url/urlnew.html.twig') ;

    })->name('inserturl');
    
  $app->get(
    '/editurl/:id',
    function ($id) use($app,$env) {
        $env['urldb']->rendereditview($id,$env['globalobj'],'Views/Url/urledit.html.twig');

    })->name('editurl');

$app->post(
    '/updateurl/:id',
    function ($id) use($app,$env) {
        $env['urldb']->updateitem($env['globalobj']->getcurrentuser()
            ,$id   
            ,$app->request()->post('curricullumid')
            ,$app->request()->post('type')   
            ,$app->request()->post('name')
            ,$app->request()->post('link')  
        )

           ;
    })->name('updateurl');

$app->get(
    '/viewurl/:id',
    function ($id) use($app,$env) {
        $env['urldb']->renderdeleteview($id,$env['globalobj'],'Views/Url/urldelete.html.twig');

    })->name('viewurl');

$app->post(
    '/deleteurl/:id',
    function ($id) use($app,$env) {
        $env['urldb']->deleteitem($id);
    })->name('deleteurl');
  
    
  $app->get(
    '/test',
    function () use($app) {
        $app->render('Views/Test/test.html.twig');

    })->name('test');  
//-----------------End url CRUD---------------------- 
//-----------------Role CRUD---------------------
$app->get(
    '/roles',
    function () use($app,$env) {

        $env['roledb']->rendergridview($env['globalobj'],'Views/Role/roles.html.twig');

    })->name('roles');

$app->get(
    '/newrole',
    function () use($app,$env) {
        $env['roledb']->rendernewview('','','','Views/Role/rolenew.html.twig');
        
    })->name('newrole');

$app->post(
    '/newrole',
    function () use($app,$env) {

        $env['roledb']->addnewitem($env['globalobj']->getcurrentuser()
                ,$app->request()->post('role') 
                ,$app->request()->post('description')
                ,'Views/Role/rolenew.html.twig') ;
        
    })->name('insertrole');

$app->get(
    '/editrole/:id',
    function ($id) use($app,$env) {
        $env['roledb']->rendereditview($id,'Views/Role/roleedit.html.twig');

    })->name('editrole');

$app->post(
    '/updaterole/:id',
    function ($id) use($app,$env) {
    $env['roledb']->updateitem($env['globalobj']->getcurrentuser()
            ,$id
            ,$app->request()->post('role')   
            ,$app->request()->post('description')) ;
    })->name('updaterole');  

$app->get(
    '/viewrole/:id',
    function ($id) use($app,$env) {
        $env['roledb']->renderdeleteview($id,'Views/Role/roledelete.html.twig');

    })->name('viewrole');

$app->post(
    '/deleterole/:id',
    function ($id) use($app,$env) {
        $env['roledb']->deleteitem($id);
    })->name('deleterole');
    
    //--- Role Actions---------------------------------
    $app->get(
    '/roleactions/:id',
    function ($id) use($app,$env) {
        $env['roledb']->render_roleactions_grid($id,'Views/Role/roleactions.html.twig');

    })->name('roleactions');
    
     $app->get(
    '/newroleaction/:roleid',
    function ($roleid) use($app,$env) {
        $env['roledb']->render_new_roleaction($roleid,'Views/Role/newroleaction.html.twig');

    })->name('newroleaction');
    $app->post(
    '/newroleaction/:roleid',
    function ($roleid) use($app,$env) {
     $env['roledb']->add_new_roleaction($env['globalobj']->getcurrentuser(),$roleid,$app->request()->post('actionid'),'Views/Role/newroleaction.html.twig');

    })->name('insertroleaction');
    
    $app->get(
    '/viewroleaction/:roleid/:actionid',
    function ($roleid,$actionid) use($app,$env) {
        $env['roledb']->render_delete_roleaction($roleid,$actionid,'Views/Role/deleteroleaction.html.twig');

    })->name('viewroleaction');

    $app->post(
    '/deleteroleaction/:roleid/:actionid',
    function ($roleid,$actionid) use($app,$env) {
        $env['roledb']->delete_roleaction_item($roleid,$actionid);
    })->name('deleteroleaction');

    //--------End Role Actions------------------------------

//-----------------End role CRUD----------------------
//-----------------Action CRUD---------------------
$app->get(
    '/actions',
    function () use($app,$env) {

        $env['actiondb']->rendergridview($env['globalobj'],'Views/Action/actions.html.twig');

    })->name('actions');

$app->get(
    '/newaction',
    function () use($app,$env) {
        $env['actiondb']->rendernewview('','','','Views/Action/actionnew.html.twig');
        
    })->name('newaction');

$app->post(
    '/newaction',
    function () use($app,$env) {

        $env['actiondb']->addnewitem($env['globalobj']->getcurrentuser()
                ,$app->request()->post('action') 
                ,$app->request()->post('description')
                ,'Views/Action/actionnew.html.twig') ;
        
    })->name('insertaction');

$app->get(
    '/editaction/:id',
    function ($id) use($app,$env) {
        $env['actiondb']->rendereditview($id,'Views/Action/actionedit.html.twig');

    })->name('editaction');

$app->post(
    '/updateaction/:id',
    function ($id) use($app,$env) {
    $env['actiondb']->updateitem($env['globalobj']->getcurrentuser()
            ,$id
            ,$app->request()->post('action')   
            ,$app->request()->post('description')) ;
    })->name('updateaction');  

$app->get(
    '/viewaction/:id',
    function ($id) use($app,$env) {
        $env['actiondb']->renderdeleteview($id,'Views/Action/actiondelete.html.twig');

    })->name('viewaction');

$app->post(
    '/deleteaction/:id',
    function ($id) use($app,$env) {
        $env['actiondb']->deleteitem($id);
    })->name('deleteaction');

//-----------------End Action CRUD----------------------     
    
$app->run();
