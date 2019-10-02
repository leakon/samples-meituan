<?php


require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use SamplesMeituan\Factory;
use SamplesMeituan\IgnoreConfig;

try {

    $receipt        = isset($argv[1]) ? strval($argv[1]) : '123';
    $secret         = isset($argv[2]) ? strval($argv[2]) : '123';

	$config 	= IgnoreConfig::getConfig();

	$app 		= Factory::app($config);

	$uuid 		= '73a0a604e406ee55f3789e7fa4a45c9b';

	$result 	= $app->tuangou->receiptPrepare($uuid, $receipt);

	print_r($result);

} catch (Exception $exp) {

    print_r($exp);

}


echo    "\nOK\n";