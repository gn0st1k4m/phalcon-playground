<?php

namespace Phpg\Application\Service;

use Phalcon\Di;

interface InjectableInterface
{
    public static function injectTo(Di $di);
}
