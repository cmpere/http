<?php

namespace LiaTec\Http\Concerns;

use LiaTec\Http\Middlewares\ChecksForExceptions;
use LiaTec\Http\Contracts\Authorizable;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Client;

/**
 * Se encarga de crear un cliente custom
 */
trait MakesCustomClient
{
    /**
     * Factory para cliente custom
     *
     * @param  Authorizable                           $credential
     * @param  array                                  $options
     * @param  callable|MockHandler|HandlerStack|null $mock
     * @return static
     */
    public static function custom(Authorizable $credential, $options = [], $mock = null)
    {
        $stack  = is_null($mock) ? HandlerStack::create() : HandlerStack::create($mock);
        $client = new Client(array_merge(['handler' => $stack], $options));
        $stack->push(new ChecksForExceptions(), 'checks_exceptions');

        return new static($credential, $client);
    }
}
