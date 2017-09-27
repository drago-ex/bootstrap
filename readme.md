## Drago Bootstrap

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/bca7c54deec24262898d74e62dcfbb1e)](https://www.codacy.com/app/accgit/bootstrap?utm_source=github.com&utm_medium=referral&utm_content=drago-ex/bootstrap&utm_campaign=badger)

Drago Bootstrap rozšiřuje Nette Bootstrap o vyhledávání konfiguračních souborů, dále pak usnadňuje
volání metody pro vyhledávání tříd a spuštění aplikace.

## Jak funguje vyhledávání konfiguračních souborů

Vyhledávání funguje tak, že se spustí jen tehdy, když nebude existovat mezipaměť (Drago.CacheConf), do mezipaměti
se ukládají pouze nalezené cesty konfiguračních souborů, ty se pak předají do systémového kontejneru.

V případě, že budeme potřebovat přednostně načíst některé konfigurační soubory, uděláme to tak, že před název souboru
přidáme číslo, tím si zajistíme to, že se tyto konfigurační soubory budou načítat přednostně, můžeme je dokonce
libovolně seřadit podle čísla, obecně zde platí pravidlo, že čím vyšší bude číslo, tím vyšší bude priorita.

## Upozornění

Protože se ukádají pouze cesty konfiguračních souborů do mezipaměti (cache) je nutné ji promazat vždy,
když vytvoříme nebo vymažeme konfigurační soubor, aby se znova vygeneroval systémový kontejner.

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
