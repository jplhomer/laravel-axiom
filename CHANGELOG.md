# Changelog

All notable changes to `laravel-axiom` will be documented in this file.

## v1.2.0 - 2026-07-16

### Added

- Laravel 13 support (`illuminate/contracts` and CI matrix)

### Fixed

- CI dependency resolution for Laravel 10–13 support (Pest 2/3/4, testbench mapping, PHPStan PHP version)
- Dropped PHP 8.1 from the CI matrix because current Pest/paratest versions no longer resolve on 8.1
- Pest 4 no longer treats missing coverage driver as a failure (`phpunit.xml.dist` coverage reports removed)
- Raised Orchestra Testbench floors so prefer-lowest avoids the `$latestResponse` Pest incompatibility

## Unreleased

### Fixed

- CI dependency resolution for Laravel 10–13 support (Pest 2/3/4, testbench mapping, PHPStan PHP version)
- Dropped PHP 8.1 from the CI matrix because current Pest/paratest versions no longer resolve on 8.1
- Pest 4 no longer treats missing coverage driver as a failure (`phpunit.xml.dist` coverage reports removed)
- Raised Orchestra Testbench floors so prefer-lowest avoids the `$latestResponse` Pest incompatibility

## v1.1 - 2024-04-19

Laravel 11 support.

## v0.1.0 - Initial Release - 2024-01-17

YOLO!
