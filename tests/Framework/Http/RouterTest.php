<?php

namespace Tests\Framework\Http;

use PHPUnit\Framework\TestCase;
use Framework\Http\Router\Exceptions\RequestNotMatchedException;
use Framework\Http\Router\Router;
use Framework\Http\Router\RouteCollection;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Uri;

class RouterTest extends TestCase
{
    /** @test */
    public function it_handle_correct_method(): void
    {
        $routes = new RouteCollection();

        $routes->get($nameGet = 'blog', '/blog', $handlerGet = 'handler_get');
        $routes->post($namePost = 'blog_edit', '/blog', $handlerPost = 'handler_post');

        $router = new Router($routes);

        $result = $router->match($this->buildRequest('GET', '/blog'));
        $this->assertEquals($nameGet, $result->getName());
        $this->assertEquals($handlerGet, $result->getHandler());

        $result = $router->match($this->buildRequest('POST', '/blog'));
        $this->assertEquals($namePost, $result->getName());
        $this->assertEquals($handlerPost, $result->getHandler());
    }

    /** @test */
    public function it_expect_missing_method(): void
    {
        $routes = new RouteCollection();

        $routes->post('blog', '/blog', 'handler_post');

        $router = new Router($routes);

        $this->expectException(RequestNotMatchedException::class);
        $router->match($this->buildRequest('DELETE', '/blog'));
    }

    /** @test */
    public function it_parse_correct_attributes(): void
    {
        $routes = new RouteCollection();

        $routes->get($name = 'blog_show', '/blog/{id}', 'handler', ['id' => '\d+']);

        $router = new Router($routes);

        $result = $router->match($this->buildRequest('GET', '/blog/5'));

        $this->assertEquals($name, $result->getName());
        $this->assertEquals(['id' => '5'], $result->getAttributes());
    }

    /** @test */
    public function it_parse_incorrect_attributes(): void
    {
        $routes = new RouteCollection();

        $routes->get($name = 'blog_show', '/blog/{id}', 'handler', ['id' => '\d+']);

        $router = new Router($routes);

        $this->expectException(RequestNotMatchedException::class);
        $router->match($this->buildRequest('GET', '/blog/slug'));
    }

    /** @test */
    public function it_generate_uri_from_name(): void
    {
        $routes = new RouteCollection();

        $routes->get('blog', '/blog', 'handler');
        $routes->get('blog_show', '/blog/{id}', 'handler', ['id' => '\id+']);

        $router = new Router($routes);

        $this->assertEquals('/blog', $router->generate('blog'));
        $this->assertEquals('/blog/5', $router->generate('blog_show', ['id' => 5]));
    }

    /** @test */
    public function it_generate_missing_attribute(): void
    {
        $routes = new RouteCollection();

        $routes->get($name = 'blog_show', '/blog{id}', 'handler', ['id' => '\d+']);

        $router = new Router($routes);

        $this->expectException(\InvalidArgumentException::class);
        $router->generate('blog_show', ['slug' => 'post']);
    }

    private function buildRequest($method, $uri): ServerRequest
    {
        return (new ServerRequest())
            ->withMethod($method)
            ->withUri(new Uri($uri));
    }
}