<?php

declare(strict_types = 1);

use Nette\Caching\Cache;
use Nette\Caching\Storages\FileStorage;


class ConfigCache
{
	/** @var string */
	private $key;

	/** @var string */
	private $tempDir;


	public function __construct(string $key, string $tempDir)
	{
		$this->key = $key;
		$this->tempDir = $tempDir;
	}


	private function storage(): Cache
	{
		$cache = new Cache(new FileStorage($this->tempDir . '/cache'), $this->key);
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
}
