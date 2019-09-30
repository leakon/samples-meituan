<?php


require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use SamplesRequest\Request;


$request 		= new Request();

$url 			= 'https://localdev.intab.cn/project/secret_services/samples/project/web/youmi/api-v1.0/weixin/wxPush';

$result 		= $request->post($url);

var_dump($result);
