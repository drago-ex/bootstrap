<?php

/**
 * Drago Extension
 * Package built on Nette Framework
 */

declare(strict_types=1);

namespace Drago\Bootstrap;

use Nette\Bootstrap\Configurator;
use Nette\Caching\Cache;
use Nette\Caching\Storages\FileStorage;
use Nette\Utils\Finder;
use Throwable;
use Tracy\Debugger;


/**
 * The ExtraConfigurator class manages loading and caching configuration files (.neon).
 * It automatically caches configuration files in development mode and ensures efficient cache handling in production.
 * The cache is invalidated on each request in development, while in production it is cached without expiration,
 * improving performance unless configuration files are modified.
 */
class ExtraConfigurator extends Configurator
{
	public const string Caching = 'drago.cacheConf';


	/**
	 * Searches for configuration files (.neon) in the given directories and stores them in cache.
	 * If the cache is already available, the cached configuration files are loaded.
	 * In development mode, the cache is invalidated on each request to allow immediate updates.
	 *
	 * @param array|string $paths Directories to search for configuration files
	 * @param array|string ...$exclude Directories or files to exclude from the search
	 * @return static
	 * @throws Throwable
	 */
	public function addFindConfig(array|string $paths, array|string ...$exclude): static
	{
		// Set up the file storage and cache object.
		$storage = new FileStorage($this->getCacheDirectory());
		$cache = new Cache($storage, 'config.search');

		// Load the cache data if it exists (used in both production and development modes).
		$cachedItems = $cache->load(self::Caching);

		// If in development (debug) mode, invalidate the cache to allow updates.
		if (Debugger::$productionMode === false) {
			$items = $this->finder($paths, ...$exclude);
			foreach ($items as $item) {
				$this->addConfig($item);
			}

			// Remove the cache to ensure it's rebuilt on the next request.
			$cache->remove(self::Caching);

		} else {
			if (!$cachedItems) {
				$items = $this->finder($paths, ...$exclude);
				$cache->save(self::Caching, $items, [
					Cache::All => true,
				]);
				$cachedItems = $items;
			}

			// Apply the cached configuration files.
			foreach ($cachedItems as $item) {
				$this->addConfig($item);
			}
		}

		return $this;
	}


	private function finder(array|string $paths, array|string ...$exclude): array
	{
		// Initialize Finder and apply filtering.
		$finder = Finder::findFiles('*.neon')
			->from($paths)
			->exclude($exclude)
			->sortBy(function ($file) {
				return $file->getBasename();
			});

		// Collect all files' real paths.
		$items = [];
		foreach ($finder as $file) {
			$items[] = $file->getRealPath();
		}

		return $items;
	}
}
