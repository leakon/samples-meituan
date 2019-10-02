<?php

/*
 */

namespace SamplesMeituan\Kernel;

use SamplesMeituan\Kernel\BaseAccessToken;

/**
 * Class AuthorizerAccessToken.
 *
 */
class AccessToken extends BaseAccessToken
{
    /**
     * @var string
     */
    protected $endpointToGetToken = 'https://openapi.dianping.com/router/oauth/token';

    /**
     * @return array
     */
    protected function getCredentials(): array
    {
        $default            = [
                                'grant_type'    => 'authorization_code',
                            ];
        return  array_merge($default, $this->app->config->all());
    }
}
