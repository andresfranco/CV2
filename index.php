<?php
require 'Slim/Slim.php';
require_once 'Slim/View.php';
require 'Views/Twig.php';
require_once 'Backend/libraries/PHPMailer/class.phpmailer.php';
require_once 'Backend/libraries/PHPMailer/PHPMailerAutoload.php';
require_once 'Backend/libraries/medoo.php';
// Load backend Controllers
foreach (scandir('Backend/Controller') as $filename) {
    $path = 'Backend/Controller' . '/' . $filename;
    if (is_file($path)){require_once $path;}
}
session_cache_limiter(false);
session_start();
\Slim\Slim::registerAutoloader();
Twig_Autoloader::register();
$twigView = new Slim\Views\Twig();
//---Configure Slim --------------------------------------------
$app = new \Slim\Slim(array(
    'templates.path' => './Backend',
    'view' => $twigView
));
//---Set twig globals
$twig = $app->view()->getEnvironment();
$twig->addGlobal("session", $_SESSION);

$twigobj =$app->view()->getInstance();
//----Define enviroment variables-----------------------------
$medoo =new medoo();
$env = $app->environment();
$env['globalobj'] = new GlobalController();
$env['securityobj'] = new SecurityController($app, $medoo);
$env['languagedb'] = new LanguageController($app,$medoo);
$env['curricullumdb'] =new CurricullumController($app,$medoo);
$env['translationdb'] =new TranslationController($app,$medoo);
$env['educationdb'] = new EducationController($app, $medoo);
$env['workdb'] = new WorkController($app, $medoo);
$env['skilldb'] = new SkillController($app,$medoo);
$env['projectdb'] = new ProjectController($app, $medoo);
$env['projecttagdb'] = new ProjecttagController($app, $medoo);
$env['frontend'] = new FrontendController($app, $medoo, $env['globalobj'], $env['languagedb'], $env['translationdb'], $env['educationdb'], $env['skilldb'], $env['projectdb'], $env['projecttagdb']);
$env['manageemailobj'] =new ManageEmail($medoo);
$env['translatetagdb']=new TranslatetagController($app, $medoo);
$env['userdb']=new UserController($app,$medoo,$env['securityobj']);
$env['sysparamdb']=new SystemParameterController($app, $medoo);
$env['multiparamdb']=new MultiparamController($app,$medoo);
$env['urldb']=new UrlController($app, $medoo,$env['curricullumdb']);
$env['roledb']=new RoleController($app, $medoo);
$env['actiondb']=new ActionController($app,$medoo);
//------------------------------------------------------------
$twig->addGlobal("securityobj",$env['securityobj']);
//---------Set Base Url for css and scripts----------
$app->hook('slim.before', function () use ($app,$env) {
    $basepath = $env['globalobj']->getsysparam('basepath');
    $templatepath=$env['globalobj']->getsysparam('templatepath');
    $backendtitle=$env['globalobj']->getsysparam('apptitle');
    $app->view()->appendData(array('basepath' => $basepath,'templatepath'=>$templatepath,'backendtitle'=>$backendtitle));
});
// Load Routes 
require 'routes/info.php';
require 'routes/frontend.php';
require 'routes/login.php';
require 'routes/languages.php';
require 'routes/curricullum.php';
require 'routes/translation.php';
require 'routes/education.php';
require 'routes/work.php';
require 'routes/skills.php';
require 'routes/projects.php';
require 'routes/contact_form.php';
require 'routes/users.php';
require 'routes/user_roles.php';
require 'routes/sysparam.php';
require 'routes/roles.php';
require 'routes/urls.php'; 
require 'routes/actions.php'; 
 //Not found route--------------------------------------------
$app->notFound(function () use ($app) {
$app->render('Views/Error/404error.html.twig');
});
$app->run();