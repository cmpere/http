<?php

namespace LiaTec\Http\Contracts;

use Psr\Http\Message\RequestInterface;

interface Authorizable
{
    public function applyToRequest(RequestInterface $request);

    public function getTokenRequestParameters(): array;
}
