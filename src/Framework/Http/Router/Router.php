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

    /**
     * @param $name
     * @param $path
     * @param $handler
     * @param array $methods
     * @param array $options
     */
    public function addRoute($name, $path, $handler, array $methods, array $options): void;
}
