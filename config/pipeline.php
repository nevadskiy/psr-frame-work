<?php

use App\Http\Middleware;
use Framework\Http\Middleware\RouteMiddleware;
use Framework\Http\Middleware\DispatchMiddleware;

$app->pipe(Middleware\ProfilerMiddleware::class);
$app->pipe($container->get(Middleware\ErrorHandlerMiddleware::class));
$app->pipe(Middleware\CredentialsMiddleware::class);
$app->pipe(Middleware\SetLocaleMiddleware::class);
$app->pipe($container->get(RouteMiddleware::class));
$app->pipe($container->get(DispatchMiddleware::class));