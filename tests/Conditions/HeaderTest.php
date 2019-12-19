<?php

declare(strict_types=1);

namespace TijmenWierenga\Tests\Guzzle\Mocking\Conditions;

use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;
use TijmenWierenga\Guzzle\Mocking\Conditions\Header;

final class HeaderTest extends TestCase
{
    public function testItMatchesIfHeaderExists(): void
    {
        $condition = Header::exists('X-Token');
        $requestWithHeader = (new Request('GET', '/'))->withHeader('X-Token', 'my-token');
        $requestWithoutHeader = (new Request('GET', '/'));

        static::assertTrue($condition($requestWithHeader));
        static::assertFalse($condition($requestWithoutHeader));
    }

    public function testItMatchesARequestWithAValue(): void
    {
        $condition = Header::withValue('X-Token', 'my-token');
        $requestWithHeader = (new Request('GET', '/'))->withHeader('X-Token', 'my-token');
        $requestWithWrongHeader = (new Request('GET', '/'))->withHeader('X-Token', 'wrong');
        $requestWithoutHeader = (new Request('GET', '/'));

        static::assertTrue($condition($requestWithHeader));
        static::assertFalse($condition($requestWithWrongHeader));
        static::assertFalse($condition($requestWithoutHeader));
    }
}
