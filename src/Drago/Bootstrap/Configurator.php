<?php declare(strict_types = 1);

/**
 * Drago Bootstrap
 * @copyright Zdeněk Papučík
 */
namespace Drago;

use Nette;
use Nette\Utils;
use Nette\Caching;
use Throwable;

/**
 * Initial system DI container generator.
 * @package Drago\Bootstrap
 */
class Configurator extends Nette\Configurator
{
	/**
	 * Cache for automatically found configuration files.
	 */
	const CACHING = 'Drago.CacheConf';


	/**
	 * Auto-loading Classes.
	 * @param $path string|string[] absolute path
	 */
	public function addAutoload($path)
	{
		$this->createRobotLoader()
			->addDirectory($path)
			->register();
	}


	/**
	 * Find configuration files.
	 * @param $paths string|string[]
	 * @param mixed  string|string[]
	 */
	public function addFindConfig($paths, ...$exclude)
	{
		$storage = new Caching\Storages\FileStorage($this->getCacheDirectory());
		$cache   = new Caching\Cache($storage, self::CACHING);

		// Check the stored cache.
		if (!$cache->load(self::CACHING)) {

			// Setting search parameters.
			$finder = Utils\Finder::findFiles('*.neon')
				->from($paths)
				->exclude($exclude);

			$items = [];
			foreach ($finder as $row) {
				$items[] = $row->getPathname();
			}

			$names = [];
			foreach ($items as $row) {
				$names[] = basename($row);
			}

			// Sort by numbers (if listed in the file name).
			array_multisort($names, SORT_NUMERIC, $items);
			$cache->save(self::CACHING, $items);
		}

		// Loading cached saved.
		if ($cache->load(self::CACHING)) {
			foreach ($cache->load(self::CACHING) as $row) {
				$this->addConfig($row);
			}
		}
	}


	/**
	 * Dispatch a HTTP request to a front controller.
	 * @throws Throwable
	 */
	public function run()
	{
		$this->createContainer()
			->getByType(Nette\Application\Application::class)
			->run();
	}
}
