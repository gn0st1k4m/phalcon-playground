<?php

namespace Phpg\Application;

use Phalcon\Config as Container;

class Config
{
    /** @var string | null */
    private $cacheFile;

    /** @var string */
    private $configGlobPath;

    /**
     * @param string $env
     * @param string | null $cacheFile
     */
    public function __construct($env, $cacheFile = null)
    {
        $this->configGlobPath = sprintf('config/{,*.}{global,%s,local}.php', $env);
        $this->cacheFile = $cacheFile;
    }

    /**
     * @return array
     */
    public function read()
    {
        if ($this->isCached()) {
            return $this->cachedConfig();
        }

        $config = new Container;
        foreach (glob($this->configGlobPath, GLOB_BRACE) as $file) {
            $config->merge(new Container(require $file));
        }
        $config = $config->toArray();

        if ($this->isCachable()) {
            $this->writeCacheWith($config);
        }

        return $config;
    }

    /**
     * @return bool
     */
    private function isCached()
    {
        return $this->isCachable() && file_exists($this->cacheFile);
    }

    /**
     * @return bool
     */
    private function isCachable()
    {
        return is_string($this->cacheFile);
    }

    /**
     * @return array
     */
    private function cachedConfig()
    {
        return include $this->cacheFile;
    }

    /**
     * @param array $config
     */
    private function writeCacheWith(array $config)
    {
        $content = "<?php\nreturn " . var_export($config, 1) . ';';
        file_put_contents($this->cacheFile, $content);
    }
}

