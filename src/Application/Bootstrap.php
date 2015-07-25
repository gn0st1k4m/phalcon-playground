<?php

namespace Phpg\Application;

use Phalcon\Config;

class Bootstrap
{
    /** @var Config */
    private $config;

    /**
     * @param string $configGlobPath
     */
    public function __construct($configGlobPath)
    {
        $this->createConfigFrom($configGlobPath);
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

    public function run()
    {

    }
}
