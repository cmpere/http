<?php

namespace LiaTec\Http\Token;

use LiaTec\Http\AccessToken;

/**
 * @property string $access_token
 * @property string $token_type
 */
class BearerToken extends AccessToken
{
    public function __toString()
    {
        return "{$this->token_type} {$this->access_token}";
    }
}
