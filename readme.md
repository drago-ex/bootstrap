## Drago Bootstrap

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/bca7c54deec24262898d74e62dcfbb1e)](https://www.codacy.com/app/accgit/bootstrap?utm_source=github.com&utm_medium=referral&utm_content=drago-ex/bootstrap&utm_campaign=badger)

Basic configuration for applications.

## Requirements

- PHP 5.6 or higher
- composer

## Installation

```
composer require drago-ex/bootstrap
```

## Configuration example

```php
// Configure the application.
$app = new Drago\Configurator;

// Autoloading classes.
$app->addAutoload(__DIR__);

// Searching configuration files.
$app->addFindConfig(__DIR__ . '/path/to/dir');

// Run application.
$app->run();
```

## Description of the method that searches for configuration files

When running an application, the existence of the cache (Drago.CacheConf) is verified, and if it is empty, it activates
searching for configuration files. During searches, the paths to the configuration files found are found,
which is stored in the cache and then passed to the system container.

## How to specify the priorities for configuration files

If we need to preload some configuration files, we will do so before the file name
add a number. In general, the rule that the higher the number, the higher the priority will be.

## Warning

Because caches only save paths to configuration files, it must always be deleted when
create or delete configuration files to generate a new system container.
