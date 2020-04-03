<?php

declare(strict_types = 1);

use Tester\Assert;

/** @var $boot Drago\Bootstrap\ExtraConfigurator */
$boot = require __DIR__ . '/../bootstrap.php';


test(function () use ($boot) {
	$boot->addFindConfig(__DIR__ . '/../file/conf');

	$configCache = new ConfigCache($boot::CACHING, TEMP_DIR);
	$config = $configCache->getCache();

	Assert::same('conf.neon', $config[0]);
	Assert::same('9.conf.neon', $config[1]);

	$configCache->remove();
});


test(function () use ($boot) {
	$boot->addFindConfig([
		__DIR__ . '/../file/conf',
		__DIR__ . '/../file/conf.2',
	]);

	$configCache = new ConfigCache($boot::CACHING, TEMP_DIR);
	$config = $configCache->getCache();

	Assert::same('exclude.neon', $config[0]);
	Assert::same('conf.neon', $config[1]);
	Assert::same('9.conf.neon', $config[2]);

	$configCache->remove();
});


test(function () use ($boot) {
	$boot->addFindConfig(__DIR__ . '/../file', 'conf.2');

	$configCache = new ConfigCache($boot::CACHING, TEMP_DIR);
	$config = $configCache->getCache();

	Assert::same('conf.neon', $config[0]);
	Assert::same('9.conf.neon', $config[1]);
	Assert::false(in_array('exclude.neon', $config, true));

	$configCache->remove();
});


test(function () use ($boot) {
	Assert::type(Nette\Application\Application::class, $boot->app());
});
