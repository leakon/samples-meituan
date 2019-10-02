<?php

namespace SamplesMeituan;

use SamplesMeituan\Kernel\ServiceContainer;

class Application extends ServiceContainer {

    protected $providers = [
    	Tuangou\ServiceProvider::class,	
    ];

}
