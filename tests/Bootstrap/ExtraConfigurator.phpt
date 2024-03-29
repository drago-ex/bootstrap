<?php

/**
 * Test: Drago\Bootstrap\ExtraConfigurator
 */

declare(strict_types=1);

use Nette\Application\Application;
use Tester\Assert;

/** @var $boot Drago\Bootstrap\ExtraConfigurator */
$boot = require __DIR__ . '/../bootstrap.php';


$configCache = fn(): ConfigCache => new ConfigCache($boot::Caching, TempDir);


test('Find the configuration file from one place', function () use ($boot, $configCache) {
	$boot->addFindConfig(ConfDir . '/conf');
	$config = $configCache()->getCache();

	Assert::same('conf.neon', $config[0]);
	Assert::same('9.conf.neon', $config[1]);

	$configCache()->remove();
});


test('Find the configuration file from multiple locations', function () use ($boot, $configCache) {
	$boot->addFindConfig([
		ConfDir . '/conf',
		ConfDir . '/conf.2',
	]);

	$config = $configCache()->getCache();

	Assert::same('exclude.neon', $config[0]);
	Assert::same('conf.neon', $config[1]);
	Assert::same('9.conf.neon', $config[2]);

	$configCache()->remove();
});


test('Find the configuration file and exclude which we do not want', function () use ($boot, $configCache) {
	$boot->addFindConfig(ConfDir, 'conf.2');
	$config = $configCache()->getCache();

	Assert::same('conf.neon', $config[0]);
	Assert::same('9.conf.neon', $config[1]);
	Assert::false(in_array('exclude.neon', $config, true));

	$configCache()->remove();
});


test('Check type', function () use ($boot) {
	Assert::type(Application::class, $boot->app());
});
