<?php

declare(strict_types=1);

namespace App\Department\Domain;

use App\Common\UUID;
use Stringable;

class DepartmentId implements Stringable
{
    public function __construct(private readonly UUID $department)
    {
    }

    public function __toString(): string
    {
        return $this->department->__toString();
    }
}
