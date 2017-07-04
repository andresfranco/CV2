<?php
$app->get(
    '/roles',
    function () use($app,$env) {

        $env['roledb']->rendergridview($env['globalobj'],'Views/Role/roles.html.twig');

    })->name('roles');

$app->get(
    '/newrole',
    function () use($app,$env) {
        $env['roledb']->rendernewview('','','','Views/Role/rolenew.html.twig');
        
    })->name('newrole');

$app->post(
    '/newrole',
    function () use($app,$env) {

        $env['roledb']->addnewitem($env['globalobj']->getcurrentuser()
                ,$app->request()->post('role') 
                ,$app->request()->post('description')
                ,'Views/Role/rolenew.html.twig') ;
        
    })->name('insertrole');

$app->get(
    '/editrole/:id',
    function ($id) use($app,$env) {
        $env['roledb']->rendereditview($id,'Views/Role/roleedit.html.twig');

    })->name('editrole');

$app->post(
    '/updaterole/:id',
    function ($id) use($app,$env) {
    $env['roledb']->updateitem($env['globalobj']->getcurrentuser()
            ,$id
            ,$app->request()->post('role')   
            ,$app->request()->post('description')) ;
    })->name('updaterole');  

$app->get(
    '/viewrole/:id',
    function ($id) use($app,$env) {
        $env['roledb']->renderdeleteview($id,'Views/Role/roledelete.html.twig');

    })->name('viewrole');

$app->post(
    '/deleterole/:id',
    function ($id) use($app,$env) {
        $env['roledb']->deleteitem($id);
    })->name('deleterole');
    
    //--- Role Actions---------------------------------
    $app->get(
    '/roleactions/:id',
    function ($id) use($app,$env) {
        $env['roledb']->render_roleactions_grid($id,'Views/Role/roleactions.html.twig');

    })->name('roleactions');
    
     $app->get(
    '/newroleaction/:roleid',
    function ($roleid) use($app,$env) {
        $env['roledb']->render_new_roleaction($roleid,'Views/Role/newroleaction.html.twig');

    })->name('newroleaction');
    $app->post(
    '/newroleaction/:roleid',
    function ($roleid) use($app,$env) {
     $env['roledb']->add_new_roleaction($env['globalobj']->getcurrentuser(),$roleid,$app->request()->post('actionid'),'Views/Role/newroleaction.html.twig');

    })->name('insertroleaction');
    
    $app->get(
    '/viewroleaction/:roleid/:actionid',
    function ($roleid,$actionid) use($app,$env) {
        $env['roledb']->render_delete_roleaction($roleid,$actionid,'Views/Role/deleteroleaction.html.twig');

    })->name('viewroleaction');

    $app->post(
    '/deleteroleaction/:roleid/:actionid',
    function ($roleid,$actionid) use($app,$env) {
        $env['roledb']->delete_roleaction_item($roleid,$actionid);
    })->name('deleteroleaction');
