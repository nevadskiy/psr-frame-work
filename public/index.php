<?php

use Zend\Diactoros\Response\SapiEmitter;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;
use Framework\Http\Router\RouteCollection;
use Framework\Http\Router\Router;
use Framework\Http\Router\Exceptions\RequestNotMatchedException;

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
| Initialization section
|--------------------------------------------------------------------------
*/
//if (preg_match('#json#i', $request->getHeader('Content-Type'))) {
//    $request = $request->withParsedBody(json_encode($request->getBody()->getContents()));
//}

// How body parsing works
// 1. $request->getBody();
// 2. file_get_contents('php://input');
// 3. $request->getParsedBody();

/*
|--------------------------------------------------------------------------
| Action section
|--------------------------------------------------------------------------
*/

// First parse url without get params
// Next Route section is a lot of IF-STATEMENTS for url request
// after request process

$routes = new RouteCollection();

$routes->get('home', '/', function (ServerRequestInterface $request) {

    $lang = $request->getAttribute('lang');
    $name = $request->getQueryParams()['name'] ?? 'Guest';
    $contentBody = '';
    $contentBody .= 'Hello, ' . $name . PHP_EOL;
    $contentBody .= 'Your lang, ' . $lang . PHP_EOL;

    return (new HtmlResponse($contentBody))
        ->withHeader('X-Developer', 'Vitasik');
});

$routes->get('about', '/about', function() {
    return new HtmlResponse('Simple Site');
});

$routes->get('blog', '/blog', function () {
    return new JsonResponse([
        ['id' => 2, 'title' => 'The Second Post'],
        ['id' => 1, 'title' => 'The First Post'],
    ]);
});

$routes->get('blog_show', '/blog/{id}', function(ServerRequestInterface $request) {
    $id = $request->getAttribute('id');
    if ($id > 5) {
        return new JsonResponse(['error' => 'Undefined page'], 404);
    }
    return new JsonResponse(['id' => $id, 'title' => 'The Post #' . $id]);
}, ['id' => '\d+']);

$router = new Router($routes);

/**
 * Running
 *
 */


$request = ServerRequestFactory::fromGlobals();
$request = $request->withAttribute('lang', getLang($_GET, $_COOKIE, $_SESSION, $_SERVER));

try {
    $result = $router->match($request);

    foreach ($result->getAttributes() as $attribute => $value) {
        $request = $request->withAttribute($attribute, $value);
    }

    $action = $result->getHandler();
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
