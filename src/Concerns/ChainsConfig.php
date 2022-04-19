<?php

namespace LiaTec\Http\Concerns;

use LiaTec\Http\Contracts\Authorizable;

/**
 * Permite encadenar la configuracion del cliente
 */
trait ChainsConfig
{
    /**
     * Url base para la comunicacion
     *
     * @var string
     */
    protected $baseUrl = 'localhost';

    /**
     * Protocolo
     *
     * @var string
     */
    protected $protocol = 'https';

    /**
     * Puerto
     *
     * @var string
     */
    protected $port;

    /**
     * Activa o desactiva el debug de las comunicaciones
     *
     * @var boolean
     */
    protected $debug;

    /**
     * Credencial a utilizar
     *
     * @var Authorizable
     */
    protected $credential;

    /**
     * Establece el puerto al que se debe conectar el cliente
     *
     * @param  string $port
     * @return self
     */
    public function port($port = null)
    {
        $this->port = $port;
        return $this;
    }

    /**
     * Obtiene el puerto configurado
     *
     * @return string
     */
    public function getPort(): string
    {
        return $this->port;
    }

    /**
     * Establece el protocolo con el que se comunica el cliente
     *
     * @param  string $protocol
     * @return self
     */
    public function protocol($protocol = null)
    {
        $this->protocol = $protocol;
        return $this;
    }

    public function getProtocol(): string
    {
        return $this->protocol;
    }

    /**
     * Establece la url base
     *
     * @param  string $baseUrl
     * @return self
     */
    public function baseUrl($baseUrl = null)
    {
        $this->baseUrl = $baseUrl;
        return $this;
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * Habilita el debug del cliente
     *
     * @param  string $active
     * @return self
     */
    public function debug($active = true)
    {
        $this->debug = $active;
        return $this;
    }

    public function getDebug(): bool
    {
        return $this->debug;
    }

    /**
     * Obtiene la credencial que usara el cliente
     *
     * @return Authorizable
     */
    public function getCredential(): Authorizable
    {
        return $this->credential;
    }

    /**
     * Establece la credencial para el cliente
     *
     * @param  Authorizable $authorizable
     * @return self
     */
    public function credential(Authorizable $authorizable)
    {
        $this->credential = $authorizable;

        $this;
    }
}
