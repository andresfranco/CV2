<?php
$app->get(
    '/login',
    function ()use($app,$twigobj) {
         
         $app->render('Views/Security/login.html.twig',array('username'=>'','password'=>'','errormessage'=>'','loginaction'=>$app->urlFor('validateuser')));
    }
)->name('login');


$app->get(
    '/logout',
    function ()use($app,$env) {
    $env['securityobj']->logout();
    }
)->name('logout');

$app->post(
    '/validateuser',
    function ()use($app,$env) {
         $username=$app->request()->post('username');
         $password = $app->request()->post('password');
         $env['securityobj']->validatepassword($username,$password);
    }
)->name('validateuser');
$app->get(
    '/home',
    function ()use($app) {
        $app->render('Views/Home/home.html.twig',array('link'=>'/home','option'=>'Home','route'=>''));
    }
)->name('home');

