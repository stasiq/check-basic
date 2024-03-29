<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require __DIR__ . '/vendor/autoload.php';

use App\Check as Check;
use Dotenv\Dotenv as Dotenv;

$env = Dotenv::createUnsafeImmutable(__DIR__);
$env->load();

$check = new Check();
$check->Check();
