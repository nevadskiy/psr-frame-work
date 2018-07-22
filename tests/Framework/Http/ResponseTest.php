<?php

namespace Tests\Framework\Http;

use PHPUnit\Framework\TestCase;
use Framework\Http\Response\Response;

class ResponseTest extends TestCase
{
    /** @test */
    public function it_empty(): void
    {
        $response = new Response($body = 'content');

        $this->assertEquals($body, $response->getBody()->getContents());
        $this->assertEquals($body, $response->getBody());
        $this->assertEquals('OK', $response->getReasonPhrase());
    }

    /** @test */
    public function it_handle_404_error(): void
    {
        $response = new Response($body = 'wrong', $status = 404);

        $this->assertEquals($body, $response->getBody());
        $this->assertEquals($status, $response->getStatusCode());
        $this->assertEquals('Not Found', $response->getReasonPhrase());
    }

    /** @test */
    public function it_has_headers(): void
    {
        $response = (new Response())
            ->withHeader($name1 = 'X-Header-1', $value1 = 'value_1')
            ->withHeader($name2 = 'X-Header-2', $value2 = 'value_2');

        $this->assertEquals([$name1 => $value1, $name2 => $value2], $response->getHeaders());
    }
}