<?php

namespace Api\Test\Unit\Api\Http\Action;

use Api\Http\Action\HomeAction;
use Laminas\Diactoros\ServerRequest;
use PHPUnit\Framework\TestCase;

class HomePageTest extends TestCase
{
    public function testSuccess(): void
    {
        $action = new HomeAction();

        $response = $action->handle(new ServerRequest());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($content = $response->getBody()->getContents());

        $data = json_decode($content, true);

        $this->assertEquals([
            'name' => 'Api',
            'version' => '1.0',
        ], $data);
    }
}