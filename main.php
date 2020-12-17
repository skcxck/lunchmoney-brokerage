<?php

use Dotenv\Dotenv;
use Skcxck\Lunchmoney\Commands\ProcessBrokerageAssets;

require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$process = new ProcessBrokerageAssets();
$process->run();