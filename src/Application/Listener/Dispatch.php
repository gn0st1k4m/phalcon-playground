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
            /** @var \Phpg\Application\Service\View $view */
            $view = $dispatcher->getDI()->get('view');

            if ($view->isPicked()) {
                return;
            }

            $viewPathParts = array_diff(
                explode('\\', strtolower($dispatcher->getHandlerClass())),
                explode('\\', strtolower($dispatcher->getDefaultNamespace()))
            );
            $viewPathParts[] = $dispatcher->getActionName();

            $view->pick(implode(DIRECTORY_SEPARATOR, $viewPathParts));
        }
    }

    /**
     * @param Event      $event
     * @param Dispatcher $dispatcher
     */
    public function beforeDispatchLoop(Event $event, Dispatcher $dispatcher)
    {
        /** @var \Phalcon\Http\Request $request */
        $request = $dispatcher->getDI()->get('request');
        if ($request->isAjax()) {
            /** @var \Phpg\Application\Service\View $view */
            $view = $dispatcher->getDI()->get('view');
            $view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_ACTION_VIEW);
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
