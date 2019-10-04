<?php

declare(strict_types = 1);

require __DIR__ . '/bootstrap.php';
require __DIR__ . '/ConfigCache.php';
require __DIR__ . '/Bootstrap/configFindByString.php';
require __DIR__ . '/Bootstrap/configFindByArray.php';
require __DIR__ . '/Bootstrap/configExcludeDirectoryFind.php';
require __DIR__ . '/Bootstrap/configRunning.php';


testByString();
testByArray();
testExcludeDirectory();
