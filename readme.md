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

## Description of the method that searches for configuration files
When running an application, the existence of the cache (Drago.CacheConf) is verified, and if it is empty, it activates searching for configuration files. During searches, the paths to the configuration files found are found, which is stored in the cache and then passed to the system container.

## How to specify the priorities for configuration files
If we need to preload some configuration files, we will do so before the file name add a number. In general, the rule that the higher the number, the higher the priority will be.

## Notice
Because caches only save paths to configuration files, it must always be deleted when create or delete configuration files to generate a new system container.

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
