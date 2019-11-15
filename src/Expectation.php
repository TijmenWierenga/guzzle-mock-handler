<?php

declare(strict_types=1);

namespace TijmenWierenga\Guzzle\Mocking;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class Expectation
{
    /**
     * @var callable
     */
    private $condition;
    private ResponseInterface $response;

    public function __construct(callable $condition, ResponseInterface $response)
    {
        $this->condition = $condition;
        $this->response = $response;
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    public function matches(RequestInterface $request): bool
    {
        $condition = $this->condition;

        return $condition($request);
    }
}
