<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require __DIR__ . '/vendor/autoload.php';

use App\Check as Check;

$check = new Check();
$check->Check();

/*
$url = 'http://comagic2.intensa-dev.ru/robots.txt';
$url = 'https://www.comagic.ru/robots.txt';
$headers = get_headers($url, 1);

if (isset($headers['WWW-Authenticate'])) {
    echo 'Basic-y';
} else {
    $contents = file_get_contents($url);
    echo '<pre>',print_r($contents),'</pre>'; ;
}*/