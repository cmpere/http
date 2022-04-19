<?php

namespace LiaTec\Http\Testing;

class EchoClient
{
    /**
     * Arreglo de respuestas, la llave es el recurso que hay que pedir
     * @var array - Arreglo de respuestas
     */
    protected $responses = [];

    public function __construct($responses)
    {
        $this->responses = $responses;
    }

    /**
     * Busca la respuesta en las responses
     *
     * @param  string $resource
     * @return mixed
     */
    private function response($resource)
    {
        if (array_key_exists($resource, $this->responses)) {
            return $this->responses[$resource];
        }

        return [];
    }

    /**
     * Fake de la funcion get
     *
     * @param  string $resource
     * @return mixed
     */
    public function get($resource)
    {
        return $this->response($resource);
    }

    /**
     * Fake de la funcion post
     *
     * @param  string $resource
     * @param  array  $data
     * @return mixed
     */
    public function post($resource, $data = [])
    {
        return $this->response($resource);
    }

    /**
     * Fake de la funcion put
     *
     * @param  string $resource
     * @return mixed
     */
    public function put($resource, $data = [])
    {
        return $this->response($resource);
    }

    /**
     * Fake de la funcion patch
     *
     * @param  string $resource
     * @param  array  $data
     * @return mixed
     */
    public function patch($resource, $data = [])
    {
        return $this->response($resource);
    }

    /**
     * Fake de la funcion delete
     *
     * @param  string $resource
     * @return mixed
     */
    public function delete($resource)
    {
        return $this->response($resource);
    }
}
