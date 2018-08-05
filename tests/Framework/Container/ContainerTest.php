<?php

namespace Tests\Framework\Container;

use Framework\Container\Container;
use Framework\Container\ServiceNotFoundException;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    /** @test */
    public function it_store_primitives()
    {
        $container = new Container();

        $container->set($name = 'name', $value = 5);
        $this->assertEquals($value, $container->get($name));

        $container->set($name = 'name', $value = 'string');
        $this->assertEquals($value, $container->get($name));

        $container->set($name = 'name', $value = ['array']);
        $this->assertEquals($value, $container->get($name));

        $container->set($name = 'name', $value = new \stdClass());
        $this->assertEquals($value, $container->get($name));
    }

    /** @test */
    public function it_expect_not_found()
    {
        $container = new Container();

        $this->expectException(ServiceNotFoundException::class);

        $container->get('email');
    }
}
