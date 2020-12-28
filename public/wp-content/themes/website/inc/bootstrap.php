<?php

$app = new App\Theme(dirname(__DIR__));

$app->singleton(
    \App\Contracts\Kernel::class,
    \App\Foundation\Kernel::class
);

return $app;
