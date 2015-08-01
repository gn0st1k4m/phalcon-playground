<?php

namespace Phpg\Application;

use Phalcon\Config;
use Phalcon\Di;
use Phalcon\Events;
use Phalcon\Mvc;

class Bootstrap
{
    /** @var array */
    private $config;

    /** @var Di */
    private $di;

    /**
     * @param string $configGlobPath
     */
    public function __construct($configGlobPath)
    {
        $this->createConfigFrom($configGlobPath);
        $this->createDi();
    }

    /**
     * @param string $configGlobPath
     * @return Mvc\Application
     */
    public static function init($configGlobPath)
    {
        $self = new self($configGlobPath);
        return $self->createApplication();
    }

    /**
     * @param $configGlobPath
     */
    private function createConfigFrom($configGlobPath)
    {
        $config = new Config;
        foreach (glob($configGlobPath, GLOB_BRACE) as $file) {
            $config->merge(new Config(require $file));
        }

        $this->config = $config->toArray();
    }

    private function createDi()
    {
        $diFactory = new DiFactory($this->config);
        $this->di = $diFactory->create();
    }

    /**
     * @return Mvc\Application
     */
    public function createApplication()
    {
        return MvcFactory::createWith($this->di);
    }
}
