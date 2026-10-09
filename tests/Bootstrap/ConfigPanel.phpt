<?php

/**
 * Test: Drago\Bootstrap\ConfigPanel
 */

declare(strict_types=1);

use Drago\Bootstrap\ExtraConfigurator;
use Tester\Assert;
use Tracy\Debugger;

/** @var $boot ExtraConfigurator */
require __DIR__ . '/../bootstrap.php';


test('Panel lists configs in loading order', function () {
	Debugger::$productionMode = false;
	$boot = new ExtraConfigurator;
	$tempDir = TempDir . '/panel'; // separate cache, tests run in parallel
	@mkdir($tempDir);
	$boot->setTempDirectory($tempDir);
	$boot->addFindConfig([ConfDir . '/one', ConfDir . '/two']);
	$boot->addConfig(ConfDir . '/one/common.neon');

	$panel = Debugger::getBar()->getPanel('drago.config');
	Assert::notNull($panel);
	Assert::contains('Config (4)', $panel->getTab());

	$html = str_replace('\\', '/', $panel->getPanel()); // normalize Windows paths
	Assert::true(strpos($html, 'one/common.neon') < strpos($html, 'one/services.neon'));
	Assert::true(strpos($html, 'one/services.neon') < strpos($html, 'two/exclude.neon'));
	Assert::contains('loaded again', $html);
	Assert::contains('Searches', $html);

	Debugger::$productionMode = null;
});
