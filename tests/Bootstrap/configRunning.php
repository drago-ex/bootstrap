<?php

declare(strict_types = 1);

use Drago\ExtraConfigurator;
use Nette\Application\BadRequestException;
use Nette\Http\IRequest;
use Nette\Http\IResponse;
use Tester\Assert;


function boot()
{
	$app = new ExtraConfigurator;
	$app->setTempDirectory(getTempDir());
	return $app;
}

$httpRequest = IRequest::class;
$httpResponse = IResponse::class;

Assert::exception(function () use ($httpRequest, $httpResponse) {
	boot()->running();
}, BadRequestException::class, 'No route for HTTP request.');
