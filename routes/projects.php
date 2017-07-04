<?php
$app->get(
    '/projects',
    function () use($app,$env) {

        $env['projectdb']->rendergridview($env['globalobj'],'Views/Project/projects.html.twig');

    })->name('projects');

$app->get(
    '/newproject',
    function () use($app,$env) {
       $env['projectdb']->rendernewview('','','','','','',$env['globalobj'],'Views/Project/projectnew.html.twig');

    })->name('newproject');
    
 $app->post(
    '/newproject',
    function () use($app,$env) {
        $env['projectdb']->addnewitem($env['globalobj']->getcurrentuser()
            ,$app->request()->post('curricullumid')
            ,$app->request()->post('name')   
            ,$app->request()->post('description')
            ,$app->request()->post('link')
            ,$_FILES
            ,$env['globalobj']    
            ,'Views/Project/projectnew.html.twig') ;

    })->name('insertproject');
    
  $app->get(
    '/editproject/:id',
    function ($id) use($app,$env) {
        $env['projectdb']->rendereditview($id,$env['globalobj'],'Views/Project/projectedit.html.twig');

    })->name('editproject');

$app->post(
    '/updateproject/:id',
    function ($id) use($app,$env) {
        $env['projectdb']->updateitem($env['globalobj']->getcurrentuser()
            ,$id  
            ,$app->request()->post('curricullumid')
            ,$app->request()->post('name')    
            ,$app->request()->post('description')
            ,$app->request()->post('link')
              
        );
    })->name('updateproject');

$app->get(
    '/viewproject/:id',
    function ($id) use($app,$env) {
        $env['projectdb']->renderdeleteview($id,$env['globalobj'],'Views/Project/projectdelete.html.twig');

    })->name('viewproject');

$app->post(
    '/deleteproject/:id',
    function ($id) use($app,$env) {
        $env['projectdb']->deleteitem($id);
    })->name('deleteproject');
  
 $app->get(
    '/projectpicture/:id',
    function ($id) use($app,$env) {
        $env['projectdb']->render_project_picture($id,'Views/Project/projectpicture.html.twig','');

    })->name('projectpicture');
    
 $app->post(
    '/updatepicture/:id',
    function ($id) use($app,$env) {
        $env['projectdb']->update_picture_item($env['globalobj']->getcurrentuser(),$id ,$_FILES,'Views/Project/projectpicture.html.twig');

    })->name('updatepicture');  
    
    
    
//-----------------End project CRUD----------------------      
//-----------------projecttag CRUD---------------------
$app->get(
    '/projecttags/:projectid',
    function ($projectid) use($app,$env) {

        $env['projecttagdb']->rendergridview($projectid,'Views/Projecttag/projecttags.html.twig');

    })->name('projecttags');

$app->get(
    '/newprojecttag/:projectid',
    function ($projectid) use($app,$env) {
        $env['projecttagdb']->rendernewview($projectid,'','','Views/Projecttag/projecttagnew.html.twig');
        
    })->name('newprojecttag');

$app->post(
    '/newprojecttag',
    function () use($app,$env) {

        $env['projecttagdb']->addnewitem($env['globalobj']->getcurrentuser()
                ,$app->request()->post('projectid')
                ,$app->request()->post('tagname')
                ,'Views/Projecttag/projecttagnew.html.twig') ;
        
    })->name('insertprojecttag');

$app->get(
    '/editprojecttag/:id',
    function ($id) use($app,$env) {
        $env['projecttagdb']->rendereditview($id,'Views/Projecttag/projecttagedit.html.twig');

    })->name('editprojecttag');

$app->post(
    '/updateprojecttag/:id',
    function ($id) use($app,$env) {
    $env['projecttagdb']->updateitem($env['globalobj']->getcurrentuser(),$id,$app->request()->post('projectid'),$app->request()->post('tagname')) ;
    })->name('updateprojecttag');  

$app->get(
    '/viewprojecttag/:id',
    function ($id) use($app,$env) {
        $env['projecttagdb']->renderdeleteview($id,'Views/Projecttag/projecttagdelete.html.twig');

    })->name('viewprojecttag');

$app->post(
    '/deleteprojecttag/:id',
    function ($id) use($app,$env) {
        $env['projecttagdb']->deleteitem($id);
    })->name('deleteprojecttag');

