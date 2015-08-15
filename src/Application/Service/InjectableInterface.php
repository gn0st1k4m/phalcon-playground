<?php

namespace Phpg\Application\Service;

use Phalcon\Di;

interface InjectableInterface
{
    /**
     * @param Di $di
     */
    public static function injectTo(Di $di);
}
