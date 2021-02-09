<?php

declare(strict_types=1);

namespace TijmenWierenga\Guzzle\Mocking;

use GuzzleHttp\Promise\Create;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\RequestInterface;

final class MockHandler
{
    /**
     * @var list<Expectation>
     */
    private array $expectations;
    private ExpectationMatcher $expectationMatcher;

    public function __construct(Expectation ...$expectations)
    {
        $this->expectationMatcher = new ExpectationMatcher($this);
        $this->expectations = $expectations;
    }

    public function __invoke(RequestInterface $request): PromiseInterface
    {
        $expectation = $this->expectationMatcher->match($request);
        $expectation->addInvocation();

        return Create::promiseFor($expectation->getResponse());
    }

    public function when(callable ...$conditions): ExpectationBuilder
    {
        return (new ExpectationBuilder($this))->when(...$conditions);
    }

    public function append(Expectation $expectation): void
    {
        $this->expectations[] = $expectation;
    }

    /**
     * @internal
     * @psalm-internal TijmenWierenga\Guzzle\Mocking
     * @return Expectation[]
     */
    public function getExpectations(): array
    {
        return $this->expectations;
    }
}
