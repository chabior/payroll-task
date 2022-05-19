<?php

declare(strict_types=1);

namespace App\Payroll\Domain\Policy;

use App\Payroll\Domain\HiredAt;
use App\Payroll\Domain\Policy\Exception\PolicyException;
use App\Payroll\Domain\Salary;
use App\Payroll\Domain\YearlyBonus;
use DateTimeInterface;

class YearlyBonusSalaryPolicy implements BonusSalaryPolicy
{
    private const MAX_YEARS = 10;

    public function __construct(private readonly YearlyBonus $perYear)
    {
    }

    public function calculate(Salary $baseSalary, HiredAt $hiredAt, DateTimeInterface $at): Salary
    {
        if ($hiredAt->before($at)) {
            throw PolicyException::cantCalculateBonusSalaryBeforeEmployeeWasHired();
        }

        $yearsDiff = $hiredAt->yearsDiffFrom($at);

        $yearsDiff = min(self::MAX_YEARS, $yearsDiff);

        return new Salary($this->perYear->forYears($yearsDiff));
    }
}
