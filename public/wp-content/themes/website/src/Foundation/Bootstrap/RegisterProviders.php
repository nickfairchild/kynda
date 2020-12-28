<?php

namespace App\Foundation\Bootstrap;

use App\Contracts\Application;

class RegisterProviders
{
    public function bootstrap(Application $app)
    {
        $app->registerConfiguredProviders();
    }
}
