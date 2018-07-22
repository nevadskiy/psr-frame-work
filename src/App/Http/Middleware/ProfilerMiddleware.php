<?php

namespace App\Http\Middleware;

use Psr\Http\Message\ServerRequestInterface;

class ProfilerMiddleware
{
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        $start = microtime(true);

        $response = $next($request);

        $finish = microtime(true);

        return $response->withHeader('X-Profiler-Time', $finish - $start);
    }
}
