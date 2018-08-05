<?php

use Zend\Diactoros\ServerRequestFactory;
use Framework\Http\Application;

/*
|--------------------------------------------------------------------------
| Autoload
|--------------------------------------------------------------------------
*/
chdir(dirname(__DIR__));
require 'vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Initialization
|--------------------------------------------------------------------------
*/
$container = require 'config/container.php';
$app = $container->get(Application::class);

require 'config/pipeline.php';
require 'config/routes.php';

/*
|--------------------------------------------------------------------------
| Running
|--------------------------------------------------------------------------
*/
$request = ServerRequestFactory::fromGlobals();
$response = $app->run($request);

/*
|--------------------------------------------------------------------------
| Response
|--------------------------------------------------------------------------
*/
$sender = new \Zend\HttpHandlerRunner\Emitter\SapiEmitter();
$sender->emit($response);
