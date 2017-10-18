<?php
$skillFields =['curricullumid'=>$curricullumid
              ,'type'=>$type
              ,'skill'=>$skill
              ,'percentage'=>$percentage
              ,'level'=>$level
              ,'active'=>$active
              ,'description'=>$description
              ,'errormessage'=>$errormessage];
$app->post(
    '/skillroutes',
    function () use($app,$env) { 
    echo json_encode( $env['skilldb']->getSkillRoutes(),JSON_UNESCAPED_SLASHES);              
    })->name('skillRoutes');


$app->get('/skills',
    function () use($app,$env) {
        $env['skilldb']->rendergridview();
    })->name('skills');

$app->get('/skill(/:id)',
    function ($id=0) use($app,$env,$skillForm) {
       $skillForm['id']=$id;
       $env['skilldb']->renderSkillForm($skillForm);
    })->name('skillForm');

    
 $app->post(
    '/skill',
    function () use($app,$env) {       
        $env['skilldb']->addnewitem($app->request->post());
    })->name('insertskill');
    

$app->post('/skill/:id',
    function ($id) use($app,$env) {
        $env['skilldb']->updateitem($id,$app->request()->post()); 
    })->name('updateskill');

$app->get('/viewskill/:id',
    function ($id) use($app,$env) {
      $env['skilldb']->renderdeleteview($id);
    })->name('viewskill');

$app->post('/skill/delete/:id',
    function ($id) use($app,$env) {
       $env['skilldb']->deleteitem($id); 
    })->name('deleteskill');
  
    