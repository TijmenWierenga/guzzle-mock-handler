<?php

declare(strict_types=1);

namespace TijmenWierenga\Guzzle\Mocking\Conditions;

use Psr\Http\Message\RequestInterface;

final class Header
{
    public static function exists(string $name): callable
    {
        return fn (RequestInterface $request): bool => $request->hasHeader($name);
    }

    public static function withValue(string $name, string $value): callable
    {
        return static function (RequestInterface $request) use ($name, $value): bool {
            if (!$request->hasHeader($name)) {
                return false;
            }

            return $request->getHeader($name)[0] === $value;
        };
    }
}
