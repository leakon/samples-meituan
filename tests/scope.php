<?php


require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use SamplesMeituan\Factory;
use SamplesMeituan\IgnoreConfig;

try {

    $bid        = isset($argv[1]) ? strval($argv[1]) : '7c4ba404c5ea24b2b981d4cb44521070';
    $secret         = isset($argv[2]) ? strval($argv[2]) : '123';

	$config 	= IgnoreConfig::getConfig();

	$app 		= Factory::app($config);

	$result 	= $app->tuangou->queryScope($bid);
	print_r($result);

} catch (Exception $exp) {

    print_r($exp);

}


echo    "\nOK\n";