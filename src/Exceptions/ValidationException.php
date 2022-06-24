<?php

namespace LiaTec\Http\Exceptions;

class ValidationException extends \Exception
{
    /** @var mixed */
    protected $content;

    /** @var array */
    protected $headers;

    final public function __construct($message, $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function setHeaders($headers)
    {
        $this->headers = $headers;

        return $this;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    /**
     * Try to parse error from response
     *
     * @param  mixed  $response
     * @return static
     */
    public static function fromResponse($response)
    {
        $body = (string) $response->getBody();

        $instance = (new static(sprintf('%s: %s', $response->getReasonPhrase(), $body), $response->getStatusCode()))
        ->setHeaders($response->getHeaders())
        ->setContent($body);

        return $instance;
    }
}
