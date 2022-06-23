<?php

namespace LiaTec\Http\Middlewares;

use LiaTec\Http\Contracts\Authorizable;
use Psr\Http\Message\RequestInterface;
use LiaTec\Http\Token\BearerToken;
use LiaTec\Http\AccessToken;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Utils;

/**
 * Middleware que genera el token para autenticar al cliente
 *
 */
class RefreshOAuth2Token
{
    /**
     * Authorizable
     *
     * @var Authorizable
     */
    protected $credential;

    /**
     * AccessToken
     *
     * @var AccessToken|null
     */
    protected $token;

    protected $client;

    protected $mock;

    public function __construct($client, Authorizable $credential, $mock = null)
    {
        $this->credential = $credential;
        $this->client     = $client;
        $this->mock       = $mock;
        $this->token      = null;
    }

    public function __invoke(callable $next)
    {
        return function (RequestInterface $request, array $options = []) use ($next) {
            $request = $this->applyToken($request);
            return $next($request, $options);
        };
    }

    /**
     * Aplica el token al request
     *
     * @param  RequestInterface $request
     * @return RequestInterface
     */
    protected function applyToken(RequestInterface $request)
    {
        // TODO: determinar si ya esta guardado el token en un storage local
        $this->fetchAccessToken();

        return Utils::modifyRequest($request, [
            'set_headers' => [
                'Authorization' => (string) $this->getToken(),
            ],
        ]);
    }

    /**
     * Obtiene el token cargado
     *
     * @return AccessToken
     */
    protected function getToken(): AccessToken
    {
        return $this->token;
    }

    /**
     * Carga el token desde el servicio remoto
     *
     * @return void
     */
    private function fetchAccessToken()
    {
        $stack = is_null($this->mock) ? HandlerStack::create() : HandlerStack::create($this->mock);
        $stack->push(new ChecksForExceptions(), 'checks_exceptions');

        $response = $this->client->request('POST', $this->credential->access_token_uri, [
            'form_params' => $this->credential->getTokenRequestParameters(),
            'handler'     => $stack,
        ]);

        $response    = \GuzzleHttp\json_decode((string) $response->getBody(), true);
        $this->token = new BearerToken($response);
    }
}
