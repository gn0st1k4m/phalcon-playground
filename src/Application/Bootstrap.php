<?php

namespace Phpg\Application;

use Phalcon\Cli\Console;
use Phalcon\Config;
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
     * @param string $configGlobPath
     * @return Bootstrap
     */
    public static function init($configGlobPath)
    {
        $config = new Config;
        foreach (glob($configGlobPath, GLOB_BRACE) as $file) {
            $config->merge(new Config(require $file));
        }

        return new self($config->toArray(), php_sapi_name() == "cli");
    }

    public function runApplication()
    {
        if ($this->isConsole) {
            $this->createConsoleApplication()->handle($_SERVER['argv']);
        } else {
            $response = $this->createMvcApplication()->handle();
            if ($response instanceof \Phalcon\Http\ResponseInterface) {
                echo $response->getContent();
            }
        }
    }

    /**
     * @return Application
     */
    private function createMvcApplication()
    {
        $diBuilder = new DiBuilder($this->config);
        $di = $diBuilder->create();

        return new Application($di);
    }

    /**
     * @return Console
     */
    private function createConsoleApplication()
    {
        $diBuilder = new DiBuilder($this->config, new Cli);
        $di = $diBuilder->create();

        return new Console($di);
    }
}

