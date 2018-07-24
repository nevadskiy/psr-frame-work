<?php

namespace Framework\Http\Pipeline;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class Pipeline
 * @package Framework\Http\Pipeline
 */
class Pipeline
{
    /**
     * @var \SplQueue
     */
    private $queue;

    /**
     * Pipeline constructor.
     */
    public function __construct()
    {
        $this->queue = new \SplQueue();
    }

    /**
     * @param callable $middleware
     * @return $this
     */
    public function pipe(callable $middleware): Pipeline
    {
        $this->queue->enqueue($middleware);

        return $this;
    }

    /**
     * @param ServerRequestInterface $request
     * @param callable $default
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, callable $default): ResponseInterface
    {
        $delegate = new Next(clone $this->queue, $default);

        return $delegate($request);
    }
}
