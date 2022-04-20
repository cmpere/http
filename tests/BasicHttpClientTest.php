<?php

namespace Tests;

use LiaTec\Http\Testing\BasicAuthCredential;
use PHPUnit\Framework\TestCase;
use LiaTec\Http\Http;

class BasicHttpClientTest extends TestCase
{
    protected $clientTypes = [
        'basic'
    ];

    protected $credential;

    public function setUp():void
    {
        parent::setUp();
        $this->credential = new BasicAuthCredential(['user'=>'testinguser','password'=>'testingpassword']);
    }

    /** @test */
    public function it_inicialize_client_types()
    {
        foreach ($this->clientTypes as $type) {
            $instance = Http::{$type}($this->credential);

            $this->assertInstanceOf(Http::class, $instance);
        }
    }

    /** @test */
    public function it_works_with_chainable_config()
    {
        $test_value = 'test';
        $instance   = Http::basic($this->credential);
        $instance->port($test_value)->protocol($test_value)->baseUrl($test_value)->debug(true)->credential(new BasicAuthCredential(['user'=>'testinguser','password'=>'testingpassword']));
    
        $this->assertInstanceOf(BasicAuthCredential::class, $instance->getCredential());
        $this->assertEquals($instance->getProtocol(), $test_value);
        $this->assertEquals($instance->getBaseUrl(), $test_value);
        $this->assertEquals($instance->getPort(), $test_value);
        $this->assertTrue($instance->getDebug());
    }

    /** @test */
    public function it_builds_url()
    {
        $port     = '80';
        $protocol = 'http';
        $baseUrl  = 'test.com/api';
        $resource = 'test';
        $expected = "{$protocol}://{$baseUrl}:{$port}/{$resource}";
        $instance = Http::basic($this->credential);
        $instance->port($port)->protocol($protocol)->baseUrl($baseUrl);
 
        $this->assertEquals($instance->buildUrl($resource), $expected);
    }
}
