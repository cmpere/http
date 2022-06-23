<?php

namespace LiaTec\Http\Concerns;

use Psr\Http\Message\RequestInterface;

trait ModifiesRequest
{
    /**
     * Request al que se le aplicaran los cambios
     *
     * @var RequestInterface
     */
    protected $request;

    /**
     * Arreglo de headers que se aplicaran al request
     *
     * @var array
     */
    protected $headers = [];

    /**
     * Prepara los cambios en el request, siempre y cuando exista el metodo 'request'
     * en la implementacion concreta
     *
     * @return void
     */
    private function applyHeaders()
    {
        foreach ($this->headers as $name => $value) {
            $this->request = $this->request->withHeader($name, $value);
        }
    }

    /**
     * Agrega un header para agregar al mensaje
     *
     * @param  string $name
     * @param  mixed  $value
     * @return self
     */
    public function header(string $name, $value)
    {
        $this->headers[$name] = $value;

        return $this;
    }

    /**
     * Obtiene los headers configurados para aplicar en el request
     *
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Establece el request para trabajar
     *
     * @param  RequestInterface $request
     * @return self
     */
    public function setRequest(RequestInterface $request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Obtiene el request en el que se esta trabajando
     *
     * @return RequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }
}
