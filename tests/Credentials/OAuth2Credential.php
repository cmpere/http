<?php

namespace Tests\Credentials;

use LiaTec\Http\Credential;

/**
 * @property string $grant_type
 * @property string $client_id
 * @property string $client_secret
 * @property string $access_token_uri
 */
class OAuth2Credential extends Credential
{
    public function getTokenRequestParameters(): array
    {
        return [
            'grant_type'    => $this->grant_type,
            'client_id'     => $this->client_id,
            'client_secret' => $this->client_secret,
        ];
    }
}
