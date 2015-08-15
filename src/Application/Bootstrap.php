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

    /** @var DiBuilder */
    private $diBuilder;

    /**
     * @param array $config
     * @param bool $isConsole
     */
    private function __construct(array $config, $isConsole)
    {
        $this->config = $config;
        $this->isConsole = $isConsole;
        $this->diBuilder = new DiBuilder($this->config);
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
            $this->createCliApplication()->handle($_SERVER['argv']);
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
        return new Application($this->diBuilder->createForMvc());
    }

    /**
     * @return Console
     */
    private function createCliApplication()
    {
        return new Console($this->diBuilder->createForCli());
    }
}

