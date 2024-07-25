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
		if (Debugger::$productionMode === false) {
			if ($cache->load(self::Caching)) {
				$cache->remove(self::Caching);
			}
			$files = $this->finder($paths, $exclude);
			foreach ($files as $file) {
				$this->addConfig($file);
			}

		} elseif (!$cache->load(self::Caching)) {
			$files = $this->finder($paths, $exclude);
			$cache->save(self::Caching, $files);
		}

		// Loading cached saved.
		if ($cache->load(self::Caching)) {
			foreach ($cache->load(self::Caching) as $item) {
				$this->addConfig($item);
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
