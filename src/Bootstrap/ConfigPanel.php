<?php

declare(strict_types=1);

namespace Drago\Bootstrap;

use Tracy\Helpers;
use Tracy\IBarPanel;


/** Tracy panel listing loaded configuration files in the order they were added. */
final class ConfigPanel implements IBarPanel
{
	public const string Found = 'search';
	public const string Manual = 'manual';

	/** @var list<array{file: ?string, source: string}> */
	private array $configs = [];

	/** @var list<array{paths: list<string>, exclude: list<string>, found: int, ms: float}> */
	private array $scans = [];


	public function addConfig(?string $file, string $source): void
	{
		$this->configs[] = ['file' => $file, 'source' => $source];
	}


	/**
	 * @param list<string> $paths
	 * @param list<string> $exclude
	 */
	public function addScan(array $paths, array $exclude, int $found, float $ms): void
	{
		$this->scans[] = ['paths' => $paths, 'exclude' => $exclude, 'found' => $found, 'ms' => $ms];
	}


	public function getTab(): string
	{
		return '<span title="Loaded NEON configuration files">'
			. '<svg viewBox="0 0 16 16" width="16" height="16"><path fill="#4a8bc2" d="M3 1h7l3 3v11H3z"/>'
			. '<path fill="#fff" d="M5 7h6v1H5zm0 2h6v1H5zm0 2h4v1H5z"/></svg>'
			. '<span class="tracy-label">Config (' . count($this->configs) . ')</span></span>';
	}


	public function getPanel(): string
	{
		$root = $this->commonDir();
		$seen = [];
		$rows = '';

		foreach ($this->configs as $i => $item) {
			$file = $item['file'];
			$note = $item['source'] === self::Manual ? 'addConfig()' : 'addFindConfig()';

			if ($file === null) {
				$name = '<i>inline array</i>';

			} else {
				$short = Helpers::escapeHtml(substr($file, strlen($root)));
				$uri = Helpers::editorUri($file);
				$name = $uri
					? '<a href="' . Helpers::escapeHtml($uri) . '" title="' . Helpers::escapeHtml($file) . '">' . $short . '</a>'
					: '<span title="' . Helpers::escapeHtml($file) . '">' . $short . '</span>';

				if (isset($seen[$file])) {
					$note .= ' <b style="color:#d9534f">loaded again</b>';
				}
				$seen[$file] = true;
			}

			$rows .= '<tr><td>' . ($i + 1) . '</td><td>' . $name . '</td><td>' . $note . '</td></tr>';
		}

		$scans = '';
		foreach ($this->scans as $scan) {
			$scans .= '<tr><td>' . Helpers::escapeHtml(implode(', ', $scan['paths'])) . '</td>'
				. '<td>' . Helpers::escapeHtml(implode(', ', $scan['exclude']) ?: '-') . '</td>'
				. '<td>' . $scan['found'] . '</td>'
				. '<td>' . number_format($scan['ms'], 2) . ' ms</td></tr>';
		}

		return '<h1>Loaded configuration files</h1>'
			. '<div class="tracy-inner"><div class="tracy-inner-container">'
			. ($root !== '' ? '<p>Root: <code>' . Helpers::escapeHtml($root) . '</code></p>' : '')
			. '<p>Later files override earlier ones.</p>'
			. '<table><tr><th>#</th><th>File</th><th>Added by</th></tr>' . $rows . '</table>'
			. ($scans !== ''
				? '<h2>Searches</h2><table><tr><th>Paths</th><th>Exclude</th><th>Found</th><th>Time</th></tr>' . $scans . '</table>'
				: '')
			. '</div></div>';
	}


	/** Longest directory prefix shared by all listed files. */
	private function commonDir(): string
	{
		$dirs = [];
		foreach ($this->configs as $item) {
			if ($item['file'] !== null) {
				$dirs[] = explode(DIRECTORY_SEPARATOR, dirname($item['file']));
			}
		}

		if ($dirs === []) {
			return '';
		}

		$common = array_shift($dirs);
		foreach ($dirs as $parts) {
			$n = 0;
			while (isset($common[$n], $parts[$n]) && $common[$n] === $parts[$n]) {
				$n++;
			}
			$common = array_slice($common, 0, $n);
		}

		return $common === [] ? '' : implode(DIRECTORY_SEPARATOR, $common) . DIRECTORY_SEPARATOR;
	}
}
