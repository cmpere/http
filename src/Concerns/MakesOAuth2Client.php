<?php

namespace LiaTec\Http\Concerns;

use LiaTec\Http\Middlewares\ChecksForExceptions;
use LiaTec\Http\Middlewares\RefreshOAuth2Token;
use LiaTec\Http\Contracts\Authorizable;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Client;

/**
 * makes oAuth2 Client
 */
trait MakesOAuth2Client
{
    /**
     * Factory para cliente que maneja oAuth2
     *
     * @param  Authorizable              $credential
     * @param  array                     $options
     * @param  callable|MockHandler|null $mock
     * @return static
     */
    public static function oAuth2(Authorizable $credential, $options = [], $mock = null)
    {
        $stack  = is_null($mock) ? HandlerStack::create() : HandlerStack::create($mock);
        $client = new Client(array_merge(['handler' => $stack], $options));
        $stack->push(new RefreshOAuth2Token($client, $credential, $mock), 'refresh_token');
        $stack->push(new ChecksForExceptions(), 'checks_exceptions');

        return new static($credential, $client);
    }
}
