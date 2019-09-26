<?php

declare(strict_types = 1);

use Drago\ExtraConfigurator;
use Test\Caching;
use Tester\Assert;

require __DIR__ . '/bootstrap.php';
require __DIR__ . '/Caching.php';

class TestFindByArray
{
	public function boot(): ExtraConfigurator
	{
		$app = new ExtraConfigurator();
		$app->setTempDirectory(__DIR__ . '/storage.array');
		$app->addFindConfig([
			__DIR__ . '/conf',
			__DIR__ . '/conf.2'
		]);

		$key = ExtraConfigurator::CACHING;
		$cache = new Caching();
		$configs = $cache->cache($key);

		Assert::same('conf.2.neon', $configs[0]);
		Assert::same('conf.neon',   $configs[1]);
		Assert::same('9.conf.neon', $configs[2]);

		return $app;
	}
}

$test = new TestFindByArray();
$test->boot();
