<?php

declare(strict_types = 1);

use Nette\Caching\Cache;
use Nette\Caching\Storages\FileStorage;


class Caching
{
	public function storage(string $key): Cache
	{
		$storage = new FileStorage(__DIR__ . '/storage/cache');
		$cache   = new Cache($storage, $key);
		return $cache;
	}


	public function cache(string $key): array
	{
		$cache = $this->storage($key);
		$configs = [];
		if ($cache->load($key)) {
			foreach ($cache->load($key) as $row) {
				$configs[] = basename($row);
			}
		}
		return $configs;
	}
}
