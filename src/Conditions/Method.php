<?php

declare(strict_types=1);

namespace TijmenWierenga\Guzzle\Mocking\Conditions;

use Psr\Http\Message\RequestInterface;

final class Method
{
    public static function is(string $method): callable
    {
        return fn (RequestInterface $request): bool =>
            strtoupper($request->getMethod()) === strtoupper($method);
    }
}
