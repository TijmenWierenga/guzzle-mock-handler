<?php

declare(strict_types=1);

namespace TijmenWierenga\Tests\Guzzle\Mocking;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use TijmenWierenga\Guzzle\Mocking\Conditions\Method;
use TijmenWierenga\Guzzle\Mocking\Expectation;
use TijmenWierenga\Guzzle\Mocking\MockHandler;
use TijmenWierenga\Guzzle\Mocking\UnexpectedRequestException;

final class ExpectationTest extends TestCase
{
    public function testItSetsAndMatchesDefaultExpectations(): void
    {
        $expectedResponse = new Response();
        $handler = new MockHandler(
            new Expectation(
                fn (RequestInterface $request): bool => $request->getMethod() === 'GET',
                $expectedResponse
            )
        );

        $stack = HandlerStack::create($handler);
        $client = new Client(['handler' => $stack]);

        $response = $client->request('GET', 'https://api.github.com');
        static::assertEquals($expectedResponse, $response);
    }

    public function testItCreatesAFluentExpectationWithCallable(): void
    {
        $expectedResponse = new Response();
        $handler = new MockHandler();
        $handler->when(fn (RequestInterface $request): bool => $request->getMethod() === 'GET')
            ->respondWith($expectedResponse);

        $stack = HandlerStack::create($handler);
        $client = new Client(['handler' => $stack]);

        $response = $client->request('GET', 'https://api.get-e.com');
        static::assertEquals($expectedResponse, $response);
    }

    public function testItCreatesAFluentExpectationWithInvokable(): void
    {
        $expectedResponse = new Response();
        $handler = new MockHandler();
        $handler->when(Method::is('GET'))
            ->respondWith($expectedResponse);

        $stack = HandlerStack::create($handler);
        $client = new Client(['handler' => $stack]);

        $response = $client->request('GET', 'https://api.get-e.com');
        static::assertEquals($expectedResponse, $response);
    }

    public function testItCreatesAFluentExpectationsWithMultipleCallables(): void
    {
        $expectedResponse = new Response();
        $handler = new MockHandler();
        $handler->when(
            Method::is('GET'),
            fn (RequestInterface $request): bool => $request->hasHeader('X-Token')
        )
            ->respondWith($expectedResponse);

        $stack = HandlerStack::create($handler);
        $client = new Client(['handler' => $stack]);

        $response = $client->request('GET', 'https://api.get-e.com', ['headers' => [
            'X-Token' => 'token'
        ]]);
        static::assertEquals($expectedResponse, $response);
    }

    public function testItFailsWhenTheMaximumAllowedInvocationsLimitIsHit(): void
    {
        $expectedResponse = new Response();
        $handler = new MockHandler(
            new Expectation(
                fn (RequestInterface $request): bool => true,
                $expectedResponse,
                1
            )
        );

        $stack = HandlerStack::create($handler);
        $client = new Client(['handler' => $stack]);

        $response = $client->get('/');

        static::assertEquals($expectedResponse, $response);

        $this->expectException(UnexpectedRequestException::class);

        $client->get('/');
    }

    public function testItFailsWhenReachingMaxInvocationsWithFluentBuilder(): void
    {
        $handler = new MockHandler();

        $expectedResponse = new Response();

        $handler->when(fn (RequestInterface $request): bool => true)
            ->withMaxInvocations(1)
            ->respondWith($expectedResponse);

        $stack = HandlerStack::create($handler);
        $client = new Client(['handler' => $stack]);

        $response = $client->get('/');

        static::assertEquals($expectedResponse, $response);

        $this->expectException(UnexpectedRequestException::class);

        $client->get('/');
    }

    public function testItThrowsAnErrorWhenNoExpectationCanBeMatched(): void
    {
        $this->expectException(UnexpectedRequestException::class);
        $this->expectExceptionMessage('No expectation could be matched for request: GET https://api.github.com/test');

        $handler = new MockHandler();

        $stack = HandlerStack::create($handler);
        $client = new Client(['handler' => $stack]);

        $client->request('GET', 'https://api.github.com/test');
    }
}
