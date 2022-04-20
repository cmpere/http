<?php

namespace LiaTec\Http\Exceptions;

/**
 * Intenta transformar los errores a un arreglo
 */
class ApiException extends \Exception
{
    protected $errors = [];

    public function __construct($message = null, $code = 0)
    {
        parent::__construct($message, $code);

        $this->tryToParse($message);
    }

    /**
     * Intenta parsear la respuesta de error
     *
     * @param  mixed $message
     * @return void
     */
    private function tryToParse($message)
    {
        $json = json_decode($message, true);

        if (json_last_error() === JSON_ERROR_NONE) {
            $this->errors = $json;
        }
    }

    /**
     * Obtiene los errores detectados
     *
     * @return array
     */
    public function errors(): array
    {
        return $this->errors;
    }
}
