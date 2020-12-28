<?php

namespace App\Contracts;

interface Kernel
{
    public function bootstrap();

    public function handle($request);
}
