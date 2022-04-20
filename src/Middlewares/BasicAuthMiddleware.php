<?php

namespace LiaTec\Http\Middlewares;

use GuzzleHttp\Psr7\Utils;
use LiaTec\Http\Contracts\Authorizable;
use Psr\Http\Message\RequestInterface;
use LiaTec\Http\Token\BasicToken;
use LiaTec\Http\AccessToken;

/**
 * Middleware que genera el token para autenticar al cliente
 */
class BasicAuthMiddleware
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
     * @var AccessToken
     */
    protected $token;

    public function __construct(Authorizable $credential)
    {
        $this->credential = $credential;
    }

    public function __invoke(callable $next)
    {
        return function (RequestInterface $request, array $options = []) use ($next) {
            $request = $this->applyToken($request);
            return $next($request, $options);
        };
    }

    /**
     * Determina sy hay un token valido
     *
     * @return boolean
     */
    protected function hasValidToken(): bool
    {
        return !is_null($this->token);
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
     * Aplica el token al request
     *
     * @param  RequestInterface $request
     * @return RequestInterface
     */
    protected function applyToken(RequestInterface $request)
    {
        if (!$this->isValid()) {
            $this->fetchAccessToken();
        }

        return Utils::modifyRequest($request, [
            'set_headers' => [
                'Authorization' => (string) $this->getToken(),
            ],
        ]);

        // Deprecated guzzle 7.2
        // return \GuzzleHttp\Psr7\modify_request($request, [
        //     'set_headers' => [
        //         'Authorization' => (string) $this->getToken(),
        //     ],
        // ]);
    }

    /**
     * Determina si el token es valido
     *
     * @return boolean
     */
    private function isValid(): bool
    {
        return !empty($this->token) && $this->token instanceof AccessToken;
    }

    /**
     * Carga el token desde el credential
     *
     * @return void
     */
    private function fetchAccessToken()
    {
        $params = $this->credential->getTokenRequestParameters();

        $this->token = new BasicToken($params);
    }
}
