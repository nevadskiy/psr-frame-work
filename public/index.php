<?php

use Zend\Diactoros\ServerRequestFactory;
use Framework\Http\Router\AuraRouterAdapter;
use Framework\Http\Router\Exceptions\RequestNotMatchedException;
use App\Http\Action;
use Framework\Http\ActionResolver;
use Framework\Http\Pipeline\Pipeline;
use App\Http\Middleware;
use Framework\Http\Application;


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
function getLang(array $get, array $cookie, array $session, array $server, $default = 'en') {
    if (!empty($get['lang'])) {
        return $get['lang'];
    }

    if (!empty($cookie['lang'])) {
        return $cookie['lang'];
    }

    if (!empty($session['lang'])) {
        return $session['lang'];
    }

    if (!empty($server['HTTP_ACCEPT_LANGUAGE'])) {
        return substr($server['HTTP_ACCEPT_LANGUAGE'], 0, 2);
    }

    return $default;
}

session_start();

/*
|--------------------------------------------------------------------------
| Routes
|--------------------------------------------------------------------------
*/

$params = [
    'debug' => true,
    'users' => ['admin' => 'password']
];

$aura = new Aura\Router\RouterContainer();
$routes = $aura->getMap();

$routes->get('home', '/', Action\HomeAction::class);
$routes->get('about', '/about', Action\AboutAction::class);
$routes->get('cabinet', '/cabinet', [
    new Middleware\BasicAuthActionMiddleware($params['users']),
    Action\CabinetAction::class,
]);
$routes->get('blog', '/blog', Action\Blog\IndexAction::class);
$routes->get('blog_show', '/blog/{id}', Action\Blog\ShowAction::class)->tokens(['id' => '\d+']);

$router = new AuraRouterAdapter($aura);
$resolver = new ActionResolver();
$app = new Application($resolver, new Middleware\NotFoundHandler());

$app->pipe(new Middleware\ErrorHandlerMiddleware($params['debug']))
    ->pipe(Middleware\ProfilerMiddleware::class)
    ->pipe(Middleware\CredentialsMiddleware::class);


/*
|--------------------------------------------------------------------------
| Running
|--------------------------------------------------------------------------
*/

$request = ServerRequestFactory::fromGlobals();
$request = $request->withAttribute('lang', getLang($_GET, $_COOKIE, $_SESSION, $_SERVER));

try {
    $result = $router->match($request);
    foreach ($result->getAttributes() as $attribute => $value) {
        $request = $request->withAttribute($attribute, $value);
    }
    $app->pipe($result->getHandler());
} catch (RequestNotMatchedException $e){}

$response = $app->run($request);

/*
|--------------------------------------------------------------------------
| Sending section
|--------------------------------------------------------------------------
*/

// php echo function is bad implementation for response because
// we can't add any headers to response and modify it later.
$sender = new \Zend\HttpHandlerRunner\Emitter\SapiEmitter();
$sender->emit($response);
