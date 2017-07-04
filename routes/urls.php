<?php
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
