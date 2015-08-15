<?php

namespace Phpg\Application\Factory;

use Phalcon\Events\Manager;
use Phpg\Application\Listener\Dispatch;

class DispatchEventsManager
{
    /**
     * @return Manager
     */
    public static function create()
    {
        $manager = new Manager;
        $manager->attach('dispatch', new Dispatch);

        return $manager;
    }
}
