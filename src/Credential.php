<?php

namespace LiaTec\Http;

use LiaTec\Http\Concerns\InteractsWithEnviroments;
use LiaTec\Http\Concerns\InteractsWithProtocol;
use LiaTec\Http\Concerns\ModifiesRequest;
use LiaTec\Http\Concerns\HasAttributes;
use LiaTec\Http\Contracts\Authorizable;
use Psr\Http\Message\RequestInterface;

abstract class Credential implements Authorizable
{
    use InteractsWithEnviroments;
    use InteractsWithProtocol;
    use ModifiesRequest;
    use HasAttributes;

    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;

        if (method_exists($this, 'boot')) {
            $this->boot();
        }
    }

    /**
     * Funcion utilizada por el cliente para modificar el request al realizar el envio
     *
     * @param  RequestInterface      $request
     * @return RequestInterface|null
     */
    public function applyToRequest(RequestInterface $request): ?RequestInterface
    {
        if (method_exists($this, 'request')) {
            $this->request = $request;

            $this->request();

            $this->applyHeaders();

            return $this->request;
        }

        return $request;
    }

    /**
     * Default de la implementacion para el token de oAuth2
     *
     * @return array
     */
    public function getTokenRequestParameters(): array
    {
        return [];
    }

    /**
     * Arranca la clase con una funcion que puede acceder a la instancia del credential
     *
     * @param  callable $callback
     * @return void
     */
    public function bootWith(callable $callback)
    {
        $callback($this);
    }
}
