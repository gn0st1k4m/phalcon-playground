<?php

chdir(dirname(__DIR__));

include 'vendor/autoload.php';

$env = trim(file_get_contents('./ENV'));
$configCache = './data/cache/config.php';

Phpg\Application\Bootstrap::init($env, $configCache)->runApplicationOn($_SERVER);
