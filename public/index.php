<?php

use Zend\Diactoros\ServerRequestFactory;
use Framework\Http\Application;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
session_start();

$container = require 'config/container.php';
$app = $container->get(Application::class);

require 'config/pipeline.php';
require 'config/routes.php';

$class = \Framework\Http\Middleware\RouteMiddleware::class;

$reflection = new ReflectionClass($class);

$arguments = [];

if (($constructor = $reflection->getConstructor()) !== null) {
    foreach ($constructor->getParameters() as $param) {
        $arguments[] = $container->get($param->getClass()->getName());
    }
}

$middleware = new $class(...$arguments);

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
