<?php
$app->get(
    '/info',
    function ()use($app,$env) {
      phpinfo();
    }
)->name('info');

