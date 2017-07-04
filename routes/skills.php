<?php
$app->get(
    '/skills',
    function () use($app,$env) {

        $env['skilldb']->rendergridview($env['globalobj'],'Views/Skill/skills.html.twig');

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
            ,$app->request()->post('curricullumid')
            ,$app->request()->post('type')    
            ,$app->request()->post('skill')
            ,$app->request()->post('percentage')
            ,$env['globalobj']    
            ,'Views/Skill/skillnew.html.twig') ;

    })->name('insertskill');
    
  $app->get(
    '/editskill/:id',
    function ($id) use($app,$env) {
        $env['skilldb']->rendereditview($id,$env['globalobj'],'Views/Skill/skilledit.html.twig');

    })->name('editskill');

$app->post(
    '/updateskill/:id',
    function ($id) use($app,$env) {
        $env['skilldb']->updateitem($env['globalobj']->getcurrentuser()
            ,$id    
            ,$app->request()->post('curricullumid')
            ,$app->request()->post('type')    
            ,$app->request()->post('skill')
            ,$app->request()->post('percentage')  
        )

           ;
    })->name('updateskill');

$app->get(
    '/viewskill/:id',
    function ($id) use($app,$env) {
        $env['skilldb']->renderdeleteview($id,$env['globalobj'],'Views/Skill/skilldelete.html.twig');

    })->name('viewskill');

$app->post(
    '/deleteskill/:id',
    function ($id) use($app,$env) {
        $env['skilldb']->deleteitem($id);
    })->name('deleteskill');
  
    