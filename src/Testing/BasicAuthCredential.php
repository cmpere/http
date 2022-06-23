<?php

namespace LiaTec\Http\Testing;

use LiaTec\Http\Credential;

/**
 * @property string $username
 * @property string $password
 * @property string $testBootedAttribute
 */
class BasicAuthCredential extends Credential
{
    /**
     * Inicializa la credencial
     *
     * @return void
     */
    public function boot()
    {
        $this->testBootedAttribute = 'bootattribute';
    }

    /**
     * Aplica los cambios al request
     *
     * @return void
     */
    public function request()
    {
        $this->header('Test', 'Testing');
        $this->header('BootedAttributeHeader', $this->testBootedAttribute);
    }

    public function getTokenRequestParameters(): array
    {
        return [
            'username' => $this->username,
            'password' => $this->password,
        ];
    }
}
