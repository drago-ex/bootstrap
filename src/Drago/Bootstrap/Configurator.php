<?php

/**
 * Drago Bootstrap
 * Copyright (c) 2015, Zdeněk Papučík
 */
namespace Drago;

use Nette;
use Nette\Utils;
use Nette\Caching;

/**
 * Initial system DI container generator.
 */
class Configurator extends Nette\Configurator
{
	/**
	 * Cache for automatic searching configuration files.
	 */
	const CACHING = 'Drago.CacheConf';

	public function __construct()
	{
		parent::__construct();
		$this->parameters = $this->parameters();
	}

	/**
	 * Default parameters.
	 * @return array
	 */
	private function parameters()
	{
		$parms = $this->parameters;
		$trace = debug_backtrace(PHP_VERSION_ID >= 50600 ? DEBUG_BACKTRACE_IGNORE_ARGS : false);
		$parms['appDir'] = isset($trace[1]['file']) ? dirname($trace[1]['file']) : null;
		$parms['wwwDir'] = $parms['wwwDir'] . DIRECTORY_SEPARATOR . 'www';
		return $parms;
	}

	/**
	 * Autoloading classes.
	 * @param mixed $dirs
	 */
	public function addAutoload($dirs)
	{
		$this->createRobotLoader()
			->addDirectory($dirs)
			->register();
	}

	/**
	 * Searching configuration files.
	 * @param mixed $dirs
	 * @param mixed $exclude
	 */
	public function addFindConfig($dirs, $exclude = null)
	{
		$cache = new Caching\Cache(new Caching\Storages\FileStorage($this->getCacheDirectory()), self::CACHING);
		if (!$cache->load(self::CACHING)) {

			// Configure parameters for searching configuration files.
			foreach (Utils\Finder::findFiles('*.neon')->from($dirs)->exclude($exclude) as $row) {
				$data[] = $row->getPathname();
			}

			foreach ($data as $row) {
				$name[] = basename($row);
			}

			// Sort by numbers (if listed in the file name).
			array_multisort($name, SORT_NUMERIC, $data);
			if (isset($data)) {
				$cache->save(self::CACHING, $data);
			}
		}

		// Loads data from cache.
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
