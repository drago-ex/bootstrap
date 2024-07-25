## Drago Bootstrap
Basic configuration.

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
```
composer require drago-ex/bootstrap
```

## Use
```php
class Bootstrap
{
	public static function boot(): ExtraConfigurator
	{
		$app = new ExtraConfigurator;

		// Finder configuration files.
		$app->addFindConfig(__DIR__ . '/path/to/dir');

		return $app;
	}
}
```

Multiple search.
```php
$app->addFindConfig([
	__DIR__ . '/path/to/dir',
	__DIR__ . '/path/to/dir'
]);
```

Search exclusion.
```php
$app->addFindConfig(__DIR__ . '/path/to/dir', 'exclude');
