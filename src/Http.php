<?php

namespace LiaTec\Http;

use LiaTec\Http\Contracts\Authorizable;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\RequestInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;

class Http
{
    use Concerns\MakesCustomClient;
    use Concerns\MakesBasicAuthClient;
    use Concerns\MakesOAuth2Client;
    use Concerns\ChainsConfig;
    use Concerns\BuildsUrl;

    /**
     * Transporte
     *
     * @var ClientInterface
     */
    protected $client;

    /**
     * Gets http instance
     *
     * @param Authorizable $credential
     * @param mixed        $transport
     */
    final public function __construct(Authorizable $credential = null, $transport = null)
    {
        $this->credential = $credential;
        $this->client     = $transport;
    }

    /**
     * GET
     *
     * @param  string            $resource
     * @param  array             $query
     * @param  array             $options
     * @return ResponseInterface
     */
    public function get(string $resource, array $query = [], $options = []): ResponseInterface
    {
        return $this->request('GET', $resource, array_merge([
            'query' => $query,
        ], $options));
    }

    /**
     * POST
     *
     * @param  string            $resource
     * @param  array             $data
     * @param  array             $query
     * @param  array             $options
     * @return ResponseInterface
     */
    public function post(string $resource, $data = [], array $query = [], $options = []): ResponseInterface
    {
        return $this->request('POST', $resource, array_merge(
            empty($query) ? [] : ['query' => $query],
            empty($data) ? [] : ['json' => $data],
            $options
        ));
    }

    /**
     * PUT
     *
     * @param  string            $resource
     * @param  array             $data
     * @param  array             $query
     * @param  array             $options
     * @return ResponseInterface
     */
    public function put(string $resource, $data = [], array $query = [], $options = []): ResponseInterface
    {
        return $this->request('PUT', $resource, array_merge(
            empty($query) ? [] : ['query' => $query],
            empty($data) ? [] : ['json' => $data],
            $options
        ));
    }

    /**
     * PATCH
     *
     * @param  string            $resource
     * @param  array             $data
     * @param  array             $query
     * @param  array             $options
     * @return ResponseInterface
     */
    public function patch(string $resource, $data = [], array $query = [], $options = []): ResponseInterface
    {
        return $this->request('PATCH', $resource, array_merge(
            empty($query) ? [] : ['query' => $query],
            empty($data) ? [] : ['json' => $data],
            $options
        ));
    }

    /**
     * Raw
     *
     * @param  string            $method
     * @param  string            $resource
     * @param  array             $options
     * @return ResponseInterface
     */
    public function raw($method, $resource, $options = []): ResponseInterface
    {
        $request = new Request($method, $this->buildUrl($resource));

        return  $this->send($request, $options);
    }

    /**
     * Request
     *
     * @param  string            $method
     * @param  string            $resource
     * @param  array             $options
     * @return ResponseInterface
     */
    protected function request($method, $resource, $options = []): ResponseInterface
    {
        $request = new Request($method, $this->buildUrl($resource));

        return  $this->send($request, $options);
    }

    /**
     * Send (transport)
     *
     * @param  RequestInterface  $request
     * @param  array             $options
     * @return ResponseInterface
     */
    protected function send(RequestInterface $request, array $options = []): ResponseInterface
    {
        $request = $this->credential->applyToRequest($request);

        return $this->client->send($request, $this->options($options));
    }

    /**
     * Prepara las opciones del request para mandarlas al cliente
     *
     * @param  array $options
     * @return array
     */
    private function options(array $options = []): array
    {
        return array_merge(
            $options,
            $this->debug ? ['debug' => true] : []
        );
    }
}
