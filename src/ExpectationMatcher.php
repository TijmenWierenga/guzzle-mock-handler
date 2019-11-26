<?php

declare(strict_types=1);

namespace TijmenWierenga\Guzzle\Mocking;

use Psr\Http\Message\RequestInterface;

final class ExpectationMatcher
{
    private MockHandler $mockHandler;

    public function __construct(MockHandler $mockHandler)
    {
        $this->mockHandler = $mockHandler;
    }

    public function match(RequestInterface $request): Expectation
    {
        foreach ($this->mockHandler->getExpectations() as $expectation) {
            if ($expectation->matches($request)) {
                return $expectation;
            }
        }

        throw UnexpectedRequestException::create($request);
    }
}
