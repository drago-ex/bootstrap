<?php

declare(strict_types = 1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago;

use Nette;


/**
 * Initial system DI container generator.
 */
class ExtraConfigurator extends Nette\Configurator
{
	// Cache for found configuration files.
	public const CACHING = 'Drago.CacheConf';


	/**
	 * Searching for configuration files.
	 * @param  string|string[]  $paths
	 * @param  string|string[]  $masks
	 */
	public function addFindConfig($paths, ...$exclude): self
	{
		$storage = new Nette\Caching\Storages\FileStorage($this->getCacheDirectory());
		$cache = new Nette\Caching\Cache($storage, self::CACHING);

		// Check the stored cache.
		if (!$cache->load(self::CACHING)) {
			$items = [];
			foreach (Nette\Utils\Finder::findFiles('*.neon')->from($paths)->exclude($exclude) as $key => $file) {
				$items[] = $key;
			}

			$names = [];
			foreach ($items as $row) {
				$names[] = basename($row);
			}

			array_multisort($names, SORT_NUMERIC, $items);
			$cache->save(self::CACHING, $items);
		}

		// Loading cached saved.
		$configs = [];
		if ($cache->load(self::CACHING)) {
			foreach ($cache->load(self::CACHING) as $row) {
				$configs = $this->addConfig($row);
			}
		}
		return $configs;
	}


	/**
	 * Front Controller.
	 */
	public function app(): Nette\Application\Application
	{
		return $this->createContainer()
			->getByType(Nette\Application\Application::class);
	}
}
