<?php

namespace LiaTec\Http\Concerns;

trait InteractsWithEnviroments
{
    /** @var array */
    protected $environments = [];

    /** $var string */
    protected $env;

    /**
     * Get the value of environments
     */
    public function getEnvironments(): array
    {
        return $this->environments;
    }

    /**
     * Set the value of environments
     *
     * @return self
     */
    public function setEnvironments($environments)
    {
        $this->environments = $environments;

        return $this;
    }

    /**
     * Set the value of environments
     *
     * @param  string $key
     * @param  string $url
     * @return self
     */
    public function setEnvironment($key, $url)
    {
        $this->environments[$key] = $url;

        return $this;
    }

    /**
     * Gets base url of current enviroment
     *
     * @return string
     */
    public function getEnvBaseUrl(): string
    {
        if (!is_array($this->environments)) {
            throw new \Exception('enviroments array not set.');
        }

        if (is_null($this->env)) {
            throw new \Exception('env attribute not set');
        }

        if ('null' == $this->env) {
            throw new \Exception('env attribute not set');
        }

        if (!array_key_exists($this->env, $this->environments)) {
            throw new \Exception("Enviroment '{$this->env}'=>'base_url' attribute not set.");
        }

        return $this->environments[$this->env];
    }

    /**
     * Get the value of env
     */
    public function getEnv()
    {
        return $this->env;
    }

    /**
     * Set the value of env
     *
     * @return self
     */
    public function setEnv($env)
    {
        $this->env = $env;

        return $this;
    }
}
