<?php

use App\Http\Middleware as AppMiddleware;
use Framework\Http\Middleware as CoreMiddleware;

$app->pipe(AppMiddleware\ProfilerMiddleware::class);
$app->pipe(AppMiddleware\ErrorHandlerMiddleware::class);
$app->pipe(AppMiddleware\CredentialsMiddleware::class);
$app->pipe(AppMiddleware\SetLocaleMiddleware::class);
$app->pipe(CoreMiddleware\RouteMiddleware::class);
$app->pipe(CoreMiddleware\DispatchMiddleware::class);