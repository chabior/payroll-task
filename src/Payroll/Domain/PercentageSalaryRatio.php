<?php

declare(strict_types=1);

namespace App\Payroll\Domain;

use InvalidArgumentException;

class PercentageSalaryRatio
{
    public function __construct(public readonly float $ratio)
    {
        if ($this->ratio <= 0) {
            throw new InvalidArgumentException(
                sprintf(
                    'Percentage salary ration must be higher than 0. %s given',
                    $this->ratio
                )
            );
        }
    }
}
