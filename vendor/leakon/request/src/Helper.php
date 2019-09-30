<?php

namespace SamplesRequest\Workers;

class Helper {

	/**
	 * 串联请求数据
	 *
	 * @param $mixedParam		POST 参数，如果是数组，目前只支持一维数组
	 *
	 */
	public static function joinArrayParam($mixedParam) {

		if (is_array($mixedParam)) {

			$mixedParam	= self::deepUrlEncode($mixedParam);

			$arrParam	= array();
			foreach ($mixedParam as $key => $val) {
				$arrParam[]	= $key . '=' . $val;
			}

			// join the params and convert it to string
			$mixedParam	= implode('&', $arrParam);

		}

		return	$mixedParam;

	}

	public static function deepUrlEncode($mixedVar) {

		if (is_array($mixedVar)) {

			// init array
			$retVar		= array();

			foreach ($mixedVar as $key => $val) {
				$retVar[$key]	= self::deepUrlEncode($val);
			}
		} else {

			// init string
			$retVar		= '';

			// most of cases are not array
			// so this branch get a higher priority and a better performance
			$retVar	= urlencode($mixedVar);
		}

		return	$retVar;
	}

}

