<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

chdir(dirname(__DIR__));

include 'vendor/autoload.php';

$env = getenv('APPLICATION_ENV') ? : 'production';
$configGlobPath = sprintf('config/{,*.}{global,%s,local}.php', $env);

$appBoot = new Phpg\Application\Bootstrap($configGlobPath);
$appBoot->run();
