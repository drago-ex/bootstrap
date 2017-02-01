## Drago Bootstrap

Nette Configurator compared with the Drago Configurator file is extended by an automatic
search configuration files. Found configuration files are stored in the own cache.

If necessary, we can set the configuration file priority. Priority is set to put the number
in front of the file name, the higher the number, the higher the priority.

The boot file (usually bootstrap), use this configuration:

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
$app->addFindConfig(__DIR__ . '/directory');

// Create DI container from configuration files.
$app->addConfig(__DIR__ . '/config.neon');

// Run application.
$app->run();
```
