<?php
$app->get(
    '/languages',
    function () use($app,$env) {

        $env['languagedb']->rendergridview($env['globalobj'],'Views/Language/languagelist.html.twig');

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

