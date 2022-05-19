<?php

declare(strict_types=1);

namespace App\Payroll\Domain\Policy;

use App\Payroll\Domain\BonusName;
use App\Payroll\Domain\HiredAt;
use App\Payroll\Domain\PercentageSalaryRatio;
use App\Payroll\Domain\Policy\Exception\PolicyException;
use App\Payroll\Domain\Salary;
use DateTimeInterface;

class PercentageBonusSalaryPolicy implements BonusSalaryPolicy
{
    public function __construct(private readonly PercentageSalaryRatio $ratio)
    {
    }

    public function calculate(Salary $baseSalary, HiredAt $hiredAt, DateTimeInterface $at): Salary
    {
        if ($hiredAt->before($at)) {
            throw PolicyException::cantCalculateBonusSalaryBeforeEmployeeWasHired();
        }

        return $baseSalary->multiply($this->ratio);
    }

    public function getName(): BonusName
    {
        return new BonusName('percentage');
    }
}
