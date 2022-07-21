<?php

namespace LiaTec\Http\Concerns;

trait InteractsWithProtocol
{
    protected $protocol = null;

    /**
     * Get the value of protocol
     */
    public function getProtocol()
    {
        return $this->protocol;
    }

    /**
     * Set the value of protocol
     *
     * @return self
     */
    public function setProtocol($protocol)
    {
        $this->protocol = $protocol;

        return $this;
    }
}
