<?php

declare(strict_types=1);

use Drago\Bootstrap\ExtraConfigurator;
use Tester\Environment;

require __DIR__ . '/../vendor/autoload.php';

Environment::setup();
date_default_timezone_set('Europe/Prague');

define('TEMP_DIR', __DIR__ . '/tmp');
define('CONF_DIR', __DIR__ . '/file');

@mkdir(dirname(TEMP_DIR));
@mkdir(TEMP_DIR);

$boot = new ExtraConfigurator;
$boot->setTempDirectory(TEMP_DIR);
$boot->createRobotLoader()
	->addDirectory(__DIR__)
	->addDirectory(__DIR__ . '/../src')
	->register();

return $boot;


function test(Closure $function): void
{
	$function();
}
