# Drago Bootstrap

`ExtraConfigurator` extends the Nette `Configurator`: it finds the `.neon` files in given directories and
loads them in a defined order; outside debug mode the list of found files is cached.

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://github.com/drago-ex/bootstrap/blob/main/license)
[![PHP version](https://badge.fury.io/ph/drago-ex%2Fbootstrap.svg)](https://badge.fury.io/ph/drago-ex%2Fbootstrap)
[![Tests](https://github.com/drago-ex/bootstrap/actions/workflows/tests.yml/badge.svg)](https://github.com/drago-ex/bootstrap/actions/workflows/tests.yml)
[![Coding Style](https://github.com/drago-ex/bootstrap/actions/workflows/coding-style.yml/badge.svg)](https://github.com/drago-ex/bootstrap/actions/workflows/coding-style.yml)

## Requirements

- PHP >= 8.3, < 9
- Nette Framework
- Composer

## Installation

```
composer require drago-ex/bootstrap
```

## Usage

Load all configuration files of a directory (searched recursively):

```php
use Drago\Bootstrap\ExtraConfigurator;

$configurator = new ExtraConfigurator;
$configurator->setTempDirectory(__DIR__ . '/../temp');
$configurator->addFindConfig(__DIR__ . '/config');
$container = $configurator->createContainer();
```

### Adding Multiple Directories

You can also provide multiple directories for configuration files:
```php
$configurator->addFindConfig([
    __DIR__ . '/config/first',
    __DIR__ . '/config/second'
]);
```

### Excluding Files or Directories

You can exclude certain files or directories from being loaded. Each mask is matched against the whole
file or directory name, so a file is excluded by its full name and a directory by its name:
```php
// Skips the file exclude.neon
$configurator->addFindConfig(__DIR__ . '/config', 'exclude.neon');

// Skips every file inside the Install directory
$configurator->addFindConfig(__DIR__ . '/config', 'Install');
```

### Loading Order

Found files are added in the order of their file names compared numerically, then by their paths.
Later files override earlier ones, so a numeric prefix such as `8.conf.neon` or `9.conf.neon` controls
which configuration wins.

### Cache Management

The mode is taken from the configurator, so set it before calling `addFindConfig()`:
```php
$configurator->setDebugMode(false);
$configurator->addFindConfig(__DIR__ . '/config');
```

- In debug mode, the directories are searched on every call, so new files are found immediately.
- Otherwise, the list of found files is cached in the temp directory. Each combination of paths and
  exclusions has its own cache entry, so several calls in one application, or separate configurators
  sharing the temp directory, do not affect each other.
- The cached list does not expire. After adding or removing a `.neon` file in production, clear
  `temp/cache` so the files are searched again.
