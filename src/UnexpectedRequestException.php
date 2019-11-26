<?php

declare(strict_types=1);

namespace TijmenWierenga\Guzzle\Mocking;

use Psr\Http\Message\RequestInterface;
use RuntimeException;

final class UnexpectedRequestException extends RuntimeException
{
    public static function create(RequestInterface $request): self
    {
        return new static(
            sprintf(
                'No expectation could be matched for request: %s %s',
                $request->getMethod(),
                $request->getUri()
            )
        );
    }
}
