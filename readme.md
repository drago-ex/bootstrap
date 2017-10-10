## Drago Bootstrap

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/bca7c54deec24262898d74e62dcfbb1e)](https://www.codacy.com/app/accgit/bootstrap?utm_source=github.com&utm_medium=referral&utm_content=drago-ex/bootstrap&utm_campaign=badger)

Základní konfigurace pro aplikace.

## Požadavky

- PHP 7.0.8 nebo vyšší
- composer

## Instalace

```json
composer require drago-ex/bootstrap
```

## Příklad konfigurace

```php
// Konfigurace aplikace.
$app = new Drago\Configurator;

// Nastavení parametrů pro vyhledávání tříd.
$app->addAutoload(__DIR__);

// Vytvoření systémového kontejneru.
$app->addFindConfig(__DIR__ . '/path/to/dir');

// Spuštění aplikace.
$app->run();
```
