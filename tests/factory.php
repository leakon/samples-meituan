<?php


require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use SamplesMeituan\Factory;
use SamplesRequest\Request;

// $one        = null;
// $two        = false;
// $three      = 3;

// $result     = Factory::asap($one, $two, $three);

// var_dump($result);

// $request 		= new Request();

// $url 			= 'https://localdev.intab.cn/project/secret_services/samples/project/web/youmi/api-v1.0/weixin/wxPush';

// $result 		= $request->post($url);

// var_dump($result);

$config 	= ['a' => 1];

$obj 		= Factory::tuangou($config);

print_r($obj->config);

