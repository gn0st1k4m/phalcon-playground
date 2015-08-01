<?php

namespace Phpg\Application;

use Phalcon\Di;
use Phalcon\Events;
use Phalcon\Mvc;

class DiFactory
{
    /** @var array */
    private $config;

    /** @var Di */
    private $di;

    /**
     * @param array $config
     * @param Di    $prototype
     */
    public function __construct(array $config, Di $prototype = null)
    {
        $this->config = $config;

        if ($prototype) {
            $this->di = $prototype;
        } else {
            $this->di = new Di\FactoryDefault;
        }
    }

    /**
     * @return Di
     */
    public function create()
    {
        $this->attachRouterToDi();
        $this->attachViewComponentToDi();
        $this->attachDispatcherToDi();

        return $this->di;
    }

    private function attachRouterToDi()
    {
        $this->di->setShared(
            'router',
            function () {
                $router = new Mvc\Router(false);
                $router->setUriSource(Mvc\Router::URI_SOURCE_SERVER_REQUEST_URI);
                $routes = isset($this->config['routes']) ? $this->config['routes'] : array();
                foreach ($routes as $route) {
                    $router->add(
                        $route['pattern'],
                        isset($route['paths']) ? $route['paths'] : null,
                        isset($route['httpMethods']) ? $route['httpMethods'] : null
                    );
                }
                return $router;
            }
        );
    }

    private function attachViewComponentToDi()
    {
        $this->di->setShared(
            'view',
            function () {
                $view = new Mvc\View;
                $view->setViewsDir('./view/');
                return $view;
            }
        );
    }

    private function attachDispatcherToDi()
    {
        $this->di->setShared(
            'dispatcher',
            function () {
                $eventsManager = new Events\Manager();
                $eventsManager->attach(
                    "dispatch",
                    function (Events\Event $event, Mvc\Dispatcher $dispatcher) {
                        if ($event->getType() === 'afterExecuteRoute') {
                            if ($dispatcher->getNamespaceName() !== $dispatcher->getDefaultNamespace()) {
                                $subViewDir = lcfirst(
                                    substr(
                                        $dispatcher->getNamespaceName(),
                                        strrpos($dispatcher->getNamespaceName(), '\\') + 1
                                    )
                                );
                                /** @var Mvc\View $view */
                                $view = $this->di->get('view');
                                $view->setViewsDir($view->getViewsDir() . $subViewDir . '/');
                            }
                        }
                    }
                );
                $dispatcher = new Mvc\Dispatcher;
                $dispatcher->setEventsManager($eventsManager);
                $dispatcher->setControllerSuffix(null);
                $dispatcher->setDefaultNamespace(__NAMESPACE__ . '\Controller');
                return $dispatcher;
            }
        );
    }
}
