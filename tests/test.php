<?php


require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use SamplesMeituan\Factory;


$one        = null;
$two        = false;
$three      = 3;

$result     = Factory::asap($one, $two, $three);

var_dump($result);
