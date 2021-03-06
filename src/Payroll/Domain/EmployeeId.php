<?php

declare(strict_types=1);

namespace App\Payroll\Domain;

use App\Common\UUID;

class EmployeeId
{
    public function __construct(public readonly UUID $UUID)
    {
    }
}
