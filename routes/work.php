<?php
 $app->get(
    '/worklist',
    function () use($app,$env) {

        $env['workdb']->rendergridview($env['globalobj'],'Views/Work/worklist.html.twig');

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
            ,$app->request()->post('curricullumid')
            ,$app->request()->post('company')    
            ,$app->request()->post('position')
            ,$app->request()->post('from')
            ,$app->request()->post('to') 
            ,$env['globalobj']    
            ,'Views/Work/worknew.html.twig') ;

    })->name('insertwork');
    
  $app->get(
    '/editwork/:id',
    function ($id) use($app,$env) {
        $env['workdb']->rendereditview($id,$env['globalobj'],'Views/Work/workedit.html.twig');

    })->name('editwork');

$app->post(
    '/updatework/:id',
    function ($id) use($app,$env) {
        $env['workdb']->updateitem($env['globalobj']->getcurrentuser()
            ,$id   
            ,$app->request()->post('curricullumid')
            ,$app->request()->post('company')   
            ,$app->request()->post('position')
            ,$app->request()->post('from')
            ,$app->request()->post('to')   
        )

           ;
    })->name('updatework');

$app->get(
    '/viewwork/:id',
    function ($id) use($app,$env) {
        $env['workdb']->renderdeleteview($id,$env['globalobj'],'Views/Work/workdelete.html.twig');

    })->name('viewwork');

$app->post(
    '/deletework/:id',
    function ($id) use($app,$env) {
        $env['workdb']->deleteitem($id);
    })->name('deletework');
