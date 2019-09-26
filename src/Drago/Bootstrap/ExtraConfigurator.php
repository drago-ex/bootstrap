<?php

declare(strict_types = 1);

/**
 * Drago Bootstrap
 * Package built on Nette Framework
 */
namespace Drago;

use Nette\Application\Application;
use Nette\Caching\Cache;
use Nette\Caching\Storages\FileStorage;
use Nette\Configurator;
use Nette\Loaders\RobotLoader;
use Nette\Utils\Finder;


/**
 * Initial system DI container generator.
 * @package Drago\Bootstrap
 */
class ExtraConfigurator extends Configurator
{
	const

		// Cache for automatically found configuration files.
		CACHING = 'Drago.CacheConf',

		// A way to sort the found files.
		SORTING = SORT_NUMERIC;


	/**
	 * Auto-loading Classes.
	 * @param  string  ...$paths  absolute path
	 */
	public function addRobotLoader(...$paths): RobotLoader
	{
		return $this->createRobotLoader()
			->addDirectory(...$paths)
			->register();
	}


	/**
	 * Searching for configuration files.
	 * @param  string|string[]  $paths
	 * @param  string|string[]  $masks
	 */
	public function addFindConfig($paths, ...$exclude): self
	{
		$storage = new FileStorage($this->getCacheDirectory());
		$cache   = new Cache($storage, self::CACHING);

		// Check the stored cache.
		if (!$cache->load(self::CACHING)) {

			$items = [];
			foreach (Finder::findFiles('*.neon')->from($paths)->exclude($exclude) as $key => $file) {
				$items[] = $key;
			}

			$names = [];
			foreach ($items as $row) {
				$names[] = basename($row);
			}

			array_multisort($names, self::SORTING, $items);
			$cache->save(self::CACHING, $items);
		}

		// Loading cached saved.
		if ($cache->load(self::CACHING)) {
			foreach ($cache->load(self::CACHING) as $row) {
				$configs = $this->addConfig($row);
			}
		}
		return $configs;
	}


	/**
	 * Dispatch a HTTP request to a front controller.
	 * @throws \Throwable
	 */
	public function running(): void
	{
		$this->createContainer()
			->getByType(Application::class)
			->run();
	}
}
