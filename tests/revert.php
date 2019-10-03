<?php


require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use SamplesMeituan\Factory;
use SamplesMeituan\IgnoreConfig;

try {

    $receipt        = isset($argv[1]) ? strval($argv[1]) : '9669 8661 84';
    $dealId         = isset($argv[2]) ? strval($argv[2]) : '34795568';

	$config 	= IgnoreConfig::getConfig();

	$app 		= Factory::app($config);

	$result 	= $app->tuangou->revertReceipt($dealId, $receipt);

	print_r($result);

} catch (Exception $exp) {

    print_r($exp);

}


echo    "\nOK\n";