<?php

namespace SamplesMeituan\Kernel;

use SamplesRequest\Request;

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
        $this->accessToken = $accessToken;
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

        $request        = new Request();

        if ('GET' == $method) {
            $params         = $options['query'];
            $respons        = $request->get($url, $params, $options);
        } else if ('POST' == $method) {
            $params         = $options['query'];
            $respons        = $request->post($url, $params, $options);
        } else {
            throw new Exception("Http method is invalid", 1);
        }

        return $respons;

    }

}
