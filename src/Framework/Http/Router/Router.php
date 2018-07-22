<?php

namespace Framework\Http\Router;

use Framework\Http\Router\Exceptions\RequestNotMatchedException;
use Framework\Http\Router\Exceptions\RouteNotFoundException;
use Psr\Http\Message\ServerRequestInterface;

interface Router
{
    /**
     * @param ServerRequestInterface $request
     * @throws RequestNotMatchedException
     * @return Result
     */
    public function match(ServerRequestInterface $request): Result;

    /**
     * @param string $name
     * @param array $attributes
     * @throws RouteNotFoundException
     * @return string
     */
    public function generate(string $name, array $attributes = []): string;
}
