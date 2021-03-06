<?php

namespace Framework\Http\Middleware;

use Framework\Http\ActionResolver;
use Framework\Http\Router\Result;
use Psr\Http\Message\ServerRequestInterface;

class DispatchMiddleware
{
    private $resolver;

    public function __construct(ActionResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        if (!$result = $request->getAttribute(Result::class)) {
            return $next($request);
        }

        $middleware = $this->resolver->resolve($result->getHandler());

        return $middleware($request, $next);
    }
}
