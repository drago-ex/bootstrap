<?php declare(strict_types=1);

if (@!include __DIR__ . '/../vendor/autoload.php') { // @ dependencies may not be installed
	echo 'Install Nette Tester using `composer install`';
	exit(1);
}


Tester\Environment::setup();
Tester\Environment::setupFunctions();
date_default_timezone_set('Europe/Prague');


const TempDir = __DIR__ . '/tmp';
const ConfDir = __DIR__ . '/file';

@mkdir(TempDir); // @ directory may already exist


/** Exposes the collected config files, which Nette keeps protected. */
class TestConfigurator extends Drago\Bootstrap\ExtraConfigurator
{
	/** @return list<string> */
	public function getConfigNames(): array
	{
		return array_map(
			static fn(string $config): string => basename($config),
			array_values(array_filter($this->configs, 'is_string')),
		);
	}
}


/** Creates a configurator with its own temp directory, so tests do not share a cache. */
function createConfigurator(bool $debugMode, ?string $tempDir = null): TestConfigurator
{
	$tempDir ??= TempDir . '/' . getmypid() . '-' . bin2hex(random_bytes(4));
	@mkdir($tempDir, 0o777, true); // @ directory may already exist

	$configurator = new TestConfigurator;
	$configurator->setDebugMode($debugMode);
	$configurator->setTempDirectory($tempDir);
	return $configurator;
}
