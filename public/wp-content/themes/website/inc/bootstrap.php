<?php

$app = new Kynda\Application(dirname(__DIR__));

$app->singleton(
    \Kynda\Contracts\Kernel::class,
    \Kynda\Foundation\Kernel::class
);

return $app;
