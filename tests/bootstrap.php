<?php

declare(strict_types=1);

if (@!include __DIR__ . '/../vendor/autoload.php') {
	echo 'Install Nette Tester using `composer install`';
	exit(1);
}


Tester\Environment::setup();
date_default_timezone_set('Europe/Prague');


const TempDir = __DIR__ . '/tmp';
const ConfDir = __DIR__ . '/file';

@mkdir(dirname(TempDir));
@mkdir(TempDir);

$boot = new Drago\Bootstrap\ExtraConfigurator;
$boot->setTempDirectory(TempDir);
$boot->createRobotLoader()
	->addDirectory(__DIR__)
	->addDirectory(__DIR__ . '/../src')
	->register();

return $boot;


function test(string $title, Closure $function): void
{
	$function();
}
