<?php

namespace Phpg\Application\Injector;

use Phalcon\Di;
use Phalcon\Logger\Multiple;

class Logger implements InjectorInterface
{
    public static function injectTo(Di $di)
    {
        $di->setShared('logger', function () use ($di) {
            $logger = new Multiple;
            $config = $di->get('config')['loggers'];
            foreach ($config as $logConfig) {
                $adapter = $logConfig['adapter'];
                $options = isset($logConfig['options']) ? $logConfig['options'] : null;
                $logger->push(new $adapter($logConfig['name'], $options));
            }
            return $logger;
        }
        );
    }
}
