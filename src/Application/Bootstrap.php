<?php

namespace Phpg\Application;

use Phalcon\Cli\Console;
use Phalcon\Mvc\Application;
use Phpg\Application\Service\CliHelper;

class Bootstrap
{
    /** @var array */
    private $config;

    /** @var bool */
    private $isConsole;

    /** @var DependencyInjector */
    private $di;

    /**
     * @param array $config
     * @param bool  $isConsole
     */
    private function __construct(array $config, $isConsole)
    {
        $this->config = $config;
        $this->isConsole = $isConsole;
        $this->di = new DependencyInjector($this->config);
    }

    /**
     * @param string       $env
     * @param string |null $configCacheFile
     * @return Bootstrap
     */
    public static function init($env, $configCacheFile = null)
    {
        $config = new Service\Config($env, $configCacheFile);

        return new self($config->read(), php_sapi_name() == "cli");
    }

    /**
     * @param array $server
     */
    public function runApplicationOn(array $server)
    {
        if ($this->isConsole) {
            $arguments = CliHelper::extractArgumentsFrom($server['argv']);
            $this->createCliApplication()->handle($arguments);
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
        return new Application($this->di->createForMvc());
    }

    /**
     * @return Console
     */
    private function createCliApplication()
    {
        return new Console($this->di->createForCli());
    }
}
