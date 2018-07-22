<?php

namespace Tests\App\Http\Action\Blog;

use App\Http\Action\Blog\IndexAction;
use PHPUnit\Framework\TestCase;

class IndexActionTest extends TestCase
{
    /** @test */
    public function it_send_success_response(): void
    {
        $action = new IndexAction();
        $response = $action();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode([
                ['id' => 2, 'title' => 'The Second Post'],
                ['id' => 1, 'title' => 'The First Post'],
            ]),
            $response->getBody()->getContents()
        );
    }
}