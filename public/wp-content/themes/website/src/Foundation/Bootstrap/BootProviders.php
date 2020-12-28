<?php

namespace App\Foundation\Bootstrap;

use App\Contracts\Application;

class BootProviders
{
    public function bootstrap(Application $app)
    {
        $app->boot();
    }
}
