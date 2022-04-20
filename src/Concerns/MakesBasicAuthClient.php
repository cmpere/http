<?php

namespace LiaTec\Http\Concerns;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use LiaTec\Http\Contracts\Authorizable;
use LiaTec\Http\Middlewares\BasicAuthMiddleware;
use LiaTec\Http\Middlewares\ChecksForExceptions;

/**
 * Se encarga de crear un cliente que trabaje con basic auth
 */
trait MakesBasicAuthClient
{
    /**
     * Factory para cliente que maneja basic auth
     *
     * @param  Authorizable         $credential
     * @param  array                $options
     * @param  callable|MockHandler $mock
     * @return static
     */
    public static function basic(Authorizable $credential, $options = [], $mock = null)
    {
        $stack  = is_null($mock) ? HandlerStack::create() : HandlerStack::create($mock);
        $client = new Client(array_merge(['handler' => $stack], $options));
        $stack->push(new BasicAuthMiddleware($credential), 'basic_auth');
        $stack->push(new ChecksForExceptions(), 'checks_exceptions');

        return new static($credential, $client);
    }
}
