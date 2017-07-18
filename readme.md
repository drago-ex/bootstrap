## Drago Bootstrap

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/bca7c54deec24262898d74e62dcfbb1e)](https://www.codacy.com/app/accgit/bootstrap?utm_source=github.com&utm_medium=referral&utm_content=drago-ex/bootstrap&utm_campaign=badger)

Nette Configurator extensions to automatically search for configuration files, found configuration files are cached.

If necessary, we can set the configuration file's priority. As a priority, we use the number before the file name, 
the higher the number, the higher the priority.

## Using Configurator:

```php
// Configure application.
$app = new Drago\Configurator();

// Enable debagger bar.
$app->enableDebugger(__DIR__ . '/logs');

// Temporary directory.
$app->setTempDirectory(__DIR__ . '/temporary');

// Enabled autoload classes.
$app->addAutoload(__DIR__);

// To scan multiple folders, use the array.
$app->addFindConfig(__DIR__ . '/modules');

// Create DI container from configuration files.
$app->addConfig(__DIR__ . '/config.neon');

// Run application.
$app->run();
```
