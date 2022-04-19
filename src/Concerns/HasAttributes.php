<?php

namespace LiaTec\Http\Concerns;

trait HasAttributes
{
    /**
     * Attributes
     *
     * @var array
     */
    protected $attributes;

    /**
     * Get an attribute from the model.
     *
     * @param  string $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        if (!$key) {
            return;
        }

        if (isset($this->attributes[$key])) {
            return $this->attributes[$key];
        }

        return null;
    }

    /**
     * Set a given attribute on the model.
     *
     * @param  string $key
     * @param  mixed  $value
     * @return mixed
     */
    public function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;

        return $this;
    }

    /**
     * Dynamically retrieve attributes on the model.
     *
     * @param  string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->getAttribute($key);
    }

    /**
     * Dynamically set attributes on the model.
     *
     * @param  string $key
     * @param  mixed  $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->setAttribute($key, $value);
    }
}
