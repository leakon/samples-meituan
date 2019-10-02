<?php

/*
 */

namespace SamplesMeituan\Kernel\Providers;

use SamplesMeituan\Kernel\Config;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

/**
 * Class ConfigServiceProvider.
 *
 */
class LogServiceProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $pimple A container instance
     */
    public function register(Container $pimple)
    {
        $pimple['log'] = function ($app) {
            $log = new Logger('meituan-open');
            $log->pushHandler(new StreamHandler('./logs/default.log', Logger::DEBUG));
            // return new Config($app->getConfig());
            return  $log;
        };
    }
}
