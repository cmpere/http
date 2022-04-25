<?php

namespace LiaTec\Http\Middlewares;

use LiaTec\Http\Exceptions\UnauthorizedException;
use LiaTec\Http\Exceptions\ValidationException;
use LiaTec\Http\Exceptions\NotFoundException;
use LiaTec\Http\Exceptions\ApiException;
use Psr\Http\Message\RequestInterface;

/**
 * Middleware que revisa si hay exceptiones
 */
class ChecksForExceptions
{
    public function __invoke(callable $next)
    {
        return function (RequestInterface $request, array $options = []) use (&$next) {
            return $next($request, $options)->then(
                function ($response) {
                    $code = $response->getStatusCode();

                    if ($code < 400) {
                        return $response;
                    }

                    switch ($code) {
                        case 400:
                            throw new ValidationException(json_decode($this->message($response), true));
                        case 422:
                            throw new ValidationException(json_decode($this->message($response), true));
                        case 404:
                            throw new NotFoundException($this->message($response));
                        case 401:
                            throw new UnauthorizedException($this->message($response));
                        default:
                            throw new ApiException($this->message($response), $code);
                    }
                }
            );
        };
    }

    /**
     * Gets response message
     *
     * @param  mixed  $response
     * @return string
     */
    private function message($response): string
    {
        $header = $this->getHeader('Content-Type');

        switch ($header) {
            case 'application/problem+json':
                return json_encode((string) $response->getBody());
                break;
        }

        return (string) $response->getBody();
    }

    /**
     * Gets header value if exists
     *
     * @param  string $name
     * @param  mixed  $response
     * @return void
     */
    private function getHeader($name, $response)
    {
        $header = $response->getHeader($name);

        if (!is_array($header)) {
            return null;
        }

        return $header[0];
    }
}
