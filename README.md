# Better Guzzle Mock Handler
![Build status](https://github.com/tijmenwierenga/guzzle-mock-handler/workflows/PHP%20Composer/badge.svg)

While the Guzzle HTTP client ships with a basic mock handler it might not be powerful enough in certain situations.
For example, when your requests don't arrive in an expected order. That's where this Mock Handler might come in handy.

## Requirements
Minimum PHP version is 7.4.

## Installation
Package is not published yet, so for now it can only be installed through Composer using a custom repository:

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/TijmenWierenga/guzzle-mock-handler"
        }
    ],
    "require": {
        "tijmenwierenga/guzzle-mock-handler": "dev-master"
    }
}
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

## Treeware

You're free to use this package, but if it makes it to your production environment you are required to buy the world a tree.

It’s now common knowledge that one of the best tools to tackle the climate crisis and keep our temperatures from rising above 1.5C is to <a href="https://www.bbc.co.uk/news/science-environment-48870920">plant trees</a>. If you support this package and contribute to the Treeware forest you’ll be creating employment for local families and restoring wildlife habitats.

You can buy trees here [offset.earth/treeware](https://offset.earth/treeware?gift-trees)

Read more about Treeware at [treeware.earth](http://treeware.earth)