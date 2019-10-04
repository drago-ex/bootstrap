<?php

declare(strict_types = 1);

require __DIR__ . '/bootstrap.php';
require __DIR__ . '/CacheUp.php';
require __DIR__ . '/Bootstrap/TestExclude.php';
require __DIR__ . '/Bootstrap/TestFindByArray.php';
require __DIR__ . '/Bootstrap/TestFindByString.php';

$exclude = new TestExclude();
$exclude->boot();

$array = new TestFindByArray();
$array->boot();

$string = new TestFindByString();
$string->boot();
