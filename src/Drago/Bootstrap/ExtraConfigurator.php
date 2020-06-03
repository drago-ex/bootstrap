<?php

declare(strict_types = 1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Bootstrap;

use Nette;
use Nette\Application\Application;
use Nette\Caching;


/**
 * Initial system DI container generator.
 */
class ExtraConfigurator extends Nette\Configurator
{
	// Cache for found configuration files.
	public const CACHING = 'Drago.CacheConf';


	public function __construct()
	{
		parent::__construct();
		$this->parameters = $this->parameters();
	}


	private function parameters(): array
	{
		$parms = $this->parameters;
		$trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
		$parms['appDir'] = isset($trace[1]['file']) ? dirname($trace[1]['file']) : null;
		$parms['wwwDir'] = $parms['wwwDir'] . DIRECTORY_SEPARATOR . 'www';
		return $parms;
	}


	/**
	 * Searching for configuration files.
	 * @param  string|string[]  $paths
	 * @param  string|string[]  $masks
	 * @return static
	 */
	public function addFindConfig($paths, ...$exclude)
	{
		$storage = new Caching\Storages\FileStorage($this->getCacheDirectory());
		$cache = new Caching\Cache($storage, self::CACHING);

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
