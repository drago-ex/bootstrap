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
use Tracy\Debugger;


/**
 * Initial system DI container generator.
 */
class ExtraConfigurator extends Configurator
{
	// Cache for found configuration files.
	public const Caching = 'drago.cacheConf';


	/**
	 * Searching for configuration files.
	 * @throws Throwable
	 */
	public function addFindConfig(array|string $paths, array|string ...$exclude): static
	{
		$storage = new FileStorage($this->getCacheDirectory());
		$cache = new Cache($storage, self::Caching);

		// Check the stored cache.
		if (!$cache->load(self::Caching)) {
			$items = [];
			foreach (Finder::findFiles('*.neon')->from($paths)->exclude($exclude)->sortByName() as $file) {
				$items[] = $file->getRealPath();
			}
			$cache->save(self::Caching, $items);
		}

		// Loading cached saved.
		if ($cache->load(self::Caching)) {
			foreach ($cache->load(self::Caching) as $row) {
				$this->addConfig($row);
			}
			if (Debugger::$productionMode === false) {
				$cache->remove(self::Caching);
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
