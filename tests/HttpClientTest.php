<?php

namespace Tests;

use Tests\Credentials\OAuth2Credential;
use Tests\Credentials\BasicCredential;
use Tests\Credentials\NullCredential;
use GuzzleHttp\Handler\MockHandler;
use LiaTec\Http\Token\BearerToken;
use LiaTec\Http\Token\BasicToken;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Client;
use LiaTec\Http\Http;

class HttpClientTest extends TestCase
{
    protected $nullCredential;
    protected $emptyClient;

    public function setUp(): void
    {
        parent::setUp();
        $this->nullCredential = new NullCredential();
        $this->emptyClient    = new Http();
    }

    /** @test */
    public function it_construct_http_client()
    {
        $client = new Http();
        $this->assertInstanceOf(Http::class, $client);
    }

    /** @test */
    public function it_sets_gets_port()
    {
        $this->emptyClient->port('443');
        $this->assertEquals('443', $this->emptyClient->getPort());

        $this->emptyClient->port('80');
        $this->assertEquals('80', $this->emptyClient->getPort());
    }

    /** @test */
    public function it_sets_gets_protocol()
    {
        $this->emptyClient->protocol('http');
        $this->assertEquals('http', $this->emptyClient->getProtocol());

        $this->emptyClient->protocol('https');
        $this->assertEquals('https', $this->emptyClient->getProtocol());
    }

    /** @test */
    public function it_sets_gets_base_url()
    {
        $this->emptyClient->baseUrl('base.com');
        $this->assertEquals('base.com', $this->emptyClient->getBaseUrl());

        $this->emptyClient->baseUrl('base1.com');
        $this->assertEquals('base1.com', $this->emptyClient->getBaseUrl());
    }

    /** @test */
    public function it_sets_gets_debug()
    {
        $this->emptyClient->debug();
        $this->assertTrue($this->emptyClient->getDebug());

        $this->emptyClient->debug(false);
        $this->assertFalse($this->emptyClient->getDebug());
    }

    /** @test */
    public function it_sets_gets_credential()
    {
        $this->emptyClient->credential(null);
        $this->assertNull($this->emptyClient->getCredential());

        $newCredential = new NullCredential();
        $this->emptyClient->credential($newCredential);
        $this->assertEquals($newCredential, $this->emptyClient->getCredential());
    }

    /** @test */
    public function it_sets_gets_transport()
    {
        $transport = new Client();
        $this->emptyClient->setTransport($transport);
        $this->assertEquals($transport, $this->emptyClient->getTransport());
    }

    /** @test */
    public function it_chains_config()
    {
        $value  = 'config';
        $client = new Http();
        $client->port($value)->protocol($value)->baseUrl($value)->debug(false)->credential($this->nullCredential);
        $this->assertEquals($value, $client->getPort());
        $this->assertEquals($value, $client->getProtocol());
        $this->assertEquals($value, $client->getBaseUrl());
        $this->assertEquals(false, $client->getDebug());
        $this->assertEquals($this->nullCredential, $client->getCredential());
    }

    /** @test */
    public function it_builds_url()
    {
        $port     = '80';
        $protocol = 'http';
        $baseUrl  = 'test.com/api';
        $resource = 'test';
        $expected = "{$protocol}://{$baseUrl}:{$port}/{$resource}";
        $instance = $this->emptyClient;
        $instance->port($port)->protocol($protocol)->baseUrl($baseUrl);

        $this->assertEquals($instance->buildUrl($resource), $expected);
    }

    /** @test */
    public function it_works_with_rest()
    {
        $queue = array_map(function ($method) {
            return new Response(200, ['Content-Type' => 'application/json;charset=utf-8'], json_encode([
                'method' => $method,
            ]));
        }, ['get', 'post', 'put', 'patch']);

        $stack = HandlerStack::create(new MockHandler($queue));

        $client = new Http(
            $this->nullCredential,
            new Client(array_merge(['handler' => $stack]))
        );

        $response = $client->get('/url');
        $data     = \GuzzleHttp\json_decode((string) $response->getBody(), true);
        $this->assertEquals('get', $data['method']);

        $response = $client->post('/url', ['payload' => 'data']);
        $data     = \GuzzleHttp\json_decode((string) $response->getBody(), true);
        $this->assertEquals('post', $data['method']);

        $response = $client->put('/url', ['payload' => 'data']);
        $data     = \GuzzleHttp\json_decode((string) $response->getBody(), true);
        $this->assertEquals('put', $data['method']);

        $response = $client->patch('/url', ['payload' => 'data']);
        $data     = \GuzzleHttp\json_decode((string) $response->getBody(), true);
        $this->assertEquals('patch', $data['method']);
    }

    /** @test */
    public function it_works_with_Basic_auth()
    {
        $queue = array_map(function ($method) {
            return new Response(200, ['Content-Type' => 'application/json;charset=utf-8'], json_encode([
                'action' => $method,
            ]));
        }, ['get']);

        $mock     = new MockHandler($queue);
        $username = 'testusername';
        $password = 'testpassword';

        $credential = new BasicCredential(compact('username', 'password'));
        $token      = new BasicToken(compact('username', 'password'));

        $client = Http::basic($credential, [], $mock);

        $response = $client->get('/url');

        $header = $mock->getLastRequest()->getHeader('Authorization');

        $data = \GuzzleHttp\json_decode((string) $response->getBody(), true);

        $this->assertEquals($header[0], (string) $token);
        $this->assertEquals('get', $data['action']);
    }

    /** @test */
    public function it_works_with_OAuth2_auth()
    {
        $access_token = '0398450n23984j0293d843';
        $token_type   = 'bearer';
        $scope        = 'CXS';

        $queue = array_map(function ($method) use ($access_token, $token_type, $scope) {
            return new Response(200, ['Content-Type' => 'application/json;charset=utf-8'], json_encode([
                'action'       => $method,
                'access_token' => $access_token,
                'token_type'   => $token_type,
                'expires_in'   => 3600000,
                'scope'        => $scope,
            ]));
        }, ['token', 'get']);

        $mock             = new MockHandler($queue);
        $grant_type       = 'client_credentials';
        $client_id        = '3[0m2d4[039km039sdaa';
        $client_secret    = 'asdasdasda3d3d3d3d3a';
        $access_token_uri = 'http://access.uri';

        $credential = new OAuth2Credential(compact('grant_type', 'client_id', 'client_secret', 'access_token_uri'));
        $token      = new BearerToken(compact('token_type', 'access_token'));

        $client = Http::oAuth2($credential, [], $mock);

        $response = $client->get('/url');

        $header = $mock->getLastRequest()->getHeader('Authorization');

        $data = \GuzzleHttp\json_decode((string) $response->getBody(), true);

        $this->assertEquals($header[0], (string) $token);
        $this->assertEquals('get', $data['action']);
    }
}
