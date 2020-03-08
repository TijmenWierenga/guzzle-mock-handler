<?php

declare(strict_types=1);

namespace TijmenWierenga\Tests\Guzzle\Mocking\Conditions;

use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use TijmenWierenga\Guzzle\Mocking\Conditions\Any;

final class AnyTest extends TestCase
{
    public function testItMatchesWhenOneOfManyConditionsPass(): void
    {
        $condition = new Any(
            fn (RequestInterface $request): bool => $request->getMethod() === 'GET',
            fn (RequestInterface $request): bool => $request->getMethod() === 'POST'
        );

        $getRequest = new Request('GET', 'https://google.com');
        $postRequest = new Request('POST', 'https://google.com');

        static::assertTrue($condition($getRequest));
        static::assertTrue($condition($postRequest));
    }

    public function testItDoesntMatchWhenNotASingleConditionPasses(): void
    {
        $condition = new Any(
            fn (RequestInterface $request): bool => $request->getMethod() === 'GET',
            fn (RequestInterface $request): bool => $request->getMethod() === 'POST'
        );

        $deleteRequest = new Request('DELETE', 'https://google.com');

        static::assertFalse($condition($deleteRequest));
    }
}
