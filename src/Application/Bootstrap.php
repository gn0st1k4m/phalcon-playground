<?php

namespace Phpg\Application;

use Phalcon\Cli\Console;
use Phalcon\Di\FactoryDefault\Cli;
use Phalcon\Mvc\Application;

class Bootstrap
{
    /** @var array */
    private $config;

    /** @var bool */
    private $isConsole;

    /**
     * @param array $config
     * @param bool $isConsole
     */
    private function __construct(array $config, $isConsole)
    {
        $this->config = $config;
        $this->isConsole = $isConsole;
    }

    /**
     * @param string $env
     * @param string |null $configCacheFile
     * @return Bootstrap
     */
    public static function init($env, $configCacheFile = null)
    {
        $config = new Config($env, $configCacheFile);

        return new self($config->read(), php_sapi_name() == "cli");
    }

    public function runApplication()
    {
        if ($this->isConsole) {
            $this->createConsoleApplication()->handle($_SERVER['argv']);
        } else {
            $response = $this->createMvcApplication()->handle();
            if ($response instanceof \Phalcon\Http\ResponseInterface) {
                $response->send();
            }
        }
    }

    /**
     * @return Application
     */
    private function createMvcApplication()
    {
        return new Application($this->createDi());
    }

    /**
     * @return Console
     */
    private function createConsoleApplication()
    {
        return new Console($this->createDi());
    }

    /**
     * @return \Phalcon\Di
     */
    private function createDi()
    {
        $diBuilder = new DiBuilder($this->config, $this->isConsole ? new Cli : null);

        return $diBuilder->create();
    }
}

