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
$languagedb = new LanguageController($app,$medoo);
$curricullumdb = new CurricullumController($app,$medoo);
$translationdb = new TranslationController($app,$medoo);
$educationdb = new EducationController($app, $medoo);

//----Define enviroment variables-----------------------------
$env = $app->environment();
$env['globalobj'] = $globalobj;
$env['languagedb'] = $languagedb;
$env['curricullumdb'] = $curricullumdb;
$env['translationdb'] = $translationdb;
$env['educationdb'] = $educationdb;

//------------------------------------------------------------
//-----------------Homepage-----------------------------------

$app->get(
    '/',
    function () {
        $template = <<<EOT
<!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8"/>
            <title>Slim Framework for PHP 5</title>
            <style>
                html,body,div,span,object,iframe,
                h1,h2,h3,h4,h5,h6,p,blockquote,pre,
                abbr,address,cite,code,
                del,dfn,em,img,ins,kbd,q,samp,
                small,strong,sub,sup,var,
                b,i,
                dl,dt,dd,ol,ul,li,
                fieldset,form,label,legend,
                table,caption,tbody,tfoot,thead,tr,th,td,
                article,aside,canvas,details,figcaption,figure,
                footer,header,hgroup,menu,nav,section,summary,
                time,mark,audio,video{margin:0;padding:0;border:0;outline:0;font-size:100%;vertical-align:baseline;background:transparent;}
                body{line-height:1;}
                article,aside,details,figcaption,figure,
                footer,header,hgroup,menu,nav,section{display:block;}
                nav ul{list-style:none;}
                blockquote,q{quotes:none;}
                blockquote:before,blockquote:after,
                q:before,q:after{content:'';content:none;}
                a{margin:0;padding:0;font-size:100%;vertical-align:baseline;background:transparent;}
                ins{background-color:#ff9;color:#000;text-decoration:none;}
                mark{background-color:#ff9;color:#000;font-style:italic;font-weight:bold;}
                del{text-decoration:line-through;}
                abbr[title],dfn[title]{border-bottom:1px dotted;cursor:help;}
                table{border-collapse:collapse;border-spacing:0;}
                hr{display:block;height:1px;border:0;border-top:1px solid #cccccc;margin:1em 0;padding:0;}
                input,select{vertical-align:middle;}
                html{ background: #EDEDED; height: 100%; }
                body{background:#FFF;margin:0 auto;min-height:100%;padding:0 30px;width:440px;color:#666;font:14px/23px Arial,Verdana,sans-serif;}
                h1,h2,h3,p,ul,ol,form,section{margin:0 0 20px 0;}
                h1{color:#333;font-size:20px;}
                h2,h3{color:#333;font-size:14px;}
                h3{margin:0;font-size:12px;font-weight:bold;}
                ul,ol{list-style-position:inside;color:#999;}
                ul{list-style-type:square;}
                code,kbd{background:#EEE;border:1px solid #DDD;border:1px solid #DDD;border-radius:4px;-moz-border-radius:4px;-webkit-border-radius:4px;padding:0 4px;color:#666;font-size:12px;}
                pre{background:#EEE;border:1px solid #DDD;border-radius:4px;-moz-border-radius:4px;-webkit-border-radius:4px;padding:5px 10px;color:#666;font-size:12px;}
                pre code{background:transparent;border:none;padding:0;}
                a{color:#70a23e;}
                header{padding: 30px 0;text-align:center;}
            </style>
        </head>
        <body>
            <header>
                <a href="http://www.slimframework.com"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAHIAAAA6CAYAAABs1g18AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAABRhJREFUeNrsXY+VsjAMR98twAo6Ao4gI+gIOIKOgCPICDoCjCAjXFdgha+5C3dcv/QfFB5i8h5PD21Bfk3yS9L2VpGnlGW5kS9wJMTHNRxpmjYRy6SycgRvL18OeMQOTYQ8HvIoJKiiz43hgHkq1zvK/h6e/TyJQXeV/VyWBOSHA4C5RvtMAiCc4ZB9FPjgRI8+YuKcrySO515a1hoAY3nc4G2AH52BZsn+MjaAEwIJICKAIR889HljMCcyrR0QE4v/q/BVBQva7Q1tAczG18+x+PvIswHEAslLbfGrMZKiXEOMAMy6LwlisQCJLPFMfKdBtli5dIihRyH7A627Iaiq5sJ1ThP9xoIgSdWSNVIHYmrTQgOgRyRNqm/M5PnrFFopr3F6B41cd8whRUSufUBU5EL4U93AYRnIWimCIiSI1wAaAZpJ9bPnxx8eyI3Gt4QybwWa6T/BvbQECUMQFkhd3jSkPFgrxwcynuBaNT/u6eJIlbGOBWSNIUDFEIwPZFAtBfYrfeIOSRSXuUYCsprCXwUIZWYnmEhJFMIocMDWjn206c2EsGLCJd42aWSyBNMnHxLEq7niMrY2qyDbQUbqrrTbwUPtxN1ZZCitQV4ZSd6DyoxhmRD6OFjuRUS/KdLGRHYowJZaqYgjt9Lchmi3QYA/cXBsHK6VfWNR5jgA1DLhwfFe4HqfODBpINEECCLO47LT/+HSvSd/OCOgQ8qE0DbHQUBqpC4BkKMPYPkFY4iAJXhGAYr1qmaqQDbECCg5A2NMchzR567aA4xcRKclI405Bmt46vYD7/Gcjqfk6GP/kh1wovIDSHDfiAs/8bOCQ4cf4qMt7eH5Cucr3S0aWGFfjdLHD8EhCFvXQlSqRrY5UV2O9cfZtk77jUFMXeqzCEZqSK4ICkSin2tE12/3rbVcE41OBjBjBPSdJ1N5lfYQpIuhr8axnyIy5KvXmkYnw8VbcwtTNj7fDNCmT2kPQXA+bxpEXkB21HlnSQq0gD67jnfh5KavVJa/XQYEFSaagWwbgjNA+ywstLpEWTKgc5gwVpsyO1bTII+tA6B7BPS+0PiznuM9gPKsPVXbFdADMtwbJxSmkXWfRh6AZhyyzBjIHoDmnCGaMZAKjd5hyNJYCBGDOVcg28AXQ5atAVDO3c4dSALQnYblfa3M4kc/cyA7gMIUBQCTyl4kugIpy8yA7ACqK8Uwk30lIFGOEV3rPDAELwQkr/9YjkaCPDQhCcsrAYlF1v8W8jAEYeQDY7qn6tNGWudfq+YUEr6uq6FZzBpJMUfWFDatLHMCciw2mRC+k81qCCA1DzK4aUVfrJpxnloZWCPVnOgYy8L3GvKjE96HpweQoy7iwVQclVutLOEKJxA8gaRCjSzgNI2zhh3bQhzBCQQPIHGaHaUd96GJbZz3Smmjy16u6j3FuKyNxcBarxqWWfYFE0tVVO1Rl3t1Mb05V00MQCJ71YHpNaMcsjWAfkQvPPkaNC7LqTG7JAhGXTKYf+VDeXAX9IvURoAwtTFHvyYIxtnd5tPkywrPafcwbeSuGVwFau3b76NO7SHQrvqhfFE8kM0Wvpv8gVYiYBlxL+fW/34bgP6bIC7JR7YPDubcHCPzIp4+cum7U6NlhZgK7lua3KGLeFwE2m+HblDYWSHG2SAfINuwBBfxbJEIuWZbBH4fAExD7cvaGVyXyH0dhiAYc92z3ZDfUVv+jgb8HrHy7WVO/8BFcy9vuTz+nwADAGnOR39Yg/QkAAAAAElFTkSuQmCC" alt="Slim"/></a>
            </header>
            <h1>Welcome to Slim!</h1>
            <p>
                Congratulations! Your Slim application is running. If this is
                your first time using Slim, start with this <a href="http://docs.slimframework.com/#Hello-World" target="_blank">"Hello World" Tutorial</a>.
            </p>
            <section>
                <h2>Get Started</h2>
                <ol>
                    <li>The application code is in <code>index.php</code></li>
                    <li>Read the <a href="http://docs.slimframework.com/" target="_blank">online documentation</a></li>
                    <li>Follow <a href="http://www.twitter.com/slimphp" target="_blank">@slimphp</a> on Twitter</li>
                </ol>
            </section>
            <section>
                <h2>Slim Framework Community</h2>

                <h3>Support Forum and Knowledge Base</h3>
                <p>
                    Visit the <a href="http://help.slimframework.com" target="_blank">Slim support forum and knowledge base</a>
                    to read announcements, chat with fellow Slim users, ask questions, help others, or show off your cool
                    Slim Framework apps.
                </p>

                <h3>Twitter</h3>
                <p>
                    Follow <a href="http://www.twitter.com/slimphp" target="_blank">@slimphp</a> on Twitter to receive the very latest news
                    and updates about the framework.
                </p>
            </section>
            <section style="padding-bottom: 20px">
                <h2>Slim Framework Extras</h2>
                <p>
                    Custom View classes for Smarty, Twig, Mustache, and other template
                    frameworks are available online in a separate repository.
                </p>
                <p><a href="https://github.com/codeguy/Slim-Extras" target="_blank">Browse the Extras Repository</a></p>
            </section>
        </body>
    </html>
EOT;
        echo $template;
    }
);
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

$app->run();
