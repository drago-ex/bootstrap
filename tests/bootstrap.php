<?php

declare(strict_types = 1);

require __DIR__ . '/../vendor/autoload.php';

Tester\Environment::setup();
date_default_timezone_set('Europe/Prague');

define('TEMP_DIR', __DIR__ . '/tmp');
define('CONF_DIR', __DIR__ . '/file');

@mkdir(dirname(TEMP_DIR));
@mkdir(TEMP_DIR);

$boot = new Drago\Bootstrap\ExtraConfigurator;
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
