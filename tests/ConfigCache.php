<?php

declare(strict_types=1);

use Nette\Caching\Cache;
use Nette\Caching\Storages\FileStorage;


class ConfigCache
{
	private string $key;
	private string $tempDir;


	public function __construct(string $key, string $tempDir)
	{
		$this->key = $key;
		$this->tempDir = $tempDir;
	}


	private function storage(): Cache
	{
		$tempDir = $this->tempDir . '/cache';
		return new Cache(new FileStorage($tempDir), $this->key);
	}


	/**
	 * @throws Throwable
	 */
	public function getCache(): array
	{
		$storage = $this->storage();

		/** @var list<string>|null $load */
		$load = $storage->load($this->key);
		$configs = [];
		if (is_iterable($load)) {
			foreach ($load as $row) {
				$configs[] = basename($row);
			}
		}
		return $configs;
	}


	public function remove(): void
	{
		$this->storage()->remove($this->key);
	}
}
