<?php

use Zend\Diactoros\Response\SapiEmitter;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Response\JsonResponse;
use Framework\Http\Router\RouteCollection;
use Framework\Http\Router\AuraRouterAdapter;
use Framework\Http\Router\Exceptions\RequestNotMatchedException;
use App\Http\Action;
use Framework\Http\ActionResolver;


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

//$routes = new RouteCollection();
//
//$routes->get('home', '/', Action\HomeAction::class);
//$routes->get('about', '/about', Action\AboutAction::class);
//$routes->get('blog', '/blog', Action\Blog\IndexAction::class);
//$routes->get('blog_show', '/blog/{id}', Action\Blog\ShowAction::class, ['id' => '\d+']);
//$router = new \Framework\Http\Router\SimpleRouter($routes);
//$resolver = new ActionResolver();

$aura = new Aura\Router\RouterContainer();
$routes = $aura->getMap();

$routes->get('home', '/', Action\HomeAction::class);
$routes->get('about', '/about', Action\AboutAction::class);
$routes->get('blog', '/blog', Action\Blog\IndexAction::class);
$routes->get('blog_show', '/blog/{id}', Action\Blog\ShowAction::class)->tokens(['id' => '\d+']);

$router = new AuraRouterAdapter($aura);
$resolver = new ActionResolver();

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

    $action = $resolver->resolve($result->getHandler());
    $response = $action($request);
} catch (RequestNotMatchedException $e) {
    $response = new JsonResponse(['error' => 'Undefined page'], 404);
}

/*
|--------------------------------------------------------------------------
| Postprocessing
|--------------------------------------------------------------------------
*/

// Echo is bad implementation for response because
// we can't add any headers to response and modify it later.

//$response = (new HtmlResponse($contentBody))
//    ->withHeader('X-Developer', 'Vitasik');

/*
|--------------------------------------------------------------------------
| Sending section
|--------------------------------------------------------------------------
*/

$sender = new SapiEmitter();
$sender->emit($response);
