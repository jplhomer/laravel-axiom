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
