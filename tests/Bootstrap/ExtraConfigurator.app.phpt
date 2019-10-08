<?php

declare(strict_types = 1);

namespace Test\Bootstrap;

use Drago\ExtraConfigurator;
use Nette\Application\Application;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


function boot(): ExtraConfigurator
{
	$configurator = new ExtraConfigurator;
	$configurator->setTempDirectory(getTempDir());
	return $configurator;
}


test(function () {
	Assert::type(Application::class, boot()->app());
});
