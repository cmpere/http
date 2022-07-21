<?php

namespace LiaTec\Http\Concerns;

trait InteractsWithAccessTokenUri
{
    /** @var string */
    protected $accessTokenUri;

    /**
     * Get the value of accessTokenUri
     */
    public function getAccessTokenUri(): ?string
    {
        return $this->accessTokenUri;
    }

    /**
     * Set the value of accessTokenUri
     *
     * @return self
     */
    public function setAccessTokenUri($accessTokenUri)
    {
        $this->accessTokenUri = $accessTokenUri;

        return $this;
    }
}
