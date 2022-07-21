<?php

namespace LiaTec\Http\Contracts;

use Psr\Http\Message\RequestInterface;

/**
 * @property string $access_token_uri
 */
interface Authorizable
{
    public function applyToRequest(RequestInterface $request);

    public function getTokenRequestParameters(): array;

    public function getAccessTokenUri(): ?string;
}
