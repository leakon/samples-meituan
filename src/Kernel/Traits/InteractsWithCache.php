<?php

namespace SamplesMeituan\Kernel\Traits;

use SamplesMeituan\Kernel\ServiceContainer;

/**
 * Trait InteractsWithCache.
 *
 */
trait InteractsWithCache
{
    /**
     */
    protected $cache;

    /**
     * Get cache instance.
     */
    public function getCache()
    {
        if ($this->cache) {
            return $this->cache;
        }

        if (property_exists($this, 'app') && $this->app instanceof ServiceContainer && isset($this->app['cache'])) {
            $this->setCache($this->app['cache']);

            return $this->cache;
        }

        throw new Exceptions('Cache is invalid');
    }

}
