<?php

declare(strict_types=1);

namespace TijmenWierenga\Tests\Guzzle\MockHandler;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use TijmenWierenga\Guzzle\Mocking\Conditions\RequestMethodIs;
use TijmenWierenga\Guzzle\Mocking\Expectation;
use TijmenWierenga\Guzzle\Mocking\MockHandler;

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
        $handler->when(new RequestMethodIs('GET'))
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
            new RequestMethodIs('GET'),
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
}
