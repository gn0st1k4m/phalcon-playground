<?php

namespace Phpg\Application\Listener;

use Phalcon\Dispatcher;
use Phalcon\Events\Event;
use Phalcon\Mvc;

class Dispatch
{
    /**
     * @param Event      $event
     * @param Dispatcher $dispatcher
     */
    public function afterExecuteRoute(Event $event, Dispatcher $dispatcher)
    {
        if ($dispatcher->getNamespaceName() !== $dispatcher->getDefaultNamespace()) {
            $subViewDir = lcfirst(
                substr(
                    $dispatcher->getNamespaceName(),
                    strrpos($dispatcher->getNamespaceName(), '\\') + 1
                )
            );
            /** @var \Phalcon\Mvc\View $view */
            $view = $dispatcher->getDI()->get('view');
            $view->setViewsDir($view->getViewsDir() . $subViewDir . '/');
        }
    }

    /**
     * @param Event      $event
     * @param Dispatcher $dispatcher
     * @param \Exception $e
     * @return bool
     */
    public function beforeException(Event $event, Dispatcher $dispatcher, \Exception $e)
    {
        /** @var \Phalcon\Logger\Adapter $logger */
        $logger = $dispatcher->getDI()->get('logger');
        $logger->error($e->getMessage());

        if ($dispatcher instanceof \Phalcon\Mvc\Dispatcher) {
            $dispatcher->forward(array(
                'controller' => 'error',
                'action'     => $e instanceof Mvc\Dispatcher\Exception ? 'notFound' : 'fatal',
            ));
        }

        return false;
    }
}
