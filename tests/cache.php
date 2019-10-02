<?php


require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use SamplesMeituan\Factory;
use SamplesMeituan\IgnoreConfig;
use SamplesMeituan\Kernel\Traits\InteractsWithCache;

class TestCache {

    use InteractsWithCache;

}

try {

    $key        = isset($argv[1]) ? strval($argv[1]) : 'test-1';
    $ttl        = isset($argv[2]) ? strval($argv[2]) : '30';

	$config 	= IgnoreConfig::getConfig();

	$val 		= $config;

	$instance 	= new TestCache();

	$cache 		= $instance->getCache();

	$ttl 		= (int) $ttl;

	// var_dump([$key, $val, $ttl]);

	// $result 	= $cache->set($key, $val, $ttl);
	// var_dump($result);

	$out 		= $cache->get($key);
	print_r($out);

} catch (Exception $exp) {

    print_r($exp);

}


echo    "\nOK\n";