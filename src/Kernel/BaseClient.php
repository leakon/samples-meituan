<?php

namespace SamplesMeituan\Kernel;

use SamplesHttp\Request;
use SamplesMeituan\Common\Signature;

/**
 * Class BaseClient.
 *
 */
class BaseClient
{

    /**
     */
    protected $app;

    /**
     */
    protected $accessToken;

    /**
     * @var
     */
    protected $baseUri;

    /**
     * BaseClient constructor.
     *
     */
    public function __construct(ServiceContainer $app, $accessToken = null)
    {
        $this->app = $app;
        $this->accessToken = $app->access_token;

        $this->baseUri  = $this->app->config['http']['base_uri'];

    }

    /**
     * GET request.
     *
     * @param string $url
     * @param array  $query
     *
     */
    public function httpGet(string $url, array $query = [])
    {
        return $this->request($url, 'GET', ['query' => $query]);
    }

    /**
     * POST request.
     *
     * @param string $url
     * @param array  $data
     *
     */
    public function httpPost(string $url, array $data = [])
    {
        return $this->request($url, 'POST', ['form_params' => $data]);
    }

    /**
     * JSON request.
     *
     * @param string       $url
     * @param string|array $data
     * @param array        $query
     *
     */
    public function httpPostJson(string $url, array $data = [], array $query = [])
    {
        return $this->request($url, 'POST', ['query' => $query, 'json' => $data]);
    }

    /**
     * Upload file.
     *
     * @param string $url
     * @param array  $files
     * @param array  $form
     * @param array  $query
     *
     */
    public function httpUpload(string $url, array $files = [], array $form = [], array $query = [])
    {
        $multipart = [];

        foreach ($files as $name => $path) {
            $multipart[] = [
                'name' => $name,
                'contents' => fopen($path, 'r'),
            ];
        }

        foreach ($form as $name => $contents) {
            $multipart[] = compact('name', 'contents');
        }

        return $this->request($url, 'POST', ['query' => $query, 'multipart' => $multipart, 'connect_timeout' => 30, 'timeout' => 30, 'read_timeout' => 30]);
    }

    /**
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     *
     * @return $this
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * @param string $url
     * @param string $method
     * @param array  $options
     * @param bool   $returnRaw
     *
     */
    public function request(string $url, string $method = 'GET', array $options = [], $returnRaw = false)
    {
        // if (empty($this->middlewares)) {
        //     $this->registerHttpMiddlewares();
        // }

        $url            = sprintf('%s%s', $this->baseUri, $url);

        $request        = new Request();

        if ('GET' == $method) {
            $params         = $options['query'];
            $params         = $this->addCommonParams($params);
            $params         = $this->addSignature($params);
            $respons        = $request->get($url, $params, $options);
        } else if ('POST' == $method) {
            $params         = $options['form_params'];
            $params         = $this->addCommonParams($params);
            $params         = $this->addSignature($params);
            $options['http_header']     = [
                'Content-Type: application/x-www-form-urlencoded',
            ];
            $respons        = $request->post($url, $params, $options);
        } else {
            throw new Exception("Http method is invalid", 1);
        }

        $this->app->log->debug($url, [$params, $respons]);

        $respons    = json_decode($respons, true);

        return $respons;

    }

    protected function addCommonParams($params) {
        $timestamp      = date('Y-m-d H:i:s');
        $app_key        = $this->app['config']['app_key'];
        $shop_id        = $this->app['config']['shop_id'];
        $params         = $this->accessToken->applyToParams($params);

        $common         = [
                            'format'            => 'json',
                            'sign_method'       => 'MD5',
                            'v'                 => '1',
                            'app_key'           => $app_key,
                            'timestamp'         => $timestamp,
                            'open_shop_uuid'    => $shop_id,
                        ];
        $params         = array_merge($params, $common);
        return  $params;
    }

    protected function addSignature($params) {
        $app_sec      = $this->app['config']['app_secret'];
        $sign         = Signature::getVerifySign($params, $app_sec);
        $params['sign']     = $sign;
        return  $params;
    }

}
