<?php

declare(strict_types = 1);

use Drago\ExtraConfigurator;
use Tester\Assert;

require __DIR__ . '/bootstrap.php';
require __DIR__ . '/Caching.php';

class TestExclude
{
	public function boot(): ExtraConfigurator
	{
		$app = new ExtraConfigurator();
		$app->setTempDirectory(__DIR__ . '/storage');
		$app->addFindConfig(__DIR__, 'conf.2');

		$key = ExtraConfigurator::CACHING;
		$cache = new Caching();
		$configs = $cache->cache($key);

		Assert::false(in_array('conf.2', $configs));
		Assert::same('conf.neon',   $configs[0]);
		Assert::same('9.conf.neon', $configs[1]);

		$cache->storage($key)->remove($key);

		return $app;
	}
}

$test = new TestExclude();
$test->boot();
