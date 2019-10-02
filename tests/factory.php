<?php


require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use SamplesMeituan\Factory;
use SamplesRequest\Request;

try {

    $app_key        = isset($argv[1]) ? strval($argv[1]) : '123';
    $secret         = isset($argv[2]) ? strval($argv[2]) : '123';

	$config 	= [
		'app_key'		=> '',
		'app_secret'		=> '',
	];


	// $obj 		= Factory::tuangou($config);
	$app 		= Factory::app($config);

	print_r($app->config);

	$shopDeals 	= $app->tuangou->queryShopDeal();

	print_r($shopDeals);



} catch (Exception $exp) {

    print_r($exp);

}


echo    "\nOK\n";