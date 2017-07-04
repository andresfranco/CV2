<?php
$app->get(
    '/translations',
    function () use($app,$env) {

        $env['translationdb']->rendergridview($env['globalobj'],'Views/Translation/translationlist.html.twig');

    })->name('translations');

$app->get(
    '/newtranslation',
    function () use($app,$env) {
        $env['translationdb']->rendernewview('','','','','','','',$env['globalobj'],'Views/Translation/translationnew.html.twig');

    })->name('newtranslation');

$app->post(
    '/newtranslation',
    function () use($app,$env) {
        $env['translationdb']->addnewitem($env['globalobj']->getcurrentuser()
            ,$app->request()->post('objectcode')
            ,$app->request()->post('parentid')    
            ,$app->request()->post('objectid')
            ,$app->request()->post('languagecode')
            ,$app->request()->post('field')
            ,$app->request()->post('translationcontent')
            ,$env['globalobj']    
            ,'Views/Translation/translationnew.html.twig') ;

    })->name('inserttranslation');
    
  $app->post(
    '/getfieldsajax',
    function () use($app,$env) {
     $env['translationdb']->getfieldsajax($app->request->post('objectcode'),$app->request->post('field'),$env['globalobj'],'curricullum');    
    
    })->name('getfields');  
    
   $app->post(
    '/getparentajax',
    function () use($app,$env) {
     $env['translationdb']->getparentajax($app->request->post('objectcode'),$env['globalobj']);  
       
    })->name('getparent');  
    
    $app->post(
    '/getobjectidlistajax',
    function () use($app,$env) {
     $env['translationdb']->getobjectidlistajax($env['globalobj']);  
       
    })->name('getobjectidlist');
    
     $app->post(
    '/getobjectsajax',
    function () use($app,$env) {
     $env['translationdb']->getobjects($app->request->post('objectcode'),$app->request->post('parentid'),$app->request->post('objectid'),$env['globalobj']);  
       
    })->name('getobjects');

$app->get(
    '/edittranslation/:id',
    function ($id) use($app,$env) {
        $env['translationdb']->rendereditview($id,$env['globalobj'],'Views/Translation/translationedit.html.twig');

    })->name('edittranslation');

$app->post(
    '/updatetranslation/:id',
    function ($id) use($app,$env) {
        $env['translationdb']->updateitem($env['globalobj']->getcurrentuser()
            ,$id
            ,$app->request()->post('objectcode')
            ,$app->request()->post('parentid')
            ,$app->request()->post('objectid')
            ,$app->request()->post('languagecode')
            ,$app->request()->post('field')
            ,$app->request()->post('translationcontent')
        )

           ;
    })->name('updatetranslation');

$app->get(
    '/viewtranslation/:id',
    function ($id) use($app,$env) {
        $env['translationdb']->renderdeleteview($id,$env['globalobj'],'Views/Translation/translationdelete.html.twig');

    })->name('viewtranslation');

$app->post(
    '/deletetranslation/:id',
    function ($id) use($app,$env) {
        $env['translationdb']->deleteitem($id);
    })->name('deletetranslation');
    
    //-----------------Translatetag CRUD---------------------
$app->get(
    '/translatetags',
    function () use($app,$env) {

        $env['translatetagdb']->rendergridview($env['globalobj'],'Views/Translatetag/translatetags.html.twig');

    })->name('translatetags');

$app->get(
    '/newtranslatetag',
    function () use($app,$env) {
        $env['translatetagdb']->rendernewview('','','','',$env['globalobj'],'Views/Translatetag/translatetagnew.html.twig');
        
    })->name('newtranslatetag');

$app->post(
    '/newtranslatetag',
    function () use($app,$env) {

        $env['translatetagdb']->addnewitem($env['globalobj']->getcurrentuser()
                ,$app->request()->post('languagecode')
                ,$app->request()->post('key')
                ,$app->request()->post('translation')
                ,'Views/Translatetag/translatetagnew.html.twig') ;
        
    })->name('inserttranslatetag');

$app->get(
    '/edittranslatetag/:parameter',
    function ($parameter) use($app,$env) {
        $env['translatetagdb']->rendereditview($parameter,$env['globalobj'],'Views/Translatetag/translatetagedit.html.twig');

    })->name('edittranslatetag');

$app->post(
    '/updatetranslatetag/:parameter',
    function ($parameter) use($app,$env) {
    
    $env['translatetagdb']->updateitem($env['globalobj']->getcurrentuser()
            ,$app->request()->post('languagecode')
            ,$app->request()->post('key')
            ,$app->request()->post('translation')
            ,$parameter) ;
    })->name('updatetranslatetag');  

$app->get(
    '/viewtranslatetag/:parameter',
    function ($parameter) use($app,$env) {
        $env['translatetagdb']->renderdeleteview($parameter,$env['globalobj'],'Views/Translatetag/translatetagdelete.html.twig');

    })->name('viewtranslatetag');

$app->post(
    '/deletetranslatetag/:parameter',
    function ($parameter) use($app,$env) {
        $env['translatetagdb']->deleteitem($parameter);
    })->name('deletetranslatetag');
