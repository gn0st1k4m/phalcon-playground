<?php

namespace Phpg\Application;

use Phalcon\Events;
use Phalcon\Mvc;

class DispatcherFactory
{
    /**
     * @param Mvc\View $view
     * @return Mvc\Dispatcher
     */
    public static function createWith($view)
    {
        $dispatcher = new Mvc\Dispatcher;
        $dispatcher->setEventsManager(self::createEventManager($view));
        $dispatcher->setControllerSuffix(null);
        $dispatcher->setDefaultNamespace(__NAMESPACE__ . '\Controller');

        return $dispatcher;
    }

    /**
     * @param Mvc\View $view
     * @return Events\Manager
     */
    private static function createEventManager(Mvc\View $view)
    {
        $eventsManager = new Events\Manager();

        $eventsManager->attach(
            "dispatch:afterExecuteRoute",
            function (Events\Event $event, Mvc\Dispatcher $dispatcher) use ($view) {
                if ($dispatcher->getNamespaceName() !== $dispatcher->getDefaultNamespace()) {
                    $subViewDir = lcfirst(
                        substr(
                            $dispatcher->getNamespaceName(),
                            strrpos($dispatcher->getNamespaceName(), '\\') + 1
                        )
                    );
                    $view->setViewsDir($view->getViewsDir() . $subViewDir . '/');
                }
            }
        );

        $eventsManager->attach(
            "dispatch:beforeException",
            function (Events\Event $event, Mvc\Dispatcher $dispatcher, \Exception $e) {
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
