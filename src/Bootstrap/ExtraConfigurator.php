<?php

declare(strict_types=1);

namespace Drago\Bootstrap;

use Nette\Bootstrap\Configurator;
use Nette\Caching\Cache;
use Nette\Caching\Storages\FileStorage;
use Nette\Utils\Finder;
use Throwable;


/** Extra configurator for managing loading and caching configuration files. */
class ExtraConfigurator extends Configurator
{
	public const string Caching = 'config.search';


	/**
	 * Searches for configuration files and adds them to the configurator.
	 * In debug mode the directories are searched on every call; otherwise the list of found
	 * files is cached separately for each combination of paths and exclusions.
	 * @param string|list<string> $paths
	 * @param string|list<string> $exclude
	 * @throws Throwable
	 */
	public function addFindConfig(array|string $paths, array|string ...$exclude): static
	{
		if ($this->isDebugMode()) {
			$items = $this->finder($paths, ...$exclude);

		} else {
			$storage = new FileStorage($this->getCacheDirectory());
			$cache = new Cache($storage, self::Caching);

			/** @var list<string> $items */
			$items = $cache->load(
				md5(serialize([(array) $paths, $exclude])),
				fn(): array => $this->finder($paths, ...$exclude),
			);
		}

		foreach ($items as $item) {
			$this->addConfig($item);
		}

		return $this;
	}


	/**
	 * @param string|list<string> $paths
	 * @param string|list<string> $exclude
	 * @return list<string>
	 */
	private function finder(array|string $paths, array|string ...$exclude): array
	{
		$finder = Finder::findFiles('*.neon')
			->from($paths);

		foreach ($exclude as $item) {
			$finder->exclude($item);
		}

		$items = [];
		$names = [];

		foreach ($finder as $file) {
			$path = $file->getRealPath();
			if (is_string($path)) {
				$items[] = $path;
				$names[] = $file->getBasename();
			}
		}

		array_multisort($names, SORT_NUMERIC, $items);
		return $items;
	}
}
