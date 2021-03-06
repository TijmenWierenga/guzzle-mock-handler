<?php

declare(strict_types=1);

namespace TijmenWierenga\Guzzle\Mocking;

use Closure;
use Psr\Http\Message\ResponseInterface;
use TijmenWierenga\Guzzle\Mocking\Conditions\All;

final class ExpectationBuilder
{
    /**
     * @psalm-suppress PropertyNotSetInConstructor
     */
    private Closure $condition;
    private MockHandler $mockHandler;
    private int $maxInvocations = PHP_INT_MAX;

    public function __construct(MockHandler $mockHandler)
    {
        $this->mockHandler = $mockHandler;
    }

    public function when(callable $condition, callable ...$conditions): self
    {
        if (count($conditions) > 0) {
            $this->condition = Closure::fromCallable(new All($condition, ...$conditions));
        } else {
            $this->condition = Closure::fromCallable($condition);
        }

        return $this;
    }

    public function withMaxInvocations(int $maxInvocations): self
    {
        $this->maxInvocations = $maxInvocations;

        return $this;
    }

    public function respondWith(ResponseInterface $response): void
    {
        $expectation = new Expectation($this->condition, $response, $this->maxInvocations);
        $this->mockHandler->append($expectation);
    }
}
