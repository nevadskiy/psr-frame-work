<?php

namespace Tests\Framework\Http;

use PHPUnit\Framework\TestCase;
use Framework\Http\Request\Request;

class RequestTest extends TestCase
{
    /** @test */
    public function it_empty(): void
    {
        $request = new Request();

        $this->assertEquals([], $request->getQueryParams());
        $this->assertNull($request->getParsedBody());
    }

    /** @test */
    public function it_has_query_params(): void
    {
        $request = new Request($data = [
            'name' => 'John',
            'age' => 28,
        ]);

        $this->assertEquals($data, $request->getQueryParams());
        $this->assertNull($request->getParsedBody());
    }

    /** @test */
    public function it_parsed_body(): void
    {
        $request = new Request([], $data = ['title' => 'Title']);

        $this->assertEquals([], $request->getQueryParams());
        $this->assertEquals($data, $request->getParsedBody());
    }
}