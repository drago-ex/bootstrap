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
		// Set up the file storage and cache object
		$storage = new FileStorage($this->getCacheDirectory());
		$cache = new Cache($storage, self::Caching);

		// Check if cache already exists and is still valid
		if (!$cache->load(self::Caching)) {
			$items = [];
			$names = [];

			// Search for all .neon configuration files in the specified directories
			foreach (Finder::findFiles('*.neon')->from($paths)->exclude($exclude) as $file) {
				$items[] = $file->getRealPath();  // Full path of the file
				$names[] = $file->getBasename();  // Basename of the file for sorting
			}

			// Sort the found items based on the file names (numeric sort order)
			array_multisort($items, SORT_NUMERIC, $names);

			// Save the list of found items to the cache with no expiration
			$cache->save(self::Caching, $items, [
				Cache::All => true,  // Mark cache as non-expiring
			]);
		}

		// Load and apply the cached configuration files if available
		if ($cachedItems = $cache->load(self::Caching)) {
			foreach ($cachedItems as $row) {
				$this->addConfig($row);  // Add the cached configuration file
			}

			// If in development (debug) mode, invalidate the cache to allow updates
			if (Debugger::$productionMode === false) {
				$cache->remove(self::Caching);  // Remove the cache so it will be recreated next time
			}
		}

		return $this;
	}
}
