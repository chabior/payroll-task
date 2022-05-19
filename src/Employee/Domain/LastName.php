<?php

declare(strict_types=1);

namespace App\Employee\Domain;

class LastName
{
    public function __construct(public readonly string $name)
    {
    }
}
