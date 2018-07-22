<?php

namespace Framework\Http\Router;

use Framework\Http\Router\Exceptions\RequestNotMatchedException;
use Framework\Http\Router\Exceptions\RouteNotFoundException;
use Psr\Http\Message\ServerRequestInterface;

class SimpleRouter implements Router
{
    private $routes;

    public function __construct(RouteCollection $routes)
    {
        $this->routes = $routes;
    }

    public function match(ServerRequestInterface $request): Result
    {
        foreach ($this->routes->getRoutes() as $route) {
            if ($result = $route->match($request)) {
                return $result;
            }
        }

        throw new RequestNotMatchedException($request);
    }

    public function generate(string $name, array $attributes = []): string
    {
        foreach ($this->routes->getRoutes() as $route) {
            if (null !== $url = $route->generate($name, $attributes)) {
                return $url;
            }
        }

        throw new RouteNotFoundException($name, $attributes);
    }
}
