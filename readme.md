## Drago Bootstrap

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/bca7c54deec24262898d74e62dcfbb1e)](https://www.codacy.com/app/accgit/bootstrap?utm_source=github.com&utm_medium=referral&utm_content=drago-ex/bootstrap&utm_campaign=badger)

Základní konfigurace pro aplikace.

## Požadavky

- PHP 7.0.8 nebo vyšší
- composer

## Instalace

```
composer require drago-ex/bootstrap
```

## Příklad konfigurace

```php
// Konfigurace aplikace.
$app = new Drago\Configurator;

// Nastavení parametrů pro vyhledávání tříd.
$app->addAutoload(__DIR__);

// Vyhledávaní konfiguračních souborů.
$app->addFindConfig(__DIR__ . '/path/to/dir');

// Spuštění aplikace.
$app->run();
```

## Popis metody, která vyhledává konfigurační soubory

Při spuštění aplikace se ověří existence cache (Drago.CacheConf), a jestliže bude prázdná, tak se aktivuje
vyhledávání konfiguračních souborů. Během vyhledávání se zjistí cesty k nalezeným konfiguračním souborům, 
které se uloží do cache a následně se předají systémovému kontejneru.

## Jak určit priority pro konfigurační soubory

V případě, že budeme potřebovat přednostně načíst některé konfigurační soubory, uděláme to tak, že před název souboru
přidáme číslo. Obecně zde platí pravidlo, že čím vyšší bude číslo, tím vyšší bude priorita.

## Upozornění

Protože se do cache ukládají pouze nalezené cesty ke konfiguračním souborům, je tedy nutné ji vždy promazat, když
vytvoříme nebo vymažeme konfigurační soubory, aby se vygeneroval nový systémový kontejner.
