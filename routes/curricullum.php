<?php
$app->get(
    '/curricullumlist',
    function () use($app,$env) {

        $env['curricullumdb']->rendergridview($env['globalobj'],'Views/Curricullum/curricullumlist.html.twig');

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
    
    
 //----Curricullum Picture --------------------------
    
    $app->get(
    '/curricullumpicture/:id',
    function ($id) use($app,$env) {
        $env['curricullumdb']->render_curricullum_picture($id,'','Views/Curricullum/curricullumpicture.html.twig');

    })->name('curricullumpicture');
    
    $app->post(
    '/updateprofilepicture/:id',
    function ($id) use($app,$env) {
        $env['curricullumdb']->update_picture_item($env['globalobj']->getcurrentuser(),$id ,$_FILES,'Views/Curricullum/curricullumpicture.html.twig');

    })->name('updateprofilepicture');  
 //--------------------------------------------------
    
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
    