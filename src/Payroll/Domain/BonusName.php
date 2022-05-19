<?php

declare(strict_types=1);

namespace App\Payroll\Domain;

class BonusName
{
    public function __construct(public readonly string $name)
    {
    }
}
