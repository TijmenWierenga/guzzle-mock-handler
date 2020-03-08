<?php

declare(strict_types=1);

namespace TijmenWierenga\Guzzle\Mocking\Conditions;

use Closure;
use Psr\Http\Message\RequestInterface;

/**
 * @psalm-immutable
 */
final class Any
{
    /**
     * @var Closure[]
     */
    private array $conditions;

    public function __construct(callable ...$conditions)
    {
        $this->conditions = array_map(
            fn ($callable): Closure => Closure::fromCallable($callable),
            $conditions
        );
    }

    public function __invoke(RequestInterface $request): bool
    {
        foreach ($this->conditions as $condition) {
            if ($condition($request) === true) {
                return true;
            }
        }

        return false;
    }
}
