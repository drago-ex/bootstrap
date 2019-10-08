<?php

declare(strict_types = 1);

require __DIR__ . '/../vendor/autoload.php';

Tester\Environment::setup();


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


function test(Closure $function): void
{
	$function();
}
