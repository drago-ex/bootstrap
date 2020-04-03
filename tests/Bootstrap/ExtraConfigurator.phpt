<?php

declare(strict_types = 1);

use Tester\Assert;

/** @var $boot Drago\Bootstrap\ExtraConfigurator */
$boot = require __DIR__ . '/../bootstrap.php';


$configCache = function () use ($boot): ConfigCache {
	return new ConfigCache($boot::CACHING, TEMP_DIR);
};


test(function () use ($boot, $configCache) {
	$boot->addFindConfig(CONF_DIR . '/conf');
	$config = $configCache()->getCache();

	Assert::same('conf.neon', $config[0]);
	Assert::same('9.conf.neon', $config[1]);

	$configCache()->remove();
});


test(function () use ($boot, $configCache) {
	$boot->addFindConfig([
		CONF_DIR . '/conf',
		CONF_DIR . '/conf.2',
	]);

	$config = $configCache()->getCache();

	Assert::same('exclude.neon', $config[0]);
	Assert::same('conf.neon', $config[1]);
	Assert::same('9.conf.neon', $config[2]);

	$configCache()->remove();
});


test(function () use ($boot, $configCache) {
	$boot->addFindConfig(CONF_DIR, 'conf.2');
	$config = $configCache()->getCache();

	Assert::same('conf.neon', $config[0]);
	Assert::same('9.conf.neon', $config[1]);
	Assert::false(in_array('exclude.neon', $config, true));

	$configCache()->remove();
});


test(function () use ($boot) {
	Assert::type(Nette\Application\Application::class, $boot->app());
});
