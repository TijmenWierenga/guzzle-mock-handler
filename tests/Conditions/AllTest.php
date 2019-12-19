<?php

declare(strict_types=1);

namespace TijmenWierenga\Tests\Guzzle\Mocking\Conditions;

use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use TijmenWierenga\Guzzle\Mocking\Conditions\All;

final class AllTest extends TestCase
{
    public function testItMatchesWhenAllConditionsAreTrue(): void
    {
        $firstCondition = fn (RequestInterface $request): bool => true;
        $secondCondition = fn (RequestInterface $request): bool => true;

        $wrappedCondition = new All($firstCondition, $secondCondition);
        $request = new Request('GET', '/');

        static::assertTrue($wrappedCondition($request));
    }

    public function testItDoesMatchNotWhenNotAllConditionsAreTrue(): void
    {
        $firstCondition = fn (RequestInterface $request): bool => true;
        $secondCondition = fn (RequestInterface $request): bool => false;

        $wrappedCondition = new All($firstCondition, $secondCondition);
        $request = new Request('GET', '/');

        static::assertFalse($wrappedCondition($request));
    }

    public function testItMatchesWhenNoConditionsAreProvided(): void
    {
        $wrappedCondition = new All();
        $request = new Request('GET', '/');

        static::assertTrue($wrappedCondition($request));
    }
}
