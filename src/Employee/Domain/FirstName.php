<?php

declare(strict_types=1);

namespace App\Employee\Domain;

class FirstName
{
    public function __construct(public readonly string $name)
    {
    }
}
