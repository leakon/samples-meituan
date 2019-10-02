<?php

/*
 */

namespace SamplesMeituan\Kernel\Providers;

use SamplesMeituan\Kernel\AccessToken;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class ConfigServiceProvider.
 *
 */
class AuthServiceProvider implements ServiceProviderInterface
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
        $pimple['access_token'] = function ($app) {
            return new AccessToken($app);
        };
    }
}
