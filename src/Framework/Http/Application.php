<?php

namespace Framework\Http;

use Framework\Http\Pipeline\Pipeline;
use Framework\Http\Router\Route\Route;
use Framework\Http\Router\Router;
use Psr\Http\Message\ServerRequestInterface;

class Application extends Pipeline
{
    private $resolver;
    private $default;
    private $router;

    public function __construct(ActionResolver $resolver, Router $router, callable $default)
    {
        parent::__construct();
        $this->resolver = $resolver;
        $this->default = $default;
        $this->router = $router;
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
    public function pipe($middleware): Pipeline
    {
        parent::pipe($this->resolver->resolve($middleware));

        return $this;
    }

    /**
     * @param ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function run(ServerRequestInterface $request)
    {
        return $this($request, $this->default);
    }

    /**
     * @param $name
     * @param $path
     * @param $handler
     * @param array $options
     */
    public function get($name, $path, $handler, array $options = []): void
    {
        $this->router->addRoute($name, $path, $handler, [Route::METHOD_GET], $options);
    }

    /**
     * @param $name
     * @param $path
     * @param $handler
     * @param array $options
     */
    public function post($name, $path, $handler, array $options = []): void
    {
        $this->router->addRoute($name, $path, $handler, [Route::METHOD_POST], $options);
    }


    /**
     * @param $name
     * @param $path
     * @param $handler
     * @param array $options
     */
    public function any($name, $path, $handler, array $options = []): void
    {
        $this->router->addRoute($name, $path, $handler, [], $options);
    }

    /**
     * @param $name
     * @param $path
     * @param $handler
     * @param array $options
     */
    public function route($name, $path, $handler, array $methods, array $options = []): void
    {
        $this->router->addRoute($name, $path, $handler, $methods, $options);
    }
}