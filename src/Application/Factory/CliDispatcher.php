<?php

namespace Phpg\Application\Factory;

use Phalcon\Cli;
use Phalcon\Di;
use Phalcon\Events;

class CliDispatcher
{
    /**
     * @param Di $di
     * @return Cli\Dispatcher
     */
    public static function createWith(Di $di)
    {
        $dispatcher = new Cli\Dispatcher;
        $dispatcher->setEventsManager(self::createEventManager($di));
        $dispatcher->setTaskSuffix(null);
        $dispatcher->setDefaultNamespace(str_replace('Factory', 'Task', __NAMESPACE__));

        return $dispatcher;
    }

    /**
     * @param Di $di
     * @return Events\Manager
     */
    private static function createEventManager(Di $di)
    {
        $eventsManager = new Events\Manager;

        $eventsManager->attach(
            "dispatch:beforeException",
            function (Events\Event $event, Cli\Dispatcher $dispatcher, \Exception $e) use ($di) {
                /** @var \Phalcon\Logger\Adapter $logger */
                $logger = $di->get('logger');
                $logger->error($e->getMessage());
                return false;
            }
        );

        return $eventsManager;
    }
}
