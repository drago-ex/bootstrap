<?php

declare(strict_types = 1);

use Drago\ExtraConfigurator;
use Test\Caching;
use Tester\Assert;

require __DIR__ . '/bootstrap.php';
require __DIR__ . '/Caching.php';

class TestExclude
{
	public function boot(): ExtraConfigurator
	{
		$app = new ExtraConfigurator();
		$app->setTempDirectory(__DIR__ . '/storage.exclude');
		$app->addFindConfig(__DIR__, 'exclude');

		$key = ExtraConfigurator::CACHING;
		$cache = new Caching();
		$configs = $cache->cache($key);

		Assert::same('conf.neon',   $configs[0]);
		Assert::same('9.conf.neon', $configs[1]);
		Assert::false(in_array('exclude.neon', $configs));

		return $app;
	}
}

$test = new TestExclude();
$test->boot();
