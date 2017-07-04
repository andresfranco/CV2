<?php
$app->get(
    '/sysparams',
    function () use($app,$env) {

        $env['sysparamdb']->rendergridview('Views/Sysparam/sysparams.html.twig',$env['globalobj']);

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