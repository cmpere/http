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
                        case 422:
                            $header = $response->getHeader('Content-Type');

                            if (is_array($header) && $header[0] == 'application/problem+json') {
                                throw new ValidationException(json_decode(
                                    json_encode((string)$response->getBody()),
                                    true
                                ));
                            }

                            throw new ValidationException(json_decode($response->getBody(), true));
                        case 404:
                            throw new NotFoundException((string) $response->getBody());
                        case 401:
                            throw new UnauthorizedException((string) $response->getBody());
                        default:
                            throw new ApiException((string) $response->getBody(), $code);
                    }
                }
            );
        };
    }
}
