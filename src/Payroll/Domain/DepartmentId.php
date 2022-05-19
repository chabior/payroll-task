<?php

declare(strict_types=1);

namespace App\Payroll\Domain;

use Stringable;

class DepartmentId implements Stringable
{
    public function __construct(private readonly string $department)
    {
    }

    public function __toString(): string
    {
        return $this->department;
    }
}
