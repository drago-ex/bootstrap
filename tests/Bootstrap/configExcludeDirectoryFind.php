<?php

declare(strict_types = 1);

use Drago\ExtraConfigurator;
use Tester\Assert;


function testExcludeDirectory()
{
	$app = new ExtraConfigurator;
	$app->setTempDirectory(getTempDir());
	$app->addFindConfig(getFilesDir(), 'conf.2');

	$cache = new ConfigCache(ExtraConfigurator::CACHING);
	$configs = $cache->getCache();

	Assert::same('conf.neon', $configs[0]);
	Assert::same('9.conf.neon', $configs[1]);
	Assert::false(in_array('exclude.neon', $configs, true));

	$cache->removeCache();
}
