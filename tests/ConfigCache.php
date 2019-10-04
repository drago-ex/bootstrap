<?php

declare(strict_types = 1);

use Nette\Caching\Cache;
use Nette\Caching\Storages\FileStorage;


class ConfigCache
{
	private $key;


	public function __construct(string $key)
	{
		$this->key = $key;
	}


	private function storage(): Cache
	{
		$cache = new Cache(new FileStorage(getTempDir() . 'cache'), $this->key);
		return $cache;
	}


	public function getCache(): array
	{
		$storage = $this->storage();
		$load = $storage->load($this->key);
		$configs = [];
		if ($load) {
			foreach ($load as $row) {
				$configs[] = basename($row);
			}
		}
		return $configs;
	}


	public function removeCache(): void
	{
		$this->storage()->remove($this->key);
	}
}
