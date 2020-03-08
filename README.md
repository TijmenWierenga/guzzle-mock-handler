# Better Guzzle Mock Handler
![Build status](https://github.com/tijmenwierenga/guzzle-mock-handler/workflows/PHP%20Composer/badge.svg)

[![Buy us a tree](https://img.shields.io/badge/Treeware-%F0%9F%8C%B3-lightgreen?style=for-the-badge)](https://offset.earth/treeware?gift-trees)

While the Guzzle HTTP client ships with a basic mock handler it might not be powerful enough in certain situations.
For example, when your requests don't arrive in an expected order. That's where this Mock Handler might come in handy.

You'll be able to set expectations for incoming requests. When an expectation is met a predefined response will be
returned.

## Requirements
Minimum PHP version is 7.4.

## Installation
Usually you'll want to install this package as a development dependency:

```bash
composer install tijmenwierenga/guzzle-mock-handler --dev
```

If you want to install the package for production:

```bash
composer install tijmenwierenga/guzzle-mock-handler
```

## Usage

### Instantiating the handler
The Mock Handler works in a similar way as the official Guzzle Mock Handler.
Add it as the handler for the `HandlerStack` and all requests will passed to the handler.

```php
<?php

use GuzzleHttp\Client;
use TijmenWierenga\Guzzle\Mocking\MockHandler;

$mockHandler = new MockHandler();
$handlerStack = GuzzleHttp\HandlerStack::create($mockHandler);
$client = new Client(['handler' => $handlerStack]);
```

### Adding expectations
Use the `ExpectationBuilder` in order to set request expectations:

```php
<?php

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use TijmenWierenga\Guzzle\Mocking\MockHandler;

/** @var MockHandler $mockHandler */
$mockHandler
    ->when(fn (RequestInterface $request): bool => $request->getMethod() === 'GET')
    ->respondWith(new Response(200));
```

Whenever a new request is passed to the Mock Handler it will look if there is an expectation that matches the request.
On a match, the handler will return the corresponding response.
In the example above the Mock Handler will return an empty 200 OK response on every GET request.

When no expectation matches the request, the Mock Handler will throw a `TijmenWierenga\Guzzle\Mocking\UnexpectedRequestException`.

### Invocation limits
You might want to limit the amount of invocations for an expectation.
By default, there is no invocation limit set.
The `ExpectationBuilder` is capable of limiting the amount of times a response can be returned.
This can be achieved through the `withMaxInvocations()` method on the builder:

```php
<?php

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use TijmenWierenga\Guzzle\Mocking\MockHandler;

/** @var MockHandler $mockHandler */
$mockHandler
    ->when(fn (RequestInterface $request): bool => $request->getMethod() === 'GET')
    ->withMaxInvocations(1)
    ->respondWith(new Response(200));
```

In the example above, the response will only be returned once, even when it satisfies the defined condition.
The handler will throw a `TijmenWierenga\Guzzle\Mocking\UnexpectedRequestException` on any invocation after the first.

### Built-in conditions
The package ships with a few built-in conditions. These conditions can be used to create expectations in an expressive way.

#### All
While it's perfectly valid to pass a callable and match multiple conditions, it's more expressive to use the `All` condition.
This condition accept any amount of conditions that all need to match based on the current request.

```php
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use TijmenWierenga\Guzzle\Mocking\Conditions\All;
use TijmenWierenga\Guzzle\Mocking\MockHandler;

/** @var MockHandler $mockHandler */
$mockHandler
    ->when(
        new All(
            fn (RequestInterface $request): bool => $request->getMethod() === 'GET',
            fn (RequestInterface $request): bool => $request->getUri()->getHost() === 'google.com'
        )
    )
    ->respondWith(new Response(200));
```

#### Any
This condition accepts any number of conditions. When any of the supplied conditions is met, the pre-defined response will be returned.

```php
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use TijmenWierenga\Guzzle\Mocking\Conditions\Any;
use TijmenWierenga\Guzzle\Mocking\MockHandler;

/** @var MockHandler $mockHandler */
$mockHandler
    ->when(
        new Any(
            fn (RequestInterface $request): bool => $request->getMethod() === 'GET',
            fn (RequestInterface $request): bool => $request->getMethod() === 'POST',
        )
    )
    ->respondWith(new Response(200));
```

## Treeware

You're free to use this package, but if it makes it to your production environment you are required to buy the world a tree.

It’s now common knowledge that one of the best tools to tackle the climate crisis and keep our temperatures from rising above 1.5C is to <a href="https://www.bbc.co.uk/news/science-environment-48870920">plant trees</a>. If you support this package and contribute to the Treeware forest you’ll be creating employment for local families and restoring wildlife habitats.

You can buy trees here [offset.earth/treeware](https://offset.earth/treeware?gift-trees)

Read more about Treeware at [treeware.earth](http://treeware.earth)