<?php
$app->get(
    '/users',
    function () use($app,$env) {

        $env['userdb']->rendergridview($env['globalobj'],'Views/User/users.html.twig');

    })->name('users');

$app->get(
    '/newuser',
    function () use($app,$env) {
        $env['userdb']->rendernewview('','','','','Views/User/usernew.html.twig');
        
    })->name('newuser');

$app->post(
    '/newuser',
    function () use($app,$env) {

        $env['userdb']->addnewitem($env['globalobj']->getcurrentuser()
                ,$app->request()->post('username')
                ,$app->request()->post('password')
                ,$app->request()->post('email')
                ,'Views/User/usernew.html.twig') ;
        
    })->name('insertuser');

$app->get(
    '/edituser/:id',
    function ($id) use($app,$env) {
        $env['userdb']->rendereditview($id,'Views/User/useredit.html.twig');

    })->name('edituser');

$app->post(
    '/updateuser/:id',
    function ($id) use($app,$env) {
    $env['userdb']->updateitem($env['globalobj']->getcurrentuser(),$id,$app->request()->post('username'),$app->request()->post('email')) ;
    })->name('updateuser');  

$app->get(
    '/viewuser/:id',
    function ($id) use($app,$env) {
        $env['userdb']->renderdeleteview($id,'Views/User/userdelete.html.twig');

    })->name('viewuser');

$app->post(
    '/deleteuser/:id',
    function ($id) use($app,$env) {
        $env['userdb']->deleteitem($id);
    })->name('deleteuser');

 $app->get(
    '/passwordchange/:id',
    function ($id) use($app,$env) {
        $env['userdb']->renderpasswordchange($id,'Views/User/userpasswordchange.html.twig');

    })->name('passwordchange');   
    
  $app->post(
    '/changepassword/:id',
    function ($id) use($app,$env) {
    $env['userdb']->changepassword($env['globalobj']->getcurrentuser(),$id,$app->request()->post('password')) ;
    })->name('changepassword');   
    