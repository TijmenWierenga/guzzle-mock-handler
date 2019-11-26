# Better Guzzle Mock Handler
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
The `ExpectationBuilder` can be leveraged in order to set request expectations:

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

