<?php

namespace Phpg\Application;

use Phalcon\Config;
use Phalcon\Di;
use Phalcon\Events;
use Phalcon\Mvc;

class DiBuilder
{
    /** @var array */
    private $config;

    /** @var Di */
    private $di;

    /**
     * @param array $config
     * @param Di $prototype
     */
    public function __construct(array $config, Di $prototype = null)
    {
        $this->config = $config;
        $this->di = $prototype ?: new Di\FactoryDefault;
    }

    /**
     * @return Di
     */
    public function create()
    {
        $this->di->set('config', function () {
            return new Config($this->config);
        });

        $this->di->setShared('router', function () {
            $routes = isset($this->config['routes']) ? $this->config['routes'] : array();
            return RouterFactory::createFrom($routes);
        });

        $this->di->setShared('view', function () {
            $view = new Mvc\View;
            $view->setViewsDir('./view/');
            return $view;
        });

        $this->di->setShared('dispatcher', function () {
            return DispatcherFactory::createWith($this->di);
        });

        /** @var Injector\InjectorInterface[] $injectors */
        $injectors = isset($this->config['injectors']) ? $this->config['injectors'] : array();
        foreach ($injectors as $injector) {
            $injector::injectTo($this->di);
        }

        return $this->di;
    }
}
