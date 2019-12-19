<?php

declare(strict_types=1);

namespace TijmenWierenga\Tests\Guzzle\Mocking\Conditions;

use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;
use TijmenWierenga\Guzzle\Mocking\Conditions\Method;

final class MethodTest extends TestCase
{
    public function testItMatchesAConditionBasedOnTheRequestMethod(): void
    {
        $uppercaseGetCondition = Method::is('GET');
        $lowercaseGetCondition = Method::is('get');

        $getRequest = new Request('GET', '/');
        $postRequest = new Request('POST', '/');

        static::assertTrue($uppercaseGetCondition($getRequest));
        static::assertFalse($uppercaseGetCondition($postRequest));

        static::assertTrue($lowercaseGetCondition($getRequest));
        static::assertFalse($lowercaseGetCondition($postRequest));
    }
}
