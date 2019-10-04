<?php

declare(strict_types = 1);

// The Nette Tester command-line runner can be
// invoked through the command: ../vendor/bin/tester .
if (@!include __DIR__ . '/../vendor/autoload.php') {
	echo 'Install Nette Tester using `composer install`';
	exit(1);
}

Tester\Environment::setup();
date_default_timezone_set('Europe/Prague');


function getTempDir(): string
{
	$dir = __DIR__ . '/tmp/';
	if (!is_dir($dir)) {
		mkdir($dir);
	}
	return $dir;
}


function getFilesDir(): string
{
	return __DIR__ . '/files/';
}
