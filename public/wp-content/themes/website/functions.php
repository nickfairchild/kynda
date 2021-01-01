<?php

require_once __DIR__.'/vendor/autoload.php';

$theme = require_once __DIR__.'/inc/bootstrap.php';
$theme->autoloadDirectory(__DIR__.'/inc');

$kernel = $theme->make(\App\Foundation\Kernel::class);
$kernel->bootstrap();
