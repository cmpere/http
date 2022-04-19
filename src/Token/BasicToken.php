<?php

namespace LiaTec\Http\Token;

use LiaTec\Http\AccessToken;

/**
 * Clase que genera el Basic Token para la Autenticacion
 */
class BasicToken extends AccessToken
{
    /**
     * Obtiene la representación de la clase en formato string
     */
    public function __toString()
    {
        return 'Basic ' . $this->encodeToken();
    }

    /**
     * Obtiene solo el token codificado
     */
    public function getToken()
    {
        return $this->encodeToken();
    }

    /**
     * Codifica el user:password en base 64
     */
    private function encodeToken()
    {
        return base64_encode("{$this->username}:{$this->password}");
    }
}
