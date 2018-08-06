<?php

use Zend\Diactoros\ServerRequestFactory;
use Framework\Http\Application;
use Framework\Container\Container;

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

$container = new Container(require 'config/bootstrap.php');
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
