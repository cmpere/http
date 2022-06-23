<?php

namespace LiaTec\Http;

use LiaTec\Http\Concerns\HasAttributes;

/**
 * Clase que define la estructura basica de un token de acceso,
 */
abstract class AccessToken
{
    use HasAttributes;

    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
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

    public function __toString()
    {
        return __CLASS__;
    }
}
