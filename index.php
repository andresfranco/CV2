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

//------------------------------------------------------------
//-----------------Login-----------------------------------
$twig->addGlobal("securityobj", $securityobj);

$app->get(
    '/',
    function ()use($app,$env) {
    $languagecode=$env['globalobj']->getsysparam('lang');  
    $app->response->redirect('./main'.'/'.$languagecode);
    }
)->name('root');



$app->get(
    '/main/:lang',
    function ($lang)use($app,$twigobj,$env) {
    $loader = $twigobj->getLoader();
    $loader->setPaths('Views');
    $maindata =$env['frontend']->getcurricullumdata($lang);
    //$translations
    $app->render('Mainview/frontendtemplate.html.twig',array('aboutme'=>$maindata["aboutme"]
            ,'contactdetails'=>$maindata["contactdetails"]
            ,'name'=>$maindata["name"]
            ,'maintext'=>$maindata["maintext"]
            ,'frontendobj'=>$env['frontend']
            ,'lang'=>$lang
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
        $app->render('Views/Home/home.html.twig');
    }
)->name('home');

//---------------------------------------------------


//---------Set Base Url for css and scripts----------
$app->hook('slim.before', function () use ($app) {
    $app->view()->appendData(array('basepath' => '/CV2','templatepath'=>'/CV2/Backend','backendtitle'=>'CV Maker'));
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
                ,htmlEntities($app->request()->post('code'))
                ,htmlEntities($app->request()->post('language'))
                ,'Views/Language/languagenew.html.twig') ;
        
    })->name('insertlanguage');

$app->get(
    '/editlanguage/:id',
    function ($id) use($app,$env) {
        $env['languagedb']->rendereditview($id,'Views/Language/languageedit.html.twig');

    })->name('editlanguage');

$app->post(
    '/updatelanguage',
    function () use($app,$env) {
    $env['languagedb']->updateitem($env['globalobj']->getcurrentuser(), htmlEntities($app->request()->post('code')),htmlEntities($app->request()->post('codeold')),htmlEntities($app->request()->post('language'))) ;
    })->name('updatelanguage');  

$app->get(
    '/viewlanguage/:id',
    function ($id) use($app,$env) {
        $env['languagedb']->renderdeleteview($id,'Views/Language/languagedelete.html.twig');

    })->name('viewlanguage');

$app->post(
    '/deletelanguage',
    function () use($app,$env) {
        $env['languagedb']->deleteitem(htmlEntities($app->request()->post('code')));
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
                ,htmlEntities($app->request()->post('name'))
                ,htmlEntities($app->request()->post('maintext'))
                ,htmlEntities($app->request()->post('aboutme'))
                ,htmlEntities($app->request()->post('contactdetails'))
                ,htmlEntities($app->request()->post('mainskills'))
                ,'Views/Curricullum/curricullumnew.html.twig') ;
        
    })->name('insertcurricullum');

$app->get(
    '/editcurricullum/:id',
    function ($id) use($app,$env) {
        $env['curricullumdb']->rendereditview($id,'Views/Curricullum/curricullumedit.html.twig');

    })->name('editcurricullum');

$app->post(
    '/updatecurricullum',
    function () use($app,$env) {
    $env['curricullumdb']->updateitem($env['globalobj']->getcurrentuser()
                ,htmlEntities($app->request()->post('id'))
                ,htmlEntities($app->request()->post('name'))
                ,htmlEntities($app->request()->post('maintext'))
                ,htmlEntities($app->request()->post('aboutme'))
                ,htmlEntities($app->request()->post('contactdetails'))
                ,htmlEntities($app->request()->post('mainskills')));
    })->name('updatecurricullum');  

$app->get(
    '/viewcurricullum/:id',
    function ($id) use($app,$env) {
        $env['curricullumdb']->renderdeleteview($id,'Views/Curricullum/curricullumdelete.html.twig');

    })->name('viewcurricullum');

$app->post(
    '/deletecurricullum',
    function () use($app,$env) {
        $env['curricullumdb']->deleteitem(htmlEntities($app->request()->post('id')));
    })->name('deletecurricullum');

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
        $env['translationdb']->rendernewview('','','','','','','',$env['globalobj'],$env['translationdb'],'Views/Translation/translationnew.html.twig');

    })->name('newtranslation');

$app->post(
    '/newtranslation',
    function () use($app,$env) {
        $env['translationdb']->addnewitem($env['globalobj']->getcurrentuser()
            ,htmlEntities($app->request()->post('objectcode'))
            ,htmlEntities($app->request()->post('parentid'))    
            ,htmlEntities($app->request()->post('objectid'))
            ,htmlEntities($app->request()->post('languagecode'))
            ,htmlEntities($app->request()->post('field'))
            ,htmlEntities($app->request()->post('content'))    
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
     $env['translationdb']->getobjects($app->request->post('objectcode'),$app->request->post('parentid'),$env['globalobj']);  
       
    })->name('getobjects');

$app->get(
    '/edittranslation/:id',
    function ($id) use($app,$env) {
        $env['translationdb']->rendereditview($id,$env['globalobj'],'Views/Translation/translationedit.html.twig');

    })->name('edittranslation');

$app->post(
    '/updatetranslation',
    function () use($app,$env) {
        $env['translationdb']->updateitem($env['globalobj']->getcurrentuser()
            ,htmlEntities($app->request()->post('id'))
            ,htmlEntities($app->request()->post('objectcode'))
            ,htmlEntities($app->request()->post('parentid'))
            ,htmlEntities($app->request()->post('objectid'))
            ,htmlEntities($app->request()->post('languagecode'))
            ,htmlEntities($app->request()->post('field'))
            ,htmlEntities($app->request()->post('content'))
        )

           ;
    })->name('updatetranslation');

$app->get(
    '/viewtranslation/:id',
    function ($id) use($app,$env) {
        $env['translationdb']->renderdeleteview($id,$env['globalobj'],'Views/Translation/translationdelete.html.twig');

    })->name('viewtranslation');

$app->post(
    '/deletetranslation',
    function () use($app,$env) {
        $env['translationdb']->deleteitem(htmlEntities($app->request()->post('id')));
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
            ,htmlEntities($app->request()->post('curricullumid'))
            ,htmlEntities($app->request()->post('institution'))    
            ,htmlEntities($app->request()->post('degree'))
            ,htmlEntities($app->request()->post('date'))    
            ,'Views/Education/educationnew.html.twig') ;

    })->name('inserteducation');
    
  $app->get(
    '/editeducation/:id',
    function ($id) use($app,$env) {
        $env['educationdb']->rendereditview($id,$env['globalobj'],'Views/Education/educationedit.html.twig');

    })->name('editeducation');

$app->post(
    '/updateeducation',
    function () use($app,$env) {
        $env['educationdb']->updateitem($env['globalobj']->getcurrentuser()
            ,htmlEntities($app->request()->post('id'))    
            ,htmlEntities($app->request()->post('curricullumid'))
            ,htmlEntities($app->request()->post('institution'))    
            ,htmlEntities($app->request()->post('degree'))
            ,htmlEntities($app->request()->post('date'))  
        )

           ;
    })->name('updateeducation');

$app->get(
    '/vieweducation/:id',
    function ($id) use($app,$env) {
        $env['educationdb']->renderdeleteview($id,$env['globalobj'],'Views/Education/educationdelete.html.twig');

    })->name('vieweducation');

$app->post(
    '/deleteeducation',
    function () use($app,$env) {
        $env['educationdb']->deleteitem(htmlEntities($app->request()->post('id')));
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
            ,htmlEntities($app->request()->post('curricullumid'))
            ,htmlEntities($app->request()->post('company'))    
            ,htmlEntities($app->request()->post('position'))
            ,htmlEntities($app->request()->post('from'))
            ,htmlEntities($app->request()->post('to'))   
            ,'Views/Work/worknew.html.twig') ;

    })->name('insertwork');
    
  $app->get(
    '/editwork/:id',
    function ($id) use($app,$env) {
        $env['workdb']->rendereditview($id,$env['globalobj'],'Views/Work/workedit.html.twig');

    })->name('editwork');

$app->post(
    '/updatework',
    function () use($app,$env) {
        $env['workdb']->updateitem($env['globalobj']->getcurrentuser()
            ,htmlEntities($app->request()->post('id'))    
            ,htmlEntities($app->request()->post('curricullumid'))
             ,htmlEntities($app->request()->post('company'))    
            ,htmlEntities($app->request()->post('position'))
            ,htmlEntities($app->request()->post('from'))
            ,htmlEntities($app->request()->post('to'))   
        )

           ;
    })->name('updatework');

$app->get(
    '/viewwork/:id',
    function ($id) use($app,$env) {
        $env['workdb']->renderdeleteview($id,$env['globalobj'],'Views/Work/workdelete.html.twig');

    })->name('viewwork');

$app->post(
    '/deletework',
    function () use($app,$env) {
        $env['workdb']->deleteitem(htmlEntities($app->request()->post('id')));
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
            ,htmlEntities($app->request()->post('curricullumid'))
            ,htmlEntities($app->request()->post('type'))    
            ,htmlEntities($app->request()->post('skill'))
            ,htmlEntities($app->request()->post('percentage'))
            ,$env['globalobj']    
            ,'Views/Skill/skillnew.html.twig') ;

    })->name('insertskill');
    
  $app->get(
    '/editskill/:id',
    function ($id) use($app,$env) {
        $env['skilldb']->rendereditview($id,$env['globalobj'],'Views/Skill/skilledit.html.twig');

    })->name('editskill');

$app->post(
    '/updateskill',
    function () use($app,$env) {
        $env['skilldb']->updateitem($env['globalobj']->getcurrentuser()
            ,htmlEntities($app->request()->post('id'))    
            ,htmlEntities($app->request()->post('curricullumid'))
            ,htmlEntities($app->request()->post('type'))    
            ,htmlEntities($app->request()->post('skill'))
            ,htmlEntities($app->request()->post('percentage'))  
        )

           ;
    })->name('updateskill');

$app->get(
    '/viewskill/:id',
    function ($id) use($app,$env) {
        $env['skilldb']->renderdeleteview($id,$env['globalobj'],'Views/Skill/skilldelete.html.twig');

    })->name('viewskill');

$app->post(
    '/deleteskill',
    function () use($app,$env) {
        $env['skilldb']->deleteitem(htmlEntities($app->request()->post('id')));
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
            ,htmlEntities($app->request()->post('curricullumid'))
            ,htmlEntities($app->request()->post('name'))    
            ,htmlEntities($app->request()->post('description'))
            ,htmlEntities($app->request()->post('link'))
            ,htmlEntities($app->request()->post('imagename'))    
            ,$env['globalobj']    
            ,'Views/Project/projectnew.html.twig') ;

    })->name('insertproject');
    
  $app->get(
    '/editproject/:id',
    function ($id) use($app,$env) {
        $env['projectdb']->rendereditview($id,$env['globalobj'],'Views/Project/projectedit.html.twig');

    })->name('editproject');

$app->post(
    '/updateproject',
    function () use($app,$env) {
        $env['projectdb']->updateitem($env['globalobj']->getcurrentuser()
            ,htmlEntities($app->request()->post('id'))    
            ,htmlEntities($app->request()->post('curricullumid'))
            ,htmlEntities($app->request()->post('name'))    
            ,htmlEntities($app->request()->post('description'))
            ,htmlEntities($app->request()->post('link'))
            ,htmlEntities($app->request()->post('imagename'))     
        )

           ;
    })->name('updateproject');

$app->get(
    '/viewproject/:id',
    function ($id) use($app,$env) {
        $env['projectdb']->renderdeleteview($id,$env['globalobj'],'Views/Project/projectdelete.html.twig');

    })->name('viewproject');

$app->post(
    '/deleteproject',
    function () use($app,$env) {
        $env['projectdb']->deleteitem(htmlEntities($app->request()->post('id')));
    })->name('deleteproject');
  
    
    
//-----------------End project CRUD----------------------      
//-----------------projecttag CRUD---------------------
$app->get(
    '/projecttags',
    function () use($app,$env) {

        $env['projecttagdb']->rendergridview('Views/Projecttag/projecttags.html.twig');

    })->name('projecttags');

$app->get(
    '/newprojecttag',
    function () use($app,$env) {
        $env['projecttagdb']->rendernewview('','','',$env['globalobj'],'Views/Projecttag/projecttagnew.html.twig');
        
    })->name('newprojecttag');

$app->post(
    '/newprojecttag',
    function () use($app,$env) {

        $env['projecttagdb']->addnewitem($env['globalobj']->getcurrentuser()
                ,htmlEntities($app->request()->post('projectid'))
                ,htmlEntities($app->request()->post('tagname'))
                ,$env['globalobj']
                ,'Views/Projecttag/projecttagnew.html.twig') ;
        
    })->name('insertprojecttag');

$app->get(
    '/editprojecttag/:id',
    function ($id) use($app,$env) {
        $env['projecttagdb']->rendereditview($id,$env['globalobj'],'Views/Projecttag/projecttagedit.html.twig');

    })->name('editprojecttag');

$app->post(
    '/updateprojecttag',
    function () use($app,$env) {
    $env['projecttagdb']->updateitem($env['globalobj']->getcurrentuser(), htmlEntities($app->request()->post('id')),htmlEntities($app->request()->post('projectid')),htmlEntities($app->request()->post('tagname'))) ;
    })->name('updateprojecttag');  

$app->get(
    '/viewprojecttag/:id',
    function ($id) use($app,$env) {
        $env['projecttagdb']->renderdeleteview($id,$env['globalobj'],'Views/Projecttag/projecttagdelete.html.twig');

    })->name('viewprojecttag');

$app->post(
    '/deleteprojecttag',
    function () use($app,$env) {
        $env['projecttagdb']->deleteitem(htmlEntities($app->request()->post('id')));
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
                ,htmlEntities($app->request()->post('languagecode'))
                ,htmlEntities($app->request()->post('key'))
                ,htmlEntities($app->request()->post('translation'))
                ,'Views/Translatetag/translatetagnew.html.twig') ;
        
    })->name('inserttranslatetag');

$app->get(
    '/edittranslatetag/:parameter',
    function ($parameter) use($app,$env) {
        $env['translatetagdb']->rendereditview($parameter,$env['globalobj'],'Views/Translatetag/translatetagedit.html.twig');

    })->name('edittranslatetag');

$app->post(
    '/updatetranslatetag',
    function () use($app,$env) {
    $env['translatetagdb']->updateitem($env['globalobj']->getcurrentuser()
            ,$app->request()->post('languagecode')
            ,$app->request()->post('key')
            ,$app->request()->post('translation')
            ,$app->request()->post('parameter')) ;
    })->name('updatetranslatetag');  

$app->get(
    '/viewtranslatetag/:parameter',
    function ($parameter) use($app,$env) {
        $env['translatetagdb']->renderdeleteview($parameter,$env['globalobj'],'Views/Translatetag/translatetagdelete.html.twig');

    })->name('viewtranslatetag');

$app->post(
    '/deletetranslatetag',
    function () use($app,$env) {
        $env['translatetagdb']->deleteitem($app->request()->post('parameter'));
    })->name('deletetranslatetag');

//-----------------End translatetag CRUD----------------------        
//-----------------User CRUD---------------------
$app->get(
    '/users',
    function () use($app,$env) {

        $env['userdb']->rendergridview('Views/User/users.html.twig');

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
                ,htmlEntities($app->request()->post('username'))
                ,htmlEntities($app->request()->post('password'))
                ,htmlEntities($app->request()->post('email'))
                ,'Views/User/usernew.html.twig') ;
        
    })->name('insertuser');

$app->get(
    '/edituser/:id',
    function ($id) use($app,$env) {
        $env['userdb']->rendereditview($id,'Views/User/useredit.html.twig');

    })->name('edituser');

$app->post(
    '/updateuser',
    function () use($app,$env) {
    $env['userdb']->updateitem($env['globalobj']->getcurrentuser(), $app->request()->post('id'),$app->request()->post('username'),$app->request()->post('email')) ;
    })->name('updateuser');  

$app->get(
    '/viewuser/:id',
    function ($id) use($app,$env) {
        $env['userdb']->renderdeleteview($id,'Views/User/userdelete.html.twig');

    })->name('viewuser');

$app->post(
    '/deleteuser',
    function () use($app,$env) {
        $env['userdb']->deleteitem(htmlEntities($app->request()->post('id')));
    })->name('deleteuser');

 $app->get(
    '/passwordchange/:id',
    function ($id) use($app,$env) {
        $env['userdb']->renderpasswordchange($id,'Views/User/userpasswordchange.html.twig');

    })->name('passwordchange');   
    
  $app->post(
    '/changepassword',
    function () use($app,$env) {
    $env['userdb']->changepassword($env['globalobj']->getcurrentuser(), $app->request()->post('id'),$app->request()->post('password')) ;
    })->name('changepassword');   
    
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
                ,htmlEntities($app->request()->post('code'))
                ,htmlEntities($app->request()->post('value'))
                ,htmlEntities($app->request()->post('description'))
                ,'Views/Sysparam/sysparamnew.html.twig') ;
        
    })->name('insertsysparam');

$app->get(
    '/editsysparam/:id',
    function ($id) use($app,$env) {
        $env['sysparamdb']->rendereditview($id,'Views/Sysparam/sysparamedit.html.twig');

    })->name('editsysparam');

$app->post(
    '/updatesysparam',
    function () use($app,$env) {
    $env['sysparamdb']->updateitem($env['globalobj']->getcurrentuser()
            ,$app->request()->post('id')
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
    '/deletesysparam',
    function () use($app,$env) {
        $env['sysparamdb']->deleteitem(htmlEntities($app->request()->post('id')));
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
                ,htmlEntities($app->request()->post('sysparamid'))
                ,htmlEntities($app->request()->post('value'))
                ,htmlEntities($app->request()->post('valuedesc'))
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
    '/deletemultiparam',
    function () use($app,$env) {
        $env['multiparamdb']->deleteitem($app->request()->post('id'),$app->request()->post('sysparamid'));
    })->name('deletemultiparam');

//-----------------End multiparam CRUD----------------------     
$app->run();
