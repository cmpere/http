<?php

namespace LiaTec\Http\Concerns;

trait BuildsUrl
{
    /**
     * Builds an url by given resource name.
     *
     * @param string $resource
     *
     * @return string
     */
    public function buildUrl($resource)
    {
        if (empty($this->port)) {
            return sprintf('%s://%s/%s', $this->protocol, $this->baseUrl, $resource);
        }

        return sprintf('%s://%s:%s/%s', $this->protocol, $this->baseUrl, $this->port, $resource);
    }
}
