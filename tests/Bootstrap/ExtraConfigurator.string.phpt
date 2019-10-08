<?php

declare(strict_types = 1);

namespace Test\Bootstrap;

use Drago\ExtraConfigurator;
use Tester\Assert;
use ConfigCache;

require __DIR__ . '/../bootstrap.php';


test(function () {
	$tempDir = getTempDir() . 'string';
	$configurator = new ExtraConfigurator;
	$configurator->setTempDirectory($tempDir);
	$configurator->createRobotLoader()
		->addDirectory(__DIR__ . '/../../tests')
		->register();

	$configurator->addFindConfig(getFilesDir() . 'conf');

	$cache = new ConfigCache(ExtraConfigurator::CACHING, $tempDir);
	$configs = $cache->getCache();

	Assert::same('conf.neon', $configs[0]);
	Assert::same('9.conf.neon', $configs[1]);
});
