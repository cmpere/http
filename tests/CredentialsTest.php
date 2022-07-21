<?php

namespace Tests;

use LiaTec\Http\Testing\BasicAuthCredential;
use LiaTec\Http\Token\BasicToken;
use PHPUnit\Framework\TestCase;

/**
 * TODO: Refactor de test, reestructurar las clases para probar los methodos
 */
class CredentialsTest extends TestCase
{
    protected $credential;
    protected $request;
    protected $payload;

    public function setUp(): void
    {
        parent::setUp();
        $this->payload    = 'testingattributes';
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
        $token      = new BasicToken($credential->getTokenRequestParameters());
        $encoded    = 'Basic ' . base64_encode("{$this->payload}:{$this->payload}");
        $this->assertEquals((string) $encoded, (string) $token);
    }

    /** @test */
    public function it_resolves_base_url()
    {
        $credential = new BasicAuthCredential();
        $env        = 'test';
        $credential->setEnv($env);
        $this->assertNull($credential->env);
        $credential->setEnvironment($env, "{$credential->env}_base_url");
        $this->assertEquals("{$credential->env}_base_url", $credential->getEnvBaseUrl());
    }

    /** @test */
    public function it_gets_enviroments()
    {
        $credential = new BasicAuthCredential();
        $env        = 'test';
        $credential->setEnv($env);
        $credential->setEnvironment($env, "{$env}_base_url");
        $enviroments = $credential->getEnvironments();
        $this->assertArrayHasKey($env, $enviroments);
        $this->assertEquals("{$env}_base_url", $enviroments[$env]);
    }

    /** @test */
    public function it_gets_env()
    {
        $credential = new BasicAuthCredential();
        $env        = 'test';
        $credential->setEnv($env);
        $this->assertIsString($credential->getEnv($env));
    }

    /** @test */
    public function it_gets_protocol()
    {
        $credential = new BasicAuthCredential();
        $this->assertNull($credential->protocol);
        $this->assertNull($credential->getProtocol());

        $credential->setProtocol('http');

        $this->assertEquals('http', $credential->getProtocol());
        $this->assertNull($credential->protocol);
    }

    /** @test */
    public function it_gets_access_token_uri()
    {
        $credential = new BasicAuthCredential();
        $this->assertNull($credential->getAccessTokenUri());
        $credential->setAccessTokenUri('schema://domain');
        $this->assertNotNull($credential->getAccessTokenUri());
        $this->assertEquals('schema://domain', $credential->getAccessTokenUri());
    }
}
