<?php

namespace Tests\App\Http\Action\Blog;

use App\Http\Action\Blog\ShowAction;
use PHPUnit\Framework\TestCase;
use Zend\Diactoros\ServerRequest;

class ShowActionTest extends TestCase
{
    /** @test */
    public function it_return_found_post(): void
    {
        $action = new ShowAction();
        $request = (new ServerRequest())->withAttribute('id', $id = 2);
        $response = $action($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(['id' => $id, 'title' => 'The Post #' . $id]),
            $response->getBody()->getContents()
        );
    }

    /** @test */
    public function it_handle_not_found_post(): void
    {
        $action = new ShowAction();
        $request = (new ServerRequest())->withAttribute('id', $id = 15);
        $response = $action($request);

        $this->assertEquals(404, $response->getStatusCode());
    }
}