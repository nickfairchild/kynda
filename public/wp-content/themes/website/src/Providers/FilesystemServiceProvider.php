<?php

namespace App\Providers;

use App\Support\ServiceProvider;
use Illuminate\Filesystem\Filesystem;

class FilesystemServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('files', function () {
            return new Filesystem;
        });
    }
}
