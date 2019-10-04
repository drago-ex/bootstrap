<?php

declare(strict_types = 1);

use Drago\ExtraConfigurator;
use Tester\Assert;


function testByArray()
{
	$app = new ExtraConfigurator;
	$app->setTempDirectory(getTempDir());
	$app->addFindConfig([
		getFilesDir() . 'conf',
		getFilesDir() . 'conf.2'
	]);

	$cache = new ConfigCache(ExtraConfigurator::CACHING);
	$configs = $cache->getCache();

	Assert::same('exclude.neon', $configs[0]);
	Assert::same('conf.neon', $configs[1]);
	Assert::same('9.conf.neon', $configs[2]);

	$cache->removeCache();
}
