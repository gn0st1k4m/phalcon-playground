<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

chdir(dirname(__DIR__));

include 'vendor/autoload.php';

$env = trim(file_get_contents('./ENV'));
$configCache = './data/cache/config.php';

Phapp\Application\Bootstrap::init($env, $configCache)->runApplicationOn($_SERVER);
