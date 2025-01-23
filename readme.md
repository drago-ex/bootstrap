## Drago\Bootstrap\ExtraConfigurator
`ExtraConfigurator` is a class built on top of Nette Framework's `Configurator` to simplify
loading and caching of configuration files in `.neon` format. It automatically handles
caching in development and production environments.

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://raw.githubusercontent.com/drago-ex/bootstrap/master/license.md)
[![PHP version](https://badge.fury.io/ph/drago-ex%2Fbootstrap.svg)](https://badge.fury.io/ph/drago-ex%2Fbootstrap)
[![Tests](https://github.com/drago-ex/bootstrap/actions/workflows/tests.yml/badge.svg)](https://github.com/drago-ex/bootstrap/actions/workflows/tests.yml)
[![Coding Style](https://github.com/drago-ex/bootstrap/actions/workflows/coding-style.yml/badge.svg)](https://github.com/drago-ex/bootstrap/actions/workflows/coding-style.yml)
[![CodeFactor](https://www.codefactor.io/repository/github/drago-ex/bootstrap/badge)](https://www.codefactor.io/repository/github/drago-ex/bootstrap)
[![Coverage Status](https://coveralls.io/repos/github/drago-ex/bootstrap/badge.svg?branch=master)](https://coveralls.io/github/drago-ex/bootstrap?branch=master)

## Requirements
- PHP 8.1 or higher
- composer

## Installation
Make sure you have Nette Framework installed in your project.
```
composer require nette/bootstrap nette/caching tracy/tracy
```

## Basic Usage
### Adding Configuration Files
To load configuration files from a specified directory:
```php
use Drago\Bootstrap\ExtraConfigurator;

$configurator = new ExtraConfigurator();

// Add configuration files from the 'config' directory
$configurator->addFindConfig(__DIR__ . '/config');

// Access the application (you can configure services, routing, etc.)
$app = $configurator->app();

```

## Adding Multiple Directories
You can also provide multiple directories for configuration files:
```php
$configurator->addFindConfig([
    __DIR__ . '/config/first',
    __DIR__ . '/config/second'
]);
```

## Excluding Files or Directories
You can exclude certain files or directories from being loaded:
```php
$configurator->addFindConfig(__DIR__ . '/config', 'exclude');
```
This will load all `.neon` files from the `config` directory except `exclude.neon`.

## Cache Management
In development mode, the cache is invalidated after each request to allow immediate updates.
In production mode, the cache is stored without expiration unless the configuration files are modified.
```php
use Tracy\Debugger;

// Enable production mode to use persistent cache
Debugger::$productionMode = true;

// Cache is automatically handled and invalidated only when necessary
$configurator->addFindConfig(__DIR__ . '/config');
```
