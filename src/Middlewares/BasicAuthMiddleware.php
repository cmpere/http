<?php

namespace LiaTec\Http\Middlewares;

use LiaTec\Http\Contracts\Authorizable;
use Psr\Http\Message\RequestInterface;
use LiaTec\Http\Token\BasicToken;
use LiaTec\Http\AccessToken;
use GuzzleHttp\Psr7\Utils;

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
        // TODO: establecer un storage para cachear el token
        $params      = $this->credential->getTokenRequestParameters();
        $this->token = new BasicToken($params);

        return Utils::modifyRequest($request, [
            'set_headers' => [
                'Authorization' => (string) $this->getToken(),
            ],
        ]);
    }
}
