<?php

namespace Tests\App\Http\Action;

use PHPUnit\Framework\TestCase;
use App\Http\Action\HomeAction;
use Zend\Diactoros\ServerRequest;

class HomeActionTest extends TestCase
{
    /** @test */
    public function it_greetings_guest(): void
    {
        $action = new HomeAction();

        $request = new ServerRequest();
        $response = $action($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('Hello, Guest', $response->getBody()->getContents());
    }

    /** @test */
    public function it_geetings_john(): void
    {
        $action = new HomeAction();
        $request = (new ServerRequest())
            ->withQueryParams(['name' => 'John']);

        $response = $action($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('Hello, John', $response->getBody()->getContents());
    }
}