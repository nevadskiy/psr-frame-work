<?php

use Framework\Http\Router\Router;
use Framework\Http\Router\AuraRouterAdapter;
use Framework\Http\ActionResolver;
use App\Http\Action;
use App\Http\Middleware;
use Framework\Http\Application;
use Framework\Http\Middleware\RouteMiddleware;
use Framework\Http\Middleware\DispatchMiddleware;
use Framework\Container\Container;

// Factories of services
$container->set(Application::class, function(Container $container) {
    return new Application(
        $container->get(ActionResolver::class),
        $container->get(Router::class),
        new Middleware\NotFoundHandler()
    );
});

$container->set('db', function(Container $container) {
    return new \PDO($container->get('config')['db']['dsn']);
});

$container->set(Action\CabinetAction::class, function(Container $container) {
    return [
        $container->get(Middleware\BasicAuthActionMiddleware::class),
        Action\CabinetAction::class,
    ];
});

$container->set(Router::class, function() {
    return new AuraRouterAdapter(new Aura\Router\RouterContainer());
});

$container->set(ActionResolver::class, function(Container $container) {
    return new ActionResolver($container);
});

$container->set(Middleware\ErrorHandlerMiddleware::class, function(Container $container) {
    return new Middleware\ErrorHandlerMiddleware($container->get('config')['debug']);
});

$container->set(Middleware\BasicAuthActionMiddleware::class, function(Container $container) {
    return new Middleware\BasicAuthActionMiddleware($container->get('config')['users']);
});

$container->set(RouteMiddleware::class, function(Container $container) {
    return new RouteMiddleware($container->get(Router::class));
});

$container->set(DispatchMiddleware::class, function(Container $container) {
    return new DispatchMiddleware($container->get(ActionResolver::class));
});
