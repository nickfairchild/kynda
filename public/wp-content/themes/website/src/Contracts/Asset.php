<?php

namespace App\Contracts;

interface Asset
{
    public function uri(): string;

    public function path(): string;

    public function exists(): bool;

    public function contents();
}
