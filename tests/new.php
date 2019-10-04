<?php


require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use SamplesMeituan\Factory;
use SamplesMeituan\IgnoreConfig;

try {

    $receipt        = isset($argv[1]) ? strval($argv[1]) : '123';
    $secret         = isset($argv[2]) ? strval($argv[2]) : '123';

	$config 	= IgnoreConfig::getConfig();

	$app 		= Factory::app($config);

	$tuangou 	= $app->tuangou;

	$accessToken 	= $tuangou->getAccessToken();

	$token 		= $accessToken->authNewToken();
	$accessToken->setToken($token);

	print_r($token);

} catch (Exception $exp) {

    print_r($exp);

}


echo    "\nOK\n";