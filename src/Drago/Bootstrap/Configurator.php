<?php

/**
 * Extension Nette\Configurator
 * Copyright (c) 2015, Zdeněk Papučík
 */
namespace Drago;

use Nette;
use Nette\Utils;
use Nette\Caching;

/**
 * Generování systémového kontejneru.
 */
class Configurator extends Nette\Configurator
{
	// Název mezipaměťi pro automatické vyhledávání konfiguračních souborů.
	const CACHING = 'Drago.CacheConf';

	public function __construct()
	{
		parent::__construct();
		$this->parameters = $this->parameters();
	}

	/**
	 * Adresářová struktůra aplikace.
	 * @return array
	 */
	private function parameters()
	{
		$parms = $this->parameters;
		$trace = debug_backtrace(PHP_VERSION_ID >= 70008 ? DEBUG_BACKTRACE_IGNORE_ARGS : false);
		$parms['appDir'] = isset($trace[1]['file']) ? dirname($trace[1]['file']) : null;
		$parms['wwwDir'] = $parms['wwwDir'] . DIRECTORY_SEPARATOR . 'www';
		return $parms;
	}

	/**
	 * Automatické vyhledávání tříd.
	 * @param string|array
	 */
	public function addAutoload($dirs)
	{
		$this->createRobotLoader()
			->addDirectory($dirs)
			->register();
	}

	/**
	 * Vyhledávání a uložení konfiguračních souborů do vlastní mezipaměti.
	 * @param mixed
	 * @param mixed|null
	 */
	public function addFindConfig($dirs, $exclude = null)
	{
		$cache = new Caching\Cache(new Caching\Storages\FileStorage($this->getCacheDirectory()), self::CACHING);

		// Vyhledání se spustí jen tehdy, když bude prázdná mezipaměť.
		if (!$cache->load(self::CACHING)) {

			// Vyhledání konfiguračnich souborů.
			foreach (Utils\Finder::findFiles('*.neon')->from($dirs)->exclude($exclude) as $row) {
				$data[] = $row->getPathname();
			}

			foreach ($data as $row) {
				$name[] = basename($row);
			}

			// Nalezené soubory se seřadi podle čísel (budou-li uvedené v názvu souboru).
			array_multisort($name, SORT_NUMERIC, $data);
			if (isset($data)) {
				$cache->save(self::CACHING, $data);
			}
		}

		// Načtení dat z mezipaměťi.
		if ($cache->load(self::CACHING)) {
			foreach ($cache->load(self::CACHING) as $files) {
				$this->addConfig($files);
			}
		}
	}

	/**
	 * Spuštění aplikace.
	 * @return void
	 */
	public function run()
	{
		return $this->createContainer()
			->getByType(Nette\Application\Application::class)
			->run();
	}

}
