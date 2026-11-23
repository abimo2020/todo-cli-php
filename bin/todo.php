<?php

declare(strict_types=1);

require(__DIR__ . "/../vendor/autoload.php");

use App\Model\StatusTodo;

echo "Autoload ok" . PHP_EOL;
echo StatusTodo::Finished->getMark() . PHP_EOL;