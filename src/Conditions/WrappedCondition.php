<?php

declare(strict_types=1);

namespace TijmenWierenga\Guzzle\Mocking\Conditions;

use Psr\Http\Message\RequestInterface;

final class WrappedCondition
{
    /**
     * @var callable[]
     */
    private $conditions;

    public function __construct(callable ...$conditions)
    {
        $this->conditions = $conditions;
    }

    public function __invoke(RequestInterface $request): bool
    {
        foreach ($this->conditions as $condition) {
            if ($condition($request) === false) {
                return false;
            }
        }

        return true;
    }
}
