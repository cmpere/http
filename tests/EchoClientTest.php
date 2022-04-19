<?php

namespace Tests;

use LiaTec\Http\Testing\EchoClient;
use PHPUnit\Framework\TestCase;

class EchoClientTest extends TestCase
{
    protected $client;
    
    public function setUp():void
    {
        parent::setUp();
        $this->client = new EchoClient([
            '/resource' => [ 'data'=>'ok' ]
        ]);
    }

    /** @test */
    public function it_gets_echo_response()
    {
        $response = $this->client->get('/resource');
        $this->assertResponse($response);
    }

    /** @test */
    public function it_post_echo_response()
    {
        $response = $this->client->post('/resource');
        $this->assertResponse($response);
    }


    /** @test */
    public function it_put_echo_response()
    {
        $response = $this->client->put('/resource');
        $this->assertResponse($response);
    }

    /** @test */
    public function it_patch_echo_response()
    {
        $response = $this->client->patch('/resource');
        $this->assertResponse($response);
    }

    /** @test */
    public function it_delete_echo_response()
    {
        $response = $this->client->delete('/resource');
        $this->assertResponse($response);
    }
 
    private function assertResponse($response)
    {
        $this->assertIsArray($response);
        $this->assertArrayHasKey('data', $response);
        $this->assertEquals('ok', $response['data']);
    }
}
