<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

chdir(dirname(__DIR__));

include 'vendor/autoload.php';

$env = getenv('APPLICATION_ENV') ?: 'production';
$configCache = './data/cache/config.php';

Phpg\Application\Bootstrap::init($env, $configCache)->runApplicationOn($_SERVER);
