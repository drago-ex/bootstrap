<?php

declare(strict_types = 1);

use Drago\ExtraConfigurator;
use Nette\Application\Application;
use Tester\Assert;

require __DIR__ . '/bootstrap.php';
require __DIR__ . '/ConfigCache.php';
require __DIR__ . '/Bootstrap/configFindByString.php';
require __DIR__ . '/Bootstrap/configFindByArray.php';
require __DIR__ . '/Bootstrap/configExcludeDirectoryFind.php';

testByString();
testByArray();
testExcludeDirectory();

$app = new ExtraConfigurator;
$app->setTempDirectory(getTempDir());

Assert::type(Application::class, $app->app());
