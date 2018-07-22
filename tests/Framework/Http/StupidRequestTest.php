<?php

namespace Tests\Framework\Http;

use Framework\Http\Request\StupidRequest;
use PHPUnit\Framework\TestCase;

class StupidRequestTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $_GET = [];
        $_POST = [];
    }

    /** @test */
    public function it_empty(): void
    {
        $request = new StupidRequest();

        $this->assertEquals([], $request->getQueryParams());
        $this->assertNull($request->getParsedBody());
    }

    /** @test */
    public function it_has_query_params(): void
    {
        $_GET = $data = [
            'name' => 'John',
            'age' => 28,
        ];

        $request = new StupidRequest();

        $this->assertEquals($data, $request->getQueryParams());
        $this->assertNull($request->getParsedBody());
    }

    /** @test */
    public function it_parsed_body(): void
    {
        $_POST = $data = ['title' => 'Title'];

        $request = new StupidRequest();

        $this->assertEquals($data, $request->getParsedBody());
        $this->assertEquals([], $request->getQueryParams());
    }
}