<?php

declare(strict_types=1);

namespace TijmenWierenga\Guzzle\Mocking\Conditions;

use Psr\Http\Message\RequestInterface;

final class RequestHasHeader
{
    private string $name;
    private ?string $value;

    public function __construct(string $name, string $value = null)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public function __invoke(RequestInterface $request): bool
    {
        if (!$request->hasHeader($this->name)) {
            return false;
        }

        if ($this->value === null && $request->hasHeader($this->name)) {
            return true;
        }

        return $request->getHeader($this->name)[0] === $this->value;
    }
}
