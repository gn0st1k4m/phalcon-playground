<?php

namespace Phpg\Application;

use Phalcon\Config;
use Phalcon\Di;
use Phalcon\Loader;
use Phalcon\Mvc;

class Bootstrap
{
    /** @var Config */
    private $config;

    /** @var Di */
    private $di;

    /** @var Mvc\Application */
    private $application;

    /**
     * @param string $configGlobPath
     */
    public function __construct($configGlobPath)
    {
        $this->createConfigFrom($configGlobPath);
        $this->createDi();
        $this->createApplication();
    }

    /**
     * @param $configGlobPath
     */
    private function createConfigFrom($configGlobPath)
    {
        $this->config = new Config;
        foreach (glob($configGlobPath, GLOB_BRACE) as $file) {
            $this->config->merge(new Config(require $file));
        }
    }

    private function createDi()
    {
        $this->di = new Di\FactoryDefault;

        $this->di->set(
            'router',
            function () {
                $router = new Mvc\Router(false);
                $router->setUriSource(Mvc\Router::URI_SOURCE_SERVER_REQUEST_URI);
                $routes = $this->config->application->routes->toArray();
                foreach ($routes as $route) {
                    $router->add(
                        $route['pattern'],
                        isset($route['paths']) ? $route['paths'] : null,
                        isset($route['httpMethods']) ? $route['httpMethods'] : null
                    );
                }
                return $router;
            },
            true
        );

        $this->di->set(
            'view',
            function () {
                $view = new Mvc\View;
                $view->setViewsDir($this->config->application->viewsDir);
                return $view;
            }
        );

        $this->di->set(
            'dispatcher',
            function () {
                $dispatcher = new Mvc\Dispatcher;
                $dispatcher->setControllerSuffix(null);
                $dispatcher->setDefaultNamespace($this->config->application->dispatcher->defaultNamespace);
                return $dispatcher;
            }
        );
    }

    private function createApplication()
    {
        $this->application = new Mvc\Application($this->di);
    }

    public function run()
    {
        echo $this->application->handle()->getContent();
    }
}
