<?php

namespace Phpg\Application\Injector;

use Phalcon\Di;

interface InjectorInterface
{
    public static function injectTo(Di $di);
}
