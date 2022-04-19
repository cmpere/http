<?php

namespace LiaTec\Http;

use LiaTec\Http\Contracts\Authorizable;

class Http
{
    use Concerns\MakesBasicAuthClient;
    use Concerns\ChainsConfig;
    use Concerns\BuildsUrl;

    /**
     * Cliente
     *
     * @var Client
     */
    protected $client;

    public function __construct(Authorizable $credential, $client = null)
    {
        $this->credential = $credential;
        $this->client     = $client;
    }
}
