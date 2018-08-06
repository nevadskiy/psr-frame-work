<?php

use App\Http\Action\Blog\IndexAction;
use App\Http\Action;

$app->get('home', '/', Action\HomeAction::class);
$app->get('about', '/about', Action\AboutAction::class);
$app->get('cabinet', '/cabinet', Action\CabinetAction::class);
$app->get('blog', '/blog', IndexAction::class);
$app->get('blog_show', '/blog/{id}', Action\Blog\ShowAction::class, ['tokens' => ['id' => '\d+']]);