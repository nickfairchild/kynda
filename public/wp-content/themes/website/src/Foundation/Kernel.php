<?php

namespace App\Foundation;

use App\Contracts\Application;
use App\Foundation\Bootstrap\BootProviders;
use App\Foundation\Bootstrap\LoadConfiguration;
use App\Foundation\Bootstrap\RegisterProviders;

class Kernel
{
    protected $app;

    protected $bootstrappers = [
        LoadConfiguration::class,
        RegisterProviders::class,
        BootProviders::class
    ];

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function bootstrap()
    {
        if (! $this->app->hasBeenBootstrapped()) {
            $this->app->bootstrapWith($this->bootstrappers());
        }
    }

    protected function bootstrappers()
    {
        return $this->bootstrappers;
    }

    public function getApplication()
    {
        return $this->app;
    }
}
