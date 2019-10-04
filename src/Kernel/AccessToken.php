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
        $conf               = $this->app->config;

        if (!isset($conf['shop_id']) || strlen($conf['shop_id']) != 32) {
            throw new Exception("Invalid shop ID", 1);
        }

        $credentials        = [
                                'shop_id'       => $conf['shop_id'],
                                'grant_type'    => $conf['grant_type'],
                                'app_key'       => $conf['app_key'],
                                'app_secret'    => $conf['app_secret'],
                                'auth_code'     => $conf['auth_code'],
                                'redirect_url'  => $conf['redirect_url'],
                                'token_expire'  => $conf['token_expire'],

                                'log_file'      => $conf['log_file'],
                                'log_name'      => $conf['log_name'],

                                'test_session'  => $conf['test_session'] ?? '',
                                'test_refresh'  => $conf['test_refresh'] ?? '',
                            ];

        return  $credentials;
        // return  array_merge($default, $this->app->config->all());
    }
}
