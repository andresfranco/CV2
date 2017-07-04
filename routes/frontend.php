<?php
$app->get(
    '/',
    function ()use($app,$env) {
    $languagecode=$env['globalobj']->getsysparam('lang');
    $cvid=$env['globalobj']->getcurricullumidbyparam();
    $app->response->redirect('./main'.'/'.$languagecode.'/'.$cvid);
    }
)->name('root');

$app->get(
    '/notfound',
    function ()use($app,$env) {
    $app->render('Views/Error/404error.html.twig');
    }
)->name('notfound');


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
