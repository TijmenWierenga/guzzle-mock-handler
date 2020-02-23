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
    private int $maxInvocations;
    private int $invocations = 0;

    public function __construct(callable $condition, ResponseInterface $response, int $maxInvocations = PHP_INT_MAX)
    {
        $this->condition = Closure::fromCallable($condition);
        $this->response = $response;
        $this->maxInvocations = $maxInvocations;
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    /**
     * @internal
     * @psalm-internal TijmenWierenga\Guzzle\Mocking\MockHandler
     */
    public function addInvocation(): void
    {
        $this->invocations++;
    }

    public function matches(RequestInterface $request): bool
    {
        if ($this->reachedMaxInvocations()) {
            return false;
        }

        return (bool) ($this->condition)($request);
    }

    /**
     * @psalm-mutation-free
     */
    private function reachedMaxInvocations(): bool
    {
        return $this->invocations >= $this->maxInvocations;
    }
}
