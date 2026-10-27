# Drago Bootstrap

`ExtraConfigurator` je třída postavená na `Configurator` z Nette Frameworku, která zjednodušuje
načítání a cachování konfiguračních souborů ve formátu `.neon`. Cachování ve vývojovém
i produkčním prostředí řeší sama.

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://github.com/drago-ex/bootstrap/blob/main/license)
[![PHP version](https://badge.fury.io/ph/drago-ex%2Fbootstrap.svg)](https://badge.fury.io/ph/drago-ex%2Fbootstrap)
[![Tests](https://github.com/drago-ex/bootstrap/actions/workflows/tests.yml/badge.svg)](https://github.com/drago-ex/bootstrap/actions/workflows/tests.yml)
[![Coding Style](https://github.com/drago-ex/bootstrap/actions/workflows/coding-style.yml/badge.svg)](https://github.com/drago-ex/bootstrap/actions/workflows/coding-style.yml)

## Požadavky

- PHP >= 8.3, < 9
- Nette Framework
- Composer

## Instalace

Projekt musí mít nainstalovaný Nette Framework.
```
composer require drago-ex/bootstrap
```

## Příklady

### Přidání konfiguračních souborů

Načtení konfiguračních souborů ze zadaného adresáře:

```php
use Drago\Bootstrap\ExtraConfigurator;

$configurator = new ExtraConfigurator();

// Add configuration files from the 'config' directory
$configurator->addFindConfig(__DIR__ . '/config');

// Access the application (you can configure services, routing, etc.)
$app = $configurator->app();
```

## Více adresářů

Konfigurační soubory můžete hledat i ve více adresářích:
```php
$configurator->addFindConfig([
    __DIR__ . '/config/first',
    __DIR__ . '/config/second'
]);
```

## Vynechání souborů nebo adresářů

Některé soubory nebo adresáře můžete z načítání vynechat. Každá maska se porovnává s celým
názvem souboru nebo adresáře, takže soubor vynecháte jeho celým názvem a adresář jeho názvem:
```php
// Skips the file exclude.neon
$configurator->addFindConfig(__DIR__ . '/config', 'exclude.neon');

// Skips every file inside the Install directory
$configurator->addFindConfig(__DIR__ . '/config', 'Install');
```

## Pořadí načítání

Nalezené soubory se přidávají podle názvů porovnaných číselně, potom podle cest.
Pozdější soubory přepisují dřívější, takže číselná předpona jako `8.conf.neon` nebo `9.conf.neon` určuje,
která konfigurace vyhraje.

## Cache

Režim se bere z konfigurátoru, nastavte ho proto před voláním `addFindConfig()`:
```php
$configurator->setDebugMode(false);
$configurator->addFindConfig(__DIR__ . '/config');
```

- V režimu ladění se adresáře prohledávají při každém volání, takže nové soubory se najdou hned.
- Jinak se seznam nalezených souborů ukládá do cache v dočasném adresáři. Každá kombinace cest a
  vynechání má vlastní záznam, takže několik volání v jedné aplikaci ani samostatné konfigurátory
  se sdíleným dočasným adresářem se navzájem neovlivní.
- Seznam v cache nevyprší. Po přidání nebo odebrání souboru `.neon` na produkci smažte
  `temp/cache`, aby se soubory prohledaly znovu.
