<?php

declare(strict_types = 1);

use Nette\Application\Application;
use Tester\Assert;

/** @var $boot Drago\Bootstrap\ExtraConfigurator */
$boot = require __DIR__ . '/../bootstrap.php';


test(function () use ($boot) {
	$boot->addFindConfig(__DIR__ . '/../file/conf');

	$cache = new ConfigCache($boot::CACHING, TEMP_DIR);
	$configs = $cache->getCache();

	Assert::same('conf.neon', $configs[0]);
	Assert::same('9.conf.neon', $configs[1]);

	$cache->remove();
});


test(function () use ($boot) {
	$boot->addFindConfig([
		__DIR__ . '/../file/conf',
		__DIR__ . '/../file/conf.2',
	]);

	$cache = new ConfigCache($boot::CACHING, TEMP_DIR);
	$configs = $cache->getCache();

	Assert::same('exclude.neon', $configs[0]);
	Assert::same('conf.neon', $configs[1]);
	Assert::same('9.conf.neon', $configs[2]);

	$cache->remove();
});


test(function () use ($boot) {
	$boot->addFindConfig(__DIR__ . '/../file', 'conf.2');

	$cache = new ConfigCache($boot::CACHING, TEMP_DIR);
	$configs = $cache->getCache();

	Assert::same('conf.neon', $configs[0]);
	Assert::same('9.conf.neon', $configs[1]);
	Assert::false(in_array('exclude.neon', $configs, true));

	$cache->remove();
});


test(function () use ($boot) {
	Assert::type(Application::class, $boot->app());
});
