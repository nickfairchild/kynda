<?php

namespace App\Foundation;

use App\Contracts\Application;
use App\Support\ServiceProvider;

class ProviderRepository
{
    protected Application $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function load(array $providers): void
    {
        foreach ($providers as $provider) {
            $this->createProvider($provider);

            $this->app->register($provider);
        }
    }

    public function createProvider(string $provider): ServiceProvider
    {
        return new $provider($this->app);
    }
}
