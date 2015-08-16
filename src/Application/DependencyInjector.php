<?php

namespace Phpg\Application;

use Phalcon\Config;
use Phalcon\Di;
use Phpg\Application\Factory\DispatchEventsManager;
use Phpg\Application\Service\InjectableInterface;

class DependencyInjector
{
    /** @var array */
    private $config;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @return Di
     */
    public function createForMvc()
    {
        $di = new Di\FactoryDefault;

        $this->injectConfigTo($di);

        $di->setShared('router', function () {
            $routes = isset($this->config['routes']) ? $this->config['routes'] : array();
            return Factory\Router::createFrom($routes);
        });

        $di->setShared('view', function () {
            $view = new Service\View;
            $view->setViewsDir('./view/');
            return $view;
        });

        /** @var \Phalcon\Mvc\Dispatcher $dispatcher */
        $dispatcher = $di->get('dispatcher');
        $dispatcher->setEventsManager(DispatchEventsManager::create());
        $dispatcher->setControllerSuffix(null);
        $dispatcher->setDefaultNamespace(__NAMESPACE__ . '\\Controller');

        $this->injectServicesTo($di);

        return $di;
    }

    /**
     * @return Di
     */
    public function createForCli()
    {
        $di = new Di\FactoryDefault\Cli;

        $this->injectConfigTo($di);

        /** @var \Phalcon\Cli\Dispatcher $dispatcher */
        $dispatcher = $di->get('dispatcher');
        $dispatcher->setEventsManager(DispatchEventsManager::create());
        $dispatcher->setTaskSuffix(null);
        $dispatcher->setDefaultNamespace(__NAMESPACE__ . '\\Task');

        $this->injectServicesTo($di);

        return $di;
    }

    /**
     * @param Di $di
     */
    private function injectConfigTo(Di $di)
    {
        $di->set('config', function () {
            return new Config($this->config);
        });
    }

    private function injectServicesTo(Di $di)
    {
        /** @var InjectableInterface[] $services */
        $services = isset($this->config['services']) ? $this->config['services'] : array();
        foreach ($services as $service) {
            $service::injectTo($di);
        }
    }
}
