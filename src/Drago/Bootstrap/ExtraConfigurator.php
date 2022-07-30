<?php

/**
 * Drago Extension
 * Package built on Nette Framework
 */

declare(strict_types=1);

namespace Drago\Bootstrap;

use Nette\Application\Application;
use Nette\Caching\Cache;
use Nette\Caching\Storages\FileStorage;
use Nette\Configurator;
use Nette\Utils\Finder;
use Throwable;


/**
 * Initial system DI container generator.
 */
class ExtraConfigurator extends Configurator
{
	// Cache for found configuration files.
	public const CACHING = 'drago.cacheConf';


	/**
	 * Searching for configuration files.
	 * @throws Throwable
	 */
	public function addFindConfig(array|string $paths, array|string ...$exclude): static
	{
		$storage = new FileStorage($this->getCacheDirectory());
		$cache = new Cache($storage, self::CACHING);

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
			array_multisort($names, SORT_NUMERIC, $items);
			$cache->save(self::CACHING, $items);
		}

		// Loading cached saved.
		if ($cache->load(self::CACHING)) {
			foreach ($cache->load(self::CACHING) as $row) {
				$this->addConfig($row);
			}
		}
		return $this;
	}


	/**
	 * Front Controller.
	 */
	public function app(): Application
	{
		return $this->createContainer()
			->getByType(Application::class);
	}
}
