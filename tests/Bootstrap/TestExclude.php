<?php

declare(strict_types = 1);

use Drago\ExtraConfigurator;
use Test\CacheUp;
use Tester\Assert;


class TestExclude
{
	public function boot(): ExtraConfigurator
	{
		$app = new ExtraConfigurator();
		$app->setTempDirectory(__DIR__ . '/../storage');
		$app->addFindConfig(__DIR__ . '/../files', 'conf.2');

		$key = ExtraConfigurator::CACHING;
		$cache = new CacheUp();
		$configs = $cache->cache($key);

		Assert::same('conf.neon', $configs[0]);
		Assert::same('9.conf.neon', $configs[1]);
		Assert::false(in_array('exclude.neon', $configs, true));

		$cache->storage($key)->remove($key);

		return $app;
	}
}
