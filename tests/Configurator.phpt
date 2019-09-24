<?php

declare(strict_types = 1);

use Drago\Configurator;
use Nette\Caching\Cache;
use Nette\Caching\Storages\FileStorage;
use Tester\Assert;

require __DIR__ . '/bootstrap.php';

$conf = new Configurator();
$conf->setTempDirectory(__DIR__ . '/storage');
$conf->addFindConfig(__DIR__ . '/conf');

$key = $conf::CACHING;
$storage = new FileStorage(__DIR__ . '/storage/cache');
$cache   = new Cache($storage, $key);
$configs = [];
if ($cache->load($key)) {
	foreach ($cache->load($key) as $row) {
		$configs[] = basename($row);
	}
}

Assert::same('conf.neon', 	$configs[0]);
Assert::same('9.conf.neon', $configs[1]);

$cache->remove($key);
