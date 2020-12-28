<?php

namespace App\Contracts;

interface Manifest
{
    public function get($key): Asset;
}
