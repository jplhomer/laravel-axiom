# Laravel logging handler for Axiom

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jplhomer/laravel-axiom.svg?style=flat-square)](https://packagist.org/packages/jplhomer/laravel-axiom)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/jplhomer/laravel-axiom/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/jplhomer/laravel-axiom/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/jplhomer/laravel-axiom/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/jplhomer/laravel-axiom/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/jplhomer/laravel-axiom.svg?style=flat-square)](https://packagist.org/packages/jplhomer/laravel-axiom)

This package provides a logging handler for [Axiom](https://axiom.co/). It allows you to send logs to Axiom from your Laravel application.

You can install the package via composer:

```bash
composer require jplhomer/laravel-axiom
```

Then, add a new `axiom` channel to your `config/logging.php` file:

```php
$channels = [
    // ...
    'axiom' => [
        'driver' => 'monolog',
        'handler' => Jplhomer\Axiom\AxiomLogHandler::class,
        'level' => env('LOG_LEVEL', 'debug'),
        'with' => [
            'apiToken' => env('AXIOM_API_TOKEN'),
            'dataset' => env('AXIOM_DATASET'),
        ],
    ],
]
```

Finally, be sure to set your `AXIOM_API_TOKEN` and `AXIOM_DATASET` environment variables in `.env`. You can create a token in the [Axiom dashboard](https://app.axiom.co/barkpass-lxgt/settings/api-tokens).

```bash
AXIOM_API_TOKEN=your-api-token
AXIOM_DATASET=your-dataset
```

## Performance Considerations

Since Axiom logs are sent over HTTP, you may want to consider the performance impact of sending logs during request time. By default, this package will send logs to Axiom synchronously. This means each time you log something, your application will wait for the request to Axiom to complete before continuing to process the request.

A better solution is to send structured request logs _after_ the response has been sent. To accomplish this, you can create a [terminable middleware](https://laravel.com/docs/8.x/middleware#terminable-middleware) that sends the logs to Axiom after the response has been sent to the user.

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RequestLogger
{
    /**
     * Log all the things that are relevant to the incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $context = [
            'request_host' => $request->getHost(),
            'request_path' => str($request->path())->startsWith('/') ? $request->path() : "/{$request->path()}",
            'request_query' => $request->getQueryString(),
            'request_method' => $request->method(),
            'request_user_agent' => $request->userAgent(),
        ];

        Log::withContext($context);

        // Note: You can use `Log::withContext()` to add context in other parts of your application, too!

        return $next($request);
    }

    public function terminate(Request $request, Response $response): void
    {
        $path = '/' . str($request->path())->ltrim('/');

        $startTime = defined('LARAVEL_START') ? LARAVEL_START : $request->server('REQUEST_TIME_FLOAT');

        $context = [
            'status_code' => $response->getStatusCode(),
            'processing_time_ms' => round((microtime(true) - $startTime) * 1000, 2),
            'request_controller_action' => $request->route()?->getActionName(),
        ];

        Log::info("[{$response->getStatusCode()}] {$request->method()} {$path}", $context);
    }
}
```

Then, register the middleware in your Http Kernel:

```php
// app/Http/Kernel.php

protected $middleware = [
    // ...
    \App\Http\Middleware\RequestLogger::class,
];
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

-   [Josh Larson](https://github.com/jplhomer)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
