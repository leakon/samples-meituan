<?php

/*
 */

namespace SamplesMeituan;

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
        return [
            'grant_type'    => 'authorization_code',
            'app_key'       => $this->app['config']['app_key'],
            'app_secret'    => $this->app['config']['secret'],
            'auth_code'     => $this->app['config']['auth_code'],
        ];
    }
}
