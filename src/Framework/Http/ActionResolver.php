<?php

namespace Framework\Http;

use Framework\Http\Pipeline\Pipeline;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;

class ActionResolver
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function resolve($handler): callable
    {
        if (\is_array($handler)) {
            return $this->createPipe($handler);
        }

        if (\is_string($handler)) {
            return function(ServerRequestInterface $request, callable $next) use ($handler) {
                $object = $this->container->get($handler);
                return $object($request, $next);
            };
        }

        return $handler;
    }

    private function createPipe(array $handlers): Pipeline
    {
        $pipeline = new Pipeline();
        foreach ($handlers as $handler) {
            $pipeline->pipe($this->resolve($handler));
        }

        return $pipeline;
    }
}
