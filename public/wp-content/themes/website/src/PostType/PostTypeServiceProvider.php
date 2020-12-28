<?php

namespace App\PostType;

use App\Support\ServiceProvider;

class PostTypeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind('postType', function () {
            return new PostType;
        });
    }
}
