<?php

/*
 */

namespace SamplesMeituan\Kernel\Providers;

use SamplesMeituan\Kernel\Logs\Monolog\Formatter\PrintR;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

/**
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
            $stream_handler = new StreamHandler('./logs/default.log', Logger::DEBUG);
            $stream_handler->setFormatter( new PrintR() );
            $log->pushHandler($stream_handler);
            return  $log;
        };
    }
}
