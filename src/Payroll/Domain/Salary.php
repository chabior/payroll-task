<?php

declare(strict_types=1);

namespace App\Payroll\Domain;

use InvalidArgumentException;

class Salary
{
    public function __construct(private readonly int $value)
    {
        if ($this->value < 0) {
            throw new InvalidArgumentException(
                sprintf(
                    'Salary must be higher than 0. %s given',
                    $this->value
                )
            );
        }
    }

    public function multiply(PercentageSalaryRatio $ratio): Salary
    {
        return new self((int) round((float) bcmul((string) $this->value, (string) $ratio->ratio)));
    }

    public function add(Salary $bonusSalary): Salary
    {
        return new Salary($this->value + $bonusSalary->value);
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
