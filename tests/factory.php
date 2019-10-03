<?php


require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use SamplesMeituan\Factory;
use SamplesMeituan\IgnoreConfig;

try {

    $app_key        = isset($argv[1]) ? strval($argv[1]) : '123';
    $secret         = isset($argv[2]) ? strval($argv[2]) : '123';

	$config 	= IgnoreConfig::getConfig();

	// $obj 		= Factory::tuangou($config);
	$app 		= Factory::app($config);

	// print_r($app->config['app_key']);

	$shopDeals 	= $app->tuangou->queryShopDeal();

	print_r($shopDeals);

	$app->log->info('ffooo', $config, [11,22]);

} catch (Exception $exp) {

    print_r($exp);

}


echo    "\nOK\n";