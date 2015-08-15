<?php

namespace Phpg\Application;

use Phalcon\Cli;
use Phalcon\Di;
use Phalcon\Events;
use Phalcon\Mvc;

class DispatcherBuilder
{
    /**
     * @param Di $di
     * @return Mvc\Dispatcher
     */
    public function createForMvcWith(Di $di)
    {
        $dispatcher = new Mvc\Dispatcher;
        $dispatcher->setEventsManager($this->createEventManagerForMvc($di));
        $dispatcher->setControllerSuffix(null);
        $dispatcher->setDefaultNamespace(__NAMESPACE__ . '\Controller');

        return $dispatcher;
    }

    /**
     * @param Di $di
     * @return Events\Manager
     */
    private function createEventManagerForMvc(Di $di)
    {
        $eventsManager = $this->createEventManager();

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

    /**
     * @param Di $di
     * @return Cli\Dispatcher
     */
    public function createForCliWith(Di $di)
    {
        $dispatcher = new Cli\Dispatcher;
        $dispatcher->setEventsManager($this->createEventManagerForCli($di));
        $dispatcher->setTaskSuffix(null);
        $dispatcher->setDefaultNamespace(__NAMESPACE__ . '\Task');

        return $dispatcher;
    }

    /**
     * @param Di $di
     * @return Events\Manager
     */
    private function createEventManagerForCli(Di $di)
    {
        $eventsManager = $this->createEventManager();

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

    /**
     * @return Events\Manager
     */
    private function createEventManager()
    {
        return new Events\Manager();
    }
}
