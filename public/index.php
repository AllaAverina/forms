<?php

use Forms\Views\View;
use Forms\Helpers\Session;
use Forms\Routing\Router;
use Forms\DI\Container;

//настройки для разработки
ini_set('display_errors', 1);
ini_set('log_errors', 1);

require_once __DIR__ . '/../vendor/autoload.php';

set_exception_handler(function ($e) {
    error_log($e->getMessage(), 0);
    View::renderError(500);
});

Session::start();
(new Router(new Container))->run();
