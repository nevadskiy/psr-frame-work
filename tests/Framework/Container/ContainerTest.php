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

    /** @test */
    public function it_store_closure()
    {
        $container = new Container();

        $container->set($name = 'name', function() {
            return new \stdClass();
        });

        $this->assertNotNull($value = $container->get($name));
        $this->assertInstanceOf(\stdClass::class, $value);
    }

    /** @test */
    public function it_cache_closures()
    {
        $container = new Container();

        $container->set($name = 'name', function() {
            return new \stdClass();
        });

        $this->assertNotNull($value1 = $container->get($name));
        $this->assertNotNull($value2 = $container->get($name));
        $this->assertEquals($value1, $value2);
    }

    /** @test */
    public function it_has_access_to_itself_in_closures()
    {
        $container = new Container();

        $container->set('param', $value = 15);
        $container->set($name = 'name', function(Container $container) {
            $object = new \stdClass();
            $object->param = $container->get('param');
            return $object;
        });

        $this->assertObjectHasAttribute('param', $object = $container->get($name));
        $this->assertEquals($value, $object->param);
    }
}
