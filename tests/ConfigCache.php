<?php

declare(strict_types = 1);

use Nette\Caching;


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


	private function storage(): Caching\Cache
	{
		$tempDir = $this->tempDir . '/cache';
		$storage = new Caching\Storages\FileStorage($tempDir);
		return new Caching\Cache($storage, $this->key);
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


	public function remove(): void
	{
		$this->storage()->remove($this->key);
	}
}
