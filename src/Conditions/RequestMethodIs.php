<?php

declare(strict_types=1);

namespace TijmenWierenga\Guzzle\Mocking\Conditions;

use Psr\Http\Message\RequestInterface;

final class RequestMethodIs
{
    private string $httpMethod;

    public function __construct(string $httpMethod)
    {
        $this->httpMethod = $httpMethod;
    }

    public function __invoke(RequestInterface $request): bool
    {
        return strtoupper($request->getMethod()) === strtoupper($this->httpMethod);
    }
}
