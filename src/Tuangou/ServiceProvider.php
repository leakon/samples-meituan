<?php

namespace SamplesMeituan\Tuangou;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class ServiceProvider.
 *
 */
class ServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}.
     */
    public function register(Container $app)
    {
        $app['tuangou'] = function ($app) {
            return new Client($app);
        };

    }
}
