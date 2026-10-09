<?php

declare(strict_types=1);

namespace Drago\Bootstrap;

use Nette\Bootstrap\Configurator;
use Nette\Caching\Cache;
use Nette\Caching\Storages\FileStorage;
use Nette\Utils\Finder;
use Throwable;
use Tracy\Debugger;


/** Extra configurator for managing loading and caching configuration files. */
class ExtraConfigurator extends Configurator
{
	public const string Caching = 'config.search';

	private ?ConfigPanel $panel = null;


	/** Adds a configuration file or array and records it in the Tracy panel. */
	public function addConfig(array|string $config): static
	{
		parent::addConfig($config);
		$this->panel()?->addConfig(is_string($config) ? $config : null, ConfigPanel::Manual);
		return $this;
	}


	/**
	 * Searches for configuration files and stores them in cache.
	 * @param string|list<string> $paths
	 * @param string|list<string> $exclude
	 * @throws Throwable
	 */
	public function addFindConfig(array|string $paths, array|string ...$exclude): static
	{
		$storage = new FileStorage((string) $this->getCacheDirectory());
		$cache = new Cache($storage, self::Caching);

		/** @var list<string>|null $cachedItems */
		$cachedItems = $cache->load(self::Caching);

		if (Debugger::$productionMode === false) {
			$start = hrtime(true);
			$items = $this->finder($paths, ...$exclude);
			$this->panel()?->addScan(
				(array) $paths,
				array_merge(...array_map(fn($e) => (array) $e, $exclude)),
				count($items),
				(hrtime(true) - $start) / 1e6,
			);

			foreach ($items as $item) {
				$this->addFound($item);
			}
			$cache->remove(self::Caching);

		} else {
			if (!$cachedItems) {
				$items = $this->finder($paths, ...$exclude);
				$cache->save(self::Caching, $items, [
					Cache::All => true,
				]);
				$cachedItems = $items;
			}

			foreach ($cachedItems as $item) {
				$this->addFound($item);
			}
		}

		return $this;
	}


	private function addFound(string $file): void
	{
		parent::addConfig($file);
		$this->panel()?->addConfig($file, ConfigPanel::Found);
	}


	/** Registers the panel lazily, in development mode only. */
	private function panel(): ?ConfigPanel
	{
		if (Debugger::$productionMode !== false) {
			return null;
		}

		if ($this->panel === null) {
			$this->panel = new ConfigPanel;
			Debugger::getBar()->addPanel($this->panel, 'drago.config');
		}
		return $this->panel;
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
