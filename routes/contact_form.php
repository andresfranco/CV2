<?php
 
    $app->post(
    '/sendemail',
    function () use($app,$env) {
        $env['manageemailobj']->sendemailaction($app->request()->post('contactName')
                ,$app->request()->post('contactEmail')
                ,$app->request()->post('contactSubject')
                ,$app->request()->post('contactMessage'));
    })->name('sendemail');
    
