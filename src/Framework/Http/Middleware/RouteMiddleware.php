<?php

namespace App\Http\Middleware;

use Framework\Http\ActionResolver;
use Framework\Http\Router\Router;
use Psr\Http\Message\ServerRequestInterface;
use Framework\Http\Router\Exceptions\RequestNotMatchedException;

class RouteMiddleware
{
    private $router;

    private $resolver;

    public function __construct(Router $router, ActionResolver $resolver)
    {
        $this->router = $router;
        $this->resolver = $resolver;
    }

    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        try {
            $result = $this->router->match($request);

            foreach ($result->getAttributes() as $attribute => $value) {
                $request = $request->withAttribute($attribute, $value);
            }

            $middleware = $this->resolver->resolve($result->getHandler());

            return $middleware($request, $next);
        } catch (RequestNotMatchedException $e) {
            return $next($request);
        }
    }
}
