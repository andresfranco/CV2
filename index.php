<?php
//---------------Require Classes----------------------------
require 'Slim/Slim.php';
require_once 'Slim/View.php';
require 'Views/Twig.php';
require_once 'Backend/libraries/medoo.php';
require_once 'Backend/Controller/GlobalController.php';
require_once 'Backend/Controller/LanguageController.php';
require_once 'Backend/Controller/CurricullumController.php';
require_once 'Backend/Controller/TranslationController.php';
require_once 'Backend/Controller/EducationController.php';
require_once 'Backend/Controller/WorkController.php';
require_once 'Backend/Controller/SecurityController.php';

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

//-------Set objects from classes-----------------------------
$medoo =new medoo();
$globalobj =new GlobalController();
$securityobj=new SecurityController($app, $medoo);
$languagedb = new LanguageController($app,$medoo);
$curricullumdb = new CurricullumController($app,$medoo);
$translationdb = new TranslationController($app,$medoo);
$educationdb = new EducationController($app, $medoo);
$workdb = new WorkController($app, $medoo);

//----Define enviroment variables-----------------------------
$env = $app->environment();
$env['globalobj'] = $globalobj;
$env['securityobj'] = $securityobj;
$env['languagedb'] = $languagedb;
$env['curricullumdb'] = $curricullumdb;
$env['translationdb'] = $translationdb;
$env['educationdb'] = $educationdb;
$env['workdb'] = $workdb;
//------------------------------------------------------------
//-----------------Login-----------------------------------

$app->get(
    '/login',
    function ()use($app) {
         $app->render('Views/Security/login.html.twig',array('username'=>'','password'=>'','errormessage'=>'','loginaction'=>$app->urlFor('validateuser')));
    }
);

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
$app->run();
