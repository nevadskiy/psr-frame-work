<?php

namespace Tests\Framework\Http;

use Framework\Http\Pipeline\Pipeline;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\ServerRequest;

class PipelineTest extends TestCase
{
    /** @test */
    public function it_pipes()
    {
        $pipeline = (new Pipeline())
            ->pipe(new Middleware1())
            ->pipe(new Middleware2())
            ->pipe(new Middleware3());

        $response = $pipeline(new ServerRequest(), new Last());

        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'middleware-1' => 1,
                'middleware-2' => 2,
                'middleware-3' => 3
            ]),
            $response->getBody()->getContents()
        );
    }
}

class Middleware1
{
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        return $next($request->withAttribute('middleware-1', 1));
    }
}

class Middleware2
{
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        return $next($request->withAttribute('middleware-2', 2));
    }
}

class Middleware3
{
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        return $next($request->withAttribute('middleware-3', 3));
    }
}

class Last
{
    public function __invoke(ServerRequestInterface $request)
    {
        return new JsonResponse($request->getAttributes());
    }
}