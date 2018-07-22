<?php

namespace Framework\Http;

use Framework\Http\Pipeline\Pipeline;
use Psr\Http\Message\ServerRequestInterface;

class Application extends Pipeline
{
    private $resolver;
    private $default;

    public function __construct(ActionResolver $resolver, callable $default)
    {
        parent::__construct();
        $this->resolver = $resolver;
        $this->default = $default;
    }

    /**
     * If handler has array of callables,
     * Then group it in another pipeline,
     * (It also can pipes with his $pipeline->__invoke method)
     * Then add to existing one
     *
     * @param callable $middleware
     * @return Application
     */
    public function pipe($middleware): Application
    {
        parent::pipe($this->resolver->resolve($middleware));

        return $this;
    }

    public function run(ServerRequestInterface $request)
    {
        return $this($request, $this->default);
    }
}