<?php

namespace App\Contracts;

interface Application extends Container
{
    public function basePath(string $path = ''): string;

    public function register($provider, bool $force = false);

    public function boot();
}
