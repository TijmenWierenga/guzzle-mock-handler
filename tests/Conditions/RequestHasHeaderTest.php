<?php

declare(strict_types=1);

namespace TijmenWierenga\Tests\Guzzle\Mocking\Conditions;

use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;
use TijmenWierenga\Guzzle\Mocking\Conditions\RequestHasHeader;

final class RequestHasHeaderTest extends TestCase
{
    public function testItMatchesAHeaderWithoutAValue(): void
    {
        $condition = new RequestHasHeader('X-Token');
        $requestWithHeader = (new Request('GET', '/'))->withHeader('X-Token', 'my-token');
        $requestWithoutHeader = (new Request('GET', '/'));

        static::assertTrue($condition($requestWithHeader));
        static::assertFalse($condition($requestWithoutHeader));
    }

    public function testItMatchesARequestWithAValue(): void
    {
        $condition = new RequestHasHeader('X-Token', 'my-token');
        $requestWithHeader = (new Request('GET', '/'))->withHeader('X-Token', 'my-token');
        $requestWithWrongHeader = (new Request('GET', '/'))->withHeader('X-Token', 'wrong');
        $requestWithoutHeader = (new Request('GET', '/'));

        static::assertTrue($condition($requestWithHeader));
        static::assertFalse($condition($requestWithWrongHeader));
        static::assertFalse($condition($requestWithoutHeader));
    }
}
