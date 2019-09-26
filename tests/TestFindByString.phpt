<?php

declare(strict_types = 1);

use Drago\ExtraConfigurator;
use Test\Caching;
use Tester\Assert;

require __DIR__ . '/bootstrap.php';
require __DIR__ . '/Caching.php';

class TestFindByString
{
	public function boot(): ExtraConfigurator
	{
		$app = new ExtraConfigurator();
		$app->setTempDirectory(__DIR__ . '/storage.string');
		$app->addFindConfig(__DIR__ . '/conf');

		$key = ExtraConfigurator::CACHING;
		$cache = new Caching();
		$configs = $cache->cache($key);

		Assert::same('conf.neon',   $configs[0]);
		Assert::same('9.conf.neon', $configs[1]);

		return $app;
	}
}

$test = new TestFindByString();
$test->boot();
