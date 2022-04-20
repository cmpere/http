<?php

namespace Tests;

use LiaTec\Http\Testing\BasicAuthCredential;
use PHPUnit\Framework\TestCase;
use LiaTec\Http\Http;

class BasicFakeHttpClientTest extends TestCase
{
    protected $credential;
    protected $client;

    public function setUp():void
    {
        parent::setUp();
        $this->credential = new BasicAuthCredential(['user'=>'testinguser','password'=>'testingpassword']);
        $this->client = Http::basic($this->credential)->protocol('https')->baseUrl('jsonplaceholder.typicode.com');
    }

    /** @test */
    public function it_fake_get()
    {
        $response = $this->client->get('todos/1');

        $body = $response->getBody();

        $data = $body->getSize()
        ? \GuzzleHttp\json_decode($body, true)
        : [];

        $this->assertIsArray($data);
    }

    /** @test */
    public function it_fake_post()
    {
        $response = $this->client->post('posts', [
             'title'=>'test',
             'body'=>'bar',
             'userId'=>1,
         ]);
 
        $body = $response->getBody();
 
        $data = $body->getSize()
         ? \GuzzleHttp\json_decode($body, true)
         : [];
        $this->assertIsArray($data);
    }

    /** @test */
    public function it_fake_put()
    {
        $response = $this->client->put('posts/1', [
              'id'=>1,
              'title'=>'test',
              'body'=>'bar',
              'userId'=>1,
          ]);
  
        $body = $response->getBody();
  
        $data = $body->getSize()
          ? \GuzzleHttp\json_decode($body, true)
          : [];
        $this->assertIsArray($data);
    }

    /** @test */
    public function it_fake_patch()
    {
        $response = $this->client->patch('posts/1', [
               'id'=>1,
               'title'=>'test',
               'body'=>'bar',
               'userId'=>1,
           ]);
   
        $body = $response->getBody();
   
        $data = $body->getSize()
           ? \GuzzleHttp\json_decode($body, true)
           : [];
        $this->assertIsArray($data);
    }
}
