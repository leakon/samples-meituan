<?php

namespace SamplesMeituan;

use SamplesMeituan\Kernel\ServiceContainer;

class Application extends ServiceContainer {

    protected $providers = [
    	Tuangou\ServiceProvider::class,	
    	Kernel\Providers\LogServiceProvider::class,	
    	Kernel\Providers\AuthServiceProvider::class,	
    ];

}
