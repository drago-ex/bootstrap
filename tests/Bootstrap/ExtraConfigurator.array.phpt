<?php

declare(strict_types = 1);

namespace Test\Bootstrap;

use ConfigCache;
use Drago\ExtraConfigurator;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test(function () {
	$tempDir = getTempDir() . 'array';
	$configurator = new ExtraConfigurator;
	$configurator->setTempDirectory($tempDir);
	$configurator->createRobotLoader()
		->addDirectory(__DIR__ . '/../../tests')
		->register();

	$configurator->addFindConfig([
		getFilesDir() . 'conf',
		getFilesDir() . 'conf.2',
	]);

	$cache = new ConfigCache(ExtraConfigurator::CACHING, $tempDir);
	$configs = $cache->getCache();

	Assert::same('exclude.neon', $configs[0]);
	Assert::same('conf.neon', $configs[1]);
	Assert::same('9.conf.neon', $configs[2]);
});
