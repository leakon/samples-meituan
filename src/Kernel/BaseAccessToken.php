<?php

/*
 */

namespace SamplesMeituan\Kernel;

use SamplesMeituan\Kernel\Traits\InteractsWithCache;
use SamplesHttp\Request;
use Pimple\Container;

/**
 * Class AccessToken.
 */
abstract class BaseAccessToken 
{
    use InteractsWithCache;

    /**
     * @var \Pimple\Container
     */
    protected $app;

    /**
     * @var string
     */
    protected $requestMethod = 'GET';

    /**
     * @var string
     */
    protected $endpointToGetToken;

    /**
     * @var string
     */
    protected $queryName = 'session';

    /**
     * @var array
     */
    protected $token;

    /**
     * @var int
     */
    protected $safeSeconds = 500;

    /**
     * @var string
     */
    protected $tokenKey = 'access_token';

    protected $refreshKey = 'refresh_token';
    protected $refreshType = 'refresh_token';
    protected $refreshMin = 86400;
    protected $remainKey = 'remain_refresh_count';
    protected $remainMin = 5;

    /**
     * @var string
     */
    protected $cachePrefix = 'meituan-open.kernel.access_token.3.';

    /**
     * AccessToken constructor.
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     * @return array
     */
    public function getRefreshedToken(): array
    {
        return $this->getToken(true);
    }

    /**
     * @param bool $refresh
     */
    public function getToken(bool $refresh = false): array
    {
        $cacheKey = $this->getCacheKey();
        $cache = $this->getCache();

        if (!$refresh && $cache->has($cacheKey)) {
            return $cache->get($cacheKey);
        }

        $credentials        = $this->getCredentials();

        if (isset($credentials['test_session']) && strlen($credentials['test_session'])) {
            $token  = [
                $this->remainKey        => 1,
                'expires_in'            => 2591900,
                $this->tokenKey         => $credentials['test_session'],
                $this->refreshKey       => $credentials['test_refresh'],
            ];
        } else {
            $token = $this->authNewToken();
        }

        $this->setToken($token);

        return $token;
    }

    /**
     * @param string $token
     */
    public function setToken(array $token)
    {

        // 这是 cache 的过期时间
        $expire         = 7200;

        // 这是 token 的过期时间，要在过期之前用 refresh_token 延期
        $token_expire   = $token['expires_in'];

        // 为了便于测试，可以在配置文件设置较短的过期时间，来检验自动延期的逻辑
        $credentials    = $this->getCredentials();
        if (isset($credentials['token_expire']) && $credentials['token_expire'] > 0) {
            $token_expire     = (int) $credentials['token_expire'];
        }

        $token_expire   = date('Y-m-d H:i:s', time() + $token_expire);

        $lifetime   = $token['expires_in'] ?? $expire;

        $setting    = [
                        $this->tokenKey     => $token[$this->tokenKey],
                        $this->refreshKey   => $token[$this->refreshKey],
                        'token_expire'      => $token_expire,
                        $this->remainKey    => $token[$this->remainKey],
                    ];

        $msg        = 'New token written to cache';
        $this->app->log->debug($msg, [$setting]);

        $this->getCache()->set($this->getCacheKey(), $setting, 
                                $lifetime - $this->safeSeconds);

        if (!$this->getCache()->has($this->getCacheKey())) {
            throw new RuntimeException('Failed to cache access token.');
        }

        return $this;

    }

    /**
     *
     */
    public function refresh()
    {
        $this->getToken(true);

        return $this;
    }

    public function applyToParams(array $params)
    {
        $params[$this->queryName]        = $this->getToken()[$this->tokenKey];
        return $params;
    }

    /**
     * Send http request.
     */
    protected function sendRequest(array $credentials)
    {

        $method             = $this->requestMethod;
        $options            = [];
        $options['query']   = $credentials;

        $url                = $this->endpointToGetToken;

        $request            = new Request();

        if ('GET' == $method) {
            $params         = $options['query'];
            $respons        = $request->get($url, $params, $options);
        } else if ('POST' == $method) {
            $params         = $options['query'];
            $respons        = $request->post($url, $params, $options);
        } else {
            throw new Exception("Http method is invalid", 1);
        }

        $this->app->log->debug($url, [$params, $respons]);

        $respons    = json_decode($respons, true);

        return $respons;
    }

    // 通过 auth_code 去获取新的 access_token
    // auth_code 只有一次使用机会
    public function authNewToken()
    {

        $credentials    = $this->getCredentials();

        $token          = $this->sendRequest($credentials);

        if (!isset($token['code']) || 200 != $token['code']) {
            throw new \Exception("Auth token failed ** " . $token['msg'], 1);
        }

        $this->app->log->debug('Get token by auth_code', $credentials);

        return  $token;

    }

    // 用 refresh_token 刷新 access_token
    protected function refreshToken($cacheToken)
    {

        if (empty($cacheToken) || empty($cacheToken[$this->refreshKey])) {
            throw new Exception("Cache refresh token failed", 1);
        }

        $credentials                    = $this->getCredentials();
        $credentials['grant_type']      = $this->refreshType;
        $credentials[$this->refreshKey] = $cacheToken[$this->refreshKey];

        $token      = $this->sendRequest($credentials);

        $this->setToken($token);

        $msg        = 'Token refreshed';
        $log        = [$token];

        $this->app->log->info($msg, $log);

        // check remain_refresh_count

        $token      = $this->getToken();

        if (isset($token[$this->remainKey])) {

            $remain     = (int) $token[$this->remainKey];

            if ($remain < $this->remainMin) {
                $this->notifyToReAuth($token, $this->getCredentials());
            }

        }

        return  $this->getToken();

    }

    // 在 token cache 即将过期 (expires_in) 时，自动通过 refresh_token 去刷新
    // 通常设置 crontab 任务，来定期检查 token 过期时间，即将过期时用 refresh 主动刷新并更新缓存
    public function autoRenewToken() {

        $credentials    = $this->getCredentials();
        $cacheKey       = $this->getCacheKey();
        $cache          = $this->getCache();

        $token          = $cache->get($cacheKey);

        $bool_1         = isset($token);
        $bool_2         = isset($token[$this->tokenKey]) && strlen($token[$this->tokenKey]);
        $bool_3         = isset($token[$this->refreshKey]) && strlen($token[$this->refreshKey]);

        $log            = [];

        // print_r($token);
        // var_dump([$bool_1 , $bool_2 , $bool_3]);
        // exit;

        if ($bool_1 && $bool_2 && $bool_3) {

            $refreshLeft    = strtotime($token['token_expire']) - time();

            // 即将过期
            if ($refreshLeft <= $this->refreshMin) {

                $newToken   = $this->refreshToken($token);

                $msg        = 'Token is about to expire';
                $log[]      = $credentials['shop_id'];
                $log[]      = $newToken;

            } else {

                $msg        = 'Token is good';

            }

            $this->app->log->info($msg, $log);

        } else {

            $msg    = "Auto renew token failed as invalid cache";

            $this->app->log->error($msg, $credentials);

            throw new Exception($msg, 1);

        }


    }

    protected function notifyToReAuth($token, $credentials) {

        $msg        = sprintf('Refresh Remain is low [%d] of [%d]',
                        $token[$this->remainKey], $this->remainMin
                    );

        $log        = [$credentials['shop_id'], date('Y-m-d H:i:s'), $token];

        $this->app->log->debug($msg, $log);

    }

    /**
     * @return string
     */
    protected function getCacheKey()
    {
        $key = $this->cachePrefix.md5(json_encode($this->getCredentials()));
        return  $key;
    }

    /**
     * @return string
     */
    public function getEndpoint(): string
    {
        if (empty($this->endpointToGetToken)) {
            throw new InvalidArgumentException('No endpoint for access token request.');
        }

        return $this->endpointToGetToken;
    }

    /**
     * @return string
     */
    public function getTokenKey()
    {
        return $this->tokenKey;
    }

    /**
     * Credential for get token.
     */
    abstract protected function getCredentials(): array;

}
