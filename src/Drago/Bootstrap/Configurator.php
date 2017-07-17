<?php

/**
 * Extending for Nette Framework.
 * Copyright (c) 2015, Zdeněk Papučík
 */
namespace Drago;

use Nette;
use Nette\Utils;
use Nette\Caching;

/**
 * DI container generator.
 * @author Zdeněk Papučík
 */
class Configurator extends Nette\Configurator
{
	// Name cache for storing configuration files.
	const CACHING = 'Drago.CacheConf';

	public function __construct()
	{
		parent::__construct();
		$this->parameters = $this->parameters();
	}

	/**
	 * Directory structure.
	 * @return array
	 */
	private function parameters()
	{
		$parms = $this->parameters;
		$trace = debug_backtrace(PHP_VERSION_ID >= 70008 ? DEBUG_BACKTRACE_IGNORE_ARGS : FALSE);
		$parms['appDir'] = isset($trace[1]['file']) ? dirname($trace[1]['file']) : NULL;
		$parms['wwwDir'] = $parms['wwwDir'] . DIRECTORY_SEPARATOR . 'www';
		return $parms;
	}

	/**
	 * Autoload classes.
	 * @param string|array
	 */
	public function addAutoload($dirs)
	{
		$this->createRobotLoader()
			->addDirectory($dirs)
			->register();
	}

	/**
	 * Search configuration files.
	 * @param mixed
	 * @param mixed
	 */
	public function addFindConfig($dirs, $exclude = NULL)
	{
		$cache = new Caching\Cache(new Caching\Storages\FileStorage($this->getCacheDirectory()), self::CACHING);

		// Search will be started only when the cache does not exist.
		if (!$cache->load(self::CACHING)) {

			// Search configuration files.
			foreach (Utils\Finder::findFiles('*.neon')->from($dirs)->exclude($exclude) as $row) {
				$data[] = $row->getPathname();
			}

			foreach ($data as $row) {
				$name[] = basename($row);
			}

			// Sort found files by number and put into the cache.
			array_multisort($name, SORT_NUMERIC, $data);
			if (isset($data)) {
				$cache->save(self::CACHING, $data);
			}
		}

		// Loads the data from the cache.
		if ($cache->load(self::CACHING)) {
			foreach ($cache->load(self::CACHING) as $files) {
				$this->addConfig($files);
			}
		}
	}

	/**
	 * Run application.
	 * @return void
	 */
	public function run()
	{
		return $this->createContainer()
			->getByType(Nette\Application\Application::class)
			->run();
	}

}
