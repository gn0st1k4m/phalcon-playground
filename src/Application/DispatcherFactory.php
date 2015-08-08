<?php

namespace Phpg\Application;

use Phalcon\Di;
use Phalcon\Events;
use Phalcon\Mvc;

class DispatcherFactory
{
    /**
     * @param Di $di
     * @return Mvc\Dispatcher
     */
    public static function createWith(Di $di)
    {
        $dispatcher = new Mvc\Dispatcher;
        $dispatcher->setEventsManager(self::createEventManager($di));
        $dispatcher->setControllerSuffix(null);
        $dispatcher->setDefaultNamespace(__NAMESPACE__ . '\Controller');

        return $dispatcher;
    }

    /**
     * @param Di $di
     * @return Events\Manager
     */
    private static function createEventManager(Di $di)
    {
        $eventsManager = new Events\Manager();

        $eventsManager->attach(
            "dispatch:afterExecuteRoute",
            function (Events\Event $event, Mvc\Dispatcher $dispatcher) use ($di) {
                if ($dispatcher->getNamespaceName() !== $dispatcher->getDefaultNamespace()) {
                    $subViewDir = lcfirst(
                        substr(
                            $dispatcher->getNamespaceName(),
                            strrpos($dispatcher->getNamespaceName(), '\\') + 1
                        )
                    );
                    /** @var Mvc\View $view */
                    $view = $di->get('view');
                    $view->setViewsDir($view->getViewsDir() . $subViewDir . '/');
                }
            }
        );

        $eventsManager->attach(
            "dispatch:beforeException",
            function (Events\Event $event, Mvc\Dispatcher $dispatcher, \Exception $e) use ($di) {
                /** @var \Phalcon\Logger\Adapter $logger */
                $logger = $di->get('logger');
                $logger->error($e->getMessage());
                $dispatcher->forward(array(
                    'controller' => 'error',
                    'action'     => $e instanceof Mvc\Dispatcher\Exception ? 'notFound' : 'fatal'
                ));
                return false;
            }
        );

        return $eventsManager;
    }
}
