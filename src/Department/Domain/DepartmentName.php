<?php

declare(strict_types=1);

namespace App\Department\Domain;

class DepartmentName
{
    public function __construct(public readonly string $name)
    {
    }
}
