<?php

/**
 * 简单请求类
 *
 * @author	Leakon
 * @version	2010-11-03
 * @notice	支持使用 CUrl 的 Multipart 方式 POST 数据
 *
 */

namespace SamplesRequest;
use SamplesRequest\Workers\CUrl;
use SamplesRequest\Helper;

class Request {

	const

        SIMPLE_REQUEST_TIMEOUT  = 30,
		REGEX_MATCH_PORT	    = '#http[s]?://[^/:]*[:]?(\d*)[/]?.*#i';

	public function __construct($worker = 'curl') {

		$this->worker 	= new CUrl();

	}

	/**
	 * 发送 POST 请求
	 */
	public function post($strUrl, $mixedParam = '', $options = NULL) {

		preg_match(self::REGEX_MATCH_PORT, $strUrl, $matches);
		if (isset($matches[1])) {
			$options['port']	= (int) $matches[1];
		}

		// 默认端口号 80
		$options['port']	= isset($options['port']) ? $options['port'] : 80;
		// 超时设置
		$options['timeout']	= isset($options['timeout']) ? $options['timeout'] : self::SIMPLE_REQUEST_TIMEOUT;

		$options['multipart']	= isset($options['multipart']) ? $options['multipart'] : false;

		if (is_array($mixedParam)) {
			
			if (false === $options['multipart']) {
				// 如果不是 multipart 方式，可以在调用 post 方法时把参数数组全部串联成字符串
				
				$mixedParam	= Helper::joinArrayParam($mixedParam);
				
			} else {
				// 是 multipart 方式，就不要串联，保留原数组
				// 保留的同时，每个字段都要 form-encode 编码，但 multipart 的文件上传使用 'key' => '@/file/path' 的方式不要编码
			
				$mixedParam	= Helper::deepUrlEncode($mixedParam);
				
				if (is_array($options['multipart'])) {
					// 必须是数组，把数组附加进来
					// 包含多个 'key1' => '@file1', 'key2' => '@file2', ...
					$mixedParam	= array_merge($mixedParam, $options['multipart']);
				}
				
			}
			
		}

		return	$this->worker->post($strUrl, $mixedParam, $options);

	}

	/**
	 * 发送 GET 请求
	 */
	public function get($strUrl, $mixedParam = '', $options = NULL) {

		preg_match(self::REGEX_MATCH_PORT, $strUrl, $matches);
		if (isset($matches[1])) {
			$options['port']	= (int) $matches[1];
		}

		// 默认端口号 80
		$options['port']	= isset($options['port']) ? $options['port'] : 80;
		// 超时设置
		$options['timeout']	= isset($options['timeout']) ? $options['timeout'] : self::SIMPLE_REQUEST_TIMEOUT;;

		if (is_array($mixedParam)) {

			$mixedParam	= Helper::joinArrayParam($mixedParam);

		}

		$paramSeparator		= false === strpos($strUrl, '?') ? '?' : '&';

		if (strlen($mixedParam)) {
			$strUrl		.= $paramSeparator . $mixedParam;
		}

		return	$this->worker->get($strUrl, $options);

	}

}

