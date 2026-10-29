<?php declare(strict_types=1);

/**
 * Test: Drago\Bootstrap\ExtraConfigurator
 */

use Tester\Assert;
use Tracy\Debugger;

require __DIR__ . '/../bootstrap.php';


test('Find the configuration file from one place', function () {
	$configurator = createConfigurator(debugMode: false);
	$configurator->addFindConfig(ConfDir . '/one');

	Assert::same(['common.neon', 'services.neon'], $configurator->getConfigNames());
});


test('Find the configuration file from multiple locations', function () {
	$configurator = createConfigurator(debugMode: false);
	$configurator->addFindConfig([
		ConfDir . '/one',
		ConfDir . '/two',
	]);

	Assert::same(['common.neon', 'services.neon', 'exclude.neon'], $configurator->getConfigNames());
});


test('Exclude a directory by its name', function () {
	$configurator = createConfigurator(debugMode: false);
	$configurator->addFindConfig(ConfDir, 'two');

	Assert::same(['common.neon', 'services.neon'], $configurator->getConfigNames());
});


test('Exclude a file by its full name', function () {
	$configurator = createConfigurator(debugMode: false);
	$configurator->addFindConfig(ConfDir, 'exclude.neon');

	Assert::same(['common.neon', 'services.neon'], $configurator->getConfigNames());
});


test('Cached results are kept separately for different paths and exclusions', function () {
	$tempDir = TempDir . '/shared-' . bin2hex(random_bytes(4));

	createConfigurator(false, $tempDir)->addFindConfig(ConfDir . '/two');

	$configurator = createConfigurator(false, $tempDir);
	$configurator->addFindConfig(ConfDir . '/one');
	Assert::same(['common.neon', 'services.neon'], $configurator->getConfigNames());

	$configurator = createConfigurator(false, $tempDir);
	$configurator->addFindConfig(ConfDir, 'two');
	Assert::same(['common.neon', 'services.neon'], $configurator->getConfigNames());
});


test('Production mode reuses the cached list of files', function () {
	$tempDir = TempDir . '/production-' . bin2hex(random_bytes(4));
	$confDir = $tempDir . '/config';
	@mkdir($confDir, 0o777, true); // @ directory may already exist
	file_put_contents($confDir . '/common.neon', '');

	createConfigurator(false, $tempDir)->addFindConfig($confDir);
	file_put_contents($confDir . '/later.neon', '');

	$configurator = createConfigurator(false, $tempDir);
	$configurator->addFindConfig($confDir);
	Assert::same(['common.neon'], $configurator->getConfigNames());
});


test('Debug mode finds new files immediately', function () {
	$tempDir = TempDir . '/debug-' . bin2hex(random_bytes(4));
	$confDir = $tempDir . '/config';
	@mkdir($confDir, 0o777, true); // @ directory may already exist
	file_put_contents($confDir . '/common.neon', '');

	createConfigurator(true, $tempDir)->addFindConfig($confDir);
	file_put_contents($confDir . '/later.neon', '');

	$configurator = createConfigurator(true, $tempDir);
	$configurator->addFindConfig($confDir);
	Assert::same(['common.neon', 'later.neon'], $configurator->getConfigNames());
});


test('The mode is taken from the configurator, not from Tracy', function () {
	$tempDir = TempDir . '/mode-' . bin2hex(random_bytes(4));
	$confDir = $tempDir . '/config';
	@mkdir($confDir, 0o777, true); // @ directory may already exist
	file_put_contents($confDir . '/common.neon', '');

	$productionMode = Debugger::$productionMode;
	Debugger::$productionMode = null;

	try {
		createConfigurator(true, $tempDir)->addFindConfig($confDir);
		file_put_contents($confDir . '/later.neon', '');

		$configurator = createConfigurator(true, $tempDir);
		$configurator->addFindConfig($confDir);
		Assert::same(['common.neon', 'later.neon'], $configurator->getConfigNames());
	} finally {
		Debugger::$productionMode = $productionMode;
	}
});
