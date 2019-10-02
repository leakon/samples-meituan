<?php

namespace SamplesMeituan\Kernel\Traits;

use SamplesMeituan\Kernel\ServiceContainer;
use Cache\Adapter\Redis\RedisCachePool;

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

        if (property_exists($this, 'app') 
                && $this->app instanceof ServiceContainer 
                && isset($this->app['cache'])) {
            $this->cache    = $this->app['cache'];
        }

        if (!$this->cache && \class_exists('Redis')) {
            $client         = new \Redis();
            $client->connect('127.0.0.1', 6379);
            $this->cache    = new RedisCachePool($client);
        }

        if ($this->cache) {
            return $this->cache;
        } else {
            throw new Exceptions('Cache is invalid');
        }

    }

}
