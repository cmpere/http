<?php

namespace Tests\Credentials;

use LiaTec\Http\Credential;

/**
 * @property string $username
 * @property string $password
 */
class BasicCredential extends Credential
{
    public function getTokenRequestParameters(): array
    {
        return [
            'username' => $this->username,
            'password' => $this->password,
        ];
    }
}
