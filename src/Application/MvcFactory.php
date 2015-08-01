<?php

namespace Phpg\Application;

use Phalcon\Di;
use Phalcon\Mvc;

class MvcFactory
{
    /**
     * @param Di $di
     * @return Mvc\Application
     */
    public static function createWith(Di $di)
    {
        return new Mvc\Application($di);
    }
}
