<p align="center">
  <img src="https://avatars0.githubusercontent.com/u/11717487?s=400&u=40ecb522587ebbcfe67801ccb6f11497b259f84b&v=4" width="100" alt="logo">
</p>

<h3 align="center">Drago</h3>
<p align="center">Simple packages built on Nette Framework</p>

## Info

Basic configuration for applications.

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://raw.githubusercontent.com/drago-ex/bootstrap/master/license.md)
[![PHP version](https://badge.fury.io/ph/drago-ex%2Fbootstrap.svg)](https://badge.fury.io/ph/drago-ex%2Fbootstrap)
[![CodeFactor](https://www.codefactor.io/repository/github/drago-ex/bootstrap/badge)](https://www.codefactor.io/repository/github/drago-ex/bootstrap)

## Requirements

- PHP 7.1 or higher
- composer

## Installation

```
composer require drago-ex/bootstrap
```

## Configuration example

```php
// Configure the application.
$app = new Drago\Configurator;

// Auto-loading Classes.
$app->addAutoload(__DIR__);

// Search configuration files.
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

## Notice

Because caches only save paths to configuration files, it must always be deleted when
create or delete configuration files to generate a new system container.
