<?php

declare(strict_types=1);

namespace TijmenWierenga\Tests\Guzzle\Mocking\Conditions;

use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;
use TijmenWierenga\Guzzle\Mocking\Conditions\RequestMethodIs;

final class RequestMethodIsTest extends TestCase
{
    public function testItMatchesAConditionBasedOnTheRequestMethod(): void
    {
        $uppercaseGetCondition = new RequestMethodIs('GET');
        $lowercaseGetCondition = new RequestMethodIs('get');

        $getRequest = new Request('GET', '/');
        $postRequest = new Request('POST', '/');

        static::assertTrue($uppercaseGetCondition($getRequest));
        static::assertFalse($uppercaseGetCondition($postRequest));

        static::assertTrue($lowercaseGetCondition($getRequest));
        static::assertFalse($lowercaseGetCondition($postRequest));
    }
}
