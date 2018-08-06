<?php

use Framework\Http\Router\Router;
use Framework\Http\Router\AuraRouterAdapter;
use Framework\Http\ActionResolver;
use App\Http\Action;
use App\Http\Middleware;
use Framework\Http\Application;
use Framework\Container\Container;
use Psr\Container\ContainerInterface;

// Factories of services
return [
    ContainerInterface::class => function (Container $container) {
        return $container;
    },
    Application::class => function (Container $container) {
        return new Application(
            $container->get(ActionResolver::class),
            $container->get(Router::class),
            new Middleware\NotFoundHandler()
        );
    },
    Router::class => function () {
        return new AuraRouterAdapter(new Aura\Router\RouterContainer());
    },
    ActionResolver::class => function (Container $container) {
        return new ActionResolver($container);
    },
    Middleware\ErrorHandlerMiddleware::class => function (Container $container) {
        return new Middleware\ErrorHandlerMiddleware($container->get('debug'));
    },
    Middleware\BasicAuthActionMiddleware::class => function (Container $container) {
        return new Middleware\BasicAuthActionMiddleware($container->get('users'));
    },

    // DB connection
    'db' => function (Container $container) {
        return new \PDO($container->get('config')['db']['dsn']);
    },

    // Chain of middleware
    Action\CabinetAction::class => function (Container $container) {
        return [
            $container->get(Middleware\BasicAuthActionMiddleware::class),
            Action\CabinetAction::class,
        ];
    },
];