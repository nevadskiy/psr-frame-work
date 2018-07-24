<?php

use Zend\Diactoros\ServerRequestFactory;
use Framework\Http\Router\AuraRouterAdapter;
use App\Http\Action;
use Framework\Http\ActionResolver;
use App\Http\Middleware;
use Framework\Http\Application;
use Framework\Http\Middleware\RouteMiddleware;
use Framework\Http\Middleware\DispatchMiddleware;

chdir(dirname(__DIR__));

require 'vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Debug section
|--------------------------------------------------------------------------
*/
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/*
|--------------------------------------------------------------------------
| Initialization section
|--------------------------------------------------------------------------
*/

session_start();

class Container
{
    private $definitions = [];

    public function get($id)
    {
        if (!array_key_exists($id, $this->definitions)) {
            throw new \InvalidArgumentException('Undefined parameter "'.$id.'"');
        }

        return $this->definitions[$id];
    }

    public function set($id, $value): void
    {
        $this->definitions[$id] = $value;
    }
}

$container = new Container();

$container->set('debug', true);
$container->set('users', ['admin' => 'password']);
$container->set('db', new \PDO('sqlite:database/database.sqlite'));

/*
|--------------------------------------------------------------------------
| Routes
|--------------------------------------------------------------------------
*/

$aura = new Aura\Router\RouterContainer();
$routes = $aura->getMap();

$routes->get('home', '/', Action\HomeAction::class);
$routes->get('about', '/about', Action\AboutAction::class);
$routes->get('cabinet', '/cabinet', [
    new Middleware\BasicAuthActionMiddleware($container->get('users')),
    Action\CabinetAction::class,
]);
$routes->get('blog', '/blog', new Action\Blog\IndexAction($container->get('db')));
$routes->get('blog_show', '/blog/{id}', Action\Blog\ShowAction::class)->tokens(['id' => '\d+']);

$router = new AuraRouterAdapter($aura);
$resolver = new ActionResolver();

$app = new Application($resolver, new Middleware\NotFoundHandler());
$app->pipe(Middleware\ProfilerMiddleware::class);
$app->pipe(new Middleware\ErrorHandlerMiddleware($container->get('debug')));
$app->pipe(Middleware\CredentialsMiddleware::class);
$app->pipe(Middleware\SetLocaleMiddleware::class);

// Change to pipes
$app->pipe(new RouteMiddleware($router));
$app->pipe(new DispatchMiddleware($resolver));

/*
|--------------------------------------------------------------------------
| Running
|--------------------------------------------------------------------------
*/
$request = ServerRequestFactory::fromGlobals();
$response = $app->run($request);

/*
|--------------------------------------------------------------------------
| Sending section
|--------------------------------------------------------------------------
| php echo function is bad implementation for response because
| we can't add any headers to response and modify it later.
|
*/
$sender = new \Zend\HttpHandlerRunner\Emitter\SapiEmitter();
$sender->emit($response);
