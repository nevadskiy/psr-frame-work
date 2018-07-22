<?php

namespace Framework\Http\Pipeline;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Next
{
    private $default;
    private $queue;

    public function __construct(\SplQueue $queue, callable $default)
    {
        $this->default = $default;
        $this->queue = $queue;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        if ($this->queue->isEmpty()) {
            return ($this->default)($request);
        }

        // Current queue __invoke object (can be middleware or action, etc)
        // Second argument of it __invoke function is 'next' of $this;
        // If action use 'next', it will be called '404 page' because
        // There is no items after and $default pipeline would be called
        $current = $this->queue->dequeue();

        return $current($request, function(ServerRequestInterface $request) {
            return $this($request);
        });
    }
}
