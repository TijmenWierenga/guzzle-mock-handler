<?php

declare(strict_types=1);

namespace TijmenWierenga\Guzzle\Mocking;

use Psr\Http\Message\ResponseInterface;
use TijmenWierenga\Guzzle\Mocking\Conditions\WrappedCondition;

final class ExpectationBuilder
{
    private $condition;
    private MockHandler $mockHandler;

    public function __construct(MockHandler $mockHandler)
    {
        $this->mockHandler = $mockHandler;
    }

    public function when(callable $condition, callable ...$conditions): self
    {
        if (count($conditions) > 0) {
            $this->condition = new WrappedCondition($condition, ...$conditions);
        } else {
            $this->condition = $condition;
        }

        return $this;
    }

    public function respondWith(ResponseInterface $response): void
    {
        $expectation = new Expectation($this->condition, $response);
        $this->mockHandler->append($expectation);
    }
}