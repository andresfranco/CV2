<?php
$app->get(
    '/educationlist',
    function () use($app,$env) {

        $env['educationdb']->rendergridview($env['globalobj'],'Views/Education/educationlist.html.twig');

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
            ,$app->request()->post('curricullumid')
            ,$app->request()->post('institution')    
            ,$app->request()->post('degree')
            ,$app->request()->post('date')
            ,$env['globalobj']    
            ,'Views/Education/educationnew.html.twig') ;

    })->name('inserteducation');
    
  $app->get(
    '/editeducation/:id',
    function ($id) use($app,$env) {
        $env['educationdb']->rendereditview($id,$env['globalobj'],'Views/Education/educationedit.html.twig');

    })->name('editeducation');

$app->post(
    '/updateeducation/:id',
    function ($id) use($app,$env) {
        $env['educationdb']->updateitem($env['globalobj']->getcurrentuser()
            ,$id    
            ,$app->request()->post('curricullumid')
            ,$app->request()->post('institution')    
            ,$app->request()->post('degree')
            ,$app->request()->post('date')  
        )

           ;
    })->name('updateeducation');

$app->get(
    '/vieweducation/:id',
    function ($id) use($app,$env) {
        $env['educationdb']->renderdeleteview($id,$env['globalobj'],'Views/Education/educationdelete.html.twig');

    })->name('vieweducation');

$app->post(
    '/deleteeducation/:id',
    function ($id) use($app,$env) {
        $env['educationdb']->deleteitem($id);
    })->name('deleteeducation');
  
    
    