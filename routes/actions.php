<?php
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

