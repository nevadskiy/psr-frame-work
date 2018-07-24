<?php

namespace Framework\Http\Pipeline;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class Next
 * @package Framework\Http\Pipeline
 */
class Next
{
    /**
     * Default action if stack is empty
     * @var callable
     */
    private $default;

    /**
     * Queue of pipelines
     * @var \SplQueue
     */
    private $queue;

    /**
     * Next constructor.
     * @param \SplQueue $queue
     * @param callable $default
     */
    public function __construct(\SplQueue $queue, callable $default)
    {
        $this->default = $default;
        $this->queue = $queue;
    }

    /**
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
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
