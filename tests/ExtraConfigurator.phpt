<?php

declare(strict_types = 1);

require __DIR__ . '/bootstrap.php';
require __DIR__ . '/CacheUp.php';
require __DIR__ . '/TestExclude.php';
require __DIR__ . '/TestFindByArray.php';
require __DIR__ . '/TestFindByString.php';

$exclude = new TestExclude();
$exclude->boot();

$array = new TestFindByArray();
$array->boot();

$string = new TestFindByString();
$string->boot();
