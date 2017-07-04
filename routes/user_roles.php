<?php
 $app->get(
    '/userroles/:id',
    function ($id) use($app,$env) {
        $env['userdb']->render_userroles_grid($id,'Views/User/userroles.html.twig');

    })->name('userroles');
    
     $app->get(
    '/newuserrole/:userid',
    function ($userid) use($app,$env) {
        $env['userdb']->render_new_userrole($userid,'Views/User/newuserrole.html.twig');

    })->name('newuserrole');
    $app->post(
    '/newuserrole/:userid',
    function ($userid) use($app,$env) {
     $env['userdb']->add_new_userrole($env['globalobj']->getcurrentuser(),$userid,$app->request()->post('roleid'),'Views/User/newuserrole.html.twig');

    })->name('insertuserrole');
    
    $app->get(
    '/viewuserrole/:userid/:roleid',
    function ($userid,$roleid) use($app,$env) {
        $env['userdb']->render_delete_userrole($userid,$roleid,'Views/User/deleteuserrole.html.twig');

    })->name('viewuserrole');

    $app->post(
    '/deleteuserrole/:userid/:roleid',
    function ($userid,$roleid) use($app,$env) {
        $env['userdb']->delete_userrole_item($userid,$roleid);
    })->name('deleteuserrole');
