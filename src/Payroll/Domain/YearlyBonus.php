<?php

declare(strict_types=1);

namespace App\Payroll\Domain;

class YearlyBonus
{
    public function __construct(public readonly int $perYear)
    {
    }

    public function forYears(int $years): int
    {
        if ($years < 0) {
            return 0;
        }

        return $years * $this->perYear;
    }
}
