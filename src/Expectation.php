<?php

declare(strict_types=1);

namespace TijmenWierenga\Guzzle\Mocking;

use Closure;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class Expectation
{
    private Closure $condition;
    private ResponseInterface $response;

    public function __construct(callable $condition, ResponseInterface $response)
    {
        $this->condition = Closure::fromCallable($condition);
        $this->response = $response;
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    public function matches(RequestInterface $request): bool
    {
        return ($this->condition)($request);
    }
}
