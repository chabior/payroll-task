<?php

declare(strict_types=1);

namespace App\Payroll\Domain;

use InvalidArgumentException;

class Salary
{
    public function __construct(private readonly int $base)
    {
        if ($this->base < 0) {
            throw new InvalidArgumentException(
                sprintf(
                    'Salary must be higher than 0. %s given',
                    $this->base
                )
            );
        }
    }

    public function multiply(PercentageSalaryRatio $ratio): Salary
    {
        return new self((int) round((float) bcmul((string) $this->base, (string) $ratio->ratio)));
    }
}
