<?php

namespace Framework\Http\Router\Route;

use Framework\Http\Router\Result;
use Psr\Http\Message\ServerRequestInterface;

interface Route
{
    public const METHOD_GET = 'GET';

    public const METHOD_POST = 'POST';

    public function match(ServerRequestInterface $request): ?Result;

    public function generate(string $name, array $arguments = []): ?string;
}
