<?php

namespace Tests;

use LiaTec\Http\Testing\BasicAuthCredential;
use LiaTec\Http\Token\BasicToken;
use PHPUnit\Framework\TestCase;

class BasicAuthCredentialTest extends TestCase
{
    protected $credential;
    protected $request;
    protected $payload;

    public function setUp():void
    {
        parent::setUp();
        $this->payload = 'testingattributes';
        $this->credential = new BasicAuthCredential(['foo' => $this->payload, 'bar' => $this->payload]);
        $this->request    = new \GuzzleHttp\Psr7\Request('MOVE', 'http://httpbin.org/move');
    }

    /** @test */
    public function it_has_attributes()
    {
        $this->assertEquals($this->credential->foo, $this->payload);
        $this->assertEquals($this->credential->bar, $this->payload);
    }

    /** @test */
    public function it_boots_credential()
    {
        $this->credential->bootWith(function ($credential) {
            $credential->bootDigest = "{$credential->foo}:{$credential->bar}";
        });
 
        $this->assertEquals($this->credential->bootDigest, "{$this->payload}:{$this->payload}");
    }

    /** @test */
    public function it_inicialize_credential_with_boot_function()
    {
        $this->assertEquals($this->credential->testBootedAttribute, 'bootattribute');
    }

    /** @test */
    public function it_gets_basic_auth_encoded_token()
    {
        $credential = new BasicAuthCredential(['username' => $this->payload, 'password' => $this->payload]);
        $token = new BasicToken($credential->getTokenRequestParameters());
        $encoded = "Basic " . base64_encode("{$this->payload}:{$this->payload}");
        $this->assertEquals((string) $encoded, (string) $token);
    }
}
