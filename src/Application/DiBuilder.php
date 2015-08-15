<?php

namespace Phpg\Application;

use Phalcon\Config;
use Phalcon\Di;

class DiBuilder
{
    /** @var array */
    private $config;

    /** @var DispatcherBuilder */
    private $dispatchBuilder;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->dispatchBuilder = new DispatcherBuilder;
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
            return RouterFactory::createFrom($routes);
        });

        $di->setShared('view', function () {
            $view = new \Phalcon\Mvc\View;
            $view->setViewsDir('./view/');
            return $view;
        });

        $di->setShared('dispatcher', function () use ($di) {
            return $this->dispatchBuilder->createForMvcWith($di);
        });

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

        $di->setShared('dispatcher', function () use ($di) {
            return $this->dispatchBuilder->createForCliWith($di);
        });

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
        /** @var Service\InjectableInterface[] $services */
        $services = isset($this->config['services']) ? $this->config['services'] : array();
        foreach ($services as $service) {
            $service::injectTo($di);
        }
    }
}
